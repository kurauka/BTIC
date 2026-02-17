<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Portal - Bandari Tech & Innovation Club</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --ocean: #050d1a;
            --deep: #07152b;
            --mid: #0a2040;
            --teal: #00c9a7;
            --teal-dim: #007a67;
            --amber: #f5a623;
            --sky: #38bdf8;
            --muted: #8fa3be;
            --white: #f0f6ff;
            --card-bg: rgba(10, 30, 60, 0.6);
            --border: rgba(0, 201, 167, 0.15);
            --error: #ff4d4f;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--ocean);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Shared Utilities */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--teal);
            color: var(--ocean);
            font-weight: 600;
            box-shadow: 0 0 20px rgba(0, 201, 167, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 201, 167, 0.3);
        }

        /* Login Specific */
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            padding: 2.5rem;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .login-logo span {
            color: var(--teal);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            background: rgba(5, 13, 26, 0.6);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--white);
            font-family: inherit;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--teal);
        }

        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            text-align: center;
        }

        .alert-error {
            background: rgba(255, 77, 79, 0.1);
            border: 1px solid rgba(255, 77, 79, 0.3);
            color: var(--error);
        }

        /* Background Effects */
        .bg-blobs {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }

        .blob-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: var(--teal-dim);
        }

        .blob-2 {
            bottom: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: var(--mid);
        }
    </style>
</head>

<body>
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>