# Тестовое задание

Рефакторинг контроллера для работы с [Dadata API](https://dadata.ru/api/).

## Что было сделано

- Исправлен нерабочий код, добавлены namespace и PSR-4 автозагрузка
- Внедрены `DadataInterface` и `HttpClientInterface` — зависимости от абстракций, а не реализаций
- Убраны зависимости от Laravel-хелперов (`config()`)
- API-ключ вынесен в `.env`
- Инициализация зависимостей вынесена в `bootstrap.php`
- Добавлена валидация входящего параметра `query`
- Поднят Docker-контейнер с PHP 8.4
- Написаны юнит-тесты (12 тестов, 33 assertions)

## Структура проекта

```
src/
  Contracts/
    DadataInterface.php
    HttpClientInterface.php
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
bootstrap.php
```

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

| Метод | Путь | Параметр | Описание |
|---|---|---|---|
| GET | `/inn` | `query` — ИНН | Данные о компании |
| GET | `/bank` | `query` — БИК | Данные о банке |
| GET | `/country` | `query` — название | Поиск страны |
| GET | `/address` | `query` — адрес | Поиск адреса |

### Примеры запросов

```bash
# Компания по ИНН
curl "http://localhost:8080/inn?query=7707083893"

# Банк по БИК
curl "http://localhost:8080/bank?query=044525225"

# Поиск страны
curl "http://localhost:8080/country?query=Росс"

# Поиск адреса
curl "http://localhost:8080/address?query=Москва Ленина"
```

## Тесты

### Запуск через Docker

```bash
docker compose run --rm test
```

### Локальный запуск

```bash
composer install
./vendor/bin/phpunit --testdox
```
