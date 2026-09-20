# Laravel migration notes

The legacy flat-PHP application was reorganized into Laravel controllers, middleware, services, models, Blade views, routes, configuration, and migrations.

Key files:

- `app/Services/TalesDatabase.php` — GAME/WEB MySQL access and cross-schema transaction handling.
- `app/Services/TalesRunnerAuth.php` — game-account authentication compatible with the emulator's MD5 password column.
- `app/Services/ProfileService.php` — profile, character and VIP lookup.
- `app/Http/Middleware/EnsureTalesRunnerAuthenticated.php` — login gate.
- `app/Http/Middleware/EnsureTalesRunnerAdmin.php` — `Web_User.fdAdmin` gate.
- `app/Http/Controllers/ShopController.php` — transactional shop purchase and `tblGift` delivery.
- `app/Http/Controllers/VipController.php` — concurrency-safe VIP claim flow.
- `app/Http/Controllers/TopUpController.php` — top-up submission/history/callback.
- `database/migrations/2026_09_20_000001_create_talesrunner_web_tables.php` — web schema.

The original Smarty runtime and custom mysqli compatibility wrappers are no longer required.
