# TalesRunner Shop — Laravel Edition

A Laravel rewrite of the legacy TalesRunner Shop portal. The application is **100% MySQL/MariaDB** and keeps the existing TalesRunner game-table names so the game server does not need to be redesigned.

## Stack

- Laravel 12 (`laravel/framework ^12.0`)
- PHP 8.2+
- MySQL / MariaDB
- Two logical database connections:
  - `game` → TalesRunner game schema
  - `web` → website/shop schema
- Blade + the original Materialize assets

Laravel 12 was selected deliberately for broad PHP 8.2+ hosting compatibility. You can upgrade the framework later after the portal is verified against your emulator.

## What was migrated

- Login / logout using `UserInfoFromPublisher`
- Account registration
- Password change
- Dashboard / character / TR / Cash / Points / online player information
- Item Shop and purchase flow
- Gift delivery through `tblGift`
- VIP levels and rewards
- Top-up page, history, and TMPAY callback compatibility
- Download page
- Alchemist recipe browser
- Admin shop menus and shop items
- Existing images, CSS, JS, fonts, character assets, and `items.json`
- Web database schema as a Laravel migration
- Existing SQL schema retained under `database/sql/`

The old disabled forum module was not recreated because the supplied package did not contain a complete active forum implementation. Legacy MEETA login is present but **disabled by default** because the original endpoint trusts an unsigned Base64 identity value.

## Security / compatibility changes

The game password column remains legacy `MD5` because the TalesRunner server/client expects that format. Laravel's normal password hashing must **not** be substituted unless the game server authentication implementation is changed too.

The web application itself now uses:

- Laravel sessions and session regeneration
- CSRF protection
- request validation
- route throttling on sensitive endpoints
- Query Builder / Eloquent parameter binding
- admin middleware
- authenticated middleware
- no `$_GET`, `$_POST`, or `$_SESSION` access in application code
- no ODBC / SQL Server dependency

## Installation

1. Upload the project outside the public web root if possible.
2. Point the web server document root to the project's `public/` directory.
3. Install dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

4. Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure both MySQL schemas in `.env`:

```env
GAME_DB_HOST=127.0.0.1
GAME_DB_PORT=3306
GAME_DB_DATABASE=talesrunner_game
GAME_DB_USERNAME=root
GAME_DB_PASSWORD=your_password

WEB_DB_HOST=127.0.0.1
WEB_DB_PORT=3306
WEB_DB_DATABASE=talesrunner_web
WEB_DB_USERNAME=root
WEB_DB_PASSWORD=your_password
```

6. If the web database already exists, keep it. The migration uses `hasTable()` checks. To create missing web tables / VIP defaults:

```bash
php artisan migrate --force
```

Alternatively, the legacy schema is still available at:

```text
database/sql/legacy_web_schema.sql
```

7. Make sure important game tables are InnoDB. A helper SQL file is included:

```text
database/sql/ensure_game_innodb.sql
```

8. Check the databases:

```bash
php artisan talesrunner:health
```

9. Production optimization:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Atomic GAME + WEB transactions

For registration, shop purchases, VIP rewards, and successful top-ups, the safest configuration is to keep the GAME and WEB schemas on the **same MySQL server using the same MySQL account**:

```env
TR_SHARED_DB_TRANSACTIONS=true
```

In this mode Laravel executes critical operations through one MySQL connection and references the web schema by its fully qualified name. InnoDB can therefore commit or roll back changes to both schemas in the same transaction.

If the two schemas are on separate MySQL servers, set:

```env
TR_SHARED_DB_TRANSACTIONS=false
```

The application can still connect to both, but MySQL cannot provide a normal single atomic transaction across two independent servers without an external distributed transaction design.

## TMPAY

TMPAY is disabled by default:

```env
TMPAY_ENABLED=false
```

Only enable it after confirming the endpoint, merchant ID, callback IP, and callback contract used by your payment provider.

## Legacy MEETA endpoint

Disabled by default:

```env
TR_ENABLE_MEETA_LEGACY=false
TR_MEETA_PASSWORD=
```

Do not enable it on a public production site unless the identity callback is upgraded to a signed / authenticated protocol.

## Admin access

Admin access is based on the existing web column:

```text
Web_User.fdAdmin = 1
```

After login, admins see the **Administration** button on the dashboard.

## Web server

Apache can use the provided `public/.htaccess`. For Nginx, use the standard Laravel configuration and route non-file requests to `public/index.php`.

## Verification performed on this package

- PHP syntax lint passed for all project PHP files under PHP 8.4.
- `composer.json` is valid JSON.
- `public/items.json` is valid JSON.
- No `odbc_*` calls remain.
- No raw `$_GET`, `$_POST`, or `$_SESSION` calls remain in app/routes code.

The framework dependencies are intentionally **not** bundled in the ZIP. Run `composer install` on the deployment machine.
