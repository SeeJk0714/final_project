<?php
session_start();

require "includes/functions.php";

$path = parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH);
$path = trim($path,'/');

switch($path){
    // auth
    case 'auth/login':
        require 'includes/auth/login.php';
        break;
    case 'auth/signup':
        require 'includes/auth/signup.php';
        break;
    // users
    case 'users/add':
        require 'includes/users/add.php';
        break;  
    case 'users/edit':
        require 'includes/users/edit.php';
        break;  
    case 'users/delete':
        require 'includes/users/delete.php';
        break;
    case 'users/changepwd':
        require 'includes/users/changepwd.php';
        break;
    //products
    case 'products/add':
        require 'includes/products/add.php';
        break;
    case 'products/edit':
        require 'includes/products/edit.php';
        break;
    case 'products/delete':
        require 'includes/products/delete.php';
        break;
    case 'comments/add':
        require 'includes/comments/add.php';
        break;
    case 'comments/delete':
        require 'includes/comments/delete.php';
        break;  
    //carts
    case 'carts/add':
        require 'includes/carts/add.php';
        break;
    case 'carts/delete':
        require 'includes/carts/delete.php';
        break;
    case 'carts/add2':
        require 'includes/carts/add2.php';
        break;
    case 'carts/checkout':
        require 'includes/carts/checkout.php';
        break;
    case 'carts/order':
        require 'includes/carts/order.php';
        break;
    //orders
    case 'orders/add':
        require 'includes/orders/add.php';
        break;
    case 'orders/delete':
        require 'includes/orders/delete.php';
        break;
    case 'orders/delete-product':
        require 'includes/orders/delete-product.php';
        break;
    //pages
    case 'dashboard':
        require 'pages/dashboard.php';
        break;
    case 'login':
        require 'pages/login.php';
        break;
    case 'signup':
        require 'pages/signup.php';
        break;
    case 'logout':
        require 'pages/logout.php';
        break;
    case 'product':
        require 'pages/product.php';
        break;
    //product
    case 'manage-products':
        require 'pages/products/manage-products.php';
        break;
    case 'manage-products-add':
        require 'pages/products/manage-products-add.php';
        break;
    case 'manage-products-edit':
        require 'pages/products/manage-products-edit.php';
        break;
    case 'add-comment':
        require 'pages/products/add-comment.php';
        break;
    //user
    case 'manage-users':
        require 'pages/users/manage-users.php';
        break;
    case 'manage-users-add':
        require 'pages/users/manage-users-add.php';
        break;
    case 'manage-users-edit':
        require 'pages/users/manage-users-edit.php';
        break;
    case 'manage-users-changepwd':
        require 'pages/users/manage-users-changepwd.php';
        break;
    //order
    case 'cart-form':
        require 'pages/order&cart/cart-form.php';
        break;
    case 'cart-form2':
        require 'pages/order&cart/cart-form2.php';
        break;
    case 'order-form':
        require 'pages/order&cart/order-form.php';
        break;
    case 'order-form2':
        require 'pages/order&cart/order-form2.php';
        break;
    default:
        require 'pages/home.php';
        break;
}