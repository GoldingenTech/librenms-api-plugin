# Changelog

## [1.0.3] — 2026-05-19

### Fixed

- **Routes now accept `X-Auth-Token` (the primary LibreNMS API auth mechanism).**  
  Two root causes fixed together:

  1. **Route registration order** — `ApiPluginProvider::loadRoutesFrom()` was called in
     `boot()`, which fires after LibreNMS's own route service provider has already
     registered its catch-all web route (`Route::any('/{path?}', ...)->middleware('auth')`).
     Since Laravel uses first-match routing, the catch-all won every request to
     `/plugins/...`, returning a 302 redirect to `/login` before the plugin routes were
     ever evaluated.  
     Fix: moved `loadRoutesFrom()` to `register()`. All `register()` calls precede all
     `boot()` calls in Laravel's bootstrap lifecycle, so plugin routes are now in the
     router before the catch-all is registered.

  2. **Explicit middleware** — the route group used `['middleware' => ['api']]`, relying on
     the named `api` group to contain `EnforceJson` + `auth:token`. While this is true in
     LibreNMS 26.5.0+, the dependency is fragile. Routes are now explicitly wrapped with
     `[\App\Http\Middleware\EnforceJson::class, 'auth:token']`:  
     — `EnforceJson` sets `Accept: application/json`, ensuring auth failures return HTTP
       401 JSON rather than a 302 HTML redirect.  
     — `auth:token` authenticates via `ApiTokenGuard`, which reads the `X-Auth-Token`
       request header.

## [1.0.2] — 2026-05-18

### Fixed (7 bugs preventing operation under LibreNMS 26.x / PHP 8.3)

1. `MenuHook.php`: PHP 8.3 strict typed property (`public $view` → `public string $view`)
2. `APIController.php`: missing `use Illuminate\Http\Request` import
3. `APIController.php`: missing closing `}` for class (parse error)
4. `APIController.php`: all 5 `dbFetchRows()` calls replaced with `DB::select()` (legacy procedural function unavailable in controller context in LibreNMS 26.x)
5. `APIController.php`: method signatures use short `Request` type (using fix 2's import)
6. `routes/api.php` route 1: action references wrong method name (`get_miner_port_by_mac` → `get_device_port_by_mac`)
7. `routes/api.php` route 4: URL typo `/plugims/` → `/plugins/`
