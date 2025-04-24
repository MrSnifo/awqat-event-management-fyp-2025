import mysql.connector
import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer
import json
import sys

import nltk
nltk.download('punkt_tab')
nltk.download('stopwords')

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


def preprocess_text(text):
    """Preprocess text: tokenize, remove stopwords, and apply stemming"""
    tokens = word_tokenize(text)
    stop_words = set(stopwords.words('english'))
    tokens = [word.lower() for word in tokens if word.isalpha() and word.lower() not in stop_words]
    stemmer = PorterStemmer()
    tokens = [stemmer.stem(word) for word in tokens]
    return tokens


def calculate_tfidf(documents):
    """Calculate the TF-IDF scores for the documents"""
    vectorizer = TfidfVectorizer()
    tfidf_matrix = vectorizer.fit_transform([" ".join(doc) for doc in documents])
    return vectorizer, tfidf_matrix


def search_events(query):
    """Search events using TF-IDF"""
    db = get_db_connection()
    results = fetch_events(db)

    if not results:
        print("No events found in the database.")
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

    # Tokenize events
    event_tokens = events_df['text'].apply(preprocess_text)

    # TF-IDF Calculation
    vectorizer, tfidf_matrix = calculate_tfidf(event_tokens)
    query_tokens = preprocess_text(query)
    query_vector = vectorizer.transform([" ".join(query_tokens)])
    cosine_similarities = cosine_similarity(query_vector, tfidf_matrix).flatten()

    # Sort the events based on TF-IDF cosine similarities
    tfidf_ranked_indices = np.argsort(cosine_similarities)[::-1]

    # Filter out events with zero similarity and return only the event IDs as integers
    tfidf_ranking_ids = [int(events_df.iloc[i]['id']) for i in tfidf_ranked_indices if cosine_similarities[i] > 0]

    return tfidf_ranking_ids


def main():
    if len(sys.argv) != 2:
        sys.exit(1)

    query = sys.argv[1]
    event_ids = search_events(query)
    print(event_ids, flush=True)


if __name__ == "__main__":
    main()
