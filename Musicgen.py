from flask import Flask, request, jsonify, send_file
from audiocraft.models import MusicGen
import torch
import torchaudio
import os
from flask_cors import CORS
import base64
from flask import Flask, jsonify
from sklearn.cluster import KMeans
import pandas as pd
from flask import Flask, request, jsonify
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import pymysql

app = Flask(__name__)

df = pd.read_excel('รายชื่อผู้ใช้_100คน.xlsx')

df.columns = df.columns.str.strip()


if not {'ชื่อผู้ใช้', 'อายุ', 'เพศ'}.issubset(df.columns):
    raise ValueError("Excel ต้องมีคอลัมน์: ชื่อผู้ใช้, อายุ, เพศ")


df = df.dropna(subset=['อายุ', 'เพศ'])
df.columns = df.columns.str.strip()


X = df[['อายุ', 'เพศ']]

model = KMeans(n_clusters=3, n_init='auto')
df['cluster'] = model.fit_predict(df[['อายุ', 'เพศ']])

@app.route('/')
def show_clusters():
    summary = []

    for cluster_id in sorted(df['cluster'].unique()):
        cluster_df = df[df['cluster'] == cluster_id]
        age_min = int(cluster_df['อายุ'].min())
        age_max = int(cluster_df['อายุ'].max())
        age_avg = round(cluster_df['อายุ'].mean(), 1)

        if 'แนวเพลง' in cluster_df.columns:
            top_genre = cluster_df['แนวเพลง'].mode().iloc[0]
        else:
            top_genre = 'ไม่มีข้อมูล'

        summary.append({
            'cluster': int(cluster_id),
            'count': len(cluster_df),
            'age_min': age_min,
            'age_max': age_max,
            'age_avg': age_avg,
            'top_genre': top_genre
        })

    return jsonify(summary)


def get_connection():
    return pymysql.connect(
        host='localhost',
        user='root',
        password='',
        database='musicgen',
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

@app.route('/recommend', methods=['GET'])
def recommend():
    try:
        connection = get_connection()
        with connection.cursor() as cursor:
            cursor.execute("SELECT description_audio FROM audio ORDER BY id DESC LIMIT 1")
            latest_audio = cursor.fetchone()
            print("latest_audio =", latest_audio)  # debug

            if not latest_audio or not latest_audio["description_audio"]:
                return jsonify({"error": "No recent audio found"}), 404

            cursor.execute("SELECT id, name_audio, upload_description, upload_url, upload_date FROM upload")
            upload_data = cursor.fetchall()
            print("upload_data =", upload_data)  # debug

            if not upload_data:
                return jsonify({"error": "No uploads found"}), 404

        # ตรวจว่า upload_description เป็น None หรือไม่
        descriptions = [latest_audio["description_audio"] or ""] + [
            item["upload_description"] or "" for item in upload_data
        ]

        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(descriptions)
        cosine_sim = cosine_similarity(tfidf_matrix[0:1], tfidf_matrix[1:]).flatten()

        similarity_scores = sorted(enumerate(cosine_sim), key=lambda x: x[1], reverse=True)
        top_matches = similarity_scores[:3]

        recommendations = [upload_data[i[0]] for i in top_matches]
        print("recommendations =", recommendations)  # debug

        return jsonify(recommendations)

    except Exception as e:
        print("ERROR:", e)  # ดูที่ console
        return jsonify({"error": str(e)}), 500

@app.route('/')
def index():
    return "Flask Server is running."

@app.route('/elbow')
def elbow():
    inertias = []
    k_range = list(range(1, 11))  # ลอง k = 1 ถึง 10
    for k in k_range:
        model = KMeans(n_clusters=k, n_init='auto')
        model.fit(X)
        inertias.append(model.inertia_)
    
    return jsonify({'k': k_range, 'inertia': inertias})

CORS(app)
def load_model():
    model = MusicGen.get_pretrained('facebook/musicgen-small')
    return model

def generate_music_tensors(description, duration: int):
    print("Description: ", description)
    print("Duration: ", duration)
    model = load_model()

    model.set_generation_params(
        use_sampling=True,
        top_k=250,
        duration=duration
    )

    output = model.generate(
        descriptions=[description],
        progress=True,
        return_tokens=True
    )

    return output[0]

def save_audio(samples: torch.Tensor, filename):
    sample_rate = 32000
    save_path = "static/audio"
    #os.makedirs(save_path, exist_ok=True)
    
    assert samples.dim() == 2 or samples.dim() == 3

    samples = samples.detach().cpu()
    if samples.dim() == 2:
        samples = samples[None, ...]

    audio_path = os.path.join(save_path, f"{filename}.wav")
    torchaudio.save(audio_path, samples[0], sample_rate)
    return audio_path

@app.route('/generate_music', methods=['POST'])
def generate_music():
    data = request.json
    description = data.get('description', '')
    duration = data.get('duration', 10)
    
    music_tensors = generate_music_tensors(description, duration)
    audio_filepath = save_audio(music_tensors, 'audio_output')

    base_url = request.host_url
    audio_url = f"{base_url}{audio_filepath}"
    print("Audio URL:", audio_url)

    with open(audio_filepath, 'rb') as audio_file:
        audio_base64 = base64.b64encode(audio_file.read()).decode('utf-8')

    return jsonify({
        'message': 'Music generated successfully!',
        'audio_base64': audio_base64 
        
    })



if __name__ == '__main__':
    app.run(debug=True)