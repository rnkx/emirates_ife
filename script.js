const audio = document.getElementById('audioPlayer');

/* PLAY SONG */
function playSong(title, file) {
    audio.src = 'audio/' + file;
    audio.play();
    updateNowPlaying(title);
}

/* PLAY FIRST SONG */
function playFirst() {
    const first = document.querySelector('.song-row');
    if (!first) return;

    playSong(
        first.dataset.title,
        first.dataset.file
    );
}

/* UPDATE NOW PLAYING */
function updateNowPlaying(title) {
    let now = document.getElementById('nowPlaying');
    if (!now) {
        now = document.createElement('div');
        now.id = 'nowPlaying';
        now.style.marginTop = '10px';
        document.querySelector('.controls').after(now);
    }
    now.innerText = '▶ ' + title;
}

/* TOGGLE FAVOURITE */
function toggleFavourite(songId) {
    fetch('toggle_favourite.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'song_id=' + songId
    }).then(() => location.reload());
}

/* CLEAR PLAYLIST */
function clearFavourites() {
    if (!confirm('Remove all favourites?')) return;

    fetch('clear_favourites.php')
        .then(() => location.reload());
}
