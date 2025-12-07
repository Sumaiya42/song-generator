from flask import Flask, request, send_file, jsonify
from midiutil import MIDIFile
import io
import random

app = Flask(__name__)

@app.route('/generate', methods=['POST'])
def generate_song():
    data = request.json
    seed = data.get("seed", 12345)
    length = data.get("length", 16)

    random.seed(seed)

    midi = MIDIFile(1)
    midi.addTempo(0, 0, 120)

    for i in range(length):
        pitch = random.randint(60, 72)
        midi.addNote(0, 0, pitch, i, 1, 100)

    buffer = io.BytesIO()
    midi.writeFile(buffer)
    buffer.seek(0)

    return send_file(
        buffer,
        mimetype="audio/midi",
        download_name="song.mid"
    )

@app.route('/')
def home():
    return jsonify({"status": "Music Service Running", "port": 5000})

if __name__ == '__main__':
    app.run(port=5000)
