<?php require __DIR__ . '/partials/header.php'; ?>
<h3 class="mb-3">Scannen / Buchen</h3>

<form method="post" action="/scan" class="form-section form-grid">
    <label for="terminal"   class="form-label">Terminal</label>
    <input id="terminal"    name="terminal"   class="form-control" value="101">

    <label for="standort"   class="form-label">Standort</label>
    <input id="standort"    name="standort"   class="form-control" value="Priebs Logistik">

    <label for="bearbeiter1" class="form-label">Mitarbeiter</label>
    <input id="bearbeiter1"  name="bearbeiter1" class="form-control">

    <label for="einheit"    class="form-label">Einheit</label>
    <input id="einheit"     name="einheit"    class="form-control" value="Palette">

    <label for="vorgang"    class="form-label">Vorgang</label>
    <select id="vorgang"    name="vorgang"    class="form-select">
        <option>Eingang</option>
        <option>Ausgang</option>
        <option>Storno</option>
    </select>

    <label for="barcode"    class="form-label">Scancode</label>
    <input id="barcode"     name="barcode"    class="form-control" required autofocus>

    <label for="lagerplatz" class="form-label">Lagerplatz</label>
    <input id="lagerplatz"  name="lagerplatz" class="form-control">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Buchen</button>
        <a class="btn btn-outline-secondary" href="/">Zurück</a>
    </div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
