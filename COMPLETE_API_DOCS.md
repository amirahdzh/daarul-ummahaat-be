# 📚 Daarul Ummahaat API - Complete CRUD Documentation

## 🔗 Base URL

```
http://localhost:8000/api
```

## 🎭 User Roles & Permissions

### **Admin**

-   Full access to all features
-   Can manage users, programs, donation packages
-   Can verify donations and manage fundraisers
-   Can view all statistics

### **Editor/Fundraiser Manager**

-   Can create and manage their own fundraisers
-   Can create and manage their own activities
-   Cannot access admin-only features

### **User/Donor**

-   Can view public content
-   Can make donations
-   Can view their own donation history
-   Can update their profile

### **Public (No Login)**

-   Can view published content
-   Can make anonymous donations

---

## 📋 **Programs API**

### Public Access

```http
GET /programs
GET /programs/{id}
```

### Admin Only

```http
POST /admin/programs
PUT /admin/programs/{id}
DELETE /admin/programs/{id}
```

**Create Program (Admin Only):**

```json
POST /admin/programs
{
    "title": "Beasiswa Santri",
    "description": "Program beasiswa untuk santri berprestasi",
    "image": "path/to/image.jpg",
    "external_link": "https://example.com",
    "is_published": true
}
```

---

## 📦 **Donation Packages API**

### Public Access

```http
GET /donation-packages
GET /donation-packages/{id}
```

### Admin Only

```http
POST /admin/donation-packages
PUT /admin/donation-packages/{id}
DELETE /admin/donation-packages/{id}
POST /admin/donation-packages/{id}/toggle-status
```

**Create Donation Package (Admin Only):**

```json
POST /admin/donation-packages
{
    "title": "Infaq Rutin Bulanan",
    "description": "Infaq rutin setiap bulan untuk operasional yayasan",
    "amount": 100000,
    "category": "infaq",
    "is_active": true
}
```

---

## 🎯 **Fundraisers API**

### Public Access

```http
GET /fundraisers
GET /fundraisers/{id}
```

### Admin & Editor

```http
POST /fundraisers
PUT /fundraisers/{id}
DELETE /fundraisers/{id}
```

### Admin Only

```http
POST /admin/fundraisers/{id}/update-progress
```

**Create Fundraiser (Admin/Editor):**

```json
POST /fundraisers
{
    "title": "Renovasi Masjid",
    "description": "Dana untuk renovasi masjid yayasan",
    "target_amount": 50000000,
    "deadline": "2025-12-31",
    "image": "path/to/image.jpg",
    "status": "active",
    "is_published": true
}
```

**Update Progress (Admin Only):**

```json
POST /admin/fundraisers/{id}/update-progress
{
    "current_amount": 25000000,
    "note": "Update dari donasi offline event"
}
```

---

## 🎪 **Activities API**

### Public Access

```http
GET /activities
GET /activities/{id}
GET /activities/upcoming
GET /activities/past
```

### Admin & Editor

```http
POST /activities
PUT /activities/{id}
DELETE /activities/{id}
```

**Create Activity (Admin/Editor):**

```json
POST /activities
{
    "title": "Buka Puasa Bersama",
    "description": "Acara buka puasa bersama anak yatim",
    "event_date": "2025-04-15",
    "image": "path/to/image.jpg",
    "is_published": true
}
```

---

## 💰 **Donations API**

### Public Access

```http
POST /donations
```

### Authenticated Users

```http
GET /donations
GET /donations/{id}
GET /user/donations
```

### Admin Only

```http
POST /admin/donations/manual
POST /admin/donations/{id}/confirm
POST /admin/donations/{id}/cancel
GET /admin/donations/statistics
```

**Create Donation (Public):**

```json
POST /donations
{
    "donation_package_id": 1,
    "fundraiser_id": null,
    "title": "Donasi untuk Beasiswa",
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+628123456789",
    "category": "pendidikan",
    "amount": 500000,
    "proof_image": "path/to/proof.jpg"
}
```

**Create Manual Donation (Admin Only):**

```json
POST /admin/donations/manual
{
    "fundraiser_id": 1,
    "name": "Jane Doe",
    "phone": "+628987654321",
    "category": "infaq",
    "amount": 200000,
    "status": "confirmed",
    "confirmation_note": "Donasi tunai saat event"
}
```

**Confirm Donation (Admin Only):**

```json
POST /admin/donations/{id}/confirm
{
    "confirmation_note": "Transfer verified via bank statement"
}
```

---

## 👨‍💼 **Admin Management API**

### Dashboard & Users

```http
GET /admin/dashboard
GET /admin/users
POST /admin/users/{id}/toggle-status
```

### Statistics

```http
GET /admin/donations/statistics
```

**Response Example - Dashboard:**

```json
{
    "stats": {
        "total_users": 150,
        "total_donations": 89,
        "total_fundraisers": 12,
        "total_programs": 8,
        "total_activities": 25,
        "total_donation_amount": 45000000,
        "pending_donations": 5
    },
    "recent_donations": [...]
}
```

---

## 🔍 **Search & Filter Parameters**

### Common Filters

-   `search` - Search in title, description, name, email
-   `per_page` - Items per page (default: 15)
-   `page` - Page number

### Specific Filters

**Donations:**

-   `status` - pending, confirmed, cancelled
-   `category` - infaq, zakat, sosial, etc.
-   `date_from` & `date_to` - Date range filter

**Fundraisers:**

-   `status` - active, closed, archived

**Activities:**

-   `date_from` & `date_to` - Event date range

**Donation Packages:**

-   `category` - Package category

**Users (Admin only):**

-   `role` - Filter by role name

---

## 🔐 **Authentication Headers**

For protected routes, include:

```http
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

---

## 📊 **Response Status Codes**

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request (validation errors)
-   `401` - Unauthenticated
-   `403` - Forbidden (insufficient permissions)
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error

---

## 🎯 **Scenario-Based Usage**

### **Scenario 1: Public Visitor Donations**

1. `GET /programs` - View available programs
2. `GET /fundraisers` - View active fundraisers
3. `GET /donation-packages` - View donation packages
4. `POST /donations` - Make anonymous donation

### **Scenario 2: Registered User Donations**

1. `POST /login` - Login to account
2. `GET /user/donations` - View donation history
3. `POST /donations` - Make linked donation
4. `GET /user/profile` - View/update profile

### **Scenario 3: Admin Management**

1. `POST /login` - Login as admin
2. `GET /admin/dashboard` - View dashboard
3. `GET /admin/donations` - View all donations
4. `POST /admin/donations/{id}/confirm` - Verify donations
5. `POST /admin/donations/manual` - Add offline donations

### **Scenario 4: Editor/Fundraiser Manager**

1. `POST /login` - Login as editor
2. `POST /fundraisers` - Create fundraiser
3. `POST /activities` - Create activity
4. `PUT /fundraisers/{id}` - Update own fundraiser

### **Scenario 5: Manual Donation Entry**

1. Admin receives offline donation
2. `POST /admin/donations/manual` - Enter into system
3. `POST /admin/fundraisers/{id}/update-progress` - Update campaign progress

---

## 🧪 **Testing with Postman**

Import the provided `Postman_Collection.json` which includes:

-   All authentication flows
-   All CRUD operations
-   Role-based access testing
-   Error scenario testing

### **Quick Test Sequence:**

1. Login as admin → Get token
2. Create program → Test admin access
3. Login as user → Get user token
4. Try admin endpoint with user token → Should fail
5. Create donation → Test public access
6. Confirm donation as admin → Test workflow

---

## 🎉 **Features Implemented**

✅ **Complete CRUD** for all entities
✅ **Role-based authorization**
✅ **Public access** for content viewing
✅ **Anonymous donations**
✅ **Manual donation entry**
✅ **Donation verification** workflow
✅ **Fundraiser progress** tracking
✅ **Search and filtering**
✅ **Audit logging** for admin actions
✅ **Statistics and reporting**
✅ **User donation history**

This API now fully supports all scenarios (1-5) from your requirements! 🚀
