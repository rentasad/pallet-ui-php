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
  IDNR, RTRIM(TERMINAL) AS TERMINAL,
  BUCHUNGDATUMZEITZIELSERVER,
  RTRIM(BUCHUNGSQUELLE)  AS BUCHUNGSQUELLE,
  RTRIM(BARCODE)         AS BARCODE,
  RTRIM(EINHEIT)         AS EINHEIT,
  RTRIM(VORGANG)         AS VORGANG,
  BUCHUNGDATUMZEITVORGANG,
  RTRIM(STANDORT)        AS STANDORT,
  RTRIM(LAGERPLATZ)      AS LAGERPLATZ,
  RTRIM(BEARBEITER1)     AS BEARBEITER1
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
    require __DIR__ . '/views/partials/header.php';
    echo "<div class='alert alert-danger'><strong>Fehler bei der Abfrage:</strong><br><pre class='mb-0'>" . h($e->getMessage()) . "</pre></div>";
    echo "<a class='btn btn-secondary mt-3' href='/'>Zurück</a>";
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

require __DIR__ . '/views/partials/header.php';
?>
<h3 class="mb-3">Buchungsliste</h3>
<form class="form-section row gy-2" method="get" action="/list">
    <div class="col-md-3">
        <label class="form-label">Standort</label>
        <input class="form-control" name="standort" placeholder="Standort" value="<?=h($standort)?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">Von</label>
        <input class="form-control" type="date" name="von" value="<?=h($von)?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">Bis</label>
        <input class="form-control" type="date" name="bis" value="<?=h($bis)?>">
    </div>
    <div class="col-md-2">
        <label class="form-label">Max. Datensätze</label>
        <input class="form-control" type="number" name="limit" value="<?=h((string)$limit)?>" min="1" max="100000">
    </div>
    <div class="col-md-2">
        <label class="form-label">Barcode</label>
        <input class="form-control" name="barcode" placeholder="Barcode" value="<?=h($barcode)?>">
    </div>
    <div class="col-md-1 d-flex align-items-end">
        <button class="btn btn-primary w-100">Filtern</button>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
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
    <?php if (!$rows): ?><p class="text-muted">Keine Datensätze gefunden.</p><?php endif; ?>
</div>
<a class="btn btn-outline-secondary" href="/">Zurück</a>
<?php require __DIR__ . '/views/partials/footer.php'; ?>
