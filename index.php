<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Bandari Tech & Innovation Club</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
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
    }

    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--ocean);
      color: var(--white);
      overflow-x: hidden;
    }

    /* ===== CURSOR ===== */
    .cursor {
      width: 12px;
      height: 12px;
      background: var(--teal);
      border-radius: 50%;
      position: fixed;
      top: 0;
      left: 0;
      pointer-events: none;
      z-index: 9999;
      transform: translate(-50%, -50%);
      transition: transform 0.1s, width 0.3s, height 0.3s, opacity 0.3s;
    }

    .cursor-ring {
      width: 40px;
      height: 40px;
      border: 1px solid rgba(0, 201, 167, 0.5);
      border-radius: 50%;
      position: fixed;
      top: 0;
      left: 0;
      pointer-events: none;
      z-index: 9998;
      transform: translate(-50%, -50%);
      transition: transform 0.15s ease-out, width 0.3s, height 0.3s;
    }

    /* ===== CANVAS / PARTICLES ===== */
    #stars-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }

    /* ===== NOISE OVERLAY ===== */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
      opacity: 0.4;
      z-index: 1;
      pointer-events: none;
    }

    /* ===== NAV ===== */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      padding: 1.25rem 5%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      backdrop-filter: blur(20px);
      background: rgba(5, 13, 26, 0.6);
      border-bottom: 1px solid var(--border);
      transition: all 0.4s;
    }

    nav.scrolled {
      padding: 0.85rem 5%;
      background: rgba(5, 13, 26, 0.92);
    }

    .nav-logo {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.3rem;
      color: var(--white);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .nav-logo span {
      color: var(--teal);
    }

    .logo-icon {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, var(--teal), var(--sky));
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    .nav-links {
      display: flex;
      gap: 2.2rem;
      align-items: center;
      list-style: none;
    }

    .nav-links a {
      color: var(--muted);
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 500;
      letter-spacing: 0.02em;
      transition: color 0.3s;
      position: relative;
    }

    .nav-links a::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 0;
      height: 1px;
      background: var(--teal);
      transition: width 0.3s;
    }

    .nav-links a:hover {
      color: var(--white);
    }

    .nav-links a:hover::after {
      width: 100%;
    }

    .nav-cta {
      background: transparent;
      border: 1px solid var(--teal);
      color: var(--teal) !important;
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
      transition: all 0.3s !important;
    }

    .nav-cta:hover {
      background: var(--teal) !important;
      color: var(--ocean) !important;
    }

    .nav-cta::after {
      display: none !important;
    }

    .hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
      cursor: pointer;
      background: none;
      border: none;
      padding: 0.25rem;
    }

    .hamburger span {
      display: block;
      width: 24px;
      height: 2px;
      background: var(--white);
      border-radius: 2px;
      transition: all 0.3s;
    }

    /* ===== HERO ===== */
    #hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 2;
      padding: 8rem 5% 5rem;
      overflow: hidden;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
      max-width: 1280px;
      width: 100%;
      margin: 0 auto;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(0, 201, 167, 0.08);
      border: 1px solid rgba(0, 201, 167, 0.3);
      border-radius: 100px;
      padding: 0.4rem 1rem;
      font-size: 0.78rem;
      font-weight: 500;
      color: var(--teal);
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 1.8rem;
      width: fit-content;
    }

    .badge-dot {
      width: 6px;
      height: 6px;
      background: var(--teal);
      border-radius: 50%;
      animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {

      0%,
      100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: 0.5;
        transform: scale(0.8);
      }
    }

    .hero-h1 {
      font-family: 'Syne', sans-serif;
      font-size: clamp(3rem, 5.5vw, 5.5rem);
      font-weight: 800;
      line-height: 1.02;
      letter-spacing: -0.03em;
      margin-bottom: 1.5rem;
    }

    .hero-h1 .line {
      display: block;
      overflow: hidden;
    }

    .hero-h1 .accent {
      color: var(--teal);
    }

    .hero-h1 .amber {
      color: var(--amber);
    }

    .hero-sub {
      color: var(--muted);
      font-size: 1.05rem;
      line-height: 1.75;
      max-width: 560px;
      margin-bottom: 2.5rem;
    }

    .hero-btns {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.85rem 1.8rem;
      border-radius: 8px;
      font-size: 0.92rem;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      border: none;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.1);
      transform: translateX(-100%);
      transition: transform 0.3s;
    }

    .btn:hover::before {
      transform: translateX(0);
    }

    .btn-primary {
      background: var(--teal);
      color: var(--ocean);
      font-weight: 600;
      box-shadow: 0 0 30px rgba(0, 201, 167, 0.25);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 50px rgba(0, 201, 167, 0.4);
    }

    .btn-secondary {
      background: transparent;
      color: var(--white);
      border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .btn-secondary:hover {
      border-color: rgba(255, 255, 255, 0.4);
      transform: translateY(-2px);
    }

    .btn-outline {
      background: transparent;
      color: var(--amber);
      border: 1px solid rgba(245, 166, 35, 0.4);
    }

    .btn-outline:hover {
      border-color: var(--amber);
      background: rgba(245, 166, 35, 0.08);
      transform: translateY(-2px);
    }

    /* Hero Visual */
    .hero-visual {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .globe-container {
      position: relative;
      width: 420px;
      height: 420px;
      animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-18px);
      }
    }

    .globe-ring {
      position: absolute;
      inset: 0;
      border-radius: 50%;
      border: 1px solid rgba(0, 201, 167, 0.2);
      animation: spin-slow 20s linear infinite;
    }

    .hero-logo {
      max-width: 400px;
      /* Adjust as needed */
      width: 100%;
      height: auto;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(0, 201, 167, 0.15);
      border: 1px solid rgba(0, 201, 167, 0.2);
    }

    .globe-ring:nth-child(2) {
      inset: 20px;
      border-color: rgba(56, 189, 248, 0.15);
      animation-duration: 30s;
      animation-direction: reverse;
    }

    .globe-ring:nth-child(3) {
      inset: 40px;
      border-color: rgba(245, 166, 35, 0.1);
      animation-duration: 15s;
    }

    @keyframes spin-slow {
      from {
        transform: rotate(0deg) rotateX(70deg);
      }

      to {
        transform: rotate(360deg) rotateX(70deg);
      }
    }

    .globe-core {
      position: absolute;
      inset: 60px;
      border-radius: 50%;
      background: radial-gradient(ellipse at 35% 35%, var(--mid), var(--ocean));
      border: 1px solid rgba(0, 201, 167, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .globe-core::before {
      content: '';
      position: absolute;
      width: 200%;
      height: 200%;
      background: radial-gradient(ellipse at center, transparent 40%, rgba(0, 201, 167, 0.05) 100%);
    }

    .globe-icon {
      font-size: 4rem;
      filter: drop-shadow(0 0 20px rgba(0, 201, 167, 0.6));
    }

    .orbit-dot {
      position: absolute;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: var(--teal);
      box-shadow: 0 0 12px var(--teal);
    }

    .orbit-dot:nth-child(5) {
      top: 10%;
      left: 50%;
      animation: orbit1 8s linear infinite;
    }

    .orbit-dot:nth-child(6) {
      background: var(--amber);
      box-shadow: 0 0 12px var(--amber);
      animation: orbit2 12s linear infinite;
    }

    .orbit-dot:nth-child(7) {
      background: var(--sky);
      box-shadow: 0 0 12px var(--sky);
      animation: orbit3 6s linear infinite;
    }

    @keyframes orbit1 {
      from {
        transform: rotate(0deg) translateX(190px) rotate(0deg);
      }

      to {
        transform: rotate(360deg) translateX(190px) rotate(-360deg);
      }
    }

    @keyframes orbit2 {
      from {
        transform: rotate(120deg) translateX(160px) rotate(-120deg);
      }

      to {
        transform: rotate(480deg) translateX(160px) rotate(-480deg);
      }
    }

    @keyframes orbit3 {
      from {
        transform: rotate(240deg) translateX(140px) rotate(-240deg);
      }

      to {
        transform: rotate(600deg) translateX(140px) rotate(-600deg);
      }
    }

    .stats-row {
      display: flex;
      gap: 2rem;
      margin-top: 3rem;
      flex-wrap: wrap;
    }

    .stat-item {
      border-left: 2px solid var(--teal);
      padding-left: 1rem;
    }

    .stat-num {
      font-family: 'Syne', sans-serif;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--white);
      line-height: 1;
    }

    .stat-num span {
      color: var(--teal);
    }

    .stat-label {
      font-size: 0.78rem;
      color: var(--muted);
      margin-top: 0.25rem;
    }

    /* ===== SCROLL INDICATOR ===== */
    .scroll-hint {
      position: absolute;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      color: var(--muted);
      font-size: 0.75rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .scroll-line {
      width: 1px;
      height: 40px;
      background: linear-gradient(to bottom, var(--teal), transparent);
      animation: scroll-anim 1.5s ease-in-out infinite;
    }

    @keyframes scroll-anim {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.3;
      }
    }

    /* ===== SECTION COMMON ===== */
    section {
      position: relative;
      z-index: 2;
    }

    .section-wrap {
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 5%;
    }

    .section-label {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--teal);
      margin-bottom: 1rem;
    }

    .section-label::before {
      content: '';
      display: block;
      width: 24px;
      height: 1px;
      background: var(--teal);
    }

    .section-title {
      font-family: 'Syne', sans-serif;
      font-size: clamp(2rem, 3.5vw, 3.2rem);
      font-weight: 800;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 1.2rem;
    }

    .section-sub {
      color: var(--muted);
      font-size: 1rem;
      line-height: 1.75;
      max-width: 620px;
    }

    /* ===== ABOUT ===== */
    #about {
      padding: 8rem 0;
    }

    .about-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 5rem;
      align-items: start;
    }

    .about-left .section-sub {
      max-width: 100%;
      margin-bottom: 2rem;
    }

    .vision-mission {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-top: 2rem;
    }

    .vm-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.5rem;
      transition: border-color 0.3s, transform 0.3s;
      backdrop-filter: blur(10px);
    }

    .vm-card:hover {
      border-color: rgba(0, 201, 167, 0.4);
      transform: translateY(-4px);
    }

    .vm-card h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--teal);
      margin-bottom: 0.75rem;
    }

    .vm-card p {
      font-size: 0.88rem;
      color: var(--muted);
      line-height: 1.7;
    }

    .about-right {
      position: relative;
    }

    .about-visual {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2.5rem;
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }

    .about-visual::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 300px;
      height: 300px;
      background: radial-gradient(ellipse, rgba(0, 201, 167, 0.08), transparent);
      border-radius: 50%;
    }

    .av-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.2rem;
    }

    .av-item {
      display: flex;
      flex-direction: column;
      gap: 0.4rem;
      padding: 1.2rem;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: background 0.3s;
    }

    .av-item:hover {
      background: rgba(0, 201, 167, 0.06);
    }

    .av-icon {
      font-size: 1.6rem;
    }

    .av-num {
      font-family: 'Syne', sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--teal);
    }

    .av-label {
      font-size: 0.8rem;
      color: var(--muted);
    }

    /* ===== DIVIDER ===== */
    .divider {
      height: 1px;
      background: linear-gradient(to right, transparent, var(--border), transparent);
      margin: 0 5%;
    }

    /* ===== FOCUS AREAS ===== */
    #focus {
      padding: 8rem 0;
    }

    .focus-header {
      text-align: center;
      margin-bottom: 4rem;
    }

    .focus-header .section-sub {
      margin: 0 auto;
    }

    .focus-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.5rem;
    }

    .focus-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 2rem;
      transition: all 0.4s;
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
      cursor: default;
    }

    .focus-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(to right, var(--teal), var(--sky));
      transform: scaleX(0);
      transition: transform 0.4s;
    }

    .focus-card:hover {
      transform: translateY(-6px);
      border-color: rgba(0, 201, 167, 0.4);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .focus-card:hover::after {
      transform: scaleX(1);
    }

    .focus-card:nth-child(2) .focus-icon {
      color: var(--sky);
    }

    .focus-card:nth-child(2)::after {
      background: linear-gradient(to right, var(--sky), var(--teal));
    }

    .focus-card:nth-child(3) .focus-icon {
      color: var(--amber);
    }

    .focus-card:nth-child(3)::after {
      background: linear-gradient(to right, var(--amber), var(--teal));
    }

    .focus-card:nth-child(4) .focus-icon {
      color: #4ade80;
    }

    .focus-card:nth-child(4)::after {
      background: linear-gradient(to right, #4ade80, var(--teal));
    }

    .focus-card:nth-child(5) .focus-icon {
      color: #c084fc;
    }

    .focus-card:nth-child(5)::after {
      background: linear-gradient(to right, #c084fc, var(--sky));
    }

    .focus-icon {
      font-size: 2.2rem;
      margin-bottom: 1rem;
      display: block;
    }

    .focus-card h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
    }

    .focus-card p {
      font-size: 0.87rem;
      color: var(--muted);
      line-height: 1.7;
    }

    .focus-card.wide {
      grid-column: span 2;
    }

    /* ===== PROGRAMS ===== */
    #programs {
      padding: 8rem 0;
      background: linear-gradient(180deg, transparent, rgba(0, 201, 167, 0.03), transparent);
    }

    .programs-grid {
      display: grid;
      grid-template-columns: 5fr 7fr;
      gap: 5rem;
      align-items: center;
    }

    .prog-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-top: 2rem;
    }

    .prog-item {
      display: flex;
      gap: 1rem;
      align-items: flex-start;
      padding: 1.2rem 1.5rem;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      transition: all 0.3s;
      cursor: default;
    }

    .prog-item:hover {
      border-color: rgba(0, 201, 167, 0.4);
      transform: translateX(6px);
    }

    .prog-num {
      font-family: 'Syne', sans-serif;
      font-size: 0.75rem;
      font-weight: 800;
      color: var(--teal);
      min-width: 28px;
      padding-top: 0.1rem;
    }

    .prog-content h4 {
      font-size: 0.95rem;
      font-weight: 600;
      margin-bottom: 0.2rem;
    }

    .prog-content p {
      font-size: 0.83rem;
      color: var(--muted);
      line-height: 1.5;
    }

    /* Projects Grid */
    .projects-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.2rem;
    }

    .project-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 1.8rem;
      transition: all 0.4s;
      backdrop-filter: blur(10px);
      cursor: default;
      position: relative;
      overflow: hidden;
    }

    .project-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(0, 201, 167, 0.04), transparent 60%);
      opacity: 0;
      transition: opacity 0.4s;
    }

    .project-card:hover::before {
      opacity: 1;
    }

    .project-card:hover {
      transform: translateY(-4px);
      border-color: rgba(0, 201, 167, 0.3);
    }

    .project-tag {
      display: inline-flex;
      align-items: center;
      background: rgba(0, 201, 167, 0.1);
      color: var(--teal);
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.25rem 0.7rem;
      border-radius: 100px;
      margin-bottom: 1rem;
    }

    .project-card h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      margin-bottom: 0.6rem;
    }

    .project-card p {
      font-size: 0.84rem;
      color: var(--muted);
      line-height: 1.6;
    }

    .project-card .arrow {
      margin-top: 1rem;
      color: var(--teal);
      font-size: 0.82rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      transition: gap 0.3s;
    }

    .project-card:hover .arrow {
      gap: 0.6rem;
    }

    /* ===== CTA JOIN ===== */
    #join {
      padding: 8rem 0;
    }

    .join-inner {
      background: linear-gradient(135deg, rgba(0, 201, 167, 0.08), rgba(56, 189, 248, 0.06));
      border: 1px solid rgba(0, 201, 167, 0.2);
      border-radius: 24px;
      padding: 5rem 4rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .join-inner::before {
      content: '';
      position: absolute;
      top: -80px;
      left: 50%;
      transform: translateX(-50%);
      width: 400px;
      height: 400px;
      background: radial-gradient(ellipse, rgba(0, 201, 167, 0.1), transparent 70%);
      border-radius: 50%;
    }

    .join-inner .section-label {
      justify-content: center;
    }

    .join-inner .section-title {
      margin: 0 auto 1.2rem;
    }

    .join-inner .section-sub {
      margin: 0 auto 2.5rem;
      text-align: center;
    }

    .join-btns {
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }

    /* ===== EVENTS ===== */
    #events {
      padding: 8rem 0;
    }

    .event-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 3rem;
      backdrop-filter: blur(10px);
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 2.5rem;
      align-items: center;
      transition: all 0.4s;
    }

    .event-card:hover {
      border-color: rgba(0, 201, 167, 0.35);
      transform: translateY(-4px);
    }

    .event-date {
      text-align: center;
      background: rgba(0, 201, 167, 0.08);
      border: 1px solid rgba(0, 201, 167, 0.2);
      border-radius: 14px;
      padding: 1.2rem 1.5rem;
      min-width: 90px;
    }

    .event-date .day {
      font-family: 'Syne', sans-serif;
      font-size: 2.2rem;
      font-weight: 800;
      color: var(--teal);
      line-height: 1;
    }

    .event-date .month {
      font-size: 0.78rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-top: 0.25rem;
    }

    .event-tags {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
      margin-bottom: 0.75rem;
    }

    .tag {
      font-size: 0.72rem;
      font-weight: 600;
      padding: 0.25rem 0.7rem;
      border-radius: 100px;
      background: rgba(56, 189, 248, 0.1);
      color: var(--sky);
    }

    .tag.orange {
      background: rgba(245, 166, 35, 0.1);
      color: var(--amber);
    }

    .tag.green {
      background: rgba(74, 222, 128, 0.1);
      color: #4ade80;
    }

    .event-info h3 {
      font-family: 'Syne', sans-serif;
      font-size: 1.4rem;
      font-weight: 800;
      margin-bottom: 0.6rem;
    }

    .event-info p {
      color: var(--muted);
      font-size: 0.9rem;
      line-height: 1.6;
    }

    .event-meta {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
      margin-top: 1rem;
    }

    .meta-item {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      font-size: 0.8rem;
      color: var(--muted);
    }

    /* ===== GALLERY ===== */
    #gallery {
      padding: 8rem 0;
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: auto auto;
      gap: 1rem;
    }

    .gallery-item {
      border-radius: 14px;
      overflow: hidden;
      background: var(--mid);
      border: 1px solid var(--border);
      aspect-ratio: 4/3;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      position: relative;
      transition: transform 0.4s;
      cursor: pointer;
    }

    .gallery-item:hover {
      transform: scale(0.97);
    }

    .gallery-item:first-child {
      grid-row: span 2;
      aspect-ratio: auto;
    }

    .gallery-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(5, 13, 26, 0.9) 0%, transparent 60%);
      display: flex;
      align-items: flex-end;
      padding: 1.2rem;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .gallery-item:hover .gallery-overlay {
      opacity: 1;
    }

    .gallery-label {
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--white);
    }

    .gallery-bg {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .gallery-pattern {
      width: 100%;
      height: 100%;
      opacity: 0.15;
    }

    /* ===== CONTACT ===== */
    #contact {
      padding: 8rem 0;
    }

    .contact-grid {
      display: grid;
      grid-template-columns: 1fr 1.2fr;
      gap: 4rem;
      align-items: start;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      margin-top: 2rem;
    }

    .contact-item {
      display: flex;
      gap: 1.2rem;
      align-items: flex-start;
    }

    .ci-icon {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: rgba(0, 201, 167, 0.08);
      border: 1px solid rgba(0, 201, 167, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      flex-shrink: 0;
    }

    .ci-content h4 {
      font-size: 0.82rem;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .ci-content p {
      font-size: 0.95rem;
      font-weight: 400;
    }

    .social-row {
      display: flex;
      gap: 0.75rem;
      margin-top: 1rem;
    }

    .social-btn {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: var(--card-bg);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      transition: all 0.3s;
      cursor: pointer;
      text-decoration: none;
    }

    .social-btn:hover {
      border-color: var(--teal);
      background: rgba(0, 201, 167, 0.1);
      transform: translateY(-3px);
    }

    .contact-form {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 20px;
      padding: 2.5rem;
      backdrop-filter: blur(10px);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }

    .form-group {
      margin-bottom: 1.2rem;
    }

    .form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 500;
      color: var(--muted);
      margin-bottom: 0.5rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 0.85rem 1rem;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      color: var(--white);
      font-family: 'DM Sans', sans-serif;
      font-size: 0.9rem;
      outline: none;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--teal);
      background: rgba(0, 201, 167, 0.04);
    }

    .form-group select option {
      background: var(--deep);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    /* ===== FOOTER ===== */
    footer {
      position: relative;
      z-index: 2;
      border-top: 1px solid var(--border);
      padding: 3rem 5%;
    }

    .footer-inner {
      max-width: 1280px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1.5rem;
    }

    .footer-logo {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 1.1rem;
    }

    .footer-logo span {
      color: var(--teal);
    }

    .footer-links {
      display: flex;
      gap: 2rem;
    }

    .footer-links a {
      font-size: 0.84rem;
      color: var(--muted);
      text-decoration: none;
      transition: color 0.3s;
    }

    .footer-links a:hover {
      color: var(--white);
    }

    .footer-copy {
      font-size: 0.8rem;
      color: var(--muted);
      width: 100%;
      text-align: center;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-copy span {
      color: var(--teal);
    }

    /* ===== MOBILE NAV ===== */
    .mobile-menu {
      position: fixed;
      inset: 0;
      background: rgba(5, 13, 26, 0.97);
      backdrop-filter: blur(20px);
      z-index: 200;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 2rem;
      display: flex;
      transform: translateX(100%);
      transition: transform 0.4s cubic-bezier(0.77, 0, 0.175, 1);
      visibility: hidden;
    }

    .mobile-menu.open {
      transform: translateX(0);
      visibility: visible;
    }

    .mobile-menu a {
      font-family: 'Syne', sans-serif;
      font-size: 2rem;
      font-weight: 700;
      color: var(--white);
      text-decoration: none;
      transition: color 0.3s;
    }

    .mobile-menu a:hover {
      color: var(--teal);
    }

    .close-menu {
      position: absolute;
      top: 1.5rem;
      right: 5%;
      font-size: 1.8rem;
      cursor: pointer;
      color: var(--muted);
      background: none;
      border: none;
    }

    /* ===== REVEAL ANIMATIONS ===== */
    .reveal {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .reveal-left {
      opacity: 1;
      transform: translateX(0);
      transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .reveal-left.visible {
      opacity: 1;
      transform: translateX(0);
    }

    .reveal-right {
      opacity: 1;
      transform: translateX(0);
      transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .reveal-right.visible {
      opacity: 1;
      transform: translateX(0);
    }

    .delay-1 {
      transition-delay: 0.1s;
    }

    .delay-2 {
      transition-delay: 0.2s;
    }

    .delay-3 {
      transition-delay: 0.3s;
    }

    .delay-4 {
      transition-delay: 0.4s;
    }

    .delay-5 {
      transition-delay: 0.5s;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
      .hero-grid {
        grid-template-columns: 1fr;
        text-align: center;
        position: relative;
      }

      .hero-sub {
        margin: 0 auto 2.5rem;
      }

      .hero-btns {
        justify-content: center;
      }

      .hero-visual {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 0;
        opacity: 0.15;
        pointer-events: none;
      }

      .hero-left {
        position: relative;
        z-index: 1;
      }

      .globe-container {
        width: 300px;
        height: 300px;
        margin: 0 auto;
      }

      .stats-row {
        justify-content: center;
      }

      .about-grid {
        grid-template-columns: 1fr;
      }

      .programs-grid {
        grid-template-columns: 1fr;
      }

      .focus-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .focus-card.wide {
        grid-column: span 1;
      }

      .contact-grid {
        grid-template-columns: 1fr;
      }

      .event-card {
        grid-template-columns: auto 1fr;
      }

      .event-card .btn {
        grid-column: 1/-1;
        justify-self: start;
      }
    }

    @media (max-width: 768px) {

      section,
      #about .section-wrap,
      #programs .section-wrap,
      #events .section-wrap,
      #organizers .section-wrap,
      #contact {
        padding-top: 4rem !important;
        padding-bottom: 4rem !important;
      }

      .nav-links {
        display: none;
      }

      .hamburger {
        display: flex;
      }

      .hero-h1 {
        font-size: clamp(2.4rem, 8vw, 4rem);
      }

      .focus-grid {
        grid-template-columns: 1fr;
      }

      .projects-grid {
        grid-template-columns: 1fr;
      }

      .gallery-grid {
        grid-template-columns: 1fr 1fr;
      }

      .gallery-item:first-child {
        grid-row: auto;
      }

      .vision-mission {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .footer-inner {
        flex-direction: column;
        text-align: center;
      }

      .footer-links {
        flex-wrap: wrap;
        justify-content: center;
        gap: 1rem;
      }

      .mobile-menu a {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .hero-btns {
        flex-direction: column;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .hero-h1 {
        font-size: 2.2rem;
      }
    }
  </style>
</head>

<body>

  <!-- Custom Cursor -->
  <div class="cursor" id="cursor"></div>
  <div class="cursor-ring" id="cursor-ring"></div>

  <!-- Particle Canvas -->
  <canvas id="stars-canvas"></canvas>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <button class="close-menu" id="closeMenu">✕</button>
    <a href="#about">About</a>
    <a href="#focus">Focus Areas</a>
    <a href="#programs">Programs</a>
    <a href="#events">Events</a>
    <a href="#contact">Contact</a>
  </div>

  <!-- NAV -->
  <nav id="navbar">
    <a href="#" class="nav-logo">
      <div class="logo-icon"><i class="ri-anchor-line"></i></div>
      BTIC<span>.</span>
    </a>
    <ul class="nav-links">
      <li><a href="#about">About</a></li>
      <li><a href="#focus">Focus Areas</a></li>
      <li><a href="#programs">Programs</a></li>
      <li><a href="#events">Events</a></li>
      <li><a href="#contact" class="nav-cta">Contact</a></li>
    </ul>
    <button class="hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- ===== HERO ===== -->
  <section id="hero">
    <div class="hero-grid">
      <div class="hero-left">
        <div class="hero-badge reveal">
          <span class="badge-dot"></span>
          Bandari Maritime Academy · Mombasa
        </div>
        <h1 class="hero-h1">
          <span class="line reveal delay-1"><span class="accent">Innovate.</span></span>
          <span class="line reveal delay-2">Create.</span>
          <span class="line reveal delay-3"><span class="amber">Transform.</span></span>
        </h1>
        <p class="hero-sub reveal delay-4">
          Welcome to the Bandari Tech & Innovation Club — where maritime knowledge meets modern technology. We empower
          students to explore creativity, develop digital skills, and pioneer sustainable innovations for the future of
          the blue economy.
        </p>
        <div class="hero-btns reveal delay-5">
          <a href="#" onclick="openJoinModal(event)" class="btn btn-primary"><i class="ri-rocket-2-line"></i> Join the
            Club</a>
          <a href="#programs" class="btn btn-secondary"><i class="ri-lightbulb-line"></i> Explore Projects</a>
          <a href="#events" class="btn btn-outline"><i class="ri-calendar-event-line"></i> Upcoming Events</a>
        </div>
        <div class="stats-row reveal delay-5">
          <?php
          try {
            $stmt = $conn->query("SELECT * FROM stats ORDER BY display_order ASC LIMIT 4");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              if ($row['label'] == 'Driving innovation for the maritime sector')
                continue; // Skip the tagline query
              ?>
              <div class="stat-item">
                <div class="stat-num">
                  <?php echo htmlspecialchars($row['value']); ?><span><?php echo htmlspecialchars($row['suffix']); ?></span>
                </div>
                <div class="stat-label"><?php echo htmlspecialchars($row['label']); ?></div>
              </div>
              <?php
            }
          } catch (PDOException $e) {
            echo "<!-- Stats load error -->";
          }
          ?>
        </div>
      </div>
      <div class="hero-visual">
        <div class="globe-container reveal delay-3">
          <div class="globe-ring"></div>
          <div class="globe-ring"></div>
          <div class="globe-ring"></div>
          <div class="globe-core">
            <img src="assets/images/logo.jpeg" alt="Bandari Tech Club Logo" class="hero-logo reveal delay-3"
              style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; filter: invert(1); mix-blend-mode: screen;">
          </div>
          <div class="orbit-dot"></div>
          <div class="orbit-dot"></div>
          <div class="orbit-dot"></div>
        </div>
      </div>
    </div>
    <div class="scroll-hint">
      <span>Scroll</span>
      <div class="scroll-line"></div>
    </div>
  </section>

  <!-- ===== ABOUT ===== -->
  <section id="about">
    <div class="divider"></div>
    <div class="section-wrap" style="padding-top: 8rem; padding-bottom: 8rem;">
      <div class="about-grid">
        <div class="about-left">
          <p class="section-label reveal">Who We Are</p>
          <h2 class="section-title reveal delay-1">About the Club</h2>
          <p class="section-sub reveal delay-2">
            The Bandari Tech & Innovation Club (BTIC) is a student-led organization at Bandari Maritime Academy
            dedicated to advancing digital literacy, fostering creativity, and promoting innovation across the maritime
            and technological fields.
          </p>
          <p class="section-sub reveal delay-3" style="margin-top: 1rem;">
            We equip members with the skills, mindset, and confidence to develop real-world solutions that enhance
            maritime operations, sustainability, and community development through technology.
          </p>
          <div class="vision-mission">
            <div class="vm-card reveal delay-3">
              <h4><i class="ri-telescope-line"></i> Vision</h4>
              <p>To be a leading hub of maritime innovation and digital excellence in Bandari Maritime Academy, East
                Africa, Africa and Beyond</p>
            </div>
            <div class="vm-card reveal delay-4">
              <h4><i class="ri-focus-3-line"></i> Mission</h4>
              <p>To empower students with digital skills, creative problem-solving, and innovative spirit to drive
                technological advancement in maritime and beyond.</p>
            </div>
          </div>
        </div>
        <div class="about-right reveal-right delay-2">
          <div class="about-visual">
            <div class="av-grid">
              <div class="av-item">
                <span class="av-icon"><i class="ri-computer-line"></i></span>
                <div class="av-num">50+</div>
                <div class="av-label">Students Trained</div>
              </div>
              <div class="av-item">
                <span class="av-icon"><i class="ri-ship-2-line"></i></span>
                <div class="av-num">12+</div>
                <div class="av-label">Maritime Projects</div>
              </div>
              <div class="av-item">
                <span class="av-icon"><i class="ri-trophy-line"></i></span>
                <div class="av-num">5+</div>
                <div class="av-label">Awards Won</div>
              </div>
              <div class="av-item">
                <span class="av-icon"><i class="ri-hand-heart-line"></i></span>
                <div class="av-num">8+</div>
                <div class="av-label">Industry Partners</div>
              </div>
              <div class="av-item" style="grid-column: span 2; flex-direction: row; gap: 1rem; align-items: center;">
                <span class="av-icon"><i class="ri-earth-line"></i></span>
                <div>
                  <div class="av-num" style="font-size: 1.2rem;">East Africa's Blue Economy</div>
                  <div class="av-label">Driving innovation for the maritime sector</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="divider"></div>
  </section>

  <!-- ===== FOCUS AREAS ===== -->
  <section id="focus">
    <div class="section-wrap">
      <div class="focus-header">
        <p class="section-label reveal" style="justify-content: center;">What We Do</p>
        <h2 class="section-title reveal delay-1">Our Focus Areas</h2>
        <p class="section-sub reveal delay-2">
          From digital literacy to ocean conservation, BTIC bridges the gap between technology and maritime excellence.
        </p>
      </div>
      <div class="focus-grid">
        <div class="focus-card reveal delay-1">
          <span class="focus-icon"><i class="ri-computer-line"></i></span>
          <h3>Digital Literacy</h3>
          <p>We train students in essential digital skills — from coding and data analytics to cybersecurity and AI,
            building a future-ready workforce.</p>
        </div>
        <div class="focus-card reveal delay-2">
          <span class="focus-icon"><i class="ri-anchor-line"></i></span>
          <h3>Maritime Innovation</h3>
          <p>We develop tech-based solutions for navigation, safety, logistics, and marine sustainability that reshape
            the maritime industry.</p>
        </div>
        <div class="focus-card reveal delay-3">
          <span class="focus-icon"><i class="ri-settings-3-line"></i></span>
          <h3>Engineering & Robotics</h3>
          <p>Members design and build prototypes, IoT devices, and smart systems tailored for the maritime sector's
            unique challenges.</p>
        </div>
        <div class="focus-card wide reveal delay-1">
          <span class="focus-icon"><i class="ri-earth-line"></i></span>
          <h3>Research & Sustainability</h3>
          <p>We collaborate on research that supports green energy, ocean conservation, and community-driven
            technological solutions for a sustainable blue economy.</p>
        </div>
        <div class="focus-card reveal delay-2">
          <span class="focus-icon"><i class="ri-hand-heart-line"></i></span>
          <h3>Collaboration & Mentorship</h3>
          <p>We partner with faculty, industry professionals, and organizations to inspire and nurture the next
            generation of maritime innovators.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== PROGRAMS & PROJECTS ===== -->
  <section id="programs">
    <div class="divider"></div>
    <div class="section-wrap" style="padding-top: 8rem; padding-bottom: 8rem;">
      <div class="programs-grid">
        <div>
          <p class="section-label reveal">What We Offer</p>
          <h2 class="section-title reveal delay-1">Programs & Activities</h2>
          <div class="prog-list">
            <?php
            try {
              $programs_stmt = $conn->query("SELECT * FROM programs ORDER BY display_order ASC");
              if ($programs_stmt->rowCount() > 0) {
                $p_num = 1;
                while ($prog = $programs_stmt->fetch(PDO::FETCH_ASSOC)) {
                  $delay_class = ($p_num % 2 == 0) ? 'delay-2' : 'delay-1';
                  ?>
                  <div class="prog-item reveal <?php echo $delay_class; ?>">
                    <div class="prog-num"><?php echo str_pad($p_num, 2, '0', STR_PAD_LEFT); ?></div>
                    <div class="prog-content">
                      <h4><?php echo htmlspecialchars($prog['title']); ?></h4>
                      <p><?php echo htmlspecialchars($prog['description']); ?></p>
                    </div>
                  </div>
                  <?php
                  $p_num++;
                }
              } else {
                echo '<p class="reveal" style="color:var(--muted);">More programs coming soon.</p>';
              }
            } catch (PDOException $e) {
              echo "<!-- Programs load error -->";
            }
            ?>
          </div>
        </div>
        <div>
          <p class="section-label reveal">Innovation in Action</p>
          <h2 class="section-title reveal delay-1">Featured Projects</h2>
          <div class="projects-grid" style="margin-top: 2rem;">
            <?php
            try {
              $projects_stmt = $conn->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 4");
              if ($projects_stmt->rowCount() > 0) {
                while ($proj = $projects_stmt->fetch(PDO::FETCH_ASSOC)) {
                  // Determine tag color based on category (optional simple logic)
                  $tag_style = "";
                  $cat = strtolower($proj['category']);
                  if (strpos($cat, 'ai') !== false) {
                    $tag_style = 'style="background: rgba(74,222,128,0.1); color: #4ade80;"';
                  } elseif (strpos($cat, 'app') !== false) {
                    $tag_style = 'style="background: rgba(56,189,248,0.1); color: var(--sky);"';
                  } elseif (strpos($cat, 'green') !== false) {
                    $tag_style = 'style="background: rgba(245,166,35,0.1); color: var(--amber);"';
                  }
                  ?>
                  <div class="project-card reveal delay-1">
                    <span class="project-tag" <?php echo $tag_style; ?>><?php echo htmlspecialchars($proj['category']); ?></span>
                    <h4><?php echo htmlspecialchars($proj['title']); ?></h4>
                    <p><?php echo htmlspecialchars($proj['description']); ?></p>
                    <?php if (!empty($proj['image_url'])): ?>
                      <!-- Optional: Display image if you want to change layout to include images -->
                    <?php endif; ?>
                    <a href="#" class="arrow">View Project →</a>
                  </div>
                  <?php
                }
              } else {
                echo '<p class="reveal" style="color:var(--muted); grid-column: 1/-1; text-align:center;">No projects added yet. Check back soon!</p>';
              }
            } catch (PDOException $e) {
              echo "<!-- Projects load error -->";
            }
            ?>
          </div>
          <div style="margin-top: 1.5rem;" class="reveal delay-3">
            <a href="#" class="btn btn-secondary">→ See All Projects</a>
          </div>
        </div>
      </div>
    </div>
    <div class="divider"></div>
  </section>

  <!-- ===== JOIN CTA ===== -->
  <section id="join">
    <div class="section-wrap">
      <div class="join-inner reveal">
        <p class="section-label">Join the Movement</p>
        <h2 class="section-title">Shape the Future of the<br><span style="color: var(--teal);">Maritime Industry</span>
        </h2>
        <p class="section-sub">
          Are you passionate about technology, innovation, or maritime solutions? Become part of a community that's
          pioneering the next wave of change for the blue economy.
        </p>
        <div class="join-btns">
          <a href="#" onclick="openJoinModal(event)" class="btn btn-primary"><i class="ri-edit-circle-line"></i>
            Register as a Member</a>
          <a href="#contact" class="btn btn-secondary"><i class="ri-hand-heart-line"></i> Partner with Us</a>
          <a href="#contact" class="btn btn-outline"><i class="ri-mail-send-line"></i> Contact Us</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== EVENTS ===== -->
  <section id="events">
    <div class="divider"></div>
    <div class="section-wrap" style="padding-top: 8rem; padding-bottom: 8rem;">
      <div
        style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
        <div>
          <p class="section-label reveal">What's Coming</p>
          <h2 class="section-title reveal delay-1">Upcoming Events</h2>
        </div>
        <a href="#" class="btn btn-secondary reveal delay-1">→ View All Events</a>
      </div>
      <?php
      try {
        $events_stmt = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
        if ($events_stmt->rowCount() > 0) {
          while ($event = $events_stmt->fetch(PDO::FETCH_ASSOC)) {
            $day = date('d', strtotime($event['event_date']));
            $month_year = date('F Y', strtotime($event['event_date']));
            $fmt_date = date('F j, Y', strtotime($event['event_date']));
            ?>
            <div class="event-card reveal delay-2">
              <div class="event-date">
                <div class="day"><?php echo $day; ?></div>
                <div class="month"><?php echo $month_year; ?></div>
              </div>
              <div class="event-info">
                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                <p>
                  <?php echo htmlspecialchars($event['description']); ?>
                </p>
                <div class="event-meta">
                  <span class="meta-item"><i class="ri-map-pin-line"></i>
                    <?php echo htmlspecialchars($event['location']); ?></span>
                  <span class="meta-item"><i class="ri-calendar-event-line"></i> <?php echo $fmt_date; ?></span>
                </div>
              </div>
              <a href="#" class="btn btn-primary">Register Now</a>
            </div>
            <?php
          }
        } else {
          echo '<p class="reveal delay-2" style="text-align:center; color:var(--muted); margin-top: 2rem;">No upcoming events scheduled at the moment.</p>';
        }
      } catch (PDOException $e) {
        echo "<!-- Events load error -->";
      }
      ?>
    </div>
    <div class="divider"></div>
  </section>

  <!-- ===== GALLERY ===== -->
  <section id="gallery">
    <div class="section-wrap">
      <div style="margin-bottom: 3rem;">
        <p class="section-label reveal">Our Moments</p>
        <h2 class="section-title reveal delay-1">Life at BTIC</h2>
      </div>
      <div class="gallery-grid">
        <div class="gallery-item reveal delay-1" style="background: linear-gradient(135deg, #07152b, #0a2040);">
          <div class="gallery-bg">
            <svg class="gallery-pattern" viewBox="0 0 200 300" fill="none">
              <circle cx="100" cy="150" r="80" stroke="rgba(0,201,167,0.3)" stroke-width="1" />
              <circle cx="100" cy="150" r="60" stroke="rgba(0,201,167,0.2)" stroke-width="1" />
              <circle cx="100" cy="150" r="40" stroke="rgba(0,201,167,0.15)" stroke-width="1" />
              <line x1="20" y1="150" x2="180" y2="150" stroke="rgba(0,201,167,0.1)" stroke-width="1" />
              <line x1="100" y1="70" x2="100" y2="230" stroke="rgba(0,201,167,0.1)" stroke-width="1" />
            </svg>
          </div>
          <span style="font-size: 4rem; position: relative; z-index: 1;"><i class="ri-robot-line"></i></span>
          <div class="gallery-overlay"><span class="gallery-label">Robotics Workshop</span></div>
        </div>
        <div class="gallery-item reveal delay-2" style="background: linear-gradient(135deg, #0a2040, #071a30);">
          <span style="font-size: 3rem;"><i class="ri-lightbulb-flash-line"></i></span>
          <div class="gallery-overlay"><span class="gallery-label">Hackathon 2025</span></div>
        </div>
        <div class="gallery-item reveal delay-3" style="background: linear-gradient(135deg, #071a30, #0a2040);">
          <span style="font-size: 3rem;"><i class="ri-rocket-2-line"></i></span>
          <div class="gallery-overlay"><span class="gallery-label">Innovation Fair</span></div>
        </div>
        <div class="gallery-item reveal delay-2" style="background: linear-gradient(135deg, #0a2040, #07152b);">
          <span style="font-size: 3rem;"><i class="ri-drop-line"></i></span>
          <div class="gallery-overlay"><span class="gallery-label">Marine Research</span></div>
        </div>
        <div class="gallery-item reveal delay-3" style="background: linear-gradient(135deg, #071220, #0a2040);">
          <span style="font-size: 3rem;"><i class="ri-graduation-cap-line"></i></span>
          <div class="gallery-overlay"><span class="gallery-label">Community Outreach</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== ORGANIZERS ===== -->
  <section id="organizers">
    <div class="section-wrap" style="padding-top: 8rem; padding-bottom: 8rem;">
      <div style="text-align: center; margin-bottom: 4rem;">
        <p class="section-label reveal">Who We Are</p>
        <h2 class="section-title reveal delay-1">Meet the Organizers</h2>
        <p class="section-sub reveal delay-2" style="margin: 1rem auto 0; max-width: 600px;">
          The dedicated team driving innovation and mentorship at Bandari Maritime Academy.
        </p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <?php
        try {
          $org_stmt = $conn->query("SELECT * FROM organizers ORDER BY display_order ASC, id ASC");
          if ($org_stmt->rowCount() > 0) {
            while ($org = $org_stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="reveal delay-1"
                style="background: var(--card-bg); padding: 2rem; border-radius: 12px; border: 1px solid var(--border); text-align: center;">
                <div
                  style="width: 100px; height: 100px; margin: 0 auto 1.5rem; border-radius: 50%; overflow: hidden; border: 2px solid var(--teal); padding: 4px;">
                  <?php if ($org['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($org['image_url']); ?>"
                      style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                      alt="<?php echo htmlspecialchars($org['name']); ?>">
                  <?php else: ?>
                    <div
                      style="width: 100%; height: 100%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--muted);border-radius:50%;">
                      <i class="ri-user-line"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <h4 style="font-size: 1.25rem; font-weight: 600; color: var(--white); margin-bottom: 0.25rem;">
                  <?php echo htmlspecialchars($org['name']); ?>
                </h4>
                <p
                  style="color: var(--teal); font-size: 0.9rem; font-weight: 500; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                  <?php echo htmlspecialchars($org['role']); ?>
                </p>
                <p style="color: var(--muted); font-size: 0.9rem; line-height: 1.6;">
                  <?php echo htmlspecialchars($org['bio']); ?>
                </p>
              </div>
              <?php
            }
          } else {
            // Optional fallback or just nothing
            echo '<p class="reveal" style="text-align:center; color:var(--muted); grid-column: 1/-1;">Team members being updated...</p>';
          }
        } catch (PDOException $e) {
          echo "<!-- Organizers load error -->";
        }
        ?>
      </div>
    </div>
    <div class="divider"></div>
  </section>

  <!-- ===== PARTNERS ===== -->
  <section id="partners">
    <div class="section-wrap" style="padding-top: 5rem; padding-bottom: 5rem;">
      <p class="section-label reveal" style="text-align: center;">Collaborations</p>
      <h2 class="section-title reveal delay-1" style="text-align: center; margin-bottom: 3rem;">Our Partners</h2>

      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 3rem; align-items: center;">
        <?php
        try {
          $part_stmt = $conn->query("SELECT * FROM partners ORDER BY display_order ASC, id ASC");
          if ($part_stmt->rowCount() > 0) {
            while ($p = $part_stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <a href="<?php echo htmlspecialchars($p['website_url']); ?>" target="_blank" class="reveal delay-2"
                style="transition: all 0.3s;">
                <?php if ($p['logo_url']): ?>
                  <img src="<?php echo htmlspecialchars($p['logo_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>"
                    style="height: 100px; max-width: 250px; object-fit: contain;">
                <?php else: ?>
                  <h4 style="color: var(--white); font-weight: 700; font-size: 1.5rem; margin: 0;">
                    <?php echo htmlspecialchars($p['name']); ?>
                  </h4>
                <?php endif; ?>
              </a>
              <?php
            }
          }
        } catch (PDOException $e) {
        }
        ?>
      </div>
    </div>
    <div class="divider"></div>
  </section>

  <!-- ===== CONTACT ===== -->
  <section id="contact" style="padding-top: 8rem; padding-bottom: 8rem;">
    <div class="divider" style="margin-bottom: 8rem;"></div>
    <div class="section-wrap">
      <div class="contact-grid">
        <div>
          <p class="section-label reveal">Get In Touch</p>
          <h2 class="section-title reveal delay-1">Let's Connect</h2>
          <p class="section-sub reveal delay-2">
            Have a question, an idea, or want to collaborate? We'd love to hear from you. Reach out and let's build
            something amazing together.
          </p>
          <div class="contact-info">
            <div class="contact-item reveal delay-3">
              <div class="ci-icon"><i class="ri-map-pin-line"></i></div>
              <div class="ci-content">
                <h4>Location</h4>
                <p>Bandari Maritime Academy, Mombasa, Kenya</p>
              </div>
            </div>
            <div class="contact-item reveal delay-3">
              <div class="ci-icon"><i class="ri-mail-line"></i></div>
              <div class="ci-content">
                <h4>Email</h4>
                <p>info@bandaritechclub.ac.ke</p>
              </div>
            </div>
            <div class="contact-item reveal delay-4">
              <div class="ci-icon"><i class="ri-phone-line"></i></div>
              <div class="ci-content">
                <h4>Phone</h4>
                <p>+254 7XX XXX XXX</p>
              </div>
            </div>
            <div class="contact-item reveal delay-4">
              <div class="ci-icon"><i class="ri-global-line"></i></div>
              <div class="ci-content">
                <h4>Website</h4>
                <p>www.bandaritechclub.ac.ke</p>
              </div>
            </div>
          </div>
          <div class="social-row reveal delay-5">
            <a href="#" class="social-btn" title="Facebook"><i class="ri-facebook-fill"></i></a>
            <a href="#" class="social-btn" title="LinkedIn"><i class="ri-linkedin-fill"></i></a>
            <a href="#" class="social-btn" title="Instagram"><i class="ri-instagram-line"></i></a>
            <a href="#" class="social-btn" title="GitHub"><i class="ri-github-fill"></i></a>
          </div>
        </div>
        <div class="contact-form reveal-right delay-2">
          <h3 style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.3rem; margin-bottom: 1.8rem;">Send
            us a Message</h3>

          <form action="submit_contact.php" method="POST">
            <div class="form-row">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" placeholder="John" required />
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" placeholder="Doe" required />
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="john@example.com" required />
            </div>
            <div class="form-group">
              <label for="interest_type">I'm interested in</label>
              <select id="interest_type" name="interest_type">
                <option value="General Inquiry">General Inquiry</option>
                <option value="Membership">Joining as a Member</option>
                <option value="Partnership">Partnership / Sponsorship</option>
                <option value="Collaboration">Collaboration on a Project</option>
                <option value="Media">Media / Press Inquiry</option>
              </select>
            </div>
            <div class="form-group">
              <label for="message_body">Message</label>
              <textarea id="message_body" name="message_body" placeholder="Tell us about yourself or your inquiry..."
                required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"
              style="width: 100%; justify-content: center; font-size: 1rem; padding: 1rem;">
              <i class="ri-mail-send-line"></i> Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer>
    <div class="footer-inner">
      <div>
        <div class="footer-logo">Bandari <span>Tech</span> & Innovation Club</div>
        <p style="font-size: 0.8rem; color: var(--muted); margin-top: 0.4rem;">Bandari Maritime Academy · Mombasa, Kenya
        </p>
      </div>
      <div class="footer-links">
        <a href="#about">About</a>
        <a href="#focus">Focus Areas</a>
        <a href="#programs">Programs</a>
        <a href="#events">Events</a>
        <a href="#gallery">Gallery</a>
        <a href="#contact">Contact</a>
      </div>
    </div>
    <div class="footer-copy">
      © 2026 <span>Bandari Tech & Innovation Club</span>. Powered by Students of Bandari Maritime Academy.
      <span>Innovate for the Future. <i class="ri-code-s-slash-line"></i></span>
    </div>
  </footer>

  <!-- ===== MEMBERSHIP MODAL ===== -->
  <div id="membershipModal" class="modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 style="font-family: 'Syne', sans-serif; color: var(--teal); margin-bottom: 0.5rem;">Join the Club</h2>
      <p style="color: var(--muted); margin-bottom: 1.5rem; font-size: 0.9rem;">Become a member of the Bandari Tech &
        Innovation Club.</p>

      <form action="submit_join.php" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>
          </div>
          <div class="form-group">
            <label for="admission_number">Admission Number</label>
            <input type="text" id="admission_number" name="admission_number" placeholder="e.g. BMA/001/2024" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="join_email">Email Address</label>
            <input type="email" id="join_email" name="email" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="institution">Institution</label>
            <input type="text" id="institution" name="institution" value="Bandari Maritime Academy" required>
          </div>
          <div class="form-group">
            <label for="course">Course / Department</label>
            <input type="text" id="course" name="course" placeholder="e.g. Marine Engineering" required>
          </div>
        </div>

        <div class="form-group">
          <label for="year_of_study">Year of Study</label>
          <select id="year_of_study" name="year_of_study" required>
            <option value="">Select Year...</option>
            <option value="Year 1">Year 1</option>
            <option value="Year 2">Year 2</option>
            <option value="Year 3">Year 3</option>
            <option value="Staff/Faculty">Staff / Faculty</option>
            <option value="Other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="interests">Technical Interests (Select all that apply)</label>
          <input type="text" id="interests" name="interests" placeholder="e.g. Coding, IoT, Robotics, Design..."
            required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
          Submit Application
        </button>
      </form>
    </div>
  </div>

  <style>
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(5, 13, 26, 0.9);
      backdrop-filter: blur(5px);
    }

    .modal-content {
      background-color: var(--deep);
      margin: 5% auto;
      padding: 2.5rem;
      border: 1px solid var(--teal);
      border-radius: 16px;
      width: 90%;
      max-width: 600px;
      box-shadow: 0 0 50px rgba(0, 201, 167, 0.15);
      position: relative;
      animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .close-modal {
      color: var(--muted);
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.3s;
    }

    .close-modal:hover,
    .close-modal:focus {
      color: var(--teal);
      text-decoration: none;
    }

    /* Toast Notification */
    .toast-container {
      position: fixed;
      top: 100px;
      right: 2rem;
      z-index: 10001;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      pointer-events: none;
    }

    .toast {
      background: var(--card-bg);
      backdrop-filter: blur(15px);
      border: 1px solid var(--teal);
      border-left: 5px solid var(--teal);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      color: var(--white);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      gap: 1rem;
      min-width: 300px;
      max-width: 450px;
      animation: toastIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
      pointer-events: auto;
    }

    .toast.error {
      border-color: #ff6b6b;
      border-left-color: #ff6b6b;
    }

    .toast i {
      font-size: 1.5rem;
    }

    .toast.success i {
      color: var(--teal);
    }

    .toast.error i {
      color: #ff6b6b;
    }

    .toast-content {
      flex: 1;
    }

    .toast-title {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      margin-bottom: 0.2rem;
    }

    .toast-msg {
      font-size: 0.85rem;
      color: var(--muted);
    }

    @keyframes toastIn {
      from {
        transform: translateX(120%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes toastOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }

      to {
        transform: translateX(120%);
        opacity: 0;
      }
    }
  </style>

  <script>
    // ===== CUSTOM CURSOR =====
    const cursor = document.getElementById('cursor');
    const ring = document.getElementById('cursor-ring');
    let mouseX = 0, mouseY = 0, ringX = 0, ringY = 0;
    document.addEventListener('mousemove', e => {
      mouseX = e.clientX; mouseY = e.clientY;
      cursor.style.left = mouseX + 'px';
      cursor.style.top = mouseY + 'px';
    });
    setInterval(() => {
      ringX += (mouseX - ringX) * 0.12;
      ringY += (mouseY - ringY) * 0.12;
      ring.style.left = ringX + 'px';
      ring.style.top = ringY + 'px';
    }, 16);
    document.querySelectorAll('a, button, .focus-card, .project-card, .prog-item').forEach(el => {
      el.addEventListener('mouseenter', () => {
        cursor.style.width = '20px'; cursor.style.height = '20px';
        ring.style.width = '60px'; ring.style.height = '60px';
      });
      el.addEventListener('mouseleave', () => {
        cursor.style.width = '12px'; cursor.style.height = '12px';
        ring.style.width = '40px'; ring.style.height = '40px';
      });
    });

    // ===== STAR PARTICLES =====
    const canvas = document.getElementById('stars-canvas');
    const ctx = canvas.getContext('2d');
    let particles = [];
    function resize() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    for (let i = 0; i < 120; i++) {
      particles.push({
        x: Math.random() * window.innerWidth,
        y: Math.random() * window.innerHeight,
        r: Math.random() * 1.5 + 0.3,
        ox: Math.random() * window.innerWidth,
        oy: Math.random() * window.innerHeight,
        speed: Math.random() * 0.4 + 0.1,
        angle: Math.random() * Math.PI * 2,
        pulse: Math.random() * Math.PI * 2,
        color: ['#00c9a7', '#38bdf8', '#f5a623'][Math.floor(Math.random() * 3)]
      });
    }

    function animateParticles() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach(p => {
        p.angle += p.speed * 0.008;
        p.pulse += 0.02;
        const wobble = 30;
        p.x = p.ox + Math.cos(p.angle) * wobble;
        p.y = p.oy + Math.sin(p.angle * 1.3) * wobble;
        const alpha = 0.3 + Math.sin(p.pulse) * 0.3;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = p.color + Math.floor(alpha * 255).toString(16).padStart(2, '0');
        ctx.fill();
      });
      // Draw connections
      particles.forEach((p, i) => {
        particles.slice(i + 1).forEach(q => {
          const d = Math.hypot(p.x - q.x, p.y - q.y);
          if (d < 100) {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(q.x, q.y);
            ctx.strokeStyle = `rgba(0,201,167,${0.04 * (1 - d / 100)})`;
            ctx.lineWidth = 0.5;
            ctx.stroke();
          }
        });
      });
      requestAnimationFrame(animateParticles);
    }
    animateParticles();

    // ===== NAVBAR SCROLL =====
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    });

    // ===== MOBILE MENU =====
    document.getElementById('hamburger').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.add('open');
    });
    document.getElementById('closeMenu').addEventListener('click', () => {
      document.getElementById('mobileMenu').classList.remove('open');
    });
    document.querySelectorAll('.mobile-menu a').forEach(a => {
      a.addEventListener('click', () => document.getElementById('mobileMenu').classList.remove('open'));
    });

    // ===== SCROLL REVEAL =====
    const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(el => io.observe(el));

    // ===== ANIMATED COUNTER =====
    function animateCounter(el, target, suffix = '') {
      let current = 0;
      const step = target / 40;
      const timer = setInterval(() => {
        current += step;
        if (current >= target) { current = target; clearInterval(timer); }
        el.textContent = Math.floor(current) + suffix;
      }, 40);
    }

    const countEls = document.querySelectorAll('.av-num');
    const counterIO = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          const el = e.target;
          const num = parseInt(el.textContent);
          const suffix = el.textContent.includes('+') ? '+' : '';
          animateCounter(el, num, suffix);
          counterIO.unobserve(el);
        }
      });
    }, { threshold: 0.5 });
    countEls.forEach(el => counterIO.observe(el));

    // ===== MEMBERSHIP MODAL LOGIC =====
    const modal = document.getElementById("membershipModal");
    const closeBtn = document.getElementsByClassName("close-modal")[0];

    // Open modal on specific buttons
    // We attach this to any link with class 'open-join-modal'
    function openJoinModal(e) {
      if (e) e.preventDefault();
      modal.style.display = "block";
    }

    // Close on X
    closeBtn.onclick = function () {
      modal.style.display = "none";
    }

    // Close on click outside
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    // ===== TOAST NOTIFICATION LOGIC =====
    function showToast(status, message) {
      const container = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = `toast ${status}`;

      const icon = status === 'success' ? 'ri-checkbox-circle-line' : 'ri-error-warning-line';
      const title = status === 'success' ? 'Brilliant!' : 'Attention';

      toast.innerHTML = `
        <i class="${icon}"></i>
        <div class="toast-content">
          <div class="toast-title">${title}</div>
          <div class="toast-msg">${message}</div>
        </div>
        <i class="ri-close-line" style="cursor: pointer; font-size: 1rem; opacity: 0.5;" onclick="this.parentElement.remove()"></i>
      `;

      container.appendChild(toast);

      // Auto remove after 5 seconds
      setTimeout(() => {
        toast.style.animation = 'toastOut 0.5s forwards';
        setTimeout(() => toast.remove(), 500);
      }, 5000);
    }

    // Check for status in URL
    document.addEventListener('DOMContentLoaded', () => {
      const urlParamsSearch = new URLSearchParams(window.location.search);
      const status = urlParamsSearch.get('status');
      const msg = urlParamsSearch.get('message');

      if (status && msg) {
        showToast(status, msg);
        // Clean up URL
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
      }
    });
  </script>

  <!-- ===== TOAST NOTIFICATION ===== -->
  <div id="toastContainer" class="toast-container"></div>
</body>

</html>