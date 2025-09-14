<?php require __DIR__ . '/partials/header.php'; ?>
    <div class="wrap">
        <header>
            <h1>Paletten-Buchung</h1>
            <a class="btn btn-secondary btn-sm" href="/healthz">Health</a>
        </header>

        <style>
            /* Basis: etwas Abstand zwischen Cards */
            .grid {
                display: grid;
                gap: 16px;
            }
            /* Ab Desktop: Cards nebeneinander */
            @media (min-width: 992px) {
                .grid {
                    grid-template-columns: repeat(3, 1fr);
                    align-items: start;
                }
            }
        </style>

        <div class="grid">
            <div class="card">
                <h2>Scannen / Buchen</h2>
                <p class="muted">Erfasst Ein- und Auslagerung inkl. Standort, Mitarbeiter, Lagerplatz.</p>
                <a class="btn btn-primary" href="/scan">Zur Erfassung</a>
            </div>
            <div class="card">
                <h2>Buchungsliste</h2>
                <p class="muted">Filter nach Zeitraum, Standort, maximale Treffer.</p>
                <form class="form-section row gy-2 gx-0" action="/list" method="get">
                    <label>Standort</label>
                    <input name="standort" placeholder="z.B. Priebs Logistik KG Eibau">
                    <label>Von</label>
                    <input type="date" name="von">
                    <label>Bis</label>
                    <input type="date" name="bis">
                    <label>Max. Datensätze</label>
                    <input type="number" name="limit" value="1000" min="1" max="100000">
                    <button class="btn btn-primary mt-2">Anzeigen</button>
                </form>
            </div>
            <div class="card">
                <h2>Scancode-Abfrage</h2>
                <p class="muted">Sucht Vorgänge zu einem Barcode.</p>
                <form action="/query" method="post">
                    <label>Scancode</label>
                    <input name="barcode" required>
                    <button class="btn btn-primary mt-2">Suchen</button>
                </form>
            </div>
        </div>
    </div>
<?php require __DIR__ . '/partials/footer.php'; ?>