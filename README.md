# Gopal Ring Center 🏺

A modern e-commerce platform for premium cement and clay products, built with PHP, MySQL, and Tailwind CSS.

## 📋 About Gopal Ring Center

**Gopal Ring Center** has been a trusted manufacturer of premium cement and clay products for over 20 years. We specialize in creating high-quality, durable, and aesthetically appealing products for homes, gardens, and businesses.

### Our Products Include:
- 🏺 Plant Pots & Planters
- 💍 Decorative Rings & Frames
- 🌀 Ventilation Products
- 🚧 Railings & Fencing
- 🔧 Home Pipes & Fixtures
- 🕉️ Tulsi Stands & Garden Decor

## ✨ Features

### User Features
- **User Registration & Authentication** - Secure login and registration system
- **Product Catalog** - Browse premium cement and clay products with detailed descriptions
- **Shopping Cart** - Add products to cart and manage quantities
- **Order Management** - Place orders and track order history
- **User Dashboard** - View account information and recent orders
- **Bilingual Support** - English and Nepali language options
- **Responsive Design** - Mobile-friendly interface

### Admin Features
- **Admin Dashboard** - Manage products, customers, and orders
- **Product Management** - Add, edit, and manage product inventory
- **Order Management** - View orders and update order statuses
- **Billing & Invoice Printing** - Generate bilingual (English/Nepali) printable bills for orders
- **Offline Billing** - Create custom bills for walk-in customers with manual items
- **Customer Management** - View customer details and history

## 🛠️ Tech Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, Tailwind CSS 3.0
- **Icons:** Font Awesome 6.4.0
- **Server:** Apache (XAMPP/LAMPP)

## 📦 Installation

### Prerequisites
- XAMPP/LAMPP installed and running
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Browser

### Setup Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/DhirajBro1/Gopal_Ring_Center.git
   cd Gopal_Ring_Center
   ```

2. **Database Setup**
   - Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
   - Create a new database named `gopal_ring_center`
   - Import the database schema (if available) or create tables manually

3. **Database Tables**
   
   **Users Table:**
   ```sql
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL,
       password VARCHAR(255) NOT NULL,
       phone VARCHAR(20),
       address TEXT,
       role ENUM('user', 'admin') DEFAULT 'user',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

   **Products Table:**
   ```sql
   CREATE TABLE products (
       id INT PRIMARY KEY AUTO_INCREMENT,
       name VARCHAR(255) NOT NULL,
       description TEXT,
       price DECIMAL(10, 2) NOT NULL,
       stock INT DEFAULT 0,
       image VARCHAR(255),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

   **Orders Table:**
   ```sql
   CREATE TABLE orders (
       id INT PRIMARY KEY AUTO_INCREMENT,
       user_id INT NOT NULL,
       total_amount DECIMAL(10, 2) NOT NULL,
       status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY(user_id) REFERENCES users(id)
   );
   ```

   **Order Items Table:**
   ```sql
   CREATE TABLE order_items (
       id INT PRIMARY KEY AUTO_INCREMENT,
       order_id INT NOT NULL,
       product_id INT NOT NULL,
       quantity INT NOT NULL,
       price DECIMAL(10, 2) NOT NULL,
       FOREIGN KEY(order_id) REFERENCES orders(id),
       FOREIGN KEY(product_id) REFERENCES products(id)
   );
   ```

4. **Configure Database Connection**
   - Edit `includes/db.php`
   - Update database credentials (localhost, username, password, database name)

5. **Set Folder Permissions**
   ```bash
   chmod -R 777 public/images/
   ```

6. **Start the Server**
   - Start Apache and MySQL from XAMPP/LAMPP
   - Navigate to `http://localhost/gopal_ring_center/Gopal_Ring_Center/`

## 📁 Project Structure

```
Gopal_Ring_Center/
├── index.php                 # Main entry point
├── .htaccess                 # URL rewriting
├── README.md                 # This file
├── admin/                   # Admin panel
|   ├── bill.php             #Online Bill
|   ├── offline-bill-create.php #Offline bill
|   ├── offline-bill.php        #offline bill
│   ├── dashboard.php        # Admin dashboard
│   ├── products.php         # Manage products
│   ├── customers.php        # Manage customers
│   ├── orders.php           # Manage orders
│   ├── login.php            # Admin login
│   └── logout.php           # Admin logout
├── user/                    # User interface
│   ├── landing.php          # Homepage
│   ├── products.php         # Product listing
│   ├── cart.php             # Shopping cart
│   ├── dashboard.php        # User dashboard
│   ├── login.php            # User login
│   ├── register.php         # User registration
│   └── logout.php           # User logout
├── includes/
│   └── db.php               # Database connection
├── public/
│   └── images/              # Product images
└── errors/
    └── 404.php              # 404 error page
```

## 🚀 Usage

### For Customers
1. Register a new account at `/user/register`
2. Login with your credentials
3. Browse products in the product catalog
4. Add items to your cart
5. Proceed to checkout
6. Place your order
7. View order history in your dashboard

### For Admins
1. Login at `/admin/login` with admin credentials
2. Access the admin dashboard
3. Manage products (add, edit, delete)
4. View customer information
5. Process orders and update statuses

## 💳 Payment Information

**Important Note:** 
Currently, no online payment is required. Orders are placed and processed after our team contacts you at the phone number you provided during registration.

## 📞 Contact Information

- **Phone:** +977-981-7319154
- **Email:** info@gopalringcenter.com
- **Location:** Kathmandu, Nepal

## 🔐 Security Features

- Password hashing using bcrypt (PASSWORD_BCRYPT)
- SQL injection prevention with mysqli prepared statements
- Session-based authentication
- Input validation and sanitization

## 📝 License

This project is proprietary and owned by Gopal Ring Center.

## 🤝 Contributing

This is a private project. For contributions, please contact the project owner.

## 📈 Future Enhancements

- Online payment gateway integration (Stripe, eSeewa, Khalti)
- Email notifications for orders
- Product reviews and ratings
- Advanced search and filtering
- Wishlist functionality
- SMS notifications
- Multi-language support expansion
- Mobile app development

## 🐛 Known Issues

None at the moment. Please report any issues you find.

## 👨‍💻 Developer

**Developed for:** Gopal Ring Center  
**Project Type:** E-commerce Platform  
**Last Updated:** February 2026

---

**Made with Dhiroj Kumar Pandit for Gopal Ring Center**
