# 🕌 Daarul Ummahaat API Documentation

Complete Swagger/OpenAPI documentation for the Daarul Ummahaat donation management system.

## 📋 Overview

This API provides comprehensive functionality for managing:

-   **Programs** (Admin-only CRUD)
-   **Donation Packages** (Admin-only CRUD)
-   **Fundraisers** (Admin/Editor CRUD)
-   **Activities** (Admin/Editor CRUD)
-   **Donations** (Full lifecycle management)
-   **User Management** (Profile & Admin functions)
-   **Authentication** (Role-based access control)

## 🔐 Authentication

The API uses **Bearer Token Authentication** with Laravel Sanctum.

### Default Test Accounts

```bash
# Admin Account
Email: admin@example.com
Password: password

# Regular User Account
Email: user@example.com
Password: password

# Editor Account
Email: editor@example.com
Password: password
```

## 🎯 User Roles & Permissions

| Role       | Permissions                                                        |
| ---------- | ------------------------------------------------------------------ |
| **Admin**  | Full access to all endpoints, user management, donation management |
| **Editor** | Create/edit own fundraisers and activities, view published content |
| **User**   | Donate, view own donations, manage profile, view published content |
| **Public** | View published content, create anonymous donations                 |

## 📚 Documentation Files

### 1. Swagger/OpenAPI Specification

-   **File**: `swagger.yaml`
-   **Format**: OpenAPI 3.0.3
-   **Content**: Complete API specification with all endpoints, schemas, and examples

### 2. Interactive Documentation

-   **File**: `swagger-ui.html`
-   **Format**: HTML with Swagger UI
-   **Features**:
    -   Interactive API testing
    -   Beautiful UI with custom branding
    -   Try-it-out functionality
    -   Request/response examples

## 🚀 Getting Started

### 1. View Documentation Locally

Open the interactive documentation:

```bash
# Open in browser
start swagger-ui.html

# Or serve via Python (if needed)
python -m http.server 8080
# Then visit: http://localhost:8080/swagger-ui.html
```

### 2. Test API Endpoints

1. **Start Laravel Server**:

    ```bash
    php artisan serve
    ```

2. **Login to get token**:

    ```bash
    POST http://localhost:8000/api/login
    {
      "email": "admin@example.com",
      "password": "password"
    }
    ```

3. **Use token in requests**:
    ```bash
    Authorization: Bearer YOUR_TOKEN_HERE
    ```

### 3. Import into API Tools

#### Postman

1. Import `swagger.yaml` into Postman
2. Or use the existing `Postman_Collection.json`

#### Insomnia

1. Import `swagger.yaml`
2. Configure base URL: `http://localhost:8000/api`

#### curl Examples

```bash
# Get all programs (public)
curl http://localhost:8000/api/programs

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Create program (admin only)
curl -X POST http://localhost:8000/api/admin/programs \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Test Program","description":"Test","image":"test.jpg","is_published":true}'
```

## 📊 API Endpoints Summary

### Authentication (6 endpoints)

-   `POST /login` - User login
-   `POST /register` - User registration
-   `POST /logout` - User logout
-   `GET /user` - Get current user
-   `GET /user/profile` - Get user profile
-   `PUT /user/profile` - Update user profile

### Programs (5 endpoints)

-   `GET /programs` - List programs (public)
-   `GET /programs/{id}` - Get program (public)
-   `POST /admin/programs` - Create program (admin)
-   `PUT /admin/programs/{id}` - Update program (admin)
-   `DELETE /admin/programs/{id}` - Delete program (admin)

### Donation Packages (6 endpoints)

-   `GET /donation-packages` - List packages (public)
-   `GET /donation-packages/{id}` - Get package (public)
-   `POST /admin/donation-packages` - Create package (admin)
-   `PUT /admin/donation-packages/{id}` - Update package (admin)
-   `POST /admin/donation-packages/{id}/toggle-status` - Toggle status (admin)
-   `DELETE /admin/donation-packages/{id}` - Delete package (admin)

### Fundraisers (6 endpoints)

-   `GET /fundraisers` - List fundraisers (public)
-   `GET /fundraisers/{id}` - Get fundraiser (public)
-   `POST /fundraisers` - Create fundraiser (admin/editor)
-   `PUT /fundraisers/{id}` - Update fundraiser (admin/editor own)
-   `DELETE /fundraisers/{id}` - Delete fundraiser (admin/editor own)
-   `POST /admin/fundraisers/{id}/update-progress` - Update progress (admin)

### Activities (7 endpoints)

-   `GET /activities` - List activities (public)
-   `GET /activities/{id}` - Get activity (public)
-   `GET /activities/upcoming` - Get upcoming activities (public)
-   `GET /activities/past` - Get past activities (public)
-   `POST /activities` - Create activity (admin/editor)
-   `PUT /activities/{id}` - Update activity (admin/editor own)
-   `DELETE /activities/{id}` - Delete activity (admin/editor own)

### Donations (9 endpoints)

-   `POST /donations` - Create donation (public/authenticated)
-   `GET /donations` - List all donations (admin)
-   `GET /donations/{id}` - Get donation (admin/owner)
-   `GET /user/donations` - Get user donations (user)
-   `POST /admin/donations/manual` - Create manual donation (admin)
-   `POST /admin/donations/{id}/confirm` - Confirm donation (admin)
-   `POST /admin/donations/{id}/cancel` - Cancel donation (admin)
-   `GET /admin/donations/statistics` - Get statistics (admin)

### Admin Management (4 endpoints)

-   `GET /admin/dashboard` - Admin dashboard (admin)
-   `GET /admin/users` - List users (admin)
-   `POST /admin/users/{id}/toggle-status` - Toggle user status (admin)

## 🎨 Swagger UI Features

### Custom Styling

-   Islamic green color scheme (#2c5530, #3b7441)
-   Custom header with mosque emoji
-   Professional layout with shadows and gradients

### Interactive Features

-   **Try It Out**: Test endpoints directly from the documentation
-   **Authentication**: Easily add Bearer tokens
-   **Request/Response Examples**: See real API calls and responses
-   **Schema Documentation**: Detailed data models and validation rules
-   **Error Handling**: Complete error response documentation

### Advanced Features

-   **Filtering**: Search through endpoints
-   **Deep Linking**: Share direct links to specific endpoints
-   **Model Expansion**: Expandable request/response schemas
-   **Validation**: Real-time request validation

## 🔧 Technical Details

### OpenAPI Specification

-   **Version**: 3.0.3
-   **Base URL**: `http://localhost:8000/api`
-   **Authentication**: Bearer Token (Laravel Sanctum)
-   **Content Type**: `application/json`
-   **Error Format**: Laravel validation errors

### Schema Validation

-   Complete input validation rules
-   Detailed response schemas
-   Enum values for status fields
-   Required field indicators
-   Data type specifications

### Response Codes

-   `200` - Success
-   `201` - Created
-   `401` - Unauthorized
-   `403` - Forbidden
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

## 📖 Additional Resources

-   **Postman Collection**: `Postman_Collection.json`
-   **Complete API Docs**: `COMPLETE_API_DOCS.md`
-   **Testing Guide**: `POSTMAN_TESTING_GUIDE.md`
-   **Collection Guide**: `POSTMAN_COLLECTION_COMPLETE.md`

## 🤝 Contributing

When adding new endpoints:

1. Update `swagger.yaml` with new paths and schemas
2. Add examples and descriptions
3. Update this README with endpoint counts
4. Test the endpoint in Swagger UI
5. Update Postman collection if needed

## 📝 Notes

-   All timestamps are in ISO 8601 format
-   Monetary amounts are in Indonesian Rupiah (IDR)
-   Phone numbers should include country code
-   Images are stored as filenames (string)
-   Soft deletes are used (deleted_at field)
-   Pagination follows Laravel's standard format

---

**🚀 Ready to explore the API? Open `swagger-ui.html` in your browser!**
