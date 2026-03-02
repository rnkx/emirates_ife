<?php
include 'db.php';
/* Seat simulation */
$_SESSION['seat'] = $_SESSION['seat'] ?? '53H';

/* Language handling */
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'zh');
$_SESSION['lang'] = $lang;

/* Translations */
$T = [
    'zh' => [
        'back' => '返回',
        'playlist' => '我的播放列表',
        'songs' => '首歌曲',
        'desc' => '您收藏的音轨會自動形成您的個人播放列表',
        'play' => '播放',
        'pause' => '暫停',
        'next' => '下一首',
        'prev' => '上一首',
        'clear' => '移除全部',
         'now_playing' => '正在播放',
    'paused' => '已暂停',
    'stopped' => '停止',
    'none' => '无',
    ],
    'en' => [
        'back' => 'Back',
        'playlist' => 'My Playlist',
        'songs' => 'songs',
        'desc' => 'Audio tracks that you have favourited automatically form your own personal playlist',
        'play' => 'Play',
        'pause' => 'Pause',
        'next' => 'Next',
        'prev' => 'Previous',
        'clear' => 'Remove All',
        'now_playing' => 'Now Playing',
    'paused' => 'Paused',
    'stopped' => 'Stopped',
    'none' => 'None',
    ]
][$lang];

$dir = ($lang === 'ar') ? 'rtl' : 'ltr';

/* Song list from DB */
$songs = $conn->query("SELECT * FROM songs");
$songList = [];
if($songs){
    while ($s = $songs->fetch_assoc()) {
        $songList[] = $s;
    }
}
$count = count($songList);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
<meta charset="UTF-8">
<title>Emirates ICE</title>
<link rel="stylesheet" href="style.css">
<style>
.song-row.playing {
    background-color: #2b6cb0;  /* blue background */
}

.song-row.playing .song-title,
.song-row.playing .song-artist {
    color: #fff;                 /* white text */
}

.song-no.volume-icon::before {
    content: "🔊";
    font-size: 18px;
}
.main-content {
    flex: 1;                  /* takes remaining height */
    overflow-y: auto;
    padding-bottom: 80px;     /* space above ICE */
}

    .controls { margin-top: 15px; }
    .ctrl-btn, .clear-btn { padding: 10px 15px; margin-right: 10px; cursor: pointer; }
    .fav.active { color: red; }
    .heart-icon { font-size: 150px; text-align: center; margin-bottom: 15px; background: #f0f0f0; }
    /* LEFT PANEL */
.left-panel {
    width: 35%;
    background: #f0f0f0;
    display: flex;
    flex-direction: column;
    height: 100%;
    border-right: 1px solid #f0f0f0;
}
.playlist {
    max-height: 480px;      /* adjust based on your screen */
    overflow-y: auto;       /* enable vertical scrolling */
    padding-right: 8px;     /* prevent scrollbar overlap */
}
.app-container {
    max-width: 100px;
    margin: 0 auto;
}

/* header */
.page-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
}
/* HEADER BAR */
.page-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 20px;

    border-radius: 14px 14px 0 0;
    color: #ffffff;
}

/* BACK BUTTON */
.back-btn {
     padding: 8px 16px;
    font-weight: 500;
    border: none;
    align-items: center; 
       display: flex;
    border-radius: 6px;
    background: #0072ce;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
    margin-right: 20px;
    font-size: 16px;
}

/* TOP BAR */
.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 18px;
    background: white;
    color: black;
    font-size: 14px;
    position:relative;
       box-shadow: inset 0 0 0 1px #dddddd;
}

/* Flight info */
.flight-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.flight-info .plane {
    font-size: 16px;
}

/* News ticker */
.news-ticker {
     position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    text-align: center;
    overflow: hidden;
    white-space: nowrap;
    pointer-events: none; /* prevents blocking clicks */
     
      
}

.news-text {
    display: inline-block;
    padding-left: 100%;
    animation: scrollNews 18s linear infinite;
    color:black;
     text-align: center;
}


@keyframes scrollNews {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}


/* Language switch */
.lang-switch select {
    padding: 4px 8px;
    border-radius: 4px;
    border: none;
}

/* BACK ARROW */
.back-arrow {
    font-size: 18px;
    line-height: 1;
    background-color: #0072ce;
}

/* PAGE TITLE */
.page-title {
    font-size: 20px;
    font-weight: 600;
  
}
/* ICE DOCK */
.ice-dock {
    position: absolute;
    bottom: 16px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 50;
     font-size: 100px;
}

/* ICE BUTTON CONTAINER */
.ice-button {
    display: flex;
    gap: 20px;  
    border-color:white;
     border-radius: 14px 14px 0 0;
     padding: 1px 200px;
      font-size: 200px;
      
     
}
.ice-bar {       
    padding: 20px 30px;
    border-radius: 14px 14px 0 0;
    color: #ffffff;  
     font-size: 200px;
      gap: 20px; 
}

/* COMMON ICE BUTTON STYLE */
.ice {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    
    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 26px;
    font-weight: 800;
    color: #ffffff;
    text-transform: lowercase;

    box-shadow:
        inset 0 1px 2px rgba(255,255,255,0.35),
        0 3px 6px rgba(0,0,0,0.35);

    user-select: none;
}

/* INDIVIDUAL COLORS */
.ice.i {
    background: linear-gradient(#4fcf6f, #1f9e45);
    border: 2px solid #ffffff;
    
}

.ice.c {
    background: linear-gradient(#b478ff, #6b3fb3);
    border: 2px solid #ffffff;
}

.ice.e {
    background: linear-gradient(#5faeff, #1f6fd1);
    border: 2px solid #ffffff;
}

.ice-button button,
.ice-button .ice {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ice-group {
    display: flex;
    gap: 8px;         /* tighter ICE letters */
    margin-right: 18px;  /* ✅ SAME GAP before search */
}
/* Song row play button (default) */
.song-actions button {
    background: transparent;
    color: #555;
    border: none;
    font-size: 16px;
    cursor: pointer;
}

/* ✅ Playing song row play button */
.song-row.playing .song-actions button:first-child {
    color: #ffffff;        /* white icon */
    font-weight: bold;
}
/* ICE utility buttons (Home, Search, Heart) */
.ice-button .ctrl-btn {
    background-color: gray;        /* light gray */
    border: 1px solid #dcdcdc;
    border-radius: 10px;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
}
</style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">

    <!-- LEFT: Flight info -->
    <div class="flight-info">
        ✈ KUL → DXB <span class="flight-time">2h 52m</span>
    </div>

    <!-- CENTER: News Ticker -->
    <div class="news-ticker" align="center">
        <div class="news-text" align="center">
            Breaking News: Dubai International Airport ranked world’s busiest • Emirates launches new A350 routes • Global markets close higher today
        </div>
    </div>
    
    <div class="back"></div>
    <div class="top-title"></div>
    <form method="get" class="lang-switch" id="langForm">
        <select name="lang" onchange="saveAndSwitchLanguage(this.value)">
            <option value="zh" <?= $lang=='zh'?'selected':'' ?>>中文</option>
            <option value="en" <?= $lang=='en'?'selected':'' ?>>EN</option>
        </select>
    </form>
</div>

 <div class="page-header">
    <button class="back-btn">
        <span class="back-arrow">←</span>
         <span class="back-text"><?= $T['back'] ?></span>
    </button>

    <div class="page-title"><?= $T['playlist'] ?></div>
</div>

<!-- MAIN SCREEN -->
<div class="screen">

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="heart-icon">💙</div>
        <div class="left-text"><?= $T['desc'] ?></div>
    </div>

    <div class="right-panel">
        <div class="playlist-header"><?= $count ?> <?= $T['songs'] ?></div>
        <div class="playlist">
            <?php foreach($songList as $i => $song): ?>
            <div class="song-row" data-index="<?= $i ?>" data-title="<?= htmlspecialchars($song['title']) ?>" data-file="<?= htmlspecialchars($song['audio_file']) ?>">
                <div class="song-no"><?= $i+1 ?></div>
                <div class="song-info">
                    <div class="song-title"><?= $song['title'] ?></div>
                    <div class="song-artist"><?= $song['artist'] ?></div>
                </div>
                <div class="song-actions">
                 <button class="row-play-btn"
        onclick="playSong(<?= $i ?>, this.closest('.song-row'))">
    ▶
</button>
                    <button class="fav" onclick="toggleFavourite(this, <?= $song['id'] ?>)">💙</button>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if($count==0) echo "<div>No songs found in the database.</div>"; ?>
        </div>

        <div class="controls">
            <button class="ctrl-btn" onclick="prevSong()">⏮ <?= $T['prev'] ?></button>
            <button class="ctrl-btn" id="playPauseBtn" onclick="togglePlayPause()">▶ <?= $T['play'] ?></button>
            <button class="ctrl-btn" onclick="nextSong()">⏭ <?= $T['next'] ?></button>
        </div>

        <audio id="audioPlayer"></audio>
        <div class="now-playing" id="nowPlaying">
            <span id="nowState"><?= $T['now_playing'] ?></span>: <span id="nowTitle"><?= $T['none'] ?></span> - <span id="nowArtist">---</span>
        </div>
    </div>
    
</div>
<br/>

 <!-- ICE BUTTON BAR (BOTTOM) -->
    <div class="ice-bar">
        
        <div class="ice-button">

        <button class="ctrl-btn home" title="Home">🏠</button>
             <div class="ice-group">
        <span class="ice i">i</span>
        <span class="ice c">c</span>
        <span class="ice e">e</span>
    </div>       
             <button class="ctrl-btn home" title="Home">🔍</button>
              <button class="ctrl-btn home" title="Home">❤</button>
        </div>
    </div>
<br/>

<script>
const songs = <?php echo json_encode($songList); ?>;
let currentIndex = null;
let currentSongRow = null;
const audioPlayer = document.getElementById('audioPlayer');
const playPauseBtn = document.getElementById('playPauseBtn');

// --- Restore previous state from URL ---
const urlParams = new URLSearchParams(window.location.search);
const savedIndex = parseInt(urlParams.get('song'));
const savedTime = parseFloat(urlParams.get('time'));
const savedState = urlParams.get('state') || 'stopped';
const savedScroll = parseInt(urlParams.get('scroll')) || 0;

window.addEventListener('DOMContentLoaded', () => {
    const playlist = document.querySelector('.playlist');
    playlist.scrollTop = savedScroll;

    if(!isNaN(savedIndex) && songs[savedIndex]){
        const row = document.querySelector(`.song-row[data-index='${savedIndex}']`);
        if(row){
            currentIndex = savedIndex;
            currentSongRow = row;
            audioPlayer.src = songs[savedIndex].audio_file;
            if(!isNaN(savedTime)) audioPlayer.currentTime = savedTime;

            if(savedState==='playing') audioPlayer.play().catch(()=>{});
            else if(savedState==='paused') audioPlayer.pause();
            else if(savedState==='stopped') { audioPlayer.pause(); audioPlayer.src = ''; currentIndex = null; currentSongRow = null; }

            highlightRow();
            updateNowPlayingState();
            scrollToCurrentSong();
        }
    }
    updatePlayPauseButton();
});

// --- Language switch preserving state ---
function saveAndSwitchLanguage(lang){
    const state = audioPlayer.src
        ? audioPlayer.paused ? 'paused' : 'playing'
        : 'stopped';

    const scroll = document.querySelector('.playlist').scrollTop || 0;
    const time = audioPlayer.currentTime || 0;
    const index = currentIndex ?? -1;

    const url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    url.searchParams.set('song', index);
    url.searchParams.set('time', time.toFixed(2));
    url.searchParams.set('state', state);
    url.searchParams.set('scroll', scroll);

    window.location.href = url.toString();
}

// --- Play song ---
function playSong(index, row){
    if(!songs[index]) return;
    audioPlayer.src = songs[index].audio_file;
    audioPlayer.play().catch(()=>{});
    currentIndex = index;

    if(currentSongRow){
        currentSongRow.classList.remove('playing');
        const prevNo = currentSongRow.querySelector('.song-no');
        prevNo.classList.remove('volume-icon');
        prevNo.innerText = prevNo.dataset.number;
    }

    currentSongRow = row;
    highlightRow();
    updateNowPlayingState();
    updatePlayPauseButton();
    scrollToCurrentSong();
    // Reset all row play buttons
document.querySelectorAll('.row-play-btn').forEach(btn => {
    btn.innerText = '▶';
});



}

// --- Highlight current row ---
function highlightRow(){
    if(!currentSongRow) return;
    currentSongRow.classList.add('playing');
    const no = currentSongRow.querySelector('.song-no');
    no.dataset.number = no.innerText;
    no.innerText = '';
    no.classList.add('volume-icon');
}

// --- Play/Pause toggle ---
function togglePlayPause(){
    if(currentIndex===null){
        const firstRow = document.querySelector('.song-row');
        if(firstRow) playSong(0, firstRow);
        return;
    }
    
  const rowBtn = currentSongRow?.querySelector('.row-play-btn');
  
       if(audioPlayer.paused){
        audioPlayer.play();
        if(rowBtn) rowBtn.innerText = '⏸';
    } else {
        audioPlayer.pause();
        if(rowBtn) rowBtn.innerText = '▶';
    }

    updatePlayPauseButton();
    updateNowPlayingState();
}

// --- Update Play/Pause button ---
function updatePlayPauseButton(){
    if (!audioPlayer.src || audioPlayer.paused) {
        playPauseBtn.innerText = '▶ <?= $T['play'] ?>';
        playPauseBtn.classList.remove('playing');
    } else {
        playPauseBtn.innerText = '⏸ <?= $T['pause'] ?>';
        playPauseBtn.classList.add('playing'); // ✅ turn white
    }
}


// --- Next / Previous ---
function nextSong(){
    if(currentIndex===null){ playSong(0, document.querySelector('.song-row')); return; }
    const nextIndex = (currentIndex + 1) % songs.length;
    playSong(nextIndex, document.querySelector(`.song-row[data-index='${nextIndex}']`));
}
function prevSong(){
    if(currentIndex===null){ playSong(0, document.querySelector('.song-row')); return; }
    const prevIndex = (currentIndex - 1 + songs.length) % songs.length;
    playSong(prevIndex, document.querySelector(`.song-row[data-index='${prevIndex}']`));
}

// --- Auto next when ended ---
audioPlayer.addEventListener('ended', () => {
    const rowBtn = currentSongRow?.querySelector('.row-play-btn');
if (rowBtn) rowBtn.innerText = '▶';

    nextSong();
    updatePlayPauseButton();
});

// --- Favourites ---
function toggleFavourite(btn, id){ btn.classList.toggle('active'); }
function clearFavourites(){
    if(currentSongRow) currentSongRow.classList.remove('playing');
    audioPlayer.pause();
    audioPlayer.src = '';
    currentIndex = null;
    currentSongRow = null;
    document.querySelectorAll('.fav').forEach(b => b.classList.remove('active'));
    updateNowPlayingState();
    updatePlayPauseButton();
}

// --- Now playing info ---
function updateNowPlayingState(){
    const stateEl = document.getElementById('nowState');
    const titleEl = document.getElementById('nowTitle');
    const artistEl = document.getElementById('nowArtist');

    if(!audioPlayer.src){
        stateEl.innerText = '<?= $T['stopped'] ?>';
        titleEl.innerText = '<?= $T['none'] ?>';
        artistEl.innerText = '---';
    } else if(audioPlayer.paused){
        stateEl.innerText = '<?= $T['paused'] ?>';
        titleEl.innerText = songs[currentIndex].title;
        artistEl.innerText = songs[currentIndex].artist;
    } else {
        stateEl.innerText = '<?= $T['now_playing'] ?>';
        titleEl.innerText = songs[currentIndex].title;
        artistEl.innerText = songs[currentIndex].artist;
    }
}

// --- Scroll to current song ---
function scrollToCurrentSong(){
    if(currentSongRow) currentSongRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

</script>
</body>
</html>
