<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sign In</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="theme-color" content="#1a2332">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Figtree:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/css/auth/auth.css">

    <style>
        body {
            background-color: var(--slate);
            background-image:
                radial-gradient(ellipse 100% 60% at 50% 0%, rgba(47,66,89,.9) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 0% 100%, rgba(181,114,42,.06) 0%, transparent 60%),
                radial-gradient(ellipse 50% 30% at 100% 80%, rgba(181,114,42,.04) 0%, transparent 60%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Figtree', -apple-system, sans-serif;
            position: relative;
        }

        /* Subtle dot-grid texture */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 500px;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: none; }
        }
    </style>
</head>
<body>
    <main class="auth-wrapper">
        @yield('content')
    </main>
</body>
</html>