

## Чат

### Требоваиния

- php 7.3
- nodejs
- mysql/mariadb

### Необходимые действия для запуска

Создать БД и настроить .env  
Дообавить в .env `MIX_APP_URL=${APP_URL}`  
Соответственно `APP_URL` изменить на актуальный

Выполните следующие команды:
- `composer update`
- `php artisan key:generate`
- `php artisan storage:link`
- `php artisan jwt:secret`
- `php artisan migrate --seed`
- `npm install`

### Запуск сокет сервера
- `node socket_server.js`

