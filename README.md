# linya

## Prerequisites

Before you can run this project, make sure you have the following installed on your system:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Node.js](https://nodejs.org/) (v16 or higher recommended)
- [Composer](https://getcomposer.org/)

## Setup Instructions

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd linya
   ```

2. **Install PHP dependencies using Composer**

   ```bash
   composer install
   ```

3. **Install Node.js dependencies**

   ```bash
   npm install
   ```

4. **Create your `.env` file**

   Copy the `.env` file from someone on your team or from a provided template. This file contains environment-specific settings required to run the project.

   ```bash
   # Example (ask a teammate for the .env file)
   cp .env.example .env
   # or manually copy the .env file you received into the project root
   ```

5. **Modify .env**

    Generate an APP_KEY and modify DB .env 

   ```bash
   php artisan key:generate
   ```

    DB_CONNECTION=pgsql
    DB_HOST=db
    DB_PORT=5432
    DB_DATABASE=laravel
    DB_USERNAME=laravel
    DB_PASSWORD=laravel

7. **Start the project**

   You can now start the project using Docker Compose:

   ```bash
   docker-compose up --build
   ```

8. **Run database migrations**

   Make sure your database is configured in your `.env` file and linya container is running, then run:

   ```bash
   docker-compose exec app php artisan migrate
   ```

9. **Connect pgadmin to database**

   Go to localhost:5050 and login with 
    user: admin@admin.com 
    password: admin

    Press Add New Server and set name to whatever, but ENSURE that in Connection, Host Name/Address is set to db, and Username and Password is both set to laravel. Enable save password too.
