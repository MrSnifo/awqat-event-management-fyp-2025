from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.linear_model import LogisticRegression
import mysql.connector
import pandas as pd
import numpy as np
import json
import sys


def validate_user_id(user_id):
    """Validate that user_id is a positive integer"""
    try:
        user_id = int(user_id)
        if user_id <= 0:
            raise ValueError("User ID must be positive")
        return user_id
    except ValueError as e:
        sys.exit(1)


def get_db_connection():
    """Create and return database connection"""
    try:
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="awqat"
        )
        return db
    except mysql.connector.Error as err:
        sys.exit(1)


def get_user_interactions(db, user_id):
    """Fetch user interactions from database"""
    query = """
    SELECT 
        ui.user_id,
        ui.event_id,
        ui.interaction_type,
        ui.weight,
        e.tags
    FROM user_interactions ui
    JOIN events e ON ui.event_id = e.id
    WHERE ui.user_id = %s
    """
    try:
        cursor = db.cursor()
        cursor.execute(query, (user_id,))
        rows = cursor.fetchall()
        columns = ['user_id', 'event_id', 'interaction_type', 'weight', 'tags']
        return pd.DataFrame(rows, columns=columns)
    except mysql.connector.Error as err:
        sys.exit(1)


def get_all_events(db, limit=10):
    """Fetch all events from database with optional limit"""
    try:
        cursor = db.cursor()
        cursor.execute("SELECT id FROM events ORDER BY id DESC LIMIT %s", (limit,))
        return [row[0] for row in cursor.fetchall()]
    except mysql.connector.Error as err:
        logger.error(f"Failed to fetch events: {err}")
        sys.exit(1)


def generate_recommendations(db, user_df, all_event_ids):
    """Generate recommendations for the user"""
    if user_df.empty:
        return all_event_ids[:10]  # Return top 10 normal events

    # Vectorize tags
    vectorizer = CountVectorizer(token_pattern=None, tokenizer=lambda x: x.split(','))
    tag_matrix = vectorizer.fit_transform(user_df['tags'].fillna(''))

    # Create binary labels
    y = user_df['weight'].apply(lambda w: 1 if w > 0 else 0)

    # Get all events with tags
    cursor = db.cursor()
    cursor.execute("""
    SELECT id, tags 
    FROM events 
    WHERE (end_date IS NULL AND start_date >= CURDATE()) 
       OR (end_date IS NOT NULL AND end_date >= CURDATE()) 
    ORDER BY created_at DESC
    """)
    event_rows = cursor.fetchall()
    event_df = pd.DataFrame(event_rows, columns=['event_id', 'tags'])

    # Check for single class issue
    use_model = y.nunique() >= 2

    # Train model if data is sufficient
    if use_model:
        try:
            model = LogisticRegression()
            model.fit(tag_matrix, y)
            event_vectors = vectorizer.transform(event_df['tags'].fillna(''))
            predictions = model.predict_proba(event_vectors)[:, 1]
            event_df['score'] = predictions
        except Exception as e:
            use_model = False

    if not use_model:
        # Use cosine similarity fallback
        def get_user_vector(user_df):
            tag_matrix_user = vectorizer.transform(user_df['tags'].fillna(''))
            weights = user_df['weight'].values.reshape(-1, 1)
            weighted_sum = tag_matrix_user.multiply(weights).sum(axis=0)
            return np.array(weighted_sum).flatten()

        user_vector = get_user_vector(user_df)
        event_vectors = vectorizer.transform(event_df['tags'].fillna(''))
        similarities = cosine_similarity([user_vector], event_vectors).flatten()
        event_df['score'] = similarities

    # Get top recommended event IDs
    recommended = event_df.sort_values('score', ascending=False)['event_id'].tolist()

    # Combine with remaining events (not in recommendations)
    remaining_events = [e for e in all_event_ids if e not in recommended]
    return recommended + remaining_events[:10]  # Return top 10 combined


def main():
    # Check and get user_id from command line
    if len(sys.argv) < 2:
        sys.exit(1)

    user_id = validate_user_id(sys.argv[1])

    # Database operations
    db = get_db_connection()
    try:
        user_df = get_user_interactions(db, user_id)
        all_event_ids = get_all_events(db, limit=100)

        recommended_ids = generate_recommendations(db, user_df, all_event_ids)

        print(json.dumps(recommended_ids[:10]), flush=True)

    finally:
        db.close()


if __name__ == "__main__":
    main()