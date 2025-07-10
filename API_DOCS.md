# Authentication API Documentation

## Base URL

```
http://localhost:8000/api
```

## Authentication Endpoints

### 1. User Registration

**POST** `/register`

**Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890"
}
```

**Response:**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "+1234567890",
        "role": {
            "id": 2,
            "name": "user"
        }
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

### 2. User Login

**POST** `/login`

**Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": {
            "id": 2,
            "name": "user"
        }
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
}
```

### 3. User Logout

**POST** `/logout`

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "message": "Logged out successfully"
}
```

### 4. Get Current User

**GET** `/user`

**Headers:**

```
Authorization: Bearer {token}
```

**Response:**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": {
            "id": 2,
            "name": "user"
        }
    }
}
```

## User Endpoints (Requires Authentication)

### 5. Get User Profile

**GET** `/user/profile`

**Headers:**

```
Authorization: Bearer {token}
```

### 6. Update User Profile

**PUT** `/user/profile`

**Headers:**

```
Authorization: Bearer {token}
```

**Body:**

```json
{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "phone": "+1234567891",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

## Admin Endpoints (Requires Admin Role)

### 7. Admin Dashboard

**GET** `/admin/dashboard`

**Headers:**

```
Authorization: Bearer {admin_token}
```

### 8. Get Users List

**GET** `/admin/users`

**Headers:**

```
Authorization: Bearer {admin_token}
```

**Query Parameters:**

-   `search`: Search by name or email
-   `role`: Filter by role name
-   `per_page`: Items per page (default: 15)

### 9. Toggle User Status

**POST** `/admin/users/{user_id}/toggle-status`

**Headers:**

```
Authorization: Bearer {admin_token}
```

## Default Users (After Seeding)

### Admin User

-   Email: `admin@example.com`
-   Password: `password`
-   Role: `admin`

### Regular User

-   Email: `user@example.com`
-   Password: `password`
-   Role: `user`

## Testing Commands

1. Run migrations and seed data:

```bash
php artisan migrate:fresh --seed
```

2. Start the server:

```bash
php artisan serve
```

3. Test login with curl:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```
