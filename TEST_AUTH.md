# Test Authentication & Authorization

## 🚀 Quick Start

### 1. Start the server

```bash
php artisan serve
```

### 2. Test Admin Login

```powershell
$adminLogin = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method POST -ContentType "application/json" -Body '{"email":"admin@example.com","password":"password"}'
$adminResponse = $adminLogin.Content | ConvertFrom-Json
$adminToken = $adminResponse.token
Write-Host "Admin Token: $adminToken"
```

### 3. Test Admin Dashboard Access

```powershell
$headers = @{ "Authorization" = "Bearer $adminToken" }
$dashboard = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/dashboard" -Method GET -Headers $headers
Write-Host $dashboard.Content
```

### 4. Test User Login

```powershell
$userLogin = Invoke-WebRequest -Uri "http://localhost:8000/api/login" -Method POST -ContentType "application/json" -Body '{"email":"user@example.com","password":"password"}'
$userResponse = $userLogin.Content | ConvertFrom-Json
$userToken = $userResponse.token
Write-Host "User Token: $userToken"
```

### 5. Test User Trying to Access Admin (Should Fail)

```powershell
$userHeaders = @{ "Authorization" = "Bearer $userToken" }
try {
    $adminAccess = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/dashboard" -Method GET -Headers $userHeaders
} catch {
    Write-Host "Expected error: $($_.Exception.Message)"
}
```

### 6. Test User Profile Access (Should Work)

```powershell
$profile = Invoke-WebRequest -Uri "http://localhost:8000/api/user/profile" -Method GET -Headers $userHeaders
Write-Host $profile.Content
```

## ✅ Features Implemented

### Authentication

-   ✅ User registration
-   ✅ User login
-   ✅ User logout
-   ✅ Get current user info
-   ✅ Token-based authentication (Laravel Sanctum)

### Authorization

-   ✅ Role-based access control
-   ✅ Admin role can access admin routes
-   ✅ User role can access user routes
-   ✅ Unauthorized access returns 403 error

### Admin Features

-   ✅ Admin dashboard with statistics
-   ✅ View all users
-   ✅ Toggle user status (activate/deactivate)
-   ✅ Admin action logging

### User Features

-   ✅ View profile
-   ✅ Update profile
-   ✅ Change password

### Security

-   ✅ Password hashing
-   ✅ Input validation
-   ✅ Soft deletes for users
-   ✅ Token revocation on logout
-   ✅ Role verification middleware

## 📊 Default Data

### Roles

-   `admin` - Full access to admin features
-   `user` - Standard user access
-   `editor` - Content editor access

### Default Users

-   **Admin**: admin@example.com / password
-   **User**: user@example.com / password

## 🔧 Next Steps

You can now:

1. Integrate this with your frontend application
2. Add more role-based permissions
3. Implement email verification
4. Add password reset functionality
5. Add more admin features for managing donations, fundraisers, etc.

## 📁 Files Created/Modified

### Controllers

-   `app/Http/Controllers/AuthController.php`
-   `app/Http/Controllers/UserController.php`
-   `app/Http/Controllers/AdminController.php`

### Middleware

-   `app/Http/Middleware/RoleMiddleware.php`

### Routes

-   `routes/api.php`

### Models

-   Updated `app/Models/User.php` with Sanctum and role methods

### Configuration

-   Updated `bootstrap/app.php` for middleware registration
-   Updated `database/seeders/DatabaseSeeder.php`

### Documentation

-   `API_DOCS.md`
-   `TEST_AUTH.md` (this file)
