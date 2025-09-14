<?php require __DIR__ . '/partials/header.php'; ?>
<div class="wrap">
    <h1>Scancode-Abfrage</h1>
    <form method="post" action="/query">
      <label>Scancode</label>
      <input name="barcode" required autofocus>
      <button>Suchen</button>
      <p><a href="/">← Zurück</a></p>
    </form>
  </div>
<?php require __DIR__ . '/partials/footer.php'; ?>
