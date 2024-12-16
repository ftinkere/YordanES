# Это проект Ëрдан, переписываемый с использованием архитектуры Event Sourcing
Понимаю ли я что это пушка по воробьям? Да. Я хочу использовать этот опыт длясвоего обучения и проверки своих навыков.

# Запуск и проба

1. `cp .env.example .env`

Сначала надо соответственно настроить переменные окружения. Example уже содержит данные подходящие для запуска с использованием предложенного тут же docker-compsose.yml

3. `./sail build`
4. `./sail up`
5. `./sail composer install`
6. `./sail artisan migrate --seed`

Сборка контейнеров. Запуск. Установка библиотек php. И запуск миграций и встроенных сидеров.
Приложение готово.

На данный момент там есть совсем ничего. 
`/login-by-admin`, который должен написать 'success', да `/` где после успешного логина будет написано имя admin и дата подтверждения почты.

`/login` и `/register` должны работать, там имеются примитивные формы для этого. Можно зарегистрировать пользователя и зайти под него
`/logout` тоже уже есть.
