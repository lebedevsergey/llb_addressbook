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

- Feel free to use it for personal and educational purposes

### How to use? ### 
Either run `run.sh` script - it will initialize everything and run the Addressbook with `Symfony` debug Web-server. 
The App will then be available at http://localhost:8000

Either do it manually:
1. Install `PHP` dependencies - `composer install`
2. Install `JavaScript` dependencies - `./bin/console doctrine:migrations:migrate` 
3. Create database and tables: `./bin/console doctrine:database:create && ./bin/console doctrine:schema:create`
4. (optional) Fill database with demo data: `./bin/console doctrine:fixtures:load`
5. For App testing purposes `Symfony` debug Web-server may be used: `./symfony server:start` - the Addressbook will be available at http://localhost:8000

There are also few integration tests: `./bin/phpunit`

* (c) 2020 Sergey Lebedev, licensed under the Apache License, Version 2.0
* Feel free to contact me at:
  * https://habrahabr.ru/users/sunman/
  * http://stackoverflow.com/users/7135046/sergeylebedev
  * https://www.facebook.com/sergei.lebedev.5891
