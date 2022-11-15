

## Чат

#### PHP 8.0, composer, Laravel, mysql, node.js, socket.io

login: `test@test.ru`  
password: `password`

[Demo](https://site4.ksv-test.ru/).

### Порядок установки

---

#### Если вы используете Docker

#### Необходимо наличие Node.js
После клонирования репозитория выполните следующие команды:
- `docker compose up`
- `node socket_server.js`

---

#### Если вы НЕ используете Docker

#### Необходимая версия PHP 8.0
- После клонирования репозитория, в корневой папке переименуйте файл `.env.example` в `.env`
- Измените настройки подключения к БД в `.env`
- Создать БД `chat_vue`

Выполните следующие команды:
- `composer install`
- `php artisan key:generate`
- `php artisan storage:link`
- `php artisan jwt:secret`
- `php artisan migrate --seed`
- `npm install`

#### Запуск сокет сервера
- `node socket_server.js`

