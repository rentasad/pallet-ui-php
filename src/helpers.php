<?php
declare(strict_types=1);

function h(?string $v): string { return htmlspecialchars((string)$v ?? '', ENT_QUOTES, 'UTF-8'); }
function input($name, $default='') { return $_POST[$name] ?? $_GET[$name] ?? $default; }

function parseDate(?string $d): ?string {
    if (!$d) return null;
    // Accept formats: YYYY-MM-DD, DD.MM.YYYY
    $d = trim($d);
    $dt = DateTime::createFromFormat('Y-m-d', $d) ?: DateTime::createFromFormat('d.m.Y', $d);
    if (!$dt) return null;
    return $dt->format('Y-m-d');
}

function redirect(string $path): void {
    header("Location: {$path}");
    exit;
}