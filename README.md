# Todo Listing API

## Project Overview

This project is a sample solution to the [Todo List API](https://roadmap.sh/projects/todo-list-api) challenge from [roadmap.sh](https://roadmap.sh). It provides authentication and todo management features, allowing users to register, log in, and manage their to-do lists efficiently.

## Features

- User authentication (register, login, logout, token refresh)
- CRUD operations for to-dos
- Search and filter to-dos by completion status
- Pagination and sorting

## API Endpoints

### Authentication

- `POST /api/v1/auth/register` - Register a new user.
- `POST /api/v1/auth/login` - Log in and receive an access token.
- `POST /api/v1/auth/logout` - Log out the authenticated user.
- `GET /api/v1/auth/refresh` - Refresh the authentication token.

### To-Dos

- `GET /api/v1/todos` - Retrieve paginated to-dos belonging to the authenticated user (supports search, filtering, and sorting).
- `POST /api/v1/todos` - Create a new to-do.
- `GET /api/v1/todos/{id}` - Retrieve a single to-do by its ID.
- `PUT /api/v1/todos/{id}` - Update an existing to-do.
- `DELETE /api/v1/todos/{id}` - Delete a to-do.

## Installation

Follow these steps to set up and run the API:

1. Clone the repository:

   ```sh
   git clone https://github.com/connecttoMAHDI/todo-list-api
   cd todo-list-api
   ```

2. Install dependencies:

   ```sh
   composer install
   ```

3. Copy the environment file and configure it:

   ```sh
   cp .env.example .env
   ```

4. Generate the application key:

   ```sh
   php artisan key:generate
   ```

5. Configure the database in `.env` (default uses SQLite, but you can switch to MySQL/PostgreSQL).

6. Run database migrations:

   ```sh
   php artisan migrate
   ```

7. **Fix Migration Issue:**
   If you are using 'SQLite' and encounter issues running migrations, uncomment the following extensions in `php.ini`:

   ```ini
   extension=sqlite3
   extension=pdo_sqlite
   ```

8. Run database seeder (Optional):

   ```sh
   php artisan db:seed
   ```

9. Start the development server:

   ```sh
   php artisan serve
   ```

## API Testing with Talend API Tester

A Postman-compatible collection file, `todo-listing-api.json`, is available in the project root. You can import it into the Talend API Tester Chrome extension for easy testing of API endpoints.

### Steps to Import:

1. Open the Talend API Tester Chrome extension.
2. Click on `Import`.
3. Select `todo-listing-api.json` from the project root.
4. Start testing the API endpoints.

