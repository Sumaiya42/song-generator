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

## How to Test Locally (Using Docker)

1. Clone the repository
   \\\ bash
    git clone https://github.com/Sumaiya42/song-generator.git
    cd song-generator/laravel_project

2. Copy environment file
    \\\ bash
    cp .env.example .env
   
3. Build and start Docker containers
   \\\ bash
   docker-compose build app
   docker-compose up -d

4. Run database migrations
   \\\ bash
   docker-compose exec app php artisan migrate

7. Open a browser and go to: http://localhost:8080

---

## Links

- **Deployed Project:** [Your Deployment Link](https://song-generator-11.onrender.com)  
- **GitHub Repository:** [song-generator](https://github.com/Sumaiya42/song-generator)  

---

## Notes

- The application requires **no user registration or authentication**.  
- All data is generated dynamically on the server; **no database is required**.  
- Songs are reproducible across devices and dates using the **same seed**.  
- The likes per song are applied probabilistically; fractional likes are rounded in output.  
