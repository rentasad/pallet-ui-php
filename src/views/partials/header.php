<?php
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
  function nav_item(string $href, string $label, string $current): string {
      $active = ($href === '/' ? $current === '/' : str_starts_with($current, $href)) ? 'active' : '';
      return '<li class="nav-item"><a class="nav-link ' . $active . '" href="' . $href . '">' . htmlspecialchars($label) . '</a></li>';
  }
?><!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Priebs Scan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
    body { background:#f7f9fc; }
    .container-narrow { max-width: 1080px; }
    .form-section { background:#fff; border:1px solid #e5e9f2; border-radius:14px; padding:18px; }
    .table-wrap, .table-responsive { overflow-x:auto; }
    .form-grid{
        display:grid;
        grid-template-columns:minmax(140px,220px) 1fr; /* feste Label-Spalte */
        gap:12px 16px;
        align-items:center;
    }
    .form-grid .form-label{ margin:0; }
    .form-actions{
        grid-column:2;                  /* Buttons unter den Feldern ausrichten */
        display:flex; gap:8px; flex-wrap:wrap;
    }
    @media (max-width:576px){
        .form-grid{ grid-template-columns:1fr; }
        .form-actions{ grid-column:1; }
    }

  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid container-narrow">
    <a class="navbar-brand" href="/">Priebs Scan</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Menü umschalten">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?= nav_item('/', 'Start', $path) ?>
        <?= nav_item('/scan', 'Scannen/Buchen', $path) ?>
        <?= nav_item('/list', 'Buchungsliste', $path) ?>
        <?= nav_item('/query', 'Scancode-Abfrage', $path) ?>
        <?= nav_item('/healthz', 'Health', $path) ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container container-narrow my-4">