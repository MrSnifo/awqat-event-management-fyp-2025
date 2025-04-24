import mysql.connector
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import sys
import json


def get_db_connection():
    """Create database connection"""
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="awqat"
    )


def fetch_events(db):
    """Fetch upcoming verified events"""
    cursor = db.cursor()
    cursor.execute("""
        SELECT id, title, tags, location, description FROM events WHERE 
        start_date >= CURDATE() OR end_date >= CURDATE()
    """)
    return cursor.fetchall()


def search_events(query):
    """Search events using TF-IDF on title, tags, location, and description"""
    db = get_db_connection()
    results = fetch_events(db)

    if not results:
        return []

    # Create DataFrame
    events_df = pd.DataFrame(results, columns=['id', 'title', 'tags', 'location', 'description'])

    # Combine relevant fields into a single text field
    def combine_fields(row):
        try:
            tags = ','.join(json.loads(row['tags']))
        except Exception:
            tags = ''
        return f"{row['title']} {tags} {row['location']} {row['description']}"

    events_df['text'] = events_df.apply(combine_fields, axis=1)

    # Create TF-IDF vectorizer
    vectorizer = TfidfVectorizer(stop_words='english')
    tfidf_matrix = vectorizer.fit_transform(events_df['text'])

    # Transform the user query
    query_vector = vectorizer.transform([query])

    # Calculate cosine similarity between query and all event texts
    similarities = cosine_similarity(query_vector, tfidf_matrix).flatten()

    # Add similarity scores to DataFrame and sort
    events_df['similarity'] = similarities
    ranked_events = events_df.sort_values('similarity', ascending=False)

    # Filter out events with zero similarity
    ranked_events = ranked_events[ranked_events['similarity'] > 0]
    return ranked_events[ranked_events['similarity'] > 0]['id'].head(10).tolist()



def main():
    if len(sys.argv) != 2:
        sys.exit(1)

    query = sys.argv[1]
    event_ids = search_events(query)
    print(event_ids, flush=True)


if __name__ == "__main__":
    main()