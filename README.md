**Run project:**
---
php bin/console server:start
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

### ***Routes:***
***
/login - login page.
***
/new - create new user page.
***
/personal - personal area page.