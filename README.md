### 🧑‍🍳 Public Restaurant Services
- Display dynamic pages such as *About Us* and *Contact Us*
- List food categories and menu items
- Manage products (meals) including:
  - Images
  - Start and end dates for promotions
  - Regular and discount prices
- Provide a “favorites” list (wishlist) for users
- Filter, search, and paginate menu items

---

### 🛠️ Admin Panel (Backend Services)
- Full CRUD operations for menu items and categories
- Manage users and roles (Admin, Customer, Staff)
- Add and manage discount codes and offers
- View and manage orders and reservations
- Generate reports and visual charts for restaurant analytics
- Manage static pages such as “About Us” and “Contact Us”

---

### 🔐 Authentication & Authorization
- User login and registration using **OTP (SMS code)** or email/password
- Role-based access control (Admin, User)
- User profile management:
  - Personal info
  - Addresses
  - Reservations
  - Transactions
  - Favorites list

---

### 💳 Payment Integration
- Integration with payment gateways (e.g., **Zibal**, **Stripe**, etc.)
- Create and verify transactions
- Automatically update order/reservation status after successful payment

---
#Test
-Feature 
_Unit
--------
### 🧩 API Response Structure
All responses follow a consistent format:
```json
{
  "status": "success",
  "data": { ... },
  "message": "Operation successful"
}
