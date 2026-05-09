# Developer Setup Guide

This guide will help you set up the AI Proxy API project locally for development.

## Prerequisites

- Docker and Docker Compose installed
- Git installed
- A database server running on your host machine (MySQL, PostgreSQL, or SQLite)
- Together AI API key (for AI proxy functionality)

## Quick Start

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd pic-bucket
   ```

2. **Set up Git hooks:**
   ```bash
   git config core.hooksPath .githooks
   ```
   This configures the pre-commit hook to run tests before each commit.

3. **Set up environment variables:**
   ```bash
   cd src
   cp .env.example .env
   ```
   Then edit `.env` and configure the following:
   - Database connection settings (see Database Setup below)
   - `TOGETHER_AI_API_KEY` - Your Together AI API key
   - `APP_KEY` - Will be generated in the next step

4. **Start Docker containers:**
   ```bash
   cd ..
   docker compose up -d
   ```

5. **Install dependencies and set up the application:**
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate
   docker compose exec app php artisan db:seed
   ```
   Note: The `db:seed` command creates test users and API keys for development/testing.

6. **Access the application:**
   - API: http://localhost:8003
   - The application should now be running!

## Database Setup

The project expects you to host the database separately on your host machine. The Docker containers connect to the host database using `host.docker.internal`.

### Configuration

In your `.env` file (located in `src/.env`), configure your database connection:

**For MySQL/MariaDB:**
```env
DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=host.docker.internal
DB_PORT=5432
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**For SQLite (development only):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

### Running Migrations

After configuring your database, run migrations (only for new test setup):
```bash
docker compose exec app php artisan migrate
```

**For test environment data generation, run the database seeder:**
```bash
docker compose exec app php artisan db:seed
```

This will create test users and API keys for development/testing purposes.

> **Note:** The seeder is automatically disabled in production environment for safety. It will only run in non-production environments (local, staging, testing, etc.).

## Environment Variables

Key environment variables to configure in `src/.env`:

- `APP_NAME` - Application name
- `APP_ENV` - Environment (local, staging, production)
- `APP_DEBUG` - Debug mode (true/false)
- `APP_URL` - Application URL
- `DB_CONNECTION` - Database driver (mysql, pgsql, sqlite)
- `DB_HOST` - Database host (use `host.docker.internal` for host machine)
- `DB_PORT` - Database port
- `DB_DATABASE` - Database name
- `DB_USERNAME` - Database username
- `DB_PASSWORD` - Database password
- `TOGETHER_AI_API_KEY` - Together AI API key (required)
- `TOGETHER_BASE_URL` - Together AI base URL (default: https://api.together.xyz/v1)

## Docker Services

The project uses Docker Compose with three services:

1. **app** - PHP-FPM application container (Laravel)
2. **queue** - Queue worker for background jobs
3. **web** - Nginx web server (exposed on port 8003)

### Useful Docker Commands

- Start containers: `docker compose up -d`
- Stop containers: `docker compose down`
- View logs: `docker compose logs -f [service_name]`
- Execute commands in app container: `docker compose exec app <command>`
- Rebuild containers: `docker compose up -d --build`

## Development Workflow

### Running Artisan Commands

All Laravel artisan commands should be run inside the Docker container:

```bash
docker compose exec app php artisan <command>
```

### Running Tests

The pre-commit hook automatically runs tests before each commit. To run tests manually:

```bash
docker compose exec app php artisan test
```

To run a specific test:
```bash
docker compose exec app php artisan test --filter TestName
```

### Installing Dependencies

**PHP dependencies (Composer):**
```bash
docker compose exec app composer install
docker compose exec app composer update
```

### Code Formatting

The project uses Laravel Pint for code formatting:
```bash
docker compose exec app ./vendor/bin/pint
```

## Model Management

### Fresh Update of Models

To perform a complete fresh update of all AI models (clearing existing data and re-crawling from providers):

```bash
# 1. Purge all existing AI model data
docker compose exec app php artisan db:purge-ai-data --force

# 2. Crawl prices from Together AI
docker compose exec app php artisan together:crawl-prices

# 3. Crawl prices from OpenAI
docker compose exec app php artisan openai:crawl-prices

# 4. Verify Together AI models
docker compose exec app php artisan together:verify-models

# 5. Verify OpenAI models
docker compose exec app php artisan openai:verify-models
```

**Note:** This process will:
- Remove all existing AI models and model providers from the database
- Re-crawl pricing information from both Together AI and OpenAI
- Create new model entries with `is_active = false`
- Verify models that have sale pricing configured
- Set `is_active = true` on both `ai_models` and `ai_model_providers` tables for successfully verified models

## Git Hooks

The project includes a pre-commit hook that:
- Checks if Docker containers are running
- Runs PHPUnit tests before allowing a commit
- Prevents commits if tests fail

To set up the hook:
```bash
git config core.hooksPath .githooks
```

## Troubleshooting

### Containers won't start
- Check if port 8003 is already in use
- Verify Docker is running: `docker ps`
- Check logs: `docker compose logs`

### Database connection errors
- Ensure your database server is running on the host machine
- Verify database credentials in `.env`
- For MySQL/PostgreSQL, ensure the database accepts connections from Docker containers
- Try using `127.0.0.1` instead of `host.docker.internal` if on Linux

### Permission issues
- Ensure storage directories are writable:
  ```bash
  docker compose exec app chmod -R 775 storage bootstrap/cache
  ```

### Tests failing
- Ensure database is properly configured
- Check that migrations have been run
- Verify environment variables are set correctly

## Project Structure

- `src/` - Laravel application source code
- `docker/` - Docker configuration files (nginx.conf)
- `Dockerfile` - PHP-FPM container definition
- `docker-compose.yml` - Docker Compose configuration
- `.githooks/` - Git hooks directory

## API Documentation

For a detailed description of available HTTP endpoints, request parameters, and responses, see the [API Reference](doc/api.md).

## Additional Resources

- Laravel Documentation: https://laravel.com/docs
- Docker Documentation: https://docs.docker.com/
- Together AI Documentation: https://docs.together.ai/
