<?php
$route = $_GET['route'] ?? '/';
switch($route) {
    case 'user/cart':
        include 'user/cart.php';
        break;
    case 'user/products':
        include 'user/products.php';
        break;
    case 'user/login':
        include 'user/login.php';
        break;
    case 'user/register':
        include 'user/register.php';
        break;
    case 'user/logout':
        include 'user/logout.php';
        break;
    case 'user/dashboard':
        include 'user/dashboard.php';
        break;
    case 'admin':
        include 'admin/login.php';
        break;
    case 'admin/dashboard':
        include 'admin/dashboard.php';
        break;
    case 'admin/customers':
        include 'admin/customers.php';
        break;
    case 'admin/products':
        include 'admin/products.php';
        break;
    case 'admin/orders':
        include 'admin/orders.php';
        break;
    case 'admin/bill':
        include 'admin/bill.php';
        break;
    case 'admin/offline-bill-create':
        include 'admin/offline-bill-create.php';
        break;
    case 'admin/logout':
        include 'admin/logout.php';
        break;
    case 'admin/login':
        include 'admin/login.php';
        break;
    case '/':
        include 'user/landing.php';
        break;
    default:
        // Show 404 page
        http_response_code(404);
        include 'errors/404.php'; 
        break;
}
