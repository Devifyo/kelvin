<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Happy Easter, Bulbula 🌸</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

:root {
  --blush:    #f7c5d0;
  --rose:     #e8839a;
  --deep-rose:#c45a74;
  --lilac:    #d8b4e2;
  --lavender: #ede0f5;
  --mint:     #b8e8d4;
  --peach:    #fad4b8;
  --cream:    #fff8f5;
  --white:    #ffffff;
  --text:     #5a2d42;
  --text-soft:#9b5f72;
  --gold:     #e8a86c;
}

html { scroll-behavior: smooth; }

body {
  min-height: 100vh;
  font-family: 'Lato', sans-serif;
  background: var(--cream);
  overflow-x: hidden;
  cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'%3E%3Ctext y='20' font-size='18'%3E🌸%3C/text%3E%3C/svg%3E") 12 12, auto;
}

/* ── ANIMATED BACKGROUND ── */
.bg-gradient {
  position: fixed; inset: 0; z-index: 0;
  background:
    radial-gradient(ellipse at 15% 20%, rgba(216,180,226,0.45) 0%, transparent 50%),
    radial-gradient(ellipse at 85% 10%, rgba(247,197,208,0.5) 0%, transparent 45%),
    radial-gradient(ellipse at 50% 80%, rgba(184,232,212,0.35) 0%, transparent 50%),
    radial-gradient(ellipse at 90% 80%, rgba(250,212,184,0.4) 0%, transparent 40%),
    linear-gradient(160deg, #fff0f5 0%, #f5e8ff 40%, #e8fff4 80%, #fff8f0 100%);
  animation: bgShift 12s ease-in-out infinite alternate;
}
@keyframes bgShift {
  0%   { filter: hue-rotate(0deg) brightness(1); }
  100% { filter: hue-rotate(15deg) brightness(1.04); }
}

/* ── FLOATING PETALS ── */
.petals-layer { position: fixed; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
.fp {
  position: absolute; top: -60px; font-size: var(--fs);
  animation: fallDown var(--spd) var(--del) linear infinite;
  opacity: 0.75;
}
@keyframes fallDown {
  0%   { transform: translateY(-60px) translateX(0) rotate(0deg); opacity: 0; }
  5%   { opacity: 0.8; }
  95%  { opacity: 0.6; }
  100% { transform: translateY(110vh) translateX(var(--dx)) rotate(360deg); opacity: 0; }
}

/* ── SPARKLES ── */
.sparkles { position: fixed; inset: 0; z-index: 1; pointer-events: none; }
.sp {
  position: absolute;
  animation: sparkle var(--sd) var(--sdel) ease-in-out infinite;
}
.sp::before {
  content: '✦'; font-size: var(--sfs);
  color: var(--rose); opacity: 0;
}
@keyframes sparkle {
  0%,100% { transform: scale(0) rotate(0deg); opacity: 0; }
  50% { transform: scale(1) rotate(180deg); opacity: 1; }
}

/* ── SCROLL WRAPPER ── */
.page { position: relative; z-index: 10; padding-bottom: 80px; }

/* ── HERO ── */
.hero {
  min-height: 100vh;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  text-align: center;
  padding: 40px 24px;
  position: relative;
}

.crown { font-size: 42px; animation: bounce 2s ease-in-out infinite; margin-bottom: 8px; }
@keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }

.hero-for {
  font-family: 'Lato', sans-serif;
  font-weight: 300; font-size: 13px;
  letter-spacing: 0.4em; color: var(--text-soft);
  text-transform: uppercase; margin-bottom: 12px;
  animation: fadeSlide 1s 0.3s both;
}
.hero-name {
  font-family: 'Dancing Script', cursive;
  font-size: clamp(58px, 14vw, 110px);
  font-weight: 700;
  color: var(--deep-rose);
  line-height: 0.95;
  text-shadow:
    2px 3px 0px rgba(228,131,154,0.3),
    0 0 60px rgba(232,131,154,0.25);
  animation: fadeSlide 1.2s 0.5s both;
}
.hero-nickname {
  font-family: 'Dancing Script', cursive;
  font-size: clamp(22px, 5vw, 36px);
  color: var(--lilac);
  font-weight: 600;
  animation: fadeSlide 1.2s 0.7s both;
  margin-top: 4px;
}
.hero-nickname span { color: var(--rose); }

.pill-badge {
  display: inline-flex; align-items: center; gap: 6px;
  margin: 18px auto 0;
  background: rgba(255,255,255,0.7);
  border: 1.5px solid var(--blush);
  border-radius: 50px;
  padding: 8px 22px;
  font-size: 13px; color: var(--text-soft);
  font-family: 'Lato', sans-serif; font-weight: 400;
  backdrop-filter: blur(8px);
  animation: fadeSlide 1.2s 0.9s both;
  box-shadow: 0 4px 20px rgba(228,131,154,0.12);
}

@keyframes fadeSlide {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}

.scroll-hint {
  position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%);
  display: flex; flex-direction: column; align-items: center; gap: 6px;
  animation: fadeSlide 1s 2s both;
  color: var(--text-soft); font-size: 11px; letter-spacing: 0.2em;
  text-transform: uppercase;
}
.scroll-hint .arrow { animation: arrowBounce 1.4s ease-in-out infinite; font-size: 16px; }
@keyframes arrowBounce { 0%,100%{transform:translateY(0)} 60%{transform:translateY(6px)} }

/* ── SECTIONS ── */
.section {
  max-width: 640px; margin: 0 auto;
  padding: 70px 24px 0;
  text-align: center;
}

.section-tag {
  display: inline-block;
  background: linear-gradient(135deg, var(--blush), var(--lilac));
  color: var(--deep-rose);
  font-size: 10px; font-weight: 400; letter-spacing: 0.35em;
  text-transform: uppercase; padding: 5px 16px;
  border-radius: 50px; margin-bottom: 20px;
}

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(26px, 6vw, 42px);
  color: var(--text);
  font-style: italic;
  line-height: 1.2;
  margin-bottom: 10px;
}

/* ── VERSE CARD ── */
.verse-card {
  background: rgba(255,255,255,0.72);
  border: 1.5px solid rgba(247,197,208,0.6);
  border-radius: 24px;
  padding: 36px 36px;
  box-shadow:
    0 8px 40px rgba(228,131,154,0.1),
    0 2px 8px rgba(228,131,154,0.06),
    inset 0 1px 0 rgba(255,255,255,0.9);
  backdrop-filter: blur(10px);
  position: relative;
  overflow: hidden;
  margin-top: 28px;
}
.verse-card::before {
  content: '"';
  position: absolute; top: -10px; left: 16px;
  font-family: 'Playfair Display', serif;
  font-size: 120px; color: var(--blush); opacity: 0.5;
  line-height: 1; pointer-events: none;
}
.verse-text {
  font-family: 'Playfair Display', serif;
  font-size: clamp(16px, 3vw, 20px);
  font-style: italic;
  color: var(--text);
  line-height: 1.85;
  position: relative; z-index: 1;
}
.verse-ref {
  margin-top: 16px;
  font-size: 12px; letter-spacing: 0.25em;
  color: var(--rose); text-transform: uppercase;
  font-weight: 400;
}

/* ── CROSS + RESURRECTION ── */
.resurrection-wrap {
  margin: 60px auto 0;
  max-width: 500px;
}
.resurrection-svg { width: 100%; }

/* ── LOVE LETTERS CARDS ── */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 18px;
  margin-top: 32px;
}
.love-card {
  background: rgba(255,255,255,0.75);
  border: 1.5px solid rgba(247,197,208,0.5);
  border-radius: 20px;
  padding: 28px 24px;
  box-shadow: 0 6px 28px rgba(228,131,154,0.1);
  backdrop-filter: blur(8px);
  text-align: left;
  transition: transform 0.35s ease, box-shadow 0.35s ease;
}
.love-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 40px rgba(228,131,154,0.18);
}
.love-card .icon { font-size: 30px; margin-bottom: 12px; }
.love-card .card-title {
  font-family: 'Playfair Display', serif;
  font-size: 16px; font-style: italic;
  color: var(--text); margin-bottom: 8px;
}
.love-card .card-body {
  font-size: 14px; color: var(--text-soft);
  line-height: 1.7; font-weight: 300;
}
.love-card .card-body strong { color: var(--deep-rose); font-weight: 600; }

/* ── BIG MESSAGE REVEAL ── */
.reveal-section { padding: 90px 24px 0; text-align: center; }

.letter-envelope {
  max-width: 560px; margin: 20px auto 0;
  cursor: pointer;
  position: relative;
}
.envelope-outer {
  background: linear-gradient(145deg, #fff0f5, #fce8f5);
  border: 2px solid var(--blush);
  border-radius: 20px;
  padding: 40px 36px 36px;
  box-shadow:
    0 12px 50px rgba(228,131,154,0.25),
    0 3px 10px rgba(228,131,154,0.15);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative; overflow: hidden;
}
.envelope-outer:hover { transform: scale(1.02); box-shadow: 0 20px 70px rgba(228,131,154,0.3); }
.envelope-seal {
  width: 56px; height: 56px; border-radius: 50%;
  background: linear-gradient(135deg, var(--rose), var(--deep-rose));
  display: flex; align-items: center; justify-content: center;
  font-size: 26px;
  margin: 0 auto 24px;
  box-shadow: 0 4px 16px rgba(196,90,116,0.3);
  animation: sealPulse 2s ease-in-out infinite;
}
@keyframes sealPulse {
  0%,100% { transform: scale(1); box-shadow: 0 4px 16px rgba(196,90,116,0.3); }
  50%      { transform: scale(1.06); box-shadow: 0 6px 24px rgba(196,90,116,0.45); }
}
.envelope-preview {
  font-family: 'Dancing Script', cursive;
  font-size: 24px; color: var(--text-soft); font-weight: bold;
}

/* ── MASSIVE FAST-VIBRATING BUTTON ── */
.open-btn {
  margin-top: 30px;
  display: inline-flex; align-items: center; justify-content: center; gap: 12px;
  background: linear-gradient(135deg, #ff4d79, var(--deep-rose)); 
  color: #fff; 
  border: 3px solid #fff;
  padding: 20px 40px; 
  border-radius: 50px;
  font-family: 'Lato', sans-serif;
  font-size: clamp(16px, 4vw, 22px); 
  font-weight: 900; 
  letter-spacing: 0.1em; 
  text-transform: uppercase;
  cursor: pointer;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 10px 40px rgba(196,90,116,0.6);
  transition: transform 0.25s, box-shadow 0.25s;
  /* FAST VIBRATION ANIMATION */
  animation: fastBuzz 0.15s infinite linear;
}

/* Stop vibration and enlarge when she hovers */
.open-btn:hover {
  animation: none;
  transform: translateY(-4px) scale(1.05);
  box-shadow: 0 15px 50px rgba(196,90,116,0.8);
}
.open-btn:active { transform: scale(0.95); }

/* The fast, erratic buzz */
@keyframes fastBuzz {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  20% { transform: translate(-2px, 0px) rotate(-1deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  60% { transform: translate(-1px, 2px) rotate(0deg); }
  80% { transform: translate(2px, -1px) rotate(1deg); }
  100% { transform: translate(-1px, 0px) rotate(-1deg); }
}

/* ── LETTER CONTENT ── */
.letter-content {
  display: none; opacity: 0;
  max-width: 560px; margin: 28px auto 0;
  background: rgba(255,255,255,0.95);
  border: 2px solid rgba(247,197,208,0.8);
  border-radius: 20px;
  padding: 40px 38px;
  box-shadow: 0 20px 60px rgba(228,131,154,0.3);
  backdrop-filter: blur(10px);
  transition: opacity 0.8s ease;
  text-align: left;
  position: relative;
  z-index: 10;
}
.letter-content.open { display: block; }
.letter-content.visible { opacity: 1; }
.letter-content::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
  background: linear-gradient(to right, var(--blush), var(--rose), var(--lilac), var(--mint), var(--peach));
  border-radius: 20px 20px 0 0;
}
.letter-greeting {
  font-family: 'Dancing Script', cursive;
  font-size: 32px; color: var(--deep-rose); margin-bottom: 20px; font-weight: bold;
}
.letter-body {
  font-size: 17px; line-height: 2; color: var(--text);
  font-weight: 400;
}
.letter-body em { color: var(--rose); font-style: italic; font-weight: 600; }
.letter-body strong { color: var(--deep-rose); font-weight: 700; }
.letter-sign {
  margin-top: 32px;
  font-family: 'Dancing Script', cursive;
  font-size: 26px; color: var(--text-soft);
  text-align: right;
}
.letter-sign span { display: block; color: var(--rose); font-size: 24px; margin-top: 6px; font-weight: bold;}
.confetti-row { text-align: center; margin-top: 24px; font-size: 24px; letter-spacing: 8px; }

/* ── VERSE 2 ── */
.verse2-wrap {
  max-width: 520px; margin: 70px auto 0; text-align: center; padding: 0 24px;
}
.big-verse {
  font-family: 'Playfair Display', serif;
  font-size: clamp(20px, 4vw, 28px);
  font-style: italic;
  color: var(--text);
  line-height: 1.7;
}
.big-verse em { color: var(--deep-rose); }

/* ── FOOTER ── */
.footer {
  margin-top: 80px; text-align: center; padding: 0 24px 10px;
}
.footer-flowers { font-size: 28px; letter-spacing: 8px; animation: swing 3s ease-in-out infinite; }
@keyframes swing { 0%,100%{transform:rotate(-3deg)} 50%{transform:rotate(3deg)} }
.footer-text {
  margin-top: 16px;
  font-family: 'Dancing Script', cursive;
  font-size: 22px; color: var(--rose);
}
.footer-sub {
  font-size: 12px; letter-spacing: 0.3em; color: var(--text-soft);
  text-transform: uppercase; margin-top: 6px; font-weight: 300;
}

/* ── FLOATING BANNER (HIDDEN OFF TOP BY DEFAULT) ── */
.urgent-stop {
  position: fixed;
  top: 0; left: 0; right: 0;
  background: #ff0044;
  color: white;
  text-align: center;
  padding: 16px;
  font-family: 'Lato', sans-serif;
  font-size: clamp(14px, 4vw, 18px);
  font-weight: 900;
  letter-spacing: 0.1em;
  z-index: 9999;
  cursor: pointer;
  box-shadow: 0 10px 30px rgba(255,0,68,0.5);
  transform: translateY(-100%);
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.urgent-stop.show {
  transform: translateY(0);
}
.urgent-stop:hover {
  background: #e6003d;
}

/* ── MAGICAL EXPLOSION ANIMATIONS ── */
.big-center-heart {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%) scale(0);
  font-size: 180px;
  z-index: 9998;
  pointer-events: none;
  animation: pulseBigHeart 1.8s ease-out forwards;
  filter: drop-shadow(0 0 50px rgba(255, 105, 180, 0.9));
}

@keyframes pulseBigHeart {
  0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
  30% { transform: translate(-50%, -50%) scale(1.2); opacity: 1; }
  100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
}

.magic-particle {
  position: fixed;
  top: 50%; left: 50%;
  pointer-events: none;
  z-index: 9999;
  transform: translate(-50%, -50%) scale(0);
  animation: magicExplode 2s cubic-bezier(0.25, 1, 0.5, 1) forwards;
  filter: drop-shadow(0 0 10px rgba(255,255,255,0.8));
}

@keyframes magicExplode {
  0% {
    transform: translate(-50%, -50%) scale(0) rotate(0deg);
    opacity: 1;
  }
  50% {
    opacity: 1;
  }
  100% {
    transform: translate(calc(-50% + var(--tx)), calc(-50% + var(--ty))) scale(1.5) rotate(var(--rot));
    opacity: 0;
  }
}

/* Small flying hearts for normal clicks */
.burst-heart {
  position: fixed; pointer-events: none; z-index: 999;
  font-size: 26px;
  animation: burstUp 1.2s ease-out forwards;
}
@keyframes burstUp {
  0% { opacity: 1; transform: translateY(0) scale(1); }
  100% { opacity: 0; transform: translateY(-150px) scale(0.5) rotate(30deg); }
}
</style>
</head>
<body>

<div class="urgent-stop" id="urgentStop" onclick="scrollToLetter()">
  ⚠️ WAIT! DON'T SCROLL PAST THIS! CLICK HERE ⚠️
</div>

<div class="bg-gradient"></div>
<div class="petals-layer" id="petals"></div>
<div class="sparkles" id="sparkles"></div>

<div class="page">

  <section class="hero">
    <div class="crown">👑</div>
    <p class="hero-for">For my love little girl</p>
    <h1 class="hero-name">Bulbula</h1>
    <p class="hero-nickname"><span>( Visalila </span> — my everything <span>)</span></p>
    <div class="pill-badge">🌸 A special Easter surprise just for you 🌸</div>
    <div class="scroll-hint">
      <span>scroll down</span>
      <span class="arrow">↓</span>
    </div>
  </section>

  <div class="section">
    <span class="section-tag">✦ The reason for this season ✦</span>
    <h2 class="section-title">He is risen, my love</h2>
    <div class="verse-card">
      <p class="verse-text">
        "For God so loved the world that he gave his one and only Son,
        that whoever believes in him shall not perish
        but have eternal life."
      </p>
      <p class="verse-ref">— John 3 : 16</p>
    </div>
  </div>

  <div class="resurrection-wrap">
    <svg class="resurrection-svg" viewBox="0 0 500 260" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <radialGradient id="glow1" cx="50%" cy="40%" r="55%">
          <stop offset="0%" stop-color="#fce8f5" stop-opacity="0.9"/>
          <stop offset="100%" stop-color="#fff0f8" stop-opacity="0"/>
        </radialGradient>
        <radialGradient id="tombGlow" cx="50%" cy="50%" r="50%">
          <stop offset="0%" stop-color="#f9d4e8" stop-opacity="0.8"/>
          <stop offset="100%" stop-color="transparent"/>
        </radialGradient>
      </defs>
      <ellipse cx="250" cy="120" rx="260" ry="140" fill="url(#glow1)"/>
      <ellipse cx="250" cy="242" rx="230" ry="22" fill="rgba(216,180,226,0.25)"/>
      <path d="M20 242 Q140 200 250 208 Q360 200 480 242Z" fill="rgba(184,232,212,0.3)"/>
      <rect x="236" y="60" width="14" height="90" rx="4" fill="#e8839a" opacity="0.85"/>
      <rect x="216" y="86" width="54" height="14" rx="4" fill="#e8839a" opacity="0.85"/>
      <ellipse cx="243" cy="108" rx="38" ry="50" fill="rgba(247,197,208,0.22)">
        <animate attributeName="rx" values="38;50;38" dur="4s" repeatCount="indefinite"/>
        <animate attributeName="ry" values="50;64;50" dur="4s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.22;0.4;0.22" dur="4s" repeatCount="indefinite"/>
      </ellipse>
      <ellipse cx="170" cy="224" rx="42" ry="36" fill="rgba(255,240,248,0.6)" stroke="rgba(232,131,154,0.3)" stroke-width="1"/>
      <ellipse cx="170" cy="224" rx="34" ry="28" fill="rgba(180,100,130,0.12)"/>
      <ellipse cx="128" cy="234" rx="24" ry="22" fill="rgba(216,180,226,0.7)" stroke="rgba(216,180,226,0.5)" stroke-width="1"/>
      <ellipse cx="170" cy="224" rx="28" ry="22" fill="url(#tombGlow)">
        <animate attributeName="opacity" values="0.5;1;0.5" dur="3s" repeatCount="indefinite"/>
      </ellipse>
      <line x1="340" y1="242" x2="344" y2="190" stroke="#6a9e72" stroke-width="1.5"/>
      <line x1="358" y1="242" x2="364" y2="178" stroke="#6a9e72" stroke-width="1.5"/>
      <line x1="376" y1="242" x2="374" y2="188" stroke="#6a9e72" stroke-width="1.5"/>
      <ellipse cx="344" cy="185" rx="10" ry="17" fill="#fce8f5" transform="rotate(-12,344,185)"/>
      <ellipse cx="364" cy="173" rx="10" ry="17" fill="#fce8f5" transform="rotate(8,364,173)"/>
      <ellipse cx="374" cy="183" rx="10" ry="17" fill="#fce8f5" transform="rotate(-4,374,183)"/>
      <circle cx="344" cy="183" r="3" fill="#f9c4d0"/>
      <circle cx="364" cy="171" r="3" fill="#f9c4d0"/>
      <path d="M420 120 Q435 105 445 118 Q435 122 420 120Z" fill="rgba(216,180,226,0.8)"/>
      <path d="M420 120 Q435 135 445 122 Q435 118 420 120Z" fill="rgba(247,197,208,0.8)"/>
      <path d="M420 120 Q405 105 395 118 Q405 122 420 120Z" fill="rgba(247,197,208,0.8)"/>
      <path d="M420 120 Q405 135 395 122 Q405 118 420 120Z" fill="rgba(216,180,226,0.7)"/>
      <circle cx="420" cy="120" r="2.5" fill="#e8839a"/>
      <text x="60" y="130" font-size="14" opacity="0.5" fill="#e8839a">
        ♡
        <animate attributeName="y" values="130;118;130" dur="3s" repeatCount="indefinite"/>
        <animate attributeName="opacity" values="0.5;0.8;0.5" dur="3s" repeatCount="indefinite"/>
      </text>
      <text x="460" y="100" font-size="11" opacity="0.4" fill="#d8b4e2">
        ✦
        <animate attributeName="y" values="100;88;100" dur="4s" repeatCount="indefinite"/>
      </text>
    </svg>
  </div>

  <div class="section">
    <span class="section-tag">✦ Reasons you make me smile ✦</span>
    <h2 class="section-title">My favourite things about you</h2>
    <div class="cards-grid">
      <div class="love-card">
        <div class="icon">🙏</div>
        <p class="card-title">Your faith</p>
        <p class="card-body">The way you believe so <strong>purely and deeply</strong> is one of the most beautiful things about you, Bulbula.</p>
      </div>
      <div class="love-card">
        <div class="icon">😂</div>
        <p class="card-title">You call me "stupid"</p>
        <p class="card-body">And somehow it's the <strong>sweetest thing</strong> I've ever heard. Only you could make that feel like a love song. 😏</p>
      </div>
      <div class="love-card">
        <div class="icon">💖</div>
        <p class="card-title">Your heart</p>
        <p class="card-body">Warm, kind, and <strong>endlessly giving</strong>. Easter is about love — and you live that love every single day.</p>
      </div>
      <div class="love-card">
        <div class="icon">🌸</div>
        <p class="card-title">Just… you</p>
        <p class="card-body">Visalila, you are my favourite person on this entire earth. <strong>Bar none. Always.</strong></p>
      </div>
    </div>
  </div>

  <div class="reveal-section" id="revealSection">
    
    <h2 class="section-title" style="font-family:'Playfair Display',serif;font-style:italic;color:var(--text);font-size:clamp(28px,7vw,46px);text-align:center; font-weight: bold;">
      A letter from your stupid 💌
    </h2>

    <div class="letter-envelope" id="envelope">
      <div class="envelope-outer">
        <div class="envelope-seal">💌</div>
        <p class="envelope-preview">To my Bulbula, with all my heart…</p>
      </div>
      <button class="open-btn" id="openBtn" onclick="openLetter()">
        🌸 OPEN MY EASTER LETTER 🌸
      </button>
    </div>

    <div class="letter-content" id="letterContent">
      <p class="letter-greeting">My dearest Bulbula,</p>
      <p class="letter-body">
        Happy Easter, my little girl. 🌸<br><br>

        Today is a day about <em>resurrection</em> — about hope triumphing over darkness,
        love conquering everything in its way.
        And every morning I wake up next to the thought of you,
        <strong>I feel exactly that.</strong><br><br>

        You call me stupid… and honestly? You're not wrong. 
        I'm stupid for smiling at my phone every time you text.
        I'm stupid for thinking about you at random moments.
        I'm stupid in love with you — <em>completely, helplessly, happily.</em><br><br>

        Your faith, your laughter, your warmth — they are the most precious things
        I know. On this Easter Sunday, I pray you feel the same joy
        and peace that you bring into my world every single day.<br><br>

        <strong>He is risen</strong> — and my love for you, Visalila,
        rises higher every time I think of you.<br><br>

        Sorry for always keep distracting and distrubing u. 🌷
      </p>
      <p class="letter-sign">
        Forever yours,
        <span>— Your Stupid, Sunil 💕</span>
      </p>
      <div class="confetti-row">🌸 ✝ 🌷 💖 🌼 ✦ 🦋</div>
    </div>
  </div>

  <div class="verse2-wrap">
    <p class="big-verse">
      "I have loved you with an <em>everlasting love;</em><br>
      I have drawn you with unfailing kindness."
    </p>
    <p class="verse-ref" style="margin-top:14px;font-size:12px;letter-spacing:0.25em;color:var(--rose);text-transform:uppercase">— Jeremiah 31 : 3</p>
  </div>

  <div class="footer">
    <div class="footer-flowers">🌸🌷🌼🌺🌸</div>
    <p class="footer-text">Happy Easter, Bulbula 🌸</p>
    <p class="footer-sub">He is risen · love is eternal · you are adored</p>
    <p style="margin-top:18px;font-family:'Dancing Script',cursive;font-size:18px;color:var(--text-soft)">
      from your Stupid — Sunil 💕
    </p>
  </div>

</div>

<script>
  // ── FALLING PETALS ──
  const petalEmojis = ['🌸','🌷','🌼','✿','❀','🌺','💮'];
  const petalsEl = document.getElementById('petals');
  for (let i = 0; i < 28; i++) {
    const p = document.createElement('div');
    p.className = 'fp';
    p.textContent = petalEmojis[Math.floor(Math.random() * petalEmojis.length)];
    p.style.cssText = `
      left: ${Math.random()*100}%;
      --fs: ${14 + Math.random()*12}px;
      --spd: ${7 + Math.random()*9}s;
      --del: ${Math.random()*10}s;
      --dx: ${(Math.random()-0.5)*120}px;
    `;
    petalsEl.appendChild(p);
  }

  // ── SPARKLES ──
  const sparklesEl = document.getElementById('sparkles');
  for (let i = 0; i < 18; i++) {
    const s = document.createElement('div');
    s.className = 'sp';
    s.style.cssText = `
      top: ${5 + Math.random()*90}%;
      left: ${5 + Math.random()*90}%;
      --sd: ${1.5 + Math.random()*3}s;
      --sdel: ${Math.random()*4}s;
      --sfs: ${10 + Math.random()*10}px;
    `;
    sparklesEl.appendChild(s);
  }

  // ── TRACK SCROLLING FOR WARNING BANNER ──
  let isLetterOpened = false;
  const warningBanner = document.getElementById('urgentStop');
  const envelopeContainer = document.getElementById('envelope');

  window.addEventListener('scroll', () => {
    if (isLetterOpened) return; // Stop checking if letter is already open

    // Calculate how close she is to the bottom of the page
    const scrollPosition = window.innerHeight + window.scrollY;
    const bottomThreshold = document.body.offsetHeight - 150; // Triggers when she reaches the footer

    // If she scrolls to the bottom and hasn't opened it, show the banner!
    if (scrollPosition >= bottomThreshold) {
      warningBanner.classList.add('show');
    } else {
      warningBanner.classList.remove('show');
    }
  });

  // Clicking the warning banner scrolls back to the envelope
  function scrollToLetter() {
    envelopeContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  // ── OPEN LETTER (WITH MAGICAL EXPLOSION) ──
  function openLetter() {
    isLetterOpened = true; 
    warningBanner.classList.remove('show'); 
    
    const envelope = document.getElementById('envelope');
    const letter = document.getElementById('letterContent');
    
    // Hide the envelope
    envelope.style.display = 'none';
    
    // Show the letter
    letter.classList.add('open');
    setTimeout(() => letter.classList.add('visible'), 30);
    letter.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Trigger the magical explosion!
    launchMagicExplosion();
  }

  // ── MAGICAL EXPLOSION ANIMATION ──
  function launchMagicExplosion() {
    // 1. Create the giant pulsing heart in the center
    const bigHeart = document.createElement('div');
    bigHeart.textContent = '💖';
    bigHeart.className = 'big-center-heart';
    document.body.appendChild(bigHeart);
    setTimeout(() => bigHeart.remove(), 1800); // Remove after animation

    // 2. Create the fountain burst of cute emojis
    const magicEmojis = ['💖', '💕', '🌸', '✨', '🦋', '🎀', '🧸', '🌷', '💝', '✨'];
    const numParticles = 60; // Huge burst
    
    for (let i = 0; i < numParticles; i++) {
      setTimeout(() => {
        const el = document.createElement('div');
        el.className = 'magic-particle';
        el.textContent = magicEmojis[Math.floor(Math.random() * magicEmojis.length)];
        
        // Calculate a random trajectory for the explosion
        const angle = Math.random() * Math.PI * 2;
        const distance = 20 + Math.random() * 60; // Distance to travel (vh/vw)
        
        // Set CSS variables for the animation to use
        el.style.setProperty('--tx', Math.cos(angle) * distance + 'vw');
        el.style.setProperty('--ty', Math.sin(angle) * distance + 'vh');
        
        // Randomize size and rotation
        el.style.fontSize = (15 + Math.random() * 30) + 'px';
        el.style.setProperty('--rot', (Math.random() - 0.5) * 720 + 'deg');
        
        document.body.appendChild(el);
        
        // Clean up
        setTimeout(() => el.remove(), 2000);
      }, i * 15); // Stagger the creation slightly for a fountain effect
    }
  }

  // ── CLICK ANYWHERE ELSE FOR NORMAL HEARTS ──
  document.addEventListener('click', e => {
    if (e.target.closest('#openBtn') || e.target.closest('#urgentStop')) return;
    const h = document.createElement('div');
    h.className = 'burst-heart';
    h.textContent = ['💖','🌸','💕','✨'][Math.floor(Math.random()*4)];
    h.style.left = e.clientX + 'px';
    h.style.top  = e.clientY + 'px';
    document.body.appendChild(h);
    setTimeout(() => h.remove(), 1200);
  });

  // ── INTERSECTION OBSERVER (fade cards in) ──
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.love-card, .verse-card, .section').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.9s ease, transform 0.9s ease';
    observer.observe(el);
  });
</script>
</body>
</html>