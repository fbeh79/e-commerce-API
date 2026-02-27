# E-Commerce API

A complete e-commerce web service with an admin panel and advanced features for managing products, orders, users, and payments. Built with Laravel, this project includes OTP authentication, authorization, Unit & Feature testing, and API documentation.

---

## 🎯 Features

### User
- OTP Authentication (SMS code login)
- Role-based Access Control (Authorization)
- User Profile: personal information, addresses, orders, transactions, favorites
- View products and categories
- Add products to favorites
- Apply discount codes and make online payments

### Shop
- Product management including images, price, discount, and sale period
- Dynamic menu and slider management
- Static pages: About Us, Contact Us, Footer
- Product search and filter

### Admin Panel
- Full management of products and categories
- Order and transaction management
- Discount code management
- View reports and sales statistics

---

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/fbeh79/e-commerce-API.git
cd e-commerce-API

# Install dependencies
composer install

# Setup environment file
cp .env.example .env
php artisan key:generate

# Migrate database and seed
php artisan migrate --seed

# Run local server
php artisan serve
