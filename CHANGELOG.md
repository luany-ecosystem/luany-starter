# Changelog — luany/starter

All notable changes to this project are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/).

---

## [1.0.1] — 2026-11-24

### Fixed
- The install message and the README say that `luany migrar` needs luany/cli 1.13+ (older CLIs: `luany migrate`, or `luany self-update`). Found in the first install from Packagist.

## [1.0.0] — 2026-11-24

### Added
- First release: the Luany skeleton (1.7) with accounts, in Portuguese.
  - Sign-up, login, logout, password reset by email, email verification, a panel for verified accounts.
  - Portuguese code: `Utilizador` (`luany/pt` `Modelo`), controllers with `vista()` / `validar()` / `redirecionar()`, `Esquema`/`Tabela` migrations, `Rota` routes, views with Portuguese directives, `data()` dates.
  - `APP_LOCALE=pt_AO` (→ `pt` → `en`), `LUANY_SYNTAX=pt`, `LUANY_DEV_LANGUAGE=pt`, `APP_TIMEZONE=Africa/Luanda`.
  - Every text (titles, buttons, messages, emails) in `lang/pt.php` and `lang/en.php`; views and controllers use `traduzir()`.
  - `LimitarTentativas`: 5 requests per minute per IP and route on every account form, configurable (`config/auth.php` → `throttle`, `AUTH_THROTTLE_MAX`, `AUTH_THROTTLE_SECONDS`).
  - Feature tests for every flow (fresh application per request, as under PHP-FPM).
