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
  RTRIM(VORGANG)     AS VORGANG,
  RTRIM(STANDORT)    AS STANDORT,
  RTRIM(LAGERPLATZ)  AS LAGERPLATZ,
  RTRIM(BEARBEITER1) AS BEARBEITER1,
  RTRIM(EINHEIT)     AS EINHEIT,
  RTRIM(TERMINAL)    AS TERMINAL,
  RTRIM(BARCODE)     AS BARCODE
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
    require __DIR__ . '/views/partials/header.php';
    echo "<div class='alert alert-danger'><strong>Fehler bei der Abfrage:</strong><br><pre class='mb-0'>" . h($e->getMessage()) . "</pre></div>";
    echo "<a class='btn btn-secondary mt-3' href='/'>Zurück</a>";
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

require __DIR__ . '/views/partials/header.php';
?>
<h3 class="mb-3">Scancode: <?=h($barcode)?></h3>
<div class="table-responsive">
    <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
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
    <?php if (!$rows): ?><p class="text-muted">Keine Datensätze gefunden.</p><?php endif; ?>
</div>
<a class="btn btn-outline-secondary" href="/">Zurück</a>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
