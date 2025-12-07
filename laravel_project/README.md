<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<h1 align="center">Song Generator</h1>

A Laravel-based web application that dynamically generates random, reproducible songs with options for **language**, **seed**, and **likes per song**. Supports **Table** and **Gallery** views with expandable details, audio previews, and pagination/infinite scrolling.

---

## Features

- **Language Selection:** English or Bangla.
- **Seed Configuration:** 
  - Custom seed (64-bit integer) or random seed.
  - Same seed always generates the same songs.
- **Likes per Song:** Fractional values (0–10) applied probabilistically.
- **Dynamic UI / UX:**
  - Toolbar with language, seed, likes, and view selection.
  - Table view with expandable/collapsible rows.
  - Gallery view with infinite scrolling.
  - All data updates dynamically without page reloads.
- **Song Details:**
  - Album cover, song title & artist, audio preview, and a review in Table view.
  - Cards with album cover, song title & artist, and audio previews in Gallery view.
- **Reproducibility:**
  - Same seed produces identical data.
  - Changing likes only updates the like count; titles, artists, albums, and genres remain unchanged.

---

## How to Test

1. Open the deployed project link in a browser.  
2. Use the toolbar to:
   - Switch language (English/Bangla).
   - Enter a seed or leave it empty (random seed).
   - Adjust likes per song (fractional values allowed, e.g., 0.5, 1.2, 5).
   - Switch between Table and Gallery views.
3. **Table View:**
   - Click a row to expand/collapse song details.
   - Play the audio preview and read the review.
4. **Gallery View:**
   - Scroll to load more songs infinitely.
   - Play audio previews directly on the cards.
5. **Verify reproducibility:**
   - Enter a seed, change other parameters, then return to the same seed.
   - Song titles, artists, albums, and genres should remain identical.

---

## Links

- **Deployed Project:** [Your Deployment Link](https://example.com)  
- **GitHub Repository:** [song-generator](https://github.com/YourUsername/song-generator)  
- **Video Demonstration:** [Demo Video](https://example.com/video)

---

## Notes

- The application requires **no user registration or authentication**.  
- All data is generated dynamically on the server; **no database is required**.  
- Songs are reproducible across devices and dates using the **same seed**.  
- The likes per song are applied probabilistically; fractional likes are rounded in output.  
