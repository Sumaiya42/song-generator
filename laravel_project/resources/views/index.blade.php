<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Generator</title>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .toolbar { display: flex; gap: 20px; align-items: center; margin-bottom: 20px; }
        .toolbar label { font-weight: bold; margin-right: 5px; }
        .view-buttons button { margin-right: 10px; cursor: pointer; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #eee; }

        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 15px; }
        .card { padding: 10px; border-radius: 6px; border: 1px solid #ccc; background: #f9f9f9; }
    </style>
</head>

<body>

    <h2>Song Generator</h2>

    <!-- Toolbar -->
    <div class="toolbar">

        <div>
            <label>Language:</label>
            <select id="language">
                <option value="en">English</option>
                <option value="bn">Bengali</option>
            </select>
        </div>

        <div>
            <label>Seed:</label>
            <input type="text" id="seed" placeholder="Random if empty" style="width:140px;">
        </div>

        <div>
            <label>Likes:</label>
            <input type="number" id="likes" step="0.1" min="0" max="10" value="5" style="width:70px;">
        </div>

        <div class="view-buttons">
            <label>View:</label>
            <button onclick="setView('table')">Table</button>
            <button onclick="setView('gallery')">Gallery</button>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView" style="display:block;">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Title</th><th>Artist</th><th>Album</th><th>Genre</th><th>Likes</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <div style="margin-top:15px;">
            <button id="prevBtn">Previous</button>
            <span id="pageNumber"></span>
            <button id="nextBtn">Next</button>
        </div>
    </div>

    <!-- Gallery View -->
    <div id="galleryView" style="display:none; height:600px; overflow-y:auto;">
        <div id="galleryContainer" class="gallery"></div>
    </div>


<script>

let allSongs = [];           // full data set
let currentPage = 1;         // table pagination
const pageSize = 10;

let currentView = "table";   // table or gallery

// Call API
async function fetchSongs() {
    let lang = document.getElementById("language").value;
    let seed = document.getElementById("seed").value;
    let likes = document.getElementById("likes").value;

    // Reset page & scroll on ANY parameter change
    currentPage = 1;
    document.getElementById("galleryView").scrollTop = 0;

    let payload = {
        language: lang,
        likes: parseFloat(likes),
        num_songs: 200   // enough data for scrolling/pagination
    };

    if (seed !== "") payload.seed = seed;

    let response = await fetch("/api/generate-songs", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    });

    let data = await response.json();
    allSongs = data.songs;

    render();
}

// Render based on view
function render() {
    if (currentView === "table") renderTable();
    else renderGallery();
}

// TABLE VIEW --------------------------------------
function renderTable() {
    let start = (currentPage - 1) * pageSize;
    let end = start + pageSize;
    let pageData = allSongs.slice(start, end);

    let html = "";
    pageData.forEach(s => {
        html += `
            <tr>
                <td>${s.index}</td>
                <td>${s.title}</td>
                <td>${s.artist}</td>
                <td>${s.album}</td>
                <td>${s.genre}</td>
                <td>${s.likes}</td>
            </tr>
        `;
    });

    document.getElementById("tableBody").innerHTML = html;
    document.getElementById("pageNumber").innerText =
        "Page " + currentPage + " of " + Math.ceil(allSongs.length / pageSize);
}

document.getElementById("prevBtn").onclick = () => {
    if (currentPage > 1) { currentPage--; renderTable(); }
};
document.getElementById("nextBtn").onclick = () => {
    if (currentPage * pageSize < allSongs.length) { currentPage++; renderTable(); }
};


// GALLERY VIEW -------------------------------------
function renderGallery() {
    let container = document.getElementById("galleryContainer");
    container.innerHTML = "";

    // show only first 20 initially (batch)
    loadMoreGalleryItems(20);

    document.getElementById("galleryView").onscroll = galleryScrollHandler;
}

let galleryLoaded = 20;

function galleryScrollHandler() {
    let div = document.getElementById("galleryView");

    if (div.scrollTop + div.clientHeight >= div.scrollHeight - 10) {
        loadMoreGalleryItems(20);
    }
}

function loadMoreGalleryItems(batch) {
    let container = document.getElementById("galleryContainer");

    let end = Math.min(galleryLoaded + batch, allSongs.length);

    for (let i = galleryLoaded; i < end; i++) {
        let s = allSongs[i];
        let card = `
            <div class="card">
                <h4>${s.title}</h4>
                <p><b>Artist:</b> ${s.artist}</p>
                <p><b>Album:</b> ${s.album}</p>
                <p><b>Genre:</b> ${s.genre}</p>
                <p><b>Likes:</b> ${s.likes}</p>
            </div>
        `;
        container.insertAdjacentHTML("beforeend", card);
    }

    galleryLoaded = end;
}


// SWITCH VIEW -------------------------------------
function setView(v) {
    currentView = v;

    document.getElementById("tableView").style.display =
        v === "table" ? "block" : "none";

    document.getElementById("galleryView").style.display =
        v === "gallery" ? "block" : "none";

    // Reset gallery when switching
    document.getElementById("galleryContainer").innerHTML = "";
    galleryLoaded = 20;
    document.getElementById("galleryView").scrollTop = 0;

    render();
}


// EVENT LISTENERS
document.getElementById("language").onchange = fetchSongs;
document.getElementById("likes").onchange = fetchSongs;
document.getElementById("seed").oninput = fetchSongs;

// Initial load
fetchSongs();

</script>

</body>
</html>
