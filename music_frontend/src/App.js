// src/App.js
import React, { useState, useEffect } from "react";
import LanguageSelector from "./components/LanguageSelector";
import { fetchSongs } from "./api/songService";

function App() {
  const [language, setLanguage] = useState("en");
  const [songs, setSongs] = useState([]);

  useEffect(() => {
    fetchSongs(language).then(data => setSongs(data));
  }, [language]);

  return (
    <div>
      <LanguageSelector language={language} setLanguage={setLanguage} />
      <ul>
        {songs.map(song => (
          <li key={song.index}>
            {song.index}. {song.title} - {song.artist} ({song.album}, {song.genre})
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
