# Daarul Ummahaat - Donation Management API

A comprehensive donation management system for religious foundations, built with Laravel 11 and featuring role-based access control, donation tracking, and administrative tools.

## 🚀 Features

### Core Functionality

-   **User Management**: Registration, authentication, and profile management
-   **Role-Based Access Control**: Admin, Editor, and User roles with specific permissions
-   **Donation Processing**: Support for both package-based and fundraiser donations
-   **Program Management**: Create and manage foundation programs
-   **Fundraiser Management**: Campaign-style fundraising with progress tracking
-   **Activity Management**: Event and activity announcements
-   **Admin Dashboard**: Comprehensive statistics and management tools

### Technical Features

-   **RESTful API**: Complete REST API with pagination, filtering, and search
-   **Authentication**: Laravel Sanctum token-based authentication
-   **API Documentation**: Swagger/OpenAPI 3.0 documentation
-   **Database**: MySQL with comprehensive migrations and relationships
-   **Role-based Middleware**: Secure endpoint access based on user roles

## 🛠 Technology Stack

-   **Backend**: Laravel 11
-   **Authentication**: Laravel Sanctum
-   **Database**: MySQL
-   **API Documentation**: Swagger/OpenAPI 3.0
-   **Testing**: PHPUnit
-   **Code Quality**: Laravel Pint

## 📋 User Roles & Permissions

### Admin

-   Full system access
-   User management and role assignment
-   All CRUD operations on programs, donation packages
-   Donation confirmation and management
-   Dashboard access with statistics

### Editor

-   Create and manage fundraisers and activities
-   View published content
-   Limited administrative access

### User

-   View published content
-   Make donations
-   Manage personal profile
-   View donation history

### Public (Unauthenticated)

-   View published programs, fundraisers, and activities
-   Make anonymous donations

## 🚀 Quick Start

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   MySQL 8.0 or higher
-   Node.js 18+ (for asset compilation)

### Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/amirahdzh/daarul-ummahaat-be.git
    cd daarul-ummahaat-be
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database configuration**
   Update your `.env` file with database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=daarul_ummahaat
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run migrations and seed data**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Start the development server**
    ```bash
    php artisan serve
    ```

### Default Admin Account

After seeding, you can log in with:

-   **Email**: admin@example.com
-   **Password**: password

## 📚 API Documentation

### Access Documentation

-   **Swagger UI**: `http://localhost:8000/api/docs`
-   **JSON Format**: `http://localhost:8000/api/swagger.json`
-   **YAML Format**: `http://localhost:8000/api/swagger.yaml`

### Postman Collection

Import the `Postman_Collection.json` file into Postman for comprehensive API testing.

## 🔐 Authentication

The API uses Laravel Sanctum for token-based authentication:

1. **Login** to get an access token
2. **Include the token** in the `Authorization` header: `Bearer {your-token}`
3. **Logout** to revoke the token

### Example Authentication Flow

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Use the returned token for authenticated requests
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer {your-token}"
```

## 📊 API Endpoints Overview

### Public Endpoints

-   `GET /api/programs` - List published programs
-   `GET /api/donation-packages` - List active donation packages
-   `GET /api/fundraisers` - List active fundraisers
-   `GET /api/activities` - List published activities
-   `POST /api/donations` - Create donation (anonymous)
-   `POST /api/register` - User registration
-   `POST /api/login` - User login

### Admin Endpoints

-   `GET /api/admin/dashboard` - Admin dashboard with statistics
-   `GET /api/admin/programs` - List all programs (including unpublished)
-   `POST /api/admin/programs` - Create new program
-   `GET /api/admin/users` - User management
-   `GET /api/admin/donations/statistics` - Donation statistics

### User Endpoints

-   `GET /api/user/profile` - Get user profile
-   `GET /api/user/donations` - User's donation history
-   `PUT /api/user/profile` - Update profile

## 🗃 Database Schema

### Core Tables

-   **users** - User accounts and authentication
-   **roles** - User role definitions
-   **programs** - Foundation programs
-   **donation_packages** - Predefined donation amounts
-   **fundraisers** - Campaign-style fundraising
-   **activities** - Events and announcements
-   **donations** - Donation records
-   **admin_logs** - Administrative action logging

### Key Relationships

-   Users have roles (many-to-many)
-   Donations belong to users (optional), packages, or fundraisers
-   Fundraisers and activities have creators (users)
-   Admin logs track user actions

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🔧 Development

### Code Style

```bash
# Format code with Laravel Pint
./vendor/bin/pint
```

### Database Management

```bash
# Create new migration
php artisan make:migration create_table_name

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Route Management

```bash
# List all routes
php artisan route:list

# List API routes only
php artisan route:list --path=api
```

## 📈 Monitoring & Logging

-   **Application logs**: `storage/logs/laravel.log`
-   **Admin actions**: Tracked in `admin_logs` table
-   **API access**: Standard Laravel request logging

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support and questions:

-   **Email**: admin@daarul.com
-   **Documentation**: `http://localhost:8000/api/docs`
-   **Issues**: [GitHub Issues](https://github.com/amirahdzh/daarul-ummahaat-be/issues)

---

**Built with ❤️ for Daarul Ummahaat Foundation**
