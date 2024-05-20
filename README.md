# Тестовое задание Laravel + Postgres + JWT + Docker + Swagger

## Требования

1. Создайте RESTful API для регистрации пользователей, входа и выхода из системы.
2. Реализуйте авторизацию пользователей с использованием JWT (JSON Web Tokens).
3. После входа пользователь должен иметь возможность создавать, читать, обновлять и удалять свои заметки через API.
4. Используйте паттерн Repository для работы с данными.
5. Предусмотрите механизм обработки ошибок и валидацию входных данных.
6. Интегрируйте Docker для локального развертывания и тестирования проекта.
7. Реализуйте простую документацию к вашему API.
8. Добавьте кэширование запросов для улучшения производительности.
9. Реализуйте механизм логирования событий в вашем приложении.
10. Добавьте тесты для API с использованием фреймворка PHPUnit.

## Использованные инструменты

- Laravel
- Docker
- PostgreSQL
- JWT (JSON Web Tokens)
- PHPUnit
- Swagger

## Установка и запуск

### Шаг 1: Клонирование репозитория

```sh
git clone https://github.com/balguzh1nov/testovoe_laravel
cd https://github.com/balguzh1nov/testovoe_laravel

### Шаг 2: Запуск Docker контейнеров
docker-compose up --build

--Выполнение миграций базы данных
docker-compose exec app php artisan migrate

--Запустите тесты с использованием PHPUnit:
docker-compose exec app ./vendor/bin/phpunit


```
![image](https://github.com/balguzh1nov/testovoe_laravel/assets/118799235/757dfb37-751b-475f-93ab-f02cadca6609)

![image](https://github.com/balguzh1nov/testovoe_laravel/assets/118799235/8e32b661-a381-463d-a334-80accefb3e05)

![image](https://github.com/balguzh1nov/testovoe_laravel/assets/118799235/317351fd-3e9d-4356-8a24-735c7f434413)

![image](https://github.com/balguzh1nov/testovoe_laravel/assets/118799235/ee68d680-e483-4af7-927a-45eb28a5b0b2)

![image](https://github.com/balguzh1nov/testovoe_laravel/assets/118799235/b8bfdd16-5b7e-473c-abdf-8d253472b882)
