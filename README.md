**Run project:**
---
php bin/console server:start
***
***Run consumer:***
***
bin/console rabbitmq:consumer create_gift_select -vvv
***
***Send transfers:***
***
php bin/console app:send-transfers
***

### ***DB***
***
DB Type - MySql
***
DB settings - .env line 28.
***
php bin/console doctrine:database:create
***
php bin/console do:mi:mi
***
php bin/console doctrine:fixtures:load
***

### ***Routes:***
***
/login - login page.
***
/new - create new user page.
***
/personal - personal area page.