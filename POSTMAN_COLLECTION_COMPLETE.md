# Daarul Ummahaat API - Postman Collection Complete

## API Testing Guide

This Postman collection contains **ALL** endpoints for the Daarul Ummahaat donation management system.

### Collection Features

✅ **Authentication & Authorization**

-   Login Admin, User, Editor
-   Register new users
-   Get current user
-   Logout
-   Role-based access control

✅ **Programs (Admin-only CRUD)**

-   Get all programs (public)
-   Get program by ID (public)
-   Create program (admin only)
-   Update program (admin only)
-   Delete program (admin only)

✅ **Donation Packages (Admin-only CRUD)**

-   Get all donation packages (public)
-   Get donation package by ID (public)
-   Create donation package (admin only)
-   Update donation package (admin only)
-   Toggle package status (admin only)
-   Delete donation package (admin only)

✅ **Fundraisers (Admin/Editor CRUD)**

-   Get all fundraisers (public)
-   Get fundraiser by ID (public)
-   Create fundraiser (admin/editor)
-   Update fundraiser (admin/editor own)
-   Update fundraiser progress (admin only)
-   Delete fundraiser (admin/editor own)

✅ **Activities (Admin/Editor CRUD)**

-   Get all activities (public)
-   Get upcoming activities (public)
-   Get past activities (public)
-   Get activity by ID (public)
-   Create activity (admin/editor)
-   Update activity (admin/editor own)
-   Delete activity (admin/editor own)

✅ **Donations (Full CRUD with Multiple Scenarios)**

-   Create donation (public/anonymous)
-   Create donation (authenticated user)
-   Get all donations (admin)
-   Get user donations (user own)
-   Get donation by ID
-   Create manual donation (admin only)
-   Confirm donation (admin only)
-   Cancel donation (admin only)
-   Get donation statistics (admin only)

✅ **User Management**

-   Get user profile
-   Update user profile
-   Admin dashboard
-   Get all users (admin)
-   Toggle user status (admin)

✅ **Authorization Tests**

-   Test unauthorized access attempts
-   Role-based permission validation
-   Public endpoint access without authentication

### Default Credentials

```json
Admin: {
  "email": "admin@example.com",
  "password": "password"
}

User: {
  "email": "user@example.com",
  "password": "password"
}

Editor: {
  "email": "editor@example.com",
  "password": "password"
}
```

### Test Order

1. **Authentication** - Login all three user types
2. **Public Endpoints** - Test without authentication
3. **Programs** - Admin CRUD operations
4. **Donation Packages** - Admin CRUD operations
5. **Fundraisers** - Admin/Editor CRUD operations
6. **Activities** - Admin/Editor CRUD operations
7. **Donations** - All scenarios (anonymous, authenticated, manual)
8. **User Management** - Profile and admin functions
9. **Authorization Tests** - Verify role restrictions

### Collection Variables

The collection automatically manages these variables:

-   `admin_token` - Admin authentication token
-   `user_token` - User authentication token
-   `editor_token` - Editor authentication token
-   `user_id` - Current user ID
-   `program_id` - Created program ID
-   `package_id` - Created donation package ID
-   `fundraiser_id` - Created fundraiser ID
-   `activity_id` - Created activity ID
-   `donation_id` - Created donation ID

### Scenarios Covered

✅ **Public Access**

-   View published programs, packages, fundraisers, activities
-   Create anonymous donations

✅ **Donor/User Access**

-   Register and login
-   View donation history
-   Create authenticated donations
-   Update profile

✅ **Editor Access**

-   Create/edit own fundraisers and activities
-   Cannot access admin functions
-   Cannot edit other editors' content

✅ **Admin Access**

-   Full CRUD on all entities
-   User management
-   Manual donation entry
-   Donation confirmation/cancellation
-   System statistics

✅ **Manual Entry**

-   Admin can manually enter donations
-   Bypass normal payment flow
-   Direct confirmation

### Testing Notes

-   All endpoints have proper validation
-   Error responses are handled
-   Authentication tokens are automatically captured
-   IDs are automatically stored for dependent requests
-   Role-based access is enforced
-   Admin logging is implemented for all admin actions

### API Documentation

-   Complete API documentation: `COMPLETE_API_DOCS.md`
-   Postman testing guide: `POSTMAN_TESTING_GUIDE.md`
-   Authentication guide: `TEST_AUTH.md`

Run the collection in order for best results. The authentication requests should be run first to populate the tokens.
