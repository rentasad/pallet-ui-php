<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$standort = input('standort');
$von = parseDate(input('von'));
$bis = parseDate(input('bis'));
$limit = (int)(input('limit', '1000'));
$barcode = input('barcode');

$conds = [];
$params = [];

if ($standort) { $conds[] = "RTRIM(STANDORT) = RTRIM(?)"; $params[] = $standort; }
if ($barcode)  { $conds[] = "RTRIM(BARCODE)  = RTRIM(?)"; $params[] = $barcode; }
if ($von)      { $conds[] = "CAST(BUCHUNGDATUMZEITZIELSERVER AS date) >= ?"; $params[] = $von; }
if ($bis)      { $conds[] = "CAST(BUCHUNGDATUMZEITZIELSERVER AS date) <= ?"; $params[] = $bis; }

$where = $conds ? ("WHERE " . implode(" AND ", $conds)) : "";
$limit = max(1, min($limit, 100000));

$sql = "
SELECT TOP {$limit}
  IDNR, TERMINAL,
  BUCHUNGDATUMZEITZIELSERVER,
  BUCHUNGSQUELLE,
  RTRIM(BARCODE)   AS BARCODE,
  RTRIM(EINHEIT)   AS EINHEIT,
  RTRIM(VORGANG)   AS VORGANG,
  BUCHUNGDATUMZEITVORGANG,
  RTRIM(STANDORT)  AS STANDORT,
  RTRIM(LAGERPLATZ) AS LAGERPLATZ,
  RTRIM(BEARBEITER1) AS BEARBEITER1
FROM dbo.BEWEGUNG
{$where}
ORDER BY BUCHUNGDATUMZEITZIELSERVER DESC, IDNR DESC;
";

try {
  $pdo = db();
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
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
  <title>Buchungsliste</title>
  <style>
    body{font-family:Inter,system-ui,Segoe UI,Roboto,Helvetica,Arial,sans-serif;margin:0;background:#0b1020;color:#e6eaf2}
    .wrap{max-width:1100px;margin:0 auto;padding:24px}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:8px;border-bottom:1px solid #233257;text-align:left;font-size:14px}
    .filters{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
    input{padding:8px;border-radius:10px;border:1px solid #2a3a63;background:#0c142b;color:#e6eaf2}
    a.btn, button{padding:10px 14px;border-radius:12px;border:0;background:#1c2b4a;color:#fff;text-decoration:none}
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Buchungsliste</h1>
    <form class="filters" method="get" action="/list">
      <input name="standort" placeholder="Standort" value="<?=h($standort)?>">
      <input type="date" name="von" value="<?=h($von)?>">
      <input type="date" name="bis" value="<?=h($bis)?>">
      <input type="number" name="limit" value="<?=h((string)$limit)?>" min="1" max="100000">
      <input name="barcode" placeholder="Barcode" value="<?=h($barcode)?>">
      <button>Filtern</button>
      <a class="btn" href="/">Zurück</a>
    </form>

    <table>
      <thead>
        <tr>
          <th>Zeit (Server)</th>
          <th>Vorgang</th>
          <th>Barcode</th>
          <th>Einheit</th>
          <th>Standort</th>
          <th>Lagerplatz</th>
          <th>Mitarbeiter</th>
          <th>Terminal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?=h($r['BUCHUNGDATUMZEITZIELSERVER'])?></td>
            <td><?=h($r['VORGANG'])?></td>
            <td><?=h($r['BARCODE'])?></td>
            <td><?=h($r['EINHEIT'])?></td>
            <td><?=h($r['STANDORT'])?></td>
            <td><?=h($r['LAGERPLATZ'])?></td>
            <td><?=h($r['BEARBEITER1'])?></td>
            <td><?=h($r['TERMINAL'])?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (!$rows): ?><p>Keine Datensätze gefunden.</p><?php endif; ?>
  </div>
</body>
</html>