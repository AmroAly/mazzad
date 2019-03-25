## Mazzad App

# Built with Laravel + Vuejs + Tailwindcss

### Up and running


```
    git clone git@github.com:AmroAly/mazzad.git
```

```
    cd mazzad
```

Then install the dependencies

```
    composer install
```

```
    cp .env.example .env
```
Go to your mysql client and create a database

Then let's modify the `.env` file to add the database credentials

```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=mazzad
    DB_USERNAME=root
    DB_PASSWORD=12345

```

And let's run the migration to the database tables

```
    php artisan migrate --seed
```

Last but not least we will generate the personal access keys used by Laravel passport

```
    php artisan passport:install
```

Then let's generate the App key

```
    php artisan key:generate 
```

I've crreated admin user using the laravel facrtory so you can signin as an Admin using the following email and password

```
    email: admin@mazzad.com
    password: secret
```

And Now let's run the server on `localhost:8000`

```
    php artisan serve

```