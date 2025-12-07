<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .expand-row { cursor: pointer; }
        .hidden { display: none; }
        #gallery div { display: inline-block; margin: 10px; vertical-align: top; }
        #gallery img { width: 150px; height: 150px; object-fit: cover; display: block; }
    </style>
</head>
<body class="p-3">

<div class="mb-3 d-flex gap-3 align-items-center">
    <select id="language" class="form-select w-auto">
        <option value="en">English</option>
        <option value="bn">Bangla</option>
    </select>
    <input type="number" id="seed" class="form-control w-auto" placeholder="Seed">
    <input type="number" step="0.1" id="likes" class="form-control w-auto" placeholder="Likes" value="5">
    <select id="view" class="form-select w-auto">
        <option value="table">Table</option>
        <option value="gallery">Gallery</option>
    </select>
</div>

<!-- Table Container -->
<div id="tableContainer">
    <table class="table table-bordered" id="songTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Genre</th>
                <th>Likes</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="d-flex justify-content-between">
        <button id="prevPage" class="btn btn-secondary btn-sm">Previous</button>
        <span id="pageInfo"></span>
        <button id="nextPage" class="btn btn-secondary btn-sm">Next</button>
    </div>
</div>

<!-- Gallery Container -->
<div id="galleryContainer" class="hidden">
    <div id="gallery"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let songs = [];
let currentPage = 1;
let songsPerPage = 10;
let totalPages = 1;

// Translation map
const translations = {
    en: { headers: ['#', 'Title', 'Artist', 'Album', 'Genre', 'Likes'], seed: 'Seed', likes: 'Likes' },
    bn: { headers: ['ক্রমিক', 'শিরোনাম', 'শিল্পী', 'অ্যালবাম', 'ধরন', 'পছন্দ'], seed: 'সিড', likes: 'লাইক' }
};

async function fetchSongs() {
    const language = document.getElementById('language').value;
    const seed = document.getElementById('seed').value || null;
    const likes = parseFloat(document.getElementById('likes').value) || 5;

    const response = await axios.post('/api/generate-songs', { language, seed, likes, num_songs: 100 });
    songs = response.data.songs;
    totalPages = Math.ceil(songs.length / songsPerPage);
    renderView();
}

function renderView() {
    const view = document.getElementById('view').value;
    if(view === 'table') {
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('galleryContainer').style.display = 'none';
        renderTable();
    } else {
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('galleryContainer').style.display = 'block';
        renderGallery();
    }
}

function renderTable() {
    const tbody = document.querySelector('#songTable tbody');
    tbody.innerHTML = '';
    const start = (currentPage-1)*songsPerPage;
    const end = start + songsPerPage;
    const pageSongs = songs.slice(start, end);

    pageSongs.forEach(song => {
        const tr = document.createElement('tr');
        tr.classList.add('expand-row');
        tr.innerHTML = `
            <td>${song.index}</td>
            <td>${song.title}</td>
            <td>${song.artist}</td>
            <td>${song.album}</td>
            <td>${song.genre}</td>
            <td>${song.likes}</td>
        `;
        tr.addEventListener('click', () => toggleDetails(tr, song));
        tbody.appendChild(tr);
    });

    document.getElementById('pageInfo').textContent = `${currentPage} / ${totalPages}`;
}

function toggleDetails(row, song) {
    if(row.nextSibling && row.nextSibling.classList.contains('detail-row')) {
        row.nextSibling.remove();
        return;
    }
    const tr = document.createElement('tr');
    tr.classList.add('detail-row');
    tr.innerHTML = `
        <td colspan="6">
            <div class="d-flex align-items-center gap-3">
                <img src="${song.cover}" alt="cover" width="100">
                <div>
                    <strong>${song.title}</strong> by ${song.artist}<br>
                    <audio controls src="${song.preview}"></audio><br>
                    Review: ${song.review}
                </div>
            </div>
        </td>
    `;
    row.after(tr);
}

function renderGallery() {
    const gallery = document.getElementById('gallery');
    gallery.innerHTML = '';
    songs.forEach(song => {
        const div = document.createElement('div');
        div.innerHTML = `
            <img src="${song.cover}" alt="${song.title}">
            <div><strong>${song.title}</strong></div>
            <div>${song.artist}</div>
            <audio controls src="${song.preview}"></audio>
            <div>Review: ${song.review}</div>
        `;
        gallery.appendChild(div);
    });
}


// Pagination buttons
document.getElementById('prevPage').addEventListener('click', () => {
    if(currentPage > 1) currentPage--;
    renderTable();
});
document.getElementById('nextPage').addEventListener('click', () => {
    if(currentPage < totalPages) currentPage++;
    renderTable();
});

// Update placeholders and headers based on language
function updatePlaceholders() {
    const language = document.getElementById('language').value;
    const trans = translations[language];

    document.getElementById('seed').placeholder = trans.seed;
    document.getElementById('likes').placeholder = trans.likes;

    const ths = document.querySelectorAll('#songTable thead th');
    trans.headers.forEach((header, i) => ths[i].textContent = header);
}

document.getElementById('language').addEventListener('change', () => {
    updatePlaceholders();
    fetchSongs();
});
document.getElementById('seed').addEventListener('input', fetchSongs);
document.getElementById('likes').addEventListener('input', fetchSongs);
document.getElementById('view').addEventListener('change', () => {
    currentPage = 1;
    renderView();
});

window.addEventListener('load', () => {
    updatePlaceholders();
    fetchSongs();
});
</script>
</body>
</html>
