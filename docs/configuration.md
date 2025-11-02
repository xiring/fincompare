Configuration & Environment

Core environment variables (common Laravel)

- APP_NAME, APP_ENV, APP_KEY, APP_DEBUG, APP_URL
- LOG_CHANNEL
- DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- CACHE_DRIVER, QUEUE_CONNECTION, SESSION_DRIVER, SESSION_LIFETIME, FILESYSTEM_DISK
- BROADCAST_DRIVER, MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME

Packages and features

- Sanctum: SANCTUM_STATEFUL_DOMAINS (if using SPA on a different domain)
- Telescope: TELESCOPE_ENABLED=true (in non-production) and middleware as configured
- Horizon: configure queue connection via QUEUE_CONNECTION and horizon config if enabled
- Spatie Permission: see `config/permission.php` for table names and cache settings
- Spatie Activitylog: see `config/activitylog.php`

Example .env snippet

```
APP_NAME=FinCompare
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fincompare
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
FILESYSTEM_DISK=public

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="FinCompare"

SANCTUM_STATEFUL_DOMAINS=localhost:5173
```


