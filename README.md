# Sistema CIMA

## 🛠️ Tecnologías y Versiones

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **PHP** | 8.2 | Backend |
| **Laravel** | 12.x | Framework |
| **PostgreSQL** | 15 | Base de datos |
| **Nginx** | 1.24 | Servidor web |
| **Docker** | 28+ | Contenedores |
| **Docker Compose** | 2.29+ | Orquestación |
| **Bootstrap** | 5.3 | Frontend CSS |
| **jQuery** | 3.7 | JavaScript |
| **Select2** | 4.1 | Buscadores |
| **DOMPDF** | 2.0 | Generación PDF |

## Extensiones PHP requeridas
- `pdo_pgsql`
- `bcmath`
- `ctype`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `tokenizer`
- `xml`

---

## 📁 Estructura del Proyecto

```
cima-sistema/
│
├── application/                    # Código fuente de Laravel
│   ├── app/
│   │   ├── Console/Commands/       # Comandos Artisan
│   │   ├── Http/Controllers/       # Controladores
│   │   ├── Http/Middleware/        # Middlewares
│   │   ├── Models/                 # Modelos de datos
│   │   └── Traits/                 # Traits reutilizables
│   ├── database/
│   │   ├── migrations/             # Migraciones (15 archivos)
│   │   └── seeders/                # Seeders (6 archivos)
│   ├── resources/views/            # Vistas Blade
│   ├── routes/web.php              # Todas las rutas
│   └── .env                        # Variables de entorno
│
├── docker/
│   ├── nginx/nginx.conf            # Configuración Nginx
│   └── php/Dockerfile              # Dockerfile PHP 8.2
│
├── docker-compose.yml              # Orquestación de contenedores
└── .gitignore                      # Archivos excluidos
```

---

## 🚀 Instalación Rápida

```bash
# 1. Clonar repositorio
git clone https://github.com/B3T0-o-o/cima-sistema.git
cd cima-sistema

# 2. Levantar contenedores
docker-compose up -d --build

# 3. Instalar dependencias
docker-compose exec php composer install

# 4. Ejecutar migraciones
docker-compose exec php php artisan migrate --seed

# 5. ¡Listo! Abrir http://localhost
```

---

## ⚡ Comandos básicos

```bash
# Levantar sistema
docker-compose up -d

# Detener sistema
docker-compose down

# Limpiar caché (si algo no funciona)
docker-compose exec php php artisan optimize:clear

# Ver logs
docker-compose exec php tail -f storage/logs/laravel.log

# Verificar administradores
docker-compose exec php php artisan admins:check

# Restaurar último admin eliminado
docker-compose exec php php artisan admins:restore-last
```

---

## 🔐 Acceso

| Rol | Email | Contraseña |
|-----|-------|-------------|
| **Admin** | admin@cima.edu.bo | CIMA-2026 |
| **Técnico** | tecnico@cima.edu.bo | tecnico123 |

**URL:** http://localhost

---

## 📦 Módulos

| Módulo | Funcionalidades |
|--------|-----------------|
| Clientes | CRUD + papelera |
| Parámetros | CRUD + papelera |
| Proformas | CRUD + PDF + estados |
| Informes | CRUD + archivos + PDF |
| Financiero | Control de pagos |
| Usuarios | Activar/desactivar |

---

## 👥 Roles

| Acción | Admin | Técnico |
|--------|-------|---------|
| Ver | ✅ | ✅ |
| Crear/Editar/Eliminar | ✅ | ❌ |
| Generar PDF | ✅ | ✅ |

---

## ⚠️ Notas importantes

- Los registros "eliminados" van a la papelera (soft delete)
- No se puede eliminar al último administrador
- Usuarios desactivados no pueden iniciar sesión

---

