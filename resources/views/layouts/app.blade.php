<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Kevin Thompson Ph.D. Consulting</title>
<style>
/* ─────────────────────────────────────────
   RESET
───────────────────────────────────────── */
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{font-size:16px;scroll-behavior:smooth}
body{overflow-x:hidden}
a{text-decoration:none;color:inherit}
ul{list-style:none}
button{cursor:pointer;border:none;background:none;font-family:inherit}
img{max-width:100%;display:block}

/* ─────────────────────────────────────────
   DESIGN TOKENS
───────────────────────────────────────── */
:root{
  /* palette */
  --slate:      #1a2332;
  --slate2:     #243345;
  --slate3:     #2f4259;
  --copper:     #b5722a;
  --copper2:    #d4924e;
  --copper3:    #edb97a;
  --copper4:    #f7ddb0;
  --ivory:      #faf7f2;
  --ivory2:     #f2ece3;
  --ivory3:     #e8dfd2;
  --charcoal:   #2c3a4a;
  --body-text:  #4a5968;
  --muted:      #7a8fa0;
  --white:      #ffffff;

  /* effects */
  --glow: 0 0 60px rgba(181,114,42,.08);
  --card-shadow: 0 4px 32px rgba(26,35,50,.1);
  --hover-shadow: 0 12px 56px rgba(26,35,50,.18);
}

/* ─────────────────────────────────────────
   BASE TYPOGRAPHY
───────────────────────────────────────── */
body{
  font-family: 'Palatino Linotype','Book Antiqua',Palatino,Georgia,serif;
  background: var(--ivory);
  color: var(--charcoal);
  line-height: 1.6;
}
.sans{font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif}

/* scrollbar */
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-track{background:var(--ivory2)}
::-webkit-scrollbar-thumb{background:var(--copper);border-radius:2px}

/* ─────────────────────────────────────────
   NAV
───────────────────────────────────────── */
#nav{
  position:fixed;top:0;left:0;right:0;z-index:200;
  display:flex;align-items:center;justify-content:space-between;
  padding:1.6rem 4rem;
  transition:padding .45s cubic-bezier(.4,0,.2,1),background .45s,box-shadow .45s;
}
#nav.pinned{
  background:rgba(26,35,50,.97);
  backdrop-filter:blur(18px);
  padding:1rem 4rem;
  box-shadow:0 1px 0 rgba(181,114,42,.12),0 4px 24px rgba(0,0,0,.22);
}

/* logo */
.logo{
  display:flex;align-items:center;gap:.65rem;
  font-size:1.05rem;font-weight:700;letter-spacing:.015em;
  color:var(--white);
}
.logo-mark{
  width:32px;height:32px;flex-shrink:0;
  border:1.5px solid var(--copper2);
  display:flex;align-items:center;justify-content:center;
  font-size:.62rem;font-weight:900;letter-spacing:.05em;
  color:var(--copper2);
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
}
.logo-text{line-height:1.2}
.logo-name{display:block;font-size:.95rem;font-weight:700;color:var(--white)}
.logo-sub{
  display:block;font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;
  color:var(--copper3);
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-weight:600;margin-top:.15rem;
}

/* nav links */
.nav-menu{display:flex;align-items:center;gap:2.25rem}
.nav-menu a{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.7rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;
  color:rgba(250,247,242,.65);transition:color .3s;position:relative;
}
.nav-menu a::after{
  content:'';position:absolute;bottom:-4px;left:0;
  width:0;height:1px;background:var(--copper3);transition:width .35s;
}
.nav-menu a:hover{color:var(--copper3)}
.nav-menu a:hover::after{width:100%}
.nav-contact{
  margin-left:.5rem;
  padding:.55rem 1.5rem;
  border:1.5px solid var(--copper);
  color:var(--copper3)!important;
  font-weight:700!important;
  transition:all .3s!important;
}
.nav-contact::after{display:none!important}
.nav-contact:hover{background:var(--copper)!important;color:var(--white)!important;border-color:var(--copper)!important}

/* hamburger */
.burger{display:none;flex-direction:column;gap:5px;padding:4px;z-index:201}
.burger span{display:block;width:24px;height:1.5px;background:var(--white);transition:all .3s;transform-origin:center}
.burger.x span:nth-child(1){transform:translateY(6.5px) rotate(45deg)}
.burger.x span:nth-child(2){opacity:0;transform:scaleX(0)}
.burger.x span:nth-child(3){transform:translateY(-6.5px) rotate(-45deg)}

/* mobile drawer */
.drawer{
  display:none;position:fixed;inset:0;z-index:199;
  background:var(--slate);
  flex-direction:column;align-items:center;justify-content:center;gap:2.75rem;
}
.drawer.show{display:flex}
.drawer a{
  font-family:'Palatino Linotype',Palatino,Georgia,serif;
  font-size:1.9rem;font-style:italic;font-weight:400;
  color:var(--ivory);transition:color .3s;
}
.drawer a:hover{color:var(--copper3)}
.drawer-line{width:1px;height:48px;background:rgba(181,114,42,.3)}

/* ─────────────────────────────────────────
   FOOTER
───────────────────────────────────────── */
footer{
  background:var(--slate);
  padding:2.75rem 4.5rem;
  display:flex;align-items:center;justify-content:space-between;
  flex-wrap:wrap;gap:1.5rem;
  border-top:1px solid rgba(181,114,42,.12);
}
.footer-logo{
  display:flex;align-items:center;gap:.65rem;
}
.footer-logo-mark{
  width:28px;height:28px;
  border:1px solid rgba(181,114,42,.3);
  display:flex;align-items:center;justify-content:center;
  font-family:-apple-system,sans-serif;
  font-size:.55rem;font-weight:900;letter-spacing:.05em;
  color:var(--copper2);
}
.footer-name{
  font-size:.92rem;font-weight:700;color:var(--white);
}
.footer-name span{color:var(--copper2)}
.footer-copy{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.68rem;color:rgba(250,247,242,.3);
}
.footer-links{display:flex;gap:2rem;flex-wrap:wrap}
.footer-links a{
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,sans-serif;
  font-size:.68rem;letter-spacing:.08em;text-transform:uppercase;
  color:rgba(250,247,242,.4);transition:color .3s;
}
.footer-links a:hover{color:var(--copper3)}

@media(max-width:1100px){
  #nav,#nav.pinned{padding:1.1rem 2.25rem}
  .nav-menu{display:none}
  .burger{display:flex}
}
@media(max-width:700px){
  footer{flex-direction:column;align-items:flex-start;padding:2.25rem 1.5rem}
  .footer-links{gap:1.25rem}
}
</style>
@stack('styles')
</head>
<body>

    <nav id="nav">
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-mark">KT</div>
            <div class="logo-text">
            <span class="logo-name">Kevin Thompson</span>
            <span class="logo-sub">Ph.D. Consulting</span>
            </div>
        </a>

        <div class="nav-menu">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('papers') }}">Papers &amp; Presentations</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ route('training') }}">Training</a>
            <a href="{{ route('contact') }}" class="nav-contact">Contact</a>
        </div>

        <button class="burger" id="burger" aria-label="Toggle menu" onclick="toggleDrawer()">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <div class="drawer" id="drawer">
        <a href="{{ route('about') }}" onclick="closeDrawer()">About</a>
        <div class="drawer-line"></div>
        <a href="{{ route('papers') }}" onclick="closeDrawer()">Papers &amp; Presentations</a>
        <div class="drawer-line"></div>
        <a href="{{ route('services') }}" onclick="closeDrawer()">Services</a>
        <div class="drawer-line"></div>
        <a href="{{ route('training') }}" onclick="closeDrawer()">Training</a>
        <div class="drawer-line"></div>
        <a href="{{ route('contact') }}" onclick="closeDrawer()">Contact</a>
    </div>

    <main>
        @if(request()->has('visible'))
            @yield('content')
        @endif
    </main>

    <footer>
        <div class="footer-logo">
            <div class="footer-logo-mark">KT</div>
            <div class="footer-name">Kevin Thompson <span>Ph.D.</span> Consulting</div>
        </div>
        <div class="footer-copy">&copy; {{ date('Y') }} Kevin Thompson Ph.D. Consulting. All rights reserved.</div>
        <nav class="footer-links">
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('papers') }}">Papers</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ route('training') }}">Training</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>
    </footer>

    <script>
        /* sticky nav */
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('pinned', window.scrollY > 55);
        }, { passive: true });

        /* mobile drawer */
        function toggleDrawer() {
            const b = document.getElementById('burger');
            const d = document.getElementById('drawer');
            b.classList.toggle('x');
            d.classList.toggle('show');
            document.body.style.overflow = d.classList.contains('show') ? 'hidden' : '';
        }
        function closeDrawer() {
            document.getElementById('burger').classList.remove('x');
            document.getElementById('drawer').classList.remove('show');
            document.body.style.overflow = '';
        }
    </script>
    
    @stack('scripts')

</body>
</html>