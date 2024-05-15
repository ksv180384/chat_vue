

## Чат

### PHP 8.0, composer, Laravel, mysql, node.js, socket.io, Vue.js

**Пользователь 1**  
login: `test@test.ru`  
password: `password`

**Пользователь 2**  
login: `test2@test.ru`  
password: `password`

Демо версии нет

### Порядок установки

---

#### Если вы используете Docker

#### Необходимо наличие Node.js
После клонирования репозитория выполните следующие команды:
- `docker compose up`

Чат доступен по адресу `http://localhost:8077`

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

