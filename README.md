# linya

## Prerequisites

Before you can run this project, make sure you have the following installed on your system:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- [Node.js](https://nodejs.org/) (v16 or higher recommended)
- [Composer](https://getcomposer.org/)

## Windows Users: Full Setup in WSL

If you are on Windows, it is highly recommended to use [Windows Subsystem for Linux (WSL)](https://docs.microsoft.com/en-us/windows/wsl/) for a smoother development experience. **Do all setup steps inside WSL, not on Windows.**

### 1. Install WSL
- Open PowerShell as Administrator and run:
  ```sh
  wsl --install
  ```
- Restart your computer if prompted.
- Set up your Linux distribution (e.g., Ubuntu) as instructed.

### 2. Open WSL and Set Up Your Project
- Open your WSL terminal (e.g., Ubuntu).
- Navigate to your home directory:
  ```sh
  cd ~
  ```
- Clone this repository inside WSL:
  ```sh
  git clone <REPO_URL> linya
  cd linya
  ```
  Replace `<REPO_URL>` with the actual repository URL (e.g., from GitHub).

### 3. Install VSCode WSL Extension
- In VSCode, install the [Remote - WSL extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl).
- Open the project folder in WSL by clicking the green corner icon in VSCode and selecting "Open Folder in WSL...".

### 4. Install Dependencies in WSL
Run the following commands in your WSL terminal:

```sh
sudo apt update
sudo apt install -y git
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
sudo apt install composer
sudo apt-get install php8.3-dom php8.3-xml
```

### 5. Set Directory Permissions (in project directory)
Run these commands inside your project directory:

```sh
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
```

You are now ready to continue with the rest of the setup as described below!

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
