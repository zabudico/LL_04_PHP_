# Лабораторная работа №4

Обработка и валидация форм

## Цель работы
Освоить основные принципы работы с HTML-формами в PHP, включая отправку данных на сервер и их обработку, включая валидацию данных.

Данная работа станет основой для дальнейшего изучения разработки веб-приложений. Дальнейшие лабораторные работы будут основываться на данной.

## Условие
Студенты должны выбрать тему проекта для лабораторной работы, которая будет развиваться на протяжении курса.

Система управления задачами;

Для данной лабораторной работы в качестве примера используется проект "Каталог рецептов".

## Инструкции по запуску проекта Task Manager

### Требования
- PHP 7.4 или новее
- Веб-сервер (Apache/Nginx) или встроенный PHP-сервер
- Права на запись в папку `data/`

### Установка
```bash
git clone https://github.com/your-repo/task-manager.git
cd task-manager
```

### Настройка веб-сервера

Вариант 1: Встроенный PHP-сервер (для разработки)
```bash
php -S localhost:8000 -t public/
```

Откройте в браузере: `http://localhost:8000`

Вариант 2: Apache
Настройте виртуальный хост, указав DocumentRoot на папку public/

Включите модуль mod_rewrite

Пример конфигурации:

``` apache

<VirtualHost *:80>
    ServerName task-manager.local
    DocumentRoot "/path/to/task-manager/public"
    <Directory "/path/to/task-manager/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
Вариант 3: Nginx
nginx
Copy
server {
    listen 80;
    server_name task-manager.local;
    root /path/to/task-manager/public;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}

```
Учтите, что при настройке в данном способе , нужно учитывать номер порта.

### Настройка прав доступа
``` bash
chmod -R 775 data/
chown -R www-data:www-data data/  # Для Apache
```

### Проверка установки
Откройте в браузере: http://task-manager.local

Попробуйте:

- Создать новую задачу

- Поискать задачи

- Удалить тестовую задачу

### Устранение неполадок
Ошибка записи в файл tasks.json
```bash

# Проверьте права:
ls -l data/tasks.json

# Должны быть права:
-rw-rw-r-- 1 www-data www-data
```

Страница не найдена (404)
Убедитесь, что включен mod_rewrite (для Apache)

Проверьте настройки маршрутизации в Nginx

Очистите кеш браузера

#### Ошибки CSRF

Убедитесь, что:

Включены сессии PHP

Браузер принимает cookies

Нет проблем с временной зоной PHP

### Тестовые данные
После первого запуска автоматически создается файл:
data/tasks.json .

### Демо-доступ
Для быстрого старта можно использовать предустановленные данные:

``` bash
cp data/tasks.example.json data/tasks.json
chmod 664 data/tasks.json
```

Теперь вы готовы к работе с Task Manager! 🚀

# Архитектура приложения Task Manager

Слоистая архитектура (MVC)

### 1. Presentation Layer (View)

- **Шаблоны** (`templates/`)
  - `layouts/main.php` - базовый layout
  - `tasks/*.php` - представления для задач
- **Фронтенд**
  - CSS: адаптивная верстка с Grid/Flexbox
  - JavaScript: базовая интерактивность

### 2. Application Layer (Controller)

- **Роутер** (`SimpleRouter.php`)
  - Маппинг URI → контроллеры
  - Обработка HTTP-методов
- **Контроллеры** (`TaskController`)
  - Обработка бизнес-логики
  - Взаимодействие с Model и View
  - Валидация через `TaskValidator`

### 3. Data Layer (Model)

- **Модель Task** (`Task.php`)
  - CRUD операции с JSON-хранилищем
  - Пагинация и фильтрация
  - Методы: `all()`, `find()`, `create()`, `delete()`
- **Хранилище**
  - `data/tasks.json` - JSON-база данных
  - Автоматическое создание файла при первом обращении

## Поток обработки запроса

sequenceDiagram
    participant Client as Браузер
    participant Router as Роутер
    participant Controller as TaskController
    participant Model as Task Model
    participant View as Шаблоны

    Client->>Router: HTTP Request
    Router->>Controller: Определение маршрута
    Controller->>Model: Запрос данных
    Model->>Controller: Возврат данных
    Controller->>View: Подготовка данных
    View->>Controller: Готовый HTML
    Controller->>Client: HTTP Response
    
Ключевые компоненты

Роутинг
Чистый PHP без .htaccess перезаписей

Ручной парсинг URI

Поддержка методов:

```php

if ($method === 'GET' && $uri === '/') { ... }

```

Валидация

4 уровня проверок:

CSRF-токен

Обязательные поля

Формат данных (мин. длина, допустимые категории)

Уникальность названия задачи

Безопасность
Защита от XSS:

```php

htmlspecialchars($task['title'], ENT_QUOTES)

```

CSRF-токены для форм:

```php

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

```

Фильтрация поискового запроса:

```php

$query = strtolower(trim($_GET['search']))

```

Взаимодействие компонентов

![image](https://github.com/user-attachments/assets/88181c3c-f295-4434-bfb9-ab1acd86c34a)

#### Особенности реализации

Stateless-хранилище: использование файла для хранения данных (.json)

Автономный роутинг: Не требует mod_rewrite

Гибкая пагинация: Динамический расчет через array_slice()

Кросс-платформенность: Работает на любом хостинге с PHP

Примечание: Архитектура оптимизирована для простоты поддержки и минимальных требований к окружению.

### Контрольные вопросы

1. Какие методы HTTP применяются для отправки данных формы? 
(HTTP-методы для форм)

GET - получение данных (поиск, пагинация)

POST - изменение данных (создание/удаление)

2.Что такое валидация данных, и чем она отличается от фильтрации?
 (Валидация vs Фильтрация)
   
Валидация	Фильтрация
Проверка формата данных	Очистка данных
Пример: проверка email	Пример: удаление тегов


3. PHP-функции фильтрации
   
trim() - обрезка пробелов

htmlspecialchars() - экранирование HTML

filter_var($email, FILTER_VALIDATE_EMAIL) - валидация email

json_decode() - парсинг JSON

