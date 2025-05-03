## Preparation

- Clone the repository
```bash
git clone https://github.com/w99910/livewire-test.git  
```

- Copy .env.example to .env and setup the environment variables as needed.
```bash
cp .env.example .env
```

> If you use Docker, please change the APP_PORT to any port you want, DB_HOST to database and REDIS_HOST to redis.


You can install the project without Docker or with Docker.


### Method 1: Installation without Docker
```bash
composer install && npm install && npm run build
```

- Migrate the database
```bash
php artisan migrate:fresh --seed
```

- Run the project
```bash
php artisan serve
```

- Open the application
```bash
http://localhost:8000/
```

### Method 2: Installation with Docker

- Start the Docker containers
```bash
docker compose up -d --build
```

- Open the application by using the port you set in the .env file. 
```bash
# If you set the port to 8040, you can open the application by using the following URL.
http://localhost:8040/
```

- [] https://makeup-api.herokuapp.com/