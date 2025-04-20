# 🛒 Wishlist API

A clean, token-authenticated Laravel API for managing user wishlists in an e-commerce application.

---

## 🚀 Features

- User registration & login via Sanctum (token-based)
- Add, view, and remove products from a wishlist
- Structured JSON API responses
- Token expiration logic and refresh code
- Feature and unit testing support

---

## ⚙️ Setup Instructions

---

## ⚙️ Postman Link
https://www.postman.com/softdot-team/workspace/wishlist-api/collection/236861-ac74f1e6-7aba-4976-8419-cc16e43d6a0c?action=share&creator=236861


1. **Clone the repository**

```bash
git clone <your-repo-url>
cd wishlist-api
copy .env.example .env
Update DB credentials in the .env
Run 'composer install'
Run 'php artisan migrate:fresh --seed'
For test: "php artisan test"
