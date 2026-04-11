# Qtec Task Management System

A Laravel 12 task management application built for the Qtec practical assessment.

The system supports creating, updating, filtering, scheduling, and tracking tasks through a `Controller + Service + Repository` architecture.

## Features

- Task CRUD
- Task status management from the index page
- Scheduling with `start_time` and `end_time`
- `Assigned To` support
- Filters for status, overdue tasks, nearing end tasks, and newly created tasks
- SweetAlert confirmations and success messages
- Feature test coverage for the main flows

## Tech Stack

- PHP 8.2
- Laravel 12
- MySQL
- Blade
- CSS
- SweetAlert2
- PHPUnit

## Architecture

The project follows a layered structure:

- Controller: handles HTTP requests and responses
- Service: contains application workflow and payload preparation
- Repository: handles data access
- Model: represents the `tasks` table

Core files:

- [TaskController.php](c:/xampp/htdocs/Qtec-Task-Management-System/app/Http/Controllers/TaskController.php)
- [TaskService.php](c:/xampp/htdocs/Qtec-Task-Management-System/app/Services/TaskService.php)
- [TaskRepositoryInterface.php](c:/xampp/htdocs/Qtec-Task-Management-System/app/Interfaces/TaskRepositoryInterface.php)
- [TaskRepository.php](c:/xampp/htdocs/Qtec-Task-Management-System/app/Repositories/TaskRepository.php)
- [Task.php](c:/xampp/htdocs/Qtec-Task-Management-System/app/Models/Task.php)

## Task Fields

Each task includes:

- `title`
- `description`
- `assigned_to`
- `status`
- `start_time`
- `end_time`

Allowed statuses:

- `pending`
- `in_progress`
- `completed`

## Local Setup

1. Clone the project.
2. Install dependencies:

```bash
composer install
```

3. Create the environment file if needed:

```bash
copy .env.example .env
```

4. Generate the app key:

```bash
php artisan key:generate
```

5. Configure your database credentials in `.env`.
6. Run migrations:

```bash
php artisan migrate
```

7. Start the app:

```bash
php artisan serve
```

Or, if you are using XAMPP with this project inside `htdocs`, open:

```text
http://localhost/Qtec-Task-Management-System/public
```

## Testing

Run the test suite with:

```bash
php artisan test
```

Covered flows include:

- task listing
- task creation
- validation failures
- task update
- task deletion
- status filtering
- overdue filtering
- nearing-end sorting
- newly-created filtering
- inline status updates

Main feature tests:

- [TaskManagementTest.php](c:/xampp/htdocs/Qtec-Task-Management-System/tests/Feature/TaskManagementTest.php)

## Notes

## Developer

Name: Md Sazzad Mazumder
Email: sazzad.mzd@gmail.com
