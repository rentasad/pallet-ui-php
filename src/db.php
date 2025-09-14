<?php
declare(strict_types=1);

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $server = getenv('DB_HOST') ?: '';
    $port   = getenv('DB_PORT') ?: '1433';
    $db     = getenv('DB_DATABASE') ?: 'Priebslogistik';
    $user   = getenv('DB_USERNAME') ?: 'sa';
    $pass   = getenv('DB_PASSWORD') ?: 'YourStrong!Passw0rd';

    // Fallback NIE auf localhost – lieber auf 'mssql'
    if ($server === '' || $server === '127.0.0.1' || $server === 'localhost') {
        $server = 'mssql';
    }

    // Unverschlüsselte Verbindung (wie gewünscht)
    $dsn = "sqlsrv:Server={$server},{$port};Database={$db};Encrypt=No";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // optional:
        // PDO::SQLSRV_ATTR_QUERY_TIMEOUT => 30,
    ];

    // kleine Retry-Schleife (wartet, falls SQL gerade noch warm wird)
    $attempts = 0;
    while (true) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            if (++$attempts >= 10) throw $e;
            usleep(300_000);
        }
    }
}
