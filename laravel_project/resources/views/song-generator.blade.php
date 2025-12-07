<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Song Generator</title>
    <style>
        body { padding: 20px; }
        .toolbar { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
        .table-container { margin-top: 20px; }
    </style>
</head>
<body>

<div class="toolbar">
    <select id="language" class="form-select" style="width:150px">
        <option value="en">English</option>
        <option value="bn">বাংলা</option>
    </select>

    <input type="number" id="seed" class="form-control" style="width:200px" placeholder="Random if empty">
    <input type="number" step="0.1" min="0" max="10" id="likes" class="form-control" style="width:150px" value="5" placeholder="Likes">

    <select id="view" class="form-select" style="width:150px">
        <option value="table">Table</option>
        <option value="gallery">Gallery</option>
    </select>
</div>

<div id="content" class="table-container"></div>

<script>
let songData = [];

async function fetchSongs() {
    const lang = document.getElementById("language").value;
    const seed = document.getElementById("seed").value;
    const likes = document.getElementById("likes").value;

    const res = await fetch("/api/generate-songs", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            language: lang,
            seed: seed,
            num_songs: 200,
            likes: parseFloat(likes)
        })
    });

    const data = await res.json();
    songData = data.songs;
}

function renderTable(page = 1) {
    const lang = document.getElementById("language").value;

    const labels = lang === "bn" ? {
        index: "#",
        title: "শিরোনাম",
        artist: "শিল্পী",
        album: "অ্যালবাম",
        genre: "ঘরানা",
        likes: "লাইক",
        prev: "পূর্ববর্তী",
        next: "পরবর্তী",
        page: "পৃষ্ঠা"
    } : {
        index: "#",
        title: "Title",
        artist: "Artist",
        album: "Album",
        genre: "Genre",
        likes: "Likes",
        prev: "Previous",
        next: "Next",
        page: "Page"
    };

    const itemsPerPage = 10;
    const totalPages = Math.ceil(songData.length / itemsPerPage);
    const start = (page - 1) * itemsPerPage;
    const pageSongs = songData.slice(start, start + itemsPerPage);

    let html = `<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>${labels.index}</th>
                <th>${labels.title}</th>
                <th>${labels.artist}</th>
                <th>${labels.album}</th>
                <th>${labels.genre}</th>
                <th>${labels.likes}</th>
            </tr>
        </thead>
        <tbody>`;

    pageSongs.forEach(s => {
        html += `<tr>
            <td>${s.index}</td>
            <td>${s.title}</td>
            <td>${s.artist}</td>
            <td>${s.album}</td>
            <td>${s.genre}</td>
            <td>${s.likes}</td>
        </tr>`;
    });

    html += `</tbody></table>`;

    html += `<div class="d-flex justify-content-between">
        <button class="btn btn-primary" ${page === 1 ? 'disabled' : ''} onclick="renderTable(${page-1})">${labels.prev}</button>
        <span>${labels.page} ${page} / ${totalPages}</span>
        <button class="btn btn-primary" ${page === totalPages ? 'disabled' : ''} onclick="renderTable(${page+1})">${labels.next}</button>
    </div>`;

    document.getElementById("content").innerHTML = html;
}

async function loadSongs(page = 1) {
    await fetchSongs();
    renderTable(page);
}

// Update dynamically on input change
document.querySelectorAll("#language, #seed, #likes, #view")
    .forEach(el => el.addEventListener("change", () => loadSongs(1)));

// Initial load
window.onload = () => loadSongs(1);
</script>

</body>
</html>
