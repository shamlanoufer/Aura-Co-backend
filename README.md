# ✨ Aura & Co. - Jewelry E-Commerce Backend ✨

Welcome to the backend repository for **Aura & Co.**, the modern jewelry e-commerce platform. This backend powers the secure, dynamic, and feature-rich experience of the Aura & Co. web application, handling everything from user authentication to product management and order processing.

---
![Alt text](Screenshot 2025-07-02 002122)

## 🌟 Key Features

The backend is designed to provide robust, secure, and scalable support for the frontend, with a focus on seamless integration and efficient management.

- **🔒 User Authentication & Session Management**
  - Secure registration and login for both users and admins
  - Session-based authentication with role-based access (user/admin)
  - Passwords stored using MD5 hashing (for production, consider bcrypt/argon2)

- **🛍️ Product & Inventory Management**
  - Admin panel for adding, updating, and deleting products
  - Support for multiple categories: Rings, Necklaces, Earrings, Bracelets, Accessories, Personalized Jewelry, and Featured Products
  - Image upload and management for product listings

- **🛒 Shopping Cart & Wishlist**
  - Persistent cart and wishlist for logged-in users
  - Add, update, and remove items from cart and wishlist
  - Cart and wishlist stored in the database for each user

- **📦 Order Processing**
  - Secure checkout with order summary and address collection
  - Order history and management for users and admins
  - Admin dashboard for viewing, updating, and managing all orders

- **📬 Contact & Newsletter**
  - Contact form for user inquiries, stored and managed via the admin panel
  - Newsletter subscription with email validation

- **👤 User Profile Management**
  - Users can update their profile details and profile picture
  - Admins can manage all user accounts

- **📊 Admin Dashboard**
  - Overview of orders, products, users, admins, and messages
  - Quick access to all management features

---

## 🛠️ Technologies Used

- **PHP 7+**: Core backend logic and server-side scripting
- **MySQL**: Relational database for all persistent data
- **PDO**: Secure database access with prepared statements
- **HTML5 & CSS3**: For admin and user-facing pages
- **JavaScript (ES6+)**: For interactive admin features
- **Font Awesome & Google Fonts**: For icons and typography
- **Session Management**: PHP sessions for authentication and state

---

## 🗄️ Database Structure

The backend uses a MySQL database (`aura_co_db`) with the following key tables:

- `users`: Stores user and admin accounts (`id`, `name`, `email`, `password`, `user_type`, `image`)
- `products`: Product catalog with categories, details, price, and image
- `cart`: User shopping carts, linked by `user_id`
- `wishlist`: User wishlists, linked by `user_id`
- `orders`: Order records with user, address, payment, and product summary
- `message`: Contact form submissions and user inquiries
- `newsletter`: Newsletter subscriptions

---

## 🚀 Getting Started

### Prerequisites

- **PHP 7+**
- **MySQL** (or compatible database server)
- **Web server** (e.g., Apache, Nginx, or XAMPP for local development)

### Installation

1. **Clone the repository:**
   ```sh
   git clone https://github.com/shamlanoufer/Aura-Co-backend.git
   ```
2. **Navigate to the project directory:**
   ```sh
   cd Aura-Co-backend
   ```
3. **Set up the database:**
   - Create a MySQL database named `aura_co_db`
   - Import your database schema and sample data (see `/config.php` for connection details)
4. **Configure database credentials:**
   - Edit `config.php` with your MySQL username and password

5. **Run the backend:**
   - Place the project in your web server's root directory (e.g., `htdocs` for XAMPP)
   - Access via `http://localhost/Aura-Co-backend/login.php` in your browser

---

## 📁 Project Structure

- `config.php` – Database connection and configuration
- `login.php`, `register.php`, `logout.php` – Authentication endpoints
- `admin_*.php` – Admin dashboard, product, order, user, and message management
- `shop.php`, `cart.php`, `checkout.php`, `wishlist.php` – User shopping experience
- `user_profile_update.php` – User profile management
- `contact.php`, `about.php` – Informational and contact pages
- `css/`, `js/`, `Images/`, `Logo/` – Static assets

---

## 🙏 Acknowledgements

- **Fonts:** [Google Fonts](https://fonts.google.com/)
- **Icons:** [Font Awesome](https://fontawesome.com/)
- **Images:** All images are placeholders and are credited to their respective owners.

---

<p align="center">
  <em>Thank you for supporting Aura & Co.!</em>
</p>

---

#myAura
