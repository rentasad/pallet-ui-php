<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$barcode = trim((string)input('barcode', ''));
if ($barcode === '') {
  header('Location: /query');
  exit;
}

$sql = "
SELECT TOP 200
  BUCHUNGDATUMZEITZIELSERVER,
  RTRIM(VORGANG)    AS VORGANG,
  RTRIM(STANDORT)   AS STANDORT,
  RTRIM(LAGERPLATZ) AS LAGERPLATZ,
  RTRIM(BEARBEITER1) AS BEARBEITER1,
  RTRIM(EINHEIT)    AS EINHEIT,
  RTRIM(TERMINAL)   AS TERMINAL,
  RTRIM(BARCODE)    AS BARCODE
FROM dbo.BEWEGUNG
WHERE RTRIM(BARCODE) = RTRIM(?)
ORDER BY BUCHUNGDATUMZEITZIELSERVER DESC;
";

try {
  $pdo = db();
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$barcode]);
  $rows = $stmt->fetchAll();
} catch (Throwable $e) {
  http_response_code(500);
  echo "<h1>Fehler bei der Abfrage</h1><pre>" . h($e->getMessage()) . "</pre><p><a href='/'>Zurück</a></p>";
  exit;
}

?><!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scancode: <?=h($barcode)?></title>
  <style>
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:0;background:#0b1020;color:#e6eaf2}
    .wrap{max-width:1000px;margin:0 auto;padding:24px}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:8px;border-bottom:1px solid #233257;text-align:left;font-size:14px}
    a.btn{padding:10px 14px;border-radius:12px;border:0;background:#1c2b4a;color:#fff;text-decoration:none}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Scancode: <?=h($barcode)?></h1>
    <p><a class="btn" href="/">Zurück</a></p>
    <table>
      <thead>
        <tr>
          <th>Zeit (Server)</th>
          <th>Vorgang</th>
          <th>Standort</th>
          <th>Lagerplatz</th>
          <th>Mitarbeiter</th>
          <th>Einheit</th>
          <th>Terminal</th>
          <th>Barcode</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?=h($r['BUCHUNGDATUMZEITZIELSERVER'])?></td>
            <td><?=h($r['VORGANG'])?></td>
            <td><?=h($r['STANDORT'])?></td>
            <td><?=h($r['LAGERPLATZ'])?></td>
            <td><?=h($r['BEARBEITER1'])?></td>
            <td><?=h($r['EINHEIT'])?></td>
            <td><?=h($r['TERMINAL'])?></td>
            <td><?=h($r['BARCODE'])?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (!$rows): ?><p>Keine Datensätze gefunden.</p><?php endif; ?>
  </div>
</body>
</html>