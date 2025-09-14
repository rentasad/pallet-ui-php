<?php require __DIR__ . '/partials/header.php'; ?>
<h3 class="mb-3">Scannen / Buchen</h3>

<form method="post" action="/scan" class="form-section">
    <!-- Terminal -->
    <div class="row mb-3">
        <label for="terminal" class="col-sm-4 col-form-label">Terminal</label>
        <div class="col-sm-8">
            <input id="terminal" name="terminal" class="form-control" value="101">
        </div>
    </div>

    <!-- Standort -->
    <div class="row mb-3">
        <label for="standort" class="col-sm-4 col-form-label">Standort</label>
        <div class="col-sm-8">
            <input id="standort" name="standort" class="form-control" value="Priebs Logistik">
        </div>
    </div>

    <!-- Mitarbeiter -->
    <div class="row mb-3">
        <label for="bearbeiter1" class="col-sm-4 col-form-label">Mitarbeiter</label>
        <div class="col-sm-8">
            <input id="bearbeiter1" name="bearbeiter1" class="form-control">
        </div>
    </div>

    <!-- Einheit -->
    <div class="row mb-3">
        <label for="einheit" class="col-sm-4 col-form-label">Einheit</label>
        <div class="col-sm-8">
            <input id="einheit" name="einheit" class="form-control" value="Palette">
        </div>
    </div>

    <!-- Vorgang -->
    <div class="row mb-3">
        <label for="vorgang" class="col-sm-4 col-form-label">Vorgang</label>
        <div class="col-sm-8">
            <select id="vorgang" name="vorgang" class="form-select">
                <option>Einlagerung</option>
                <option>Auslagerung</option>
                <option>Umlagerung</option>
            </select>
        </div>
    </div>

    <!-- Barcode (prominent) -->
    <div class="row mb-3">
        <label for="barcode" class="col-sm-4 col-form-label">Scancode</label>
        <div class="col-sm-8">
            <input id="barcode" name="barcode" class="form-control" required autofocus>
        </div>
    </div>

    <!-- Lagerplatz -->
    <div class="row mb-4">
        <label for="lagerplatz" class="col-sm-4 col-form-label">Lagerplatz</label>
        <div class="col-sm-8">
            <input id="lagerplatz" name="lagerplatz" class="form-control">
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-sm-8 offset-sm-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Buchen</button>
            <a class="btn btn-outline-secondary" href="/">Zurück</a>
        </div>
    </div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
