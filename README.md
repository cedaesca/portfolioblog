# César Escudero's Portfolio

This Laravel application is designed to serve as both a personal blog and portfolio. It showcases professional work and personal insights, all within a secure and user-friendly platform.

## Features

-   **Secure Authentication**: Utilizes Laravel Fortify for robust, secure login mechanisms.
-   **Personal Blog**: Easily manage and publish blog posts.
-   **Portfolio Showcase**: Categorize and display portfolio projects.
-   **Admin Account Seeding**: Automate the creation of the admin account for easy setup.

## Installation

To get started with this project locally, follow these steps:

### Clone the Repository

```bash
git clone https://github.com/cedaesca/portfolioblog.git
cd portfolioblog
```

or using SSH

```bash
git clone git@github.com:cedaesca/portfolioblog.git
cd portfolioblog
```

### Install Dependencies

```bash
composer install
```

### Set Up Environment

Duplicate `.env.example` to `.env` and configure your environment variables.

```bash
cp .env.example .env
```

### Generate App Key

```bash
php artisan key:generate
```

### Run Migrations and Seeders

This will set up your database and create the admin account.

```bash
php artisan migrate --seed
```

### Serve the Application

```bash
php artisan serve
```

Access the application at `http://localhost:8000`.

## Environment Configuration

After cloning the repository and installing dependencies, you'll need to set up your `.env` file. Here's a quick guide based on the provided `.env.example`:

-   **APP_NAME**: `"César Escudero's Portfolio"` - Set this to the name of your application.
-   **ADMIN_ACCOUNT_NAME**, **ADMIN_ACCOUNT_EMAIL**, **ADMIN_ACCOUNT_PASSWORD**: Change these from the defaults to secure your admin account.
-   **DB_CONNECTION**: Your database driver, e.g., `mysql`.
-   **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD**: Update with your database details.
-   Configure **Mail**, **AWS**, **Redis**, and **Pusher** settings as required for your project.

## Admin Login

The admin login route is `/login`. The login link is intentionally omitted from the navigation.

## Testing

To run the tests included with this application, execute:

```bash
php artisan test
```

Ensure your `.env.testing` environment is correctly configured, especially the database connection.

## Contributing

This project is designed for personal use. However, feedback and suggestions are welcome. Please open an issue to discuss what you would like to change.

## License

This project is open-sourced under the MIT License. See the `LICENSE` file for more details.

## Acknowledgements

-   [Laravel](https://laravel.com/)
-   [MIT LICENSE](LICENSE)
