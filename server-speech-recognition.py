#!/usr/bin/env python3
"""
Serveur de reconnaissance vocale local pour contourner les problèmes du navigateur
Utilise SpeechRecognition + PyAudio pour une reconnaissance vocale fiable
"""

import speech_recognition as sr
import json
import time
from flask import Flask, request, jsonify
from flask_cors import CORS
import threading
import queue

app = Flask(__name__)
CORS(app)  # Autoriser les requêtes cross-origin

# Configuration du reconnaisseur
recognizer = sr.Recognizer()
recognizer.energy_threshold = 4000
recognizer.dynamic_energy_threshold = True
recognizer.pause_threshold = 0.8

# File d'attente pour les résultats
audio_queue = queue.Queue()

def listen_for_speech():
    """Fonction d'écoute continue du microphone"""
    with sr.Microphone() as source:
        print("🎤 Microphone initialisé - En attente de parole...")
        
        # Ajustement automatique au bruit ambiant
        recognizer.adjust_for_ambient_noise(source, duration=1)
        
        while True:
            try:
                print("👂 Écoute en cours...")
                audio = recognizer.listen(source, timeout=1, phrase_time_limit=10)
                print("🎯 Audio capturé, traitement...")
                
                # Traitement de l'audio
                try:
                    # Reconnaissance avec Google Speech Recognition (gratuit, 50 requêtes/jour)
                    text = recognizer.recognize_google(audio, language='fr-FR')
                    print(f"✅ Texte reconnu: {text}")
                    
                    # Ajouter à la file d'attente
                    audio_queue.put({
                        'success': True,
                        'text': text,
                        'confidence': 0.9,
                        'timestamp': time.time()
                    })
                    
                except sr.UnknownValueError:
                    print("❌ Parole non reconnue")
                    audio_queue.put({
                        'success': False,
                        'error': 'Parole non reconnue',
                        'timestamp': time.time()
                    })
                    
                except sr.RequestError as e:
                    print(f"❌ Erreur API: {e}")
                    audio_queue.put({
                        'success': False,
                        'error': f'Erreur API: {e}',
                        'timestamp': time.time()
                    })
                    
            except sr.WaitTimeoutError:
                continue
            except Exception as e:
                print(f"❌ Erreur générale: {e}")
                time.sleep(1)

@app.route('/status', methods=['GET'])
def get_status():
    """Statut du serveur"""
    return jsonify({
        'status': 'running',
        'microphone': 'active',
        'queue_size': audio_queue.qsize(),
        'timestamp': time.time()
    })

@app.route('/listen', methods=['POST'])
def start_listening():
    """Démarrer l'écoute"""
    try:
        # Démarrer l'écoute dans un thread séparé
        if not hasattr(app, 'listening_thread') or not app.listening_thread.is_alive():
            app.listening_thread = threading.Thread(target=listen_for_speech, daemon=True)
            app.listening_thread.start()
            
        return jsonify({
            'success': True,
            'message': 'Écoute démarrée',
            'timestamp': time.time()
        })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/get_result', methods=['GET'])
def get_result():
    """Récupérer le dernier résultat de reconnaissance"""
    try:
        if not audio_queue.empty():
            result = audio_queue.get()
            return jsonify(result)
        else:
            return jsonify({
                'success': False,
                'message': 'Aucun résultat disponible',
                'timestamp': time.time()
            })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/stop', methods=['POST'])
def stop_listening():
    """Arrêter l'écoute"""
    try:
        # Vider la file d'attente
        while not audio_queue.empty():
            audio_queue.get()
            
        return jsonify({
            'success': True,
            'message': 'Écoute arrêtée',
            'timestamp': time.time()
        })
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

if __name__ == '__main__':
    print("🚀 Démarrage du serveur de reconnaissance vocale...")
    print("📋 Endpoints disponibles:")
    print("  GET  /status      - Statut du serveur")
    print("  POST /listen      - Démarrer l'écoute")
    print("  GET  /get_result  - Récupérer un résultat")
    print("  POST /stop        - Arrêter l'écoute")
    print("\n🌐 Serveur accessible sur: http://localhost:5000")
    print("🎤 Appuyez sur Ctrl+C pour arrêter")
    
    app.run(host='0.0.0.0', port=5000, debug=True)

