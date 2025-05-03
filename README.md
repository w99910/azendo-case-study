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

### Installation without Docker

You have to install the required database and ollama. So I recommend you to use Docker.

### Installation with Docker

- Start the Docker containers
```bash
docker compose up -d --build
```

- Open the application by using the port you set in the .env file. 
```bash
# If you set the port to 8040, you can open the application by using the following URL.
http://localhost:8040/
```