<?php
?><!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Paletten-Buchung</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:0;background:#0b1020;color:#e6eaf2}
    .wrap{max-width:900px;margin:0 auto;padding:24px}
    header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
    a.btn{display:inline-block;padding:12px 16px;border-radius:12px;background:#1c2b4a;color:#fff;text-decoration:none}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px}
    .card{background:#101a33;border:1px solid #1e2a4b;border-radius:16px;padding:20px}
    .card h2{margin:0 0 8px 0;font-size:18px}
    label{display:block;margin:6px 0 4px 0}
    input,select{width:100%;padding:10px;border-radius:10px;border:1px solid #2a3a63;background:#0c142b;color:#e6eaf2}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:8px;border-bottom:1px solid #233257;text-align:left;font-size:14px}
    .muted{opacity:.75}
  </style>
</head>
<body>
  <div class="wrap">
    <header>
      <h1>Paletten-Buchung</h1>
      <a class="btn" href="/healthz">Health</a>
    </header>

    <div class="grid">
      <div class="card">
        <h2>Scannen / Buchen</h2>
        <p class="muted">Erfasst Ein- und Auslagerung inkl. Standort, Mitarbeiter, Lagerplatz.</p>
        <a class="btn" href="/scan">Zur Erfassung</a>
      </div>
      <div class="card">
        <h2>Buchungsliste</h2>
        <p class="muted">Filter nach Zeitraum, Standort, maximale Treffer.</p>
        <form action="/list" method="get">
          <label>Standort</label>
          <input name="standort" placeholder="z.B. Priebs Logistik KG Eibau">
          <label>Von</label>
          <input type="date" name="von">
          <label>Bis</label>
          <input type="date" name="bis">
          <label>Max. Datensätze</label>
          <input type="number" name="limit" value="1000" min="1" max="100000">
          <button class="btn" style="margin-top:10px">Anzeigen</button>
        </form>
      </div>
      <div class="card">
        <h2>Scancode-Abfrage</h2>
        <p class="muted">Sucht Vorgänge zu einem Barcode.</p>
        <form action="/query" method="post">
          <label>Scancode</label>
          <input name="barcode" required>
          <button class="btn" style="margin-top:10px">Suchen</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>