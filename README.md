# A.Rehman Login System

A modern, secure, and fully functional PHP login system featuring a glassmorphism UI, secure session management, CSRF protection, and password reset workflows.

## Tech Stack
- **Backend:** PHP 8+
- **Database:** MySQL 5.7+ / MariaDB
- **Frontend:** HTML5, CSS3 (Glassmorphism), Vanilla JavaScript
- **Server:** XAMPP (Apache/Nginx)

## Features
- **Secure Authentication:** Uses `password_hash()` (Bcrypt) and prepared statements (PDO).
- **CSRF Protection:** All POST forms are protected with unique session-based tokens.
- **Rate Limiting:** Prevents brute-force attacks by tracking login attempts per email.
- **Password Reset:** Secure, expiring, single-use tokens with SHA-256 hashing.
- **Modern UI:** Dark theme with animated gradients, glassmorphism cards, and responsive design.
- **Session Management:** Secure session cookies, regeneration on login, and proper logout handling.

## Installation Instructions

### 1. Prerequisites
- XAMPP installed and running (Apache and MySQL).
- PHP 8.0 or higher.

### 2. Setup Database
1. Open phpMyAdmin (http://localhost/phpmyadmin).
2. Click on the **Import** tab.
3. Choose the file `sql/schema.sql`.
4. Click **Go**. This will create the `a_rehman_login` database and required tables.

### 3. Configure Database Credentials
Open `config/config.php`. If your MySQL root password is not empty, update the `DB_PASS` constant:

```php
define('DB_PASS', 'your_mysql_password');