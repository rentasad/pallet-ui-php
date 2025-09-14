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
    require __DIR__ . '/views/partials/header.php';
    echo "<div class='alert alert-warning'>Barcode fehlt.</div><a class='btn btn-secondary' href='/scan'>Zurück</a>";
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

$now = (new DateTime('now', new DateTimeZone('Europe/Berlin')));
$serverDT = $now->format('Y-m-d H:i:s'); // BUCHUNGDATUMZEITZIELSERVER

$sql = "
INSERT INTO dbo.BEWEGUNG
(TERMINAL, BUCHUNGDATUMZEITZIELSERVER, BUCHUNGSQUELLE, BARCODE, EINHEIT, VORGANG,
 BUCHUNGDATUMZEITVORGANG, STANDORT, LAGERPLATZ, BEARBEITER1)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
";

$params = [
        $terminal,
        $serverDT,
        'WEBUI',
        $barcode,
        $einheit,
        $vorgang,
        $serverDT, // Vorgangszeit = jetzt
        $standort,
        $lagerplatz,
        $bearbeiter,
];

try {
    $pdo = db();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (Throwable $e) {
    http_response_code(500);
    require __DIR__ . '/views/partials/header.php';
    echo "<div class='alert alert-danger'><strong>Fehler bei der Buchung:</strong><br><pre class='mb-0'>" . h($e->getMessage()) . "</pre></div>";
    echo "<a class='btn btn-secondary mt-3' href='/scan'>Zurück</a>";
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

redirect('/list?barcode=' . urlencode($barcode) . '&limit=20');
