import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import sys
import numpy as np


def validate_user_id(user_id):
    """Validate user ID input"""
    try:
        user_id = int(user_id)
        if user_id <= 0:
            raise ValueError
        return user_id
    except ValueError:
        sys.exit(1)


def get_db_connection():
    """Create database connection"""
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="awqat"
    )


def fetch_user_data(db, user_id):
    """Fetch user's interactions and event tags"""
    cursor = db.cursor()
    query = """
    SELECT e.tags, ui.weight
    FROM user_interactions ui
    JOIN events e ON ui.event_id = e.id
    WHERE ui.user_id = %s
    """
    cursor.execute(query, (user_id,))
    return cursor.fetchall()


def fetch_upcoming_events(db):
    """Fetch all upcoming events"""
    cursor = db.cursor()
    query = """
    SELECT id, tags 
    FROM events 
    WHERE start_date >= CURDATE() OR end_date >= CURDATE()
    """
    cursor.execute(query)
    return cursor.fetchall()


def create_tfidf_vectorizer():
    """Create TF-IDF vectorizer for tag processing"""
    return TfidfVectorizer(
        tokenizer=lambda x: x.split(','),
        max_features=1000,
        token_pattern=None  # Disable token pattern
    )


def recommend_events(user_id):
    """Main recommendation function"""
    db = get_db_connection()

    user_interactions = fetch_user_data(db, user_id)
    upcoming_events = fetch_upcoming_events(db)

    if not user_interactions:
        return [event[0] for event in upcoming_events][:10]

    user_df = pd.DataFrame(user_interactions, columns=['tags', 'weight'])
    event_df = pd.DataFrame(upcoming_events, columns=['event_id', 'tags'])

    vectorizer = create_tfidf_vectorizer()
    all_tags = pd.concat([user_df['tags'], event_df['tags']])
    vectorizer.fit(all_tags)

    # Create user profile as numpy array
    user_tfidf = vectorizer.transform(user_df['tags'])
    user_profile = user_tfidf.multiply(user_df['weight'].values.reshape(-1, 1))
    user_profile = np.asarray(user_profile.mean(axis=0))
    user_profile = user_profile.reshape(1, -1)  # Ensure 2D shape

    event_vectors = vectorizer.transform(event_df['tags'])

    # Calculate similarities
    similarities = cosine_similarity(user_profile, event_vectors.toarray()).flatten()

    event_df['similarity'] = similarities
    recommendations = event_df.sort_values('similarity', ascending=False)
    return recommendations['event_id'].tolist()[:10]


def main():
    if len(sys.argv) != 2:
        sys.exit(1)

    user_id = validate_user_id(sys.argv[1])
    recommended_events = recommend_events(user_id)
    print(recommended_events, flush=True)


if __name__ == "__main__":
    main()