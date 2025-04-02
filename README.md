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
 
    Пример работы приложения (просмотр, создание, удаление задач и т.д.)
    ![image](https://github.com/user-attachments/assets/959865b4-6f23-4174-8904-6a40f830c3a8)

    ![image](https://github.com/user-attachments/assets/0ca0d5f0-9b4e-4075-a419-68dd51f27560)

    ![image](https://github.com/user-attachments/assets/3c60582c-608c-4bb5-9b68-972a47b4d308)

    ![image](https://github.com/user-attachments/assets/368c85f0-6f90-4881-8eaf-2eb1144b8cb9)

    ![image](https://github.com/user-attachments/assets/b8495ef6-1122-4927-9c3a-7af4e7c1d019)

    ![image](https://github.com/user-attachments/assets/2f6f1aa0-6ecf-4e34-a021-3c91532d93fb)

    ![image](https://github.com/user-attachments/assets/d012b59e-03ae-447f-b9cb-6654e7f6eee9)

    ![image](https://github.com/user-attachments/assets/124076c9-9ac8-4ea1-8949-ad8d1ad58e2c)

    ![image](https://github.com/user-attachments/assets/0c3cf34b-711d-4374-98ca-5eb0705a4000)

    ![image](https://github.com/user-attachments/assets/ad190110-e8bd-4e7a-9746-44600b3df956)

    ![image](https://github.com/user-attachments/assets/cc5ef503-b4d2-4b0e-ae3e-457998d8cd8a)


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
   
| **Критерий**       | **Валидация**                          | **Фильтрация**                        |
|---------------------|----------------------------------------|----------------------------------------|
| **Цель**           | Проверить корректность данных          | Очистить/преобразовать данные          |
| **Результат**      | Ответ: данные валидны (да/нет)         | Модифицированные данные                |
| **Примеры**        | Проверка email, длины пароля           | Удаление тегов, обрезка пробелов       |
| **Когда применять**| Перед сохранением данных               | Перед обработкой/отображением данных   |


3. PHP-функции фильтрации
   
## 🛡️ Фильтрация (очистка/преобразование данных)
| Функция                     | Назначение                                                                 |
|-----------------------------|----------------------------------------------------------------------------|
| `trim($str)`                | Удаляет пробелы и управляющие символы с обоих концов строки                |
| `htmlspecialchars($str)`    | Экранирует HTML-сущности (`< → &lt;`, `> → &gt;` и т.д.)                   |
| `strip_tags($str)`          | Удаляет HTML/PHP-теги (`<script>`, `<style>` и др.)                        |
| `addslashes($str)`          | Экранирует кавычки и спецсимволы (`' → \'`)                                |
| `stripslashes($str)`        | Убирает экранирование (`\' → '`)                                           |
| `json_decode($json)`        | Преобразует JSON-строку в объект/массив (требует проверки ошибок!)         |

---

## ✅ Валидация (проверка корректности)
| Функция/Константа           | Назначение                                                                 |
|-----------------------------|----------------------------------------------------------------------------|
| `filter_var($email, FILTER_VALIDATE_EMAIL)` | Проверяет корректность формата email                                      |
| `filter_var($int, FILTER_VALIDATE_INT)`     | Проверяет, является ли значение целым числом                             |

---

# Список использованных источников

## Основные источники
1. **Официальная документация PHP**  
   https://www.php.net/manual/ru/  
   *Использована для работы с функциями валидации, фильтрации и JSON*

2. **OWASP Security Guidelines**  
   https://owasp.org/www-project-top-ten/  
   *Рекомендации по защите от XSS и CSRF-атак*

3. **MVC Architecture Best Practices**  
   https://www.tutorialspoint.com/design_pattern/mvc_pattern.htm  
   *Принципы проектирования слоистой архитектуры*

4. **JSON Schema Validation**  
   https://json-schema.org/  
   *Идеи для реализации валидации структуры задач*

5. **MDN Web Docs (HTTP Methods)**  
   https://developer.mozilla.org/ru/docs/Web/HTTP/Methods  
   *Руководство по работе с HTTP-методами*

---

### Дополнительные важные аспекты

#### 1. Безопасность
- **CSRF Protection**  
  Все формы защищены одноразовыми токенами
- **XSS Prevention**  
  Данные экранируются через `htmlspecialchars()` перед выводом
- **Input Sanitization**  
  Фильтрация GET-параметров перед использованием в SQL-подобных операциях

#### 2. Производительность
- **Оптимизация работы с JSON**  
  Кэширование прочитанных данных в памяти при частых запросах
- **Ленивая загрузка**  
  Файл хранилища создается только при первом обращении

#### 3. Расширяемость
- **Гибкая архитектура**  
  Возможность замены JSON-хранилища на реляционную БД
- **Модульная валидация**  
  Легко добавлять новые правила проверки данных

#### 4. Тестирование
- **Примеры данных**  
  Файл `tasks.example.json` для быстрого старта
- **Валидация окружения**  
  Автоматическая проверка прав доступа к хранилищу

#### 5. Документация
- **PHPDoc-аннотации**  
  Подробное описание всех методов и классов
- **Интерактивные примеры**  
  Готовые сниппеты кода в README

#### 6. Лицензия
- **MIT License**  
  Позволяет свободное использование и модификацию кода

---

#### Рекомендации по развитию
1. Реализовать **автоматическое тестирование** с PHPUnit
2. Добавить **авторизацию пользователей**
3. Внедрить **историю изменений** задач
4. Реализовать **REST API** для интеграции с внешними системами
5. Добавить **экспорт задач** в PDF/CSV форматах
