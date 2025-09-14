<?php ?><!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scannen / Buchen</title>
  <style>
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:0;background:#0b1020;color:#e6eaf2}
    .wrap{max-width:720px;margin:0 auto;padding:24px}
    label{display:block;margin:8px 0 4px}
    input,select{width:100%;padding:12px;border-radius:12px;border:1px solid #2a3a63;background:#0c142b;color:#e6eaf2}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    button{padding:12px 16px;border-radius:12px;border:0;background:#1c2b4a;color:#fff;margin-top:12px}
    a{color:#a8c1ff}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Scannen / Buchen</h1>
    <form method="post" action="/scan">
      <div class="row">
        <div>
          <label>Terminal</label>
          <input name="terminal" value="101">
        </div>
        <div>
          <label>Standort</label>
          <input name="standort" value="Priebs Logistik">
        </div>
      </div>
      <label>Mitarbeiter</label>
      <input name="bearbeiter1" value="">
      <div class="row">
        <div>
          <label>Einheit</label>
          <input name="einheit" value="Palette">
        </div>
        <div>
          <label>Vorgang</label>
          <select name="vorgang">
            <option>Eingang</option>
            <option>Ausgang</option>
            <option>Storno</option>
          </select>
        </div>
      </div>
      <label>Scancode</label>
      <input name="barcode" required autofocus>
      <label>Lagerplatz</label>
      <input name="lagerplatz">
      <button type="submit">Buchen</button>
      <p><a href="/">← Zurück</a></p>
    </form>
  </div>
</body>
</html>