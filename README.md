# Тестовое задание

Рефакторинг контроллера для работы с [Dadata API](https://dadata.ru/api/).

## Что было сделано

- Исправлен нерабочий код, добавлены namespace и PSR-4 автозагрузка
- Внедрены `DadataInterface` и `HttpClientInterface` — зависимости от абстракций, а не реализаций
- Убраны зависимости от Laravel-хелперов (`config()`)
- API-ключ вынесен в `.env`; при отсутствии ключа приложение падает явно при старте
- Инициализация зависимостей вынесена в `bootstrap.php`
- Добавлена валидация входящего параметра `query`
- Добавлена обработка ошибок HTTP-клиента: `HttpException` перехватывается в `index.php` и возвращается как JSON с кодом 502
- Поднят Docker-контейнер с PHP 8.4
- Исправлена передача `locations: null` в тело запроса — ключ добавляется только если значение передано
- Добавлена проверка результата `json_encode` и `json_decode` с явными ошибками
- Методы поиска теперь возвращают `array` вместо `?array` — пустой результат это `[]`, не `null`
- Интеграционные тесты отделены от юнит-тестов (`tests/Integration/`)
- Написаны юнит-тесты (13 тестов, 40 assertions) + 2 интеграционных теста

## Структура проекта

```
src/
  Contracts/
    DadataInterface.php
    HttpClientInterface.php
  Exceptions/
    HttpException.php
  Http/
    Controllers/
      DadataController.php
    CurlHttpClient.php
  Services/
    Dadata.php
public/
  index.php
tests/
  Unit/
    DadataTest.php
    DadataControllerTest.php
  Integration/
    CurlHttpClientTest.php
bootstrap.php
```

## CI

GitHub Actions запускает юнит-тесты автоматически при пуше в `main`/`master` и на каждый pull request.

Интеграционные тесты в CI не запускаются — они требуют внешнюю сеть.

## Запуск

### 1. Настройка окружения

```bash
cp .env.example .env
```

Вписать реальный API-ключ Dadata в `.env`:

```
DADATA_API_KEY=your_api_key_here
```

### 2. Запуск через Docker

```bash
docker compose up app
```

Сервер будет доступен на `http://localhost:8080`.

## API

| Метод | Путь | Параметры | Описание |
|---|---|---|---|
| GET / POST | `/inn` | `query` — ИНН | Данные о компании |
| GET / POST | `/bank` | `query` — БИК | Данные о банке |
| GET / POST | `/country` | `query` — название | Поиск страны |
| GET / POST | `/address` | `query` — адрес, `locations` — фильтр (только POST) | Поиск адреса |

### Примеры запросов

```bash
# Компания по ИНН
curl "http://localhost:8080/inn?query=7707083893"

# Банк по БИК
curl "http://localhost:8080/bank?query=044525225"

# Поиск страны
curl "http://localhost:8080/country?query=Росс"

# Поиск адреса (GET)
curl "http://localhost:8080/address?query=Москва Ленина"

# Поиск адреса с фильтром по стране (POST)
curl -X POST "http://localhost:8080/address" \
  -H "Content-Type: application/json" \
  -d '{"query": "Ленина", "locations": [{"country_iso_code": "RU"}]}'
```

### Коды ответа

| Код | Причина |
|---|---|
| 200 | Успех |
| 400 | Не передан параметр `query` |
| 404 | Неизвестный маршрут |
| 502 | Ошибка при обращении к Dadata API |

## Тесты

### Юнит-тесты (без сети)

```bash
# Через Docker
docker compose run --rm test

# Локально
composer install
./vendor/bin/phpunit --testsuite Unit --testdox
```

### Интеграционные тесты (требуют сеть)

```bash
./vendor/bin/phpunit --testsuite Integration --testdox
```

### Все тесты

```bash
./vendor/bin/phpunit --testdox
```
