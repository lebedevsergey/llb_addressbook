# LLB Addressbook #

### What is it? ###
- A Web-app that implements a simple address book that can store list of entries:
- The Web-app uses `Symfony` 5 PHP framework and is based on `Symfony` demo-application
- `SQLite` database is used as a persistent storage which can be reconfigured to other databases types that are supported by `Symfony` framework
- Address book entries can store:
    - Firstname
    - Lastname
    - Street and number
    - Zip
    - City
    - Country
    - Phonenumber
    - Birthday
    - Email address
    - Picture (optional)

- Feel free to use it for personal and other purposes

### How to use? ### 
1. Install `PHP` dependencies - `composer install`
2. Create database: `./bin/console doctrine:database:create`
3. Run database migrations - `./bin/console doctrine:migrations:migrate` 
4. (optional) Fill database with test data: `./bin/console doctrine:fixtures:load`
5. For App testing purposes `Symfony` debug Web-server may be used `./symfony server:start`
6. There are also a few unit and integration tests

* (c) 2020 Sergey Lebedev, licensed under the Apache License, Version 2.0
* Feel free to contact me at:
  * https://habrahabr.ru/users/sunman/
  * http://stackoverflow.com/users/7135046/sergeylebedev
  * https://www.facebook.com/sergei.lebedev.5891
