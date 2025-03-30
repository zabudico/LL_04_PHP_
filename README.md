# LL_04_PHP

Лабораторная работа №4. Обработка и валидация форм

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

   Frontend (HTML/JS)          Backend (PHP)
┌───────────────────┐       ┌───────────────────┐
│ Форма создания    │       │ TaskController    │
│ задачи            ├──────►│ → validate()      │
└───────────────────┘ POST  │ → create()        │
                            │ → save to JSON    │
┌───────────────────┐       └─────────┬─────────┘
│ Список задач      │◄─────── Task::all()
└───────────────────┘ GET   ┌─────────┴─────────┐
                            │ Task Model        │
                            │ → read/write JSON │
                            └───────────────────┘
Особенности реализации
Stateless-хранилище: использование файла для хранения данных (.json)

Автономный роутинг: Не требует mod_rewrite

Гибкая пагинация: Динамический расчет через array_slice()

Кросс-платформенность: Работает на любом хостинге с PHP

Примечание: Архитектура оптимизирована для простоты поддержки и минимальных требований к окружению.


Контрольные вопросы
1. HTTP-методы для форм
GET - получение данных (поиск, пагинация)

POST - изменение данных (создание/удаление)

2. Валидация vs Фильтрация
   
Валидация	Фильтрация
Проверка формата данных	Очистка данных
Пример: проверка email	Пример: удаление тегов


3. PHP-функции фильтрации
   
trim() - обрезка пробелов

htmlspecialchars() - экранирование HTML

filter_var($email, FILTER_VALIDATE_EMAIL) - валидация email

json_decode() - парсинг JSON

