
# Next Pay Day Test

## Setup Project

Follow steps to get the project started. Clone the project and follow steps.

### Setup Laravel
- Install Composer and Npm
```bash
 composer install
 npm install
```
- Create .env file.
- Setup databse and cumtom mail service 
- Generate key 

```bash
  php artisan generate:key
```

- Migrate database

```bash
  php artisan migrate
```

- Get User seed data (optonal)
```bash
  php artisan db:seed
```

- Run two servers

```bash
   php artisan server
   npm run dev
```


