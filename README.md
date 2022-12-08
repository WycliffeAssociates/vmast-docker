# web-docker
A docker setup for web app with custom configuration

# How to build

1. Install `docker` and `docker-compose`
2. Rename `.env.example` to `.env`
3. Rename `web/nginx/localhost.conf.example` to `web/nginx/localhost.conf`
4. Run `docker-compose up --build`

# How to use

### Main app

1. Open http://localhost in your browser to access main app
2. Go to "Signup" to create new account

### PhpMyAdmin (Database management)

1. Open http://localhost:8081 in your browser
2. Use user `root` and password from .env file (DB_ROOT_PASSWORD)
3. The name of the app database is `vmast`

### RabbitMQ Console

1. Open http://localhost:15672 in your browser
