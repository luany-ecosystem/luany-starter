# Changelog — luany/starter

All notable changes to this project are documented here.
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
Versioning follows [Semantic Versioning](https://semver.org/).

---

## [Unreleased]

### Added
- First release: the Luany skeleton (1.7) with accounts, in Portuguese.
  - Sign-up, login, logout, password reset by email, email verification, a panel for verified accounts.
  - Portuguese code: `Utilizador` (`luany/pt` `Modelo`), controllers with `vista()` / `validar()` / `redirecionar()`, `Esquema`/`Tabela` migrations, `Rota` routes, views with Portuguese directives, `data()` dates.
  - `APP_LOCALE=pt_AO` (→ `pt` → `en`), `LUANY_SYNTAX=pt`, `LUANY_DEV_LANGUAGE=pt`, `APP_TIMEZONE=Africa/Luanda`.
  - `LimitarTentativas`: 5 requests per minute per IP and route on every account form.
  - Feature tests for every flow (fresh application per request, as under PHP-FPM).
