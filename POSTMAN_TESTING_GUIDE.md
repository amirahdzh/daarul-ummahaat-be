# 🔐 Laravel Sanctum Testing Guide with Postman

## 📖 **Understanding Laravel Sanctum**

### **What is Laravel Sanctum?**

Laravel Sanctum is a lightweight package for authenticating SPAs and mobile applications using API tokens. It's simpler than Laravel Passport but perfect for most authentication needs.

### **How Sanctum Works:**

1. **Token Creation**: When user logs in, Sanctum creates a token in `personal_access_tokens` table
2. **Token Storage**: Client stores the token (usually in localStorage or secure storage)
3. **Token Usage**: Client sends token in `Authorization: Bearer {token}` header
4. **Token Validation**: Sanctum middleware validates token on each request
5. **User Context**: If valid, request continues with authenticated user context

### **Token Lifecycle:**

```
Login → Generate Token → Store Token → Use Token → Validate Token → Access Resource
  ↓
Logout → Revoke Token → Token Invalid → 401 Unauthorized
```

## 🚀 **Postman Setup & Testing**

### **Step 1: Import Collection**

1. Open Postman
2. Click "Import" button
3. Select the `Postman_Collection.json` file from your project root
4. Collection "Daarul Ummahaat API" will be imported with all requests

### **Step 2: Environment Setup**

The collection includes these variables:

-   `base_url`: http://localhost:8000/api
-   `admin_token`: Auto-filled after admin login
-   `user_token`: Auto-filled after user login
-   `user_id`: Auto-filled after login

### **Step 3: Start Testing**

#### **🔹 Test 1: Admin Login**

1. **Run Request**: `Authentication > Login Admin`
2. **Expected Result**:
    - Status: 200 OK
    - Response includes user data and token
    - `admin_token` variable automatically set

#### **🔹 Test 2: User Registration**

1. **Run Request**: `Authentication > Register User`
2. **Expected Result**:
    - Status: 201 Created
    - New user created with "user" role
    - `user_token` variable automatically set

#### **🔹 Test 3: Protected Route Access**

1. **Run Request**: `Authentication > Get Current User`
2. **Expected Result**:
    - Status: 200 OK
    - Returns current user info
    - Try removing Authorization header → should get 401

#### **🔹 Test 4: Role-Based Authorization**

1. **Run Request**: `Admin Routes > Admin Dashboard`
    - With admin token → Should work (200 OK)
2. **Run Request**: `Admin Routes > Admin Dashboard (User Token - Should Fail)`
    - With user token → Should fail (403 Forbidden)

#### **🔹 Test 5: User Management (Admin Only)**

1. **Run Request**: `Admin Routes > Get Users`
    - Should return paginated list of users
2. **Run Request**: `Admin Routes > Toggle User Status`
    - Should activate/deactivate a user

#### **🔹 Test 6: User Profile Management**

1. **Run Request**: `User Routes > Get Profile`
    - Should return user profile
2. **Run Request**: `User Routes > Update Profile`
    - Should update user information

#### **🔹 Test 7: Token Revocation**

1. **Run Request**: `Authentication > Logout`
2. **Try Protected Route**: Use same token → Should get 401

## 🔍 **Understanding the Responses**

### **Successful Login Response:**

```json
{
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role_id": 1,
        "role": {
            "id": 1,
            "name": "admin"
        }
    },
    "token": "1|abcdef123456...",
    "token_type": "Bearer"
}
```

### **Error Responses:**

**401 Unauthorized (Invalid/Missing Token):**

```json
{
    "message": "Unauthenticated."
}
```

**403 Forbidden (Insufficient Permissions):**

```json
{
    "error": "Unauthorized. Required role: admin"
}
```

**422 Validation Error:**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."]
    }
}
```

## 🔧 **Advanced Testing Scenarios**

### **Scenario 1: Token Expiration Test**

1. Generate a token
2. Manually expire it in database:
    ```sql
    UPDATE personal_access_tokens
    SET expires_at = '2020-01-01 00:00:00'
    WHERE token = 'your_token_hash';
    ```
3. Try using expired token → Should get 401

### **Scenario 2: Multiple Tokens per User**

1. Login multiple times
2. Check `personal_access_tokens` table
3. Each login creates a new token
4. All tokens remain valid until logout/expiration

### **Scenario 3: Token Abilities (Optional)**

If you implement token abilities:

```php
$token = $user->createToken('api-token', ['read', 'write']);
```

### **Scenario 4: Cross-Domain Testing**

Test CORS by changing Sanctum stateful domains:

```php
// config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost:3000')),
```

## 📊 **Database Inspection**

### **Check Personal Access Tokens:**

```sql
SELECT
    id,
    tokenable_id as user_id,
    name,
    LEFT(token, 10) as token_preview,
    last_used_at,
    created_at
FROM personal_access_tokens;
```

### **Check User Roles:**

```sql
SELECT
    u.id,
    u.name,
    u.email,
    r.name as role
FROM users u
JOIN roles r ON u.role_id = r.id;
```

## 🛠 **Troubleshooting Common Issues**

### **Issue 1: 500 Error on Login**

-   Check database connection
-   Ensure migrations are run
-   Check Laravel logs: `tail -f storage/logs/laravel.log`

### **Issue 2: Token Not Working**

-   Verify `Authorization: Bearer {token}` format
-   Check if token exists in database
-   Ensure Sanctum middleware is applied

### **Issue 3: CORS Issues**

-   Add domains to `SANCTUM_STATEFUL_DOMAINS`
-   Configure CORS middleware properly

### **Issue 4: Role Middleware Not Working**

-   Verify middleware is registered in `bootstrap/app.php`
-   Check if user has the required role
-   Ensure role relationship is loaded

## 📝 **Best Practices**

1. **Token Storage**: Store tokens securely on client side
2. **Token Rotation**: Implement token refresh for long-lived apps
3. **Rate Limiting**: Add rate limiting to auth endpoints
4. **Logging**: Log authentication attempts for security
5. **Validation**: Always validate and sanitize inputs
6. **HTTPS**: Use HTTPS in production for token security

## 🎯 **Next Steps**

After successful testing, you can:

1. Integrate with your frontend application
2. Add password reset functionality
3. Implement email verification
4. Add two-factor authentication
5. Create more granular permissions
6. Add API rate limiting
7. Implement refresh tokens for better security
