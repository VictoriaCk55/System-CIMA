# CIMA Sistema — Guía para Agentes

## Reglas de trabajo obligatorias

- Comunícate siempre en español.
- Actúa como un Senior Developer experto en Laravel.
- A partir de este punto, todo desarrollo nuevo debe seguir las convenciones oficiales de Laravel y evitar soluciones "custom" innecesarias.
- Nunca asumir comportamiento, estructura, reglas de negocio o intención del usuario. Ante cualquier duda, preguntar primero.
- Antes de modificar lógica sensible, revisar implementaciones existentes para mantener consistencia.
- Preferir:
  - Form Requests
  - Policies/Gates
  - Resource Controllers
  - Eloquent Relationships
  - Service classes solo cuando realmente aporten separación útil
  - Blade components si existe reutilización real
  - Convenciones de nombres Laravel
- Evitar duplicar lógica entre `create/edit`, controladores o vistas.
- Mantener compatibilidad con Bootstrap 5.3 y el stack actual del proyecto.

---

# Estructura del proyecto

- La aplicación Laravel vive completamente dentro de `application/`.
- Todos los comandos (`artisan`, `composer`, `npm`) deben ejecutarse desde `application/`.
- `application/vendor/` está versionado en Git.

---

# Comandos principales

Ejecutar siempre desde `application/`.

| Acción | Comando |
|--------|---------|
| Servidor local | `php artisan serve` |
| Build frontend | `npm run build` |
| Desarrollo frontend | `npm run dev` |
| Tests | `php artisan test` |
| PHPUnit directo | `vendor/bin/phpunit` |
| Migraciones | `php artisan migrate` |
| Seeders | `php artisan db:seed` |
| Lint Laravel Pint | `vendor/bin/pint` |
| Limpiar vistas | `php artisan view:clear` |

---

# Base de datos

Instancia PostgreSQL local:

| Host | Puerto | DB | Usuario | Password |
|------|------|------|------|----------|
| `127.0.0.1` | `5432` | `cima` | `postgres` | `CIMA12345` |

## Importante

- Laravel normalmente apunta a la instancia local.
- Verificar `.env` antes de ejecutar migraciones.
- No asumir configuraciones activas sin revisar.

---

# Autenticación y roles

El proyecto usa un sistema dual:

## 1. Columna `role` en `users`

Middleware actuales:

- `admin` → acepta `admin` y `tecnico`
- `tecnico` → acepta únicamente `tecnico`

## 2. Spatie Roles (`HasRoles`)

Uso típico:

```php
$user->hasRole('admin')
```

Roles existentes:

* `admin`
* `tecnico`
* `analista`

## Consideraciones importantes

* Ambos sistemas conviven.
* Antes de modificar permisos, revisar qué mecanismo usa cada flujo.
* `EnsureOneAdminRemains` evita eliminar el último administrador.

---

# PDFs (DomPDF)

Se usa:

* `barryvdh/laravel-dompdf` v3.1

Patrón estándar:

```php
$pdf = Pdf::loadView('proformas.pdf', $data);

$pdf->setPaper('letter', 'portrait');

return $pdf->download("proforma-{$proforma->codigo}.pdf");
```

o:

```php
return $pdf->stream();
```

## Problemas conocidos de DomPDF

* `rowspan` + `table-layout: fixed` + alturas fijas pueden romper layout.
* Usar:

```css
overflow: hidden;
```

en `td, th` cuando exista solapamiento.

* `width: fit-content` y `inline-block` no son confiables.
* Preferir:

  * `text-align: center` en el padre
  * wrapper con `display: inline-block`

## Imágenes en PDF

Usar:

```php
public_path()
```

No usar:

* `asset()`
* `storage_path()`

## Vistas PDF existentes

* `proformas/pdf.blade.php`
* `proformas/resultados-pdf.blade.php`
* `proformas/informe-resultados-pdf.blade.php`
* `informes/pdf/informe.blade.php`
* `proformas/cadena_custodia.blade.php`

---

# Soft Deletes

Entidades principales usan `SoftDeletes`:

* clientes
* parametros
* proformas
* informes
* users

Cada módulo tiene:

* `trash`
* `restore`
* `forceDelete`

Las rutas `trash` están fuera de los `resource controllers`.

---

# Modelos importantes

## Proforma

Tabla:

```php
proformas
```

Notas:

* `tipo_documento` casteado como `array`
* Campos agregados:

  * `codigo_cliente`
  * `numero_recepcion`
  * `hora_recepcion`

Workflow:

```text
BORRADOR → ENVIADA → APROBADA → RECHAZADA → FINALIZADA
```

## CadenaResultado

Tabla:

```php
cadena_resultados
```

Notas:

* Resultados por parámetro
* `proforma_id` nullable
* Tiene columna `vb`

IMPORTANTE:

Estos campos salen de esta tabla y NO de `parametros`:

* `limite_cuantificacion`
* `unidad`
* `limite`

## User

Fillable:

```php
name
email
password
role
```

Usa:

* columna `role`
* Spatie Roles

---

# Rutas

Todas las rutas están en:

```php
routes/web.php
```

No existen API routes.

## Orden importante

Estas rutas deben definirse antes de `Route::resource(...)`:

* `/clientes/buscar`
* `/parametros/buscar`

Si no, falla el route model binding.

## Rutas relevantes

| Acción          | Ruta                                        |
| --------------- | ------------------------------------------- |
| PDF proforma    | `GET /proformas/{proforma}/pdf`             |
| Resultados      | `GET /proformas/{id}/resultados`            |
| PDF resultados  | `GET /proformas/{id}/resultados/pdf`        |
| Cadena custodia | `GET /proformas/{proforma}/cadena-custodia` |

## Resultados

* Guardado/carga mediante `fetch()`
* No usa `localStorage`

## Fallback

Existe fallback route que redirige al home con mensaje de error.

---

# Frontend

Stack actual:

* Bootstrap 5.3
* Select2 4.1
* jQuery 3.7
* Font Awesome
* Vite 7
* Tailwind v4

## Select2

Endpoints AJAX:

* `clientes.buscar`
* `parametros.buscar`

## Blade

Uso estándar:

```php
@push('styles')
@push('scripts')
```

Definidos en:

```php
layouts/app.blade.php
```

## Persistencia multi-checkbox

Create:

```php
old('field', [])
```

Edit:

```php
old('field', $model->field ?? [])
```

Mantener este patrón.

---

# Configuración

## Timezone

```php
America/La_Paz
```

## Locale

```php
es
```

## Consideraciones

* No existe `.env.example`
* No hay CI/CD
* No hay pre-commit hooks
* No existe Makefile

---

# Convenciones para desarrollo nuevo

## Controladores

* Mantener controladores delgados.
* Validaciones en Form Requests.
* Evitar lógica pesada en controllers.

## Consultas

* Preferir Eloquent antes que Query Builder manual.
* Evitar N+1 (`with()` cuando corresponda).

## Validación

* Centralizar reglas en Form Requests.
* Mensajes en español si el módulo ya sigue ese patrón.

## Blade

* Mantener consistencia visual con Bootstrap.
* Reutilizar parciales/componentes antes de duplicar HTML.

## JavaScript

* Antes de agregar nuevas librerías, verificar si Bootstrap/jQuery ya resuelven el problema.
* No mezclar múltiples patrones frontend innecesariamente.

## Migraciones

* Nunca modificar migraciones antiguas ya ejecutadas.
* Crear nuevas migraciones incrementales.

## Cambios sensibles

Antes de modificar:

* autenticación
* roles
* PDFs
* resultados
* workflow de proformas

revisar primero todo el flujo relacionado.

---

# Verificaciones recomendadas antes de entregar cambios

Ejecutar según corresponda:

```bash
vendor/bin/pint
php artisan test
npm run build
```

Si se modificaron vistas Blade:

```bash
php artisan view:clear
```
