<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$terminal   = input('terminal', '101');
$standort   = input('standort', '');
$bearbeiter = input('bearbeiter1', '');
$einheit    = input('einheit', 'Palette');
$vorgang    = input('vorgang', 'Einlagerung');
$barcode    = input('barcode', '');
$lagerplatz = input('lagerplatz', '');

if (!$barcode) {
  http_response_code(422);
  echo "<p>Barcode fehlt.</p><p><a href='/scan'>Zurück</a></p>";
  exit;
}

$now = (new DateTime('now', new DateTimeZone('Europe/Berlin')));
$serverDT = $now->format('Y-m-d H:i:s'); // BUCHUNGDATUMZEITZIELSERVER
$exportFlag = '    '; // vier Spaces statt 'NEIN'

$sql = "
INSERT INTO dbo.BEWEGUNG
(TERMINAL, BUCHUNGDATUMZEITZIELSERVER, BUCHUNGSQUELLE, BARCODE, EINHEIT, VORGANG,
 BUCHUNGDATUMZEITVORGANG, STANDORT, LAGERPLATZ, BEARBEITER1, EXPORTFLAG)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
";

$params = [
  $terminal,
  $serverDT,
  'WEBUI', // BUCHUNGSQUELLE
  $barcode,
  $einheit,
  $vorgang,
  $serverDT, // treat scan time as vorgang time
  $standort,
  $lagerplatz,
  $bearbeiter,
  $exportFlag,
];

try {
  $pdo = db();
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
} catch (Throwable $e) {
  http_response_code(500);
  echo "<h1>Fehler bei der Buchung</h1><pre>" . h($e->getMessage()) . "</pre><p><a href='/scan'>Zurück</a></p>";
  exit;
}

redirect('/list?barcode=' . urlencode($barcode) . '&limit=20');