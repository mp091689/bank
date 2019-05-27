## How to start project

Clone the project where you want:

```bash
$ git clone git@github.com:mp091689/test.git test
```

Go to dockerized folder and run docker-compose:

```bash
$ cd test/dockerized && cp .env.example .env && docker-compose up
```

Etnter into container

```bash
$ docker exec -it dockerized_php_1 su dev
```

 Install dependencies
```bash
$ composer install
```

Run migrations
```bash
$ ./yii migrate
```

Project is ready to be used.

Точка входа это консольная команда `./yii deposit`

Бизнес логика вынесена в отдельные сервисы: app\components, 
компоненты подключаются через DI.

Созданы миграции, для проверки структуры базы данных.