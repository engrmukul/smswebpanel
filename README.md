## **Fast SMS Portal**


1. Clone project form git
   
    `git clone https://gitlab.com/smsapps/fastsmsv2.git`

2. Create a Database (Mysql, Postgresql)
3. Enter to System Directory
   
   `cd system`
3. Rename environment file\
   
   `cp .env.example .env`
4. update .env file with created database name
5. run migration

    `php artisan migrate --seed`
   
6. run development server

    `php artisan serve 0.0.0.0:8000`
