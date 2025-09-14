# Priebs Scan — Pallet Scanning UI (PHP + MSSQL + Docker)

A small, modern web UI to record **pallet in/out/relocation** movements into an existing **Microsoft SQL Server** database.
It replaces an old VB/HTML tool with a clean PHP/Apache app, runs in Docker, and is **mobile-friendly** (Bootstrap 5).

## Features

* **Scan / Book** a movement (`/scan`)
  Stores a row in `dbo.BEWEGUNG` (server timestamp, location, user, barcode, etc.).
* **Booking list** with filters (`/list`)
  Filter by location, date range, barcode, and row limit; responsive table.
* **Barcode query** (`/query`)
  Show all movements for a given barcode.
* **Health endpoint** (`/healthz`)
  JSON status + DB connectivity info (SQL Server version & current DB).
* **Responsive UI**
  Bootstrap 5, header navigation, mobile-optimized forms/tables.

## Tech Stack

* **PHP 8.3** (Apache) with `pdo_sqlsrv` / `sqlsrv`
* **Microsoft SQL Server 2022** (Express by default in Compose)
* **Bootstrap 5** for styling
* **Docker / docker-compose** (with persistent MSSQL data under `./data`)

## Project Layout

```
pallet-ui-php/
├─ docker-compose.yml
├─ Dockerfile
├─ .env.example
├─ public/
│  ├─ index.php          # Front controller
│  └─ .htaccess          # mod_rewrite → pretty URLs
└─ src/
   ├─ index.php          # Tiny router
   ├─ db.php             # PDO connection helper
   ├─ helpers.php        # small utils (input(), h(), parseDate())
   ├─ scan_submit.php    # POST handler for /scan
   ├─ list.php           # /list page (filter + table)
   ├─ query.php          # POST /query results
   └─ views/
      ├─ home.php
      ├─ scan_form.php
      ├─ query_form.php
      └─ partials/
         ├─ header.php   # Bootstrap + navbar
         └─ footer.php
```

## Prerequisites

* Docker & Docker Compose
* (Optional) Existing SQL Server reachable from the app

## Quick Start (local, with bundled SQL Server)

1. **Clone & configure**

```bash
cp .env.example .env
# adjust if needed; defaults are fine for local compose
```

2. **Prepare persistent data directory (Linux recommended)**

```bash
mkdir -p ./data
# SQL Server container runs as UID 10001 — grant access:
sudo chown -R 10001:0 ./data && sudo chmod -R 770 ./data
```

3. **Run**

```bash
docker compose up --build
```

4. **Open the app**
   `http://localhost:8080`

* Home: `/`
* Scan/Book: `/scan`
* Booking list: `/list`
* Barcode query: `/query`
* Health: `/healthz`

> On first boot, Compose starts MSSQL Express and the app waits until the DB is healthy.

## Using an External SQL Server (instead of the bundled one)

* Set in `.env` or `docker-compose.yml`:

    * `DB_HOST=host.docker.internal` (macOS/Windows Docker Desktop)
      On Linux add to `app:` service:

      ```yaml
      extra_hosts:
        - "host.docker.internal:host-gateway"
      ```
    * `DB_DATABASE=<YourDb>`
    * `DB_USERNAME=<user>`
    * `DB_PASSWORD=<pass>`
* Optionally remove the `mssql` service from `docker-compose.yml`.

## Database Schema & Data

* The app writes to **`dbo.BEWEGUNG`**. If you need to create it locally, use your schema script (e.g. `createTable.sql`).
* Insert sample data (from the container):

```bash
# copy your sample.sql into the container (or mount it)
docker compose exec -T mssql /opt/mssql-tools18/bin/sqlcmd \
  -C -S localhost -U sa -P 'YourStrong!Passw0rd' -d Priebslogistik -i /tmp/sample.sql
```

### NCHAR padding (important!)

Many columns are `nchar(...)`. This means values are space-padded on the right.
The UI uses `RTRIM(...)` in `SELECT`/filters so that `WHERE STANDORT = 'XYZ'` actually matches.

## Configuration

Environment variables (via `.env` or set directly on the `app` service):

| Var           | Default               | Notes                                                            |
| ------------- | --------------------- | ---------------------------------------------------------------- |
| `DB_HOST`     | `mssql`               | Use `mssql` for the bundled service, or your server hostname/IP. |
| `DB_PORT`     | `1433`                | MSSQL TCP port.                                                  |
| `DB_DATABASE` | `Priebslogistik`      | Target database name.                                            |
| `DB_USERNAME` | `sa`                  | DB user.                                                         |
| `DB_PASSWORD` | `YourStrong!Passw0rd` | DB password.                                                     |
| `SA_PASSWORD` | `YourStrong!Passw0rd` | Only used by the bundled SQL Server.                             |
| `APP_ENV`     | `prod`                | Optional.                                                        |

### TLS / Encryption

The default DSN in `src/db.php` is set to **`Encrypt=No`** (handy for local).
For production, consider:

* `Encrypt=Yes;TrustServerCertificate=Yes` (encrypted, no CA)
* or a proper CA-signed certificate.

## Routing & URLs

Apache serves from `public/`. Pretty URLs are handled by `.htaccess`.

* GET `/` → `views/home.php`
* GET `/scan` → `views/scan_form.php`
* POST `/scan` → `scan_submit.php` (writes to `dbo.BEWEGUNG`)
* GET `/list` → `list.php` (filters: `standort`, `von`, `bis`, `limit`, `barcode`)
* GET `/query` → `views/query_form.php`
* POST `/query` → `query.php` (shows last 200 events for a barcode)
* GET `/healthz` → JSON `{ ok, time, db: { connected, version, database } }`

## Production Notes

* Put the app behind your reverse proxy (nginx/Traefik) and enable TLS.
* Add authentication/authorization (e.g., Basic Auth, OIDC, or your SSO).
* Harden DB auth (least privileges, not `sa`).
* Use proper TLS to SQL Server and rotate credentials.

## Troubleshooting

* **404 on `/scan`, `/list`, ...**
  `mod_rewrite` / `AllowOverride All` must be enabled. Dockerfile already sets:

    * `a2enmod rewrite`
    * an Apache conf that allows overrides on `/var/www/public`

* **`SQLSTATE[HYT00] Login timeout expired`**
  The app can’t reach SQL Server. Check:

    * `DB_HOST` (use `mssql` when using the bundled service; use `host.docker.internal` for external DB on host)
    * service health: `docker compose logs mssql`
    * app sees env: `docker compose exec app php -r 'var_dump(getenv("DB_HOST"));'`
    * With external DB, firewalls/ports.

* **MSSQL container crashes at start (LSA/sqlpal errors)**
  Typically volume permission/architecture issues:

    * Ensure `./data` is owned/writable by **UID 10001** (`chown 10001:0 ./data`).
    * On ARM hosts: set `platform: linux/amd64` in `mssql` service.
    * As a last resort, initialize once with `user: "0:0"` on the MSSQL service (then remove it).

* **`declare(strict_types=1)` fatal error**
  `declare` must be the very first PHP statement in a file. Ensure no includes/whitespace before it.

* **Bootstrap forms look wider than cards**
  Don’t put `.row` directly on a bordered container. Use a wrapper:
  `<div class="form-section"><form class="row ...">...</form></div>` or add `.mx-0`/`gx-0`.

## Contributing

PRs and issues are welcome:

* Keep pages responsive and scanner-friendly (big inputs, keyboard focus on barcode).
* Follow the existing minimal router & view partials.
* Favor prepared statements & parameterized queries.

## License

MIT — see `LICENSE`
