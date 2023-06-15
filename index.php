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
    //posts
    case 'posts/add':
        require 'includes/posts/add.php';
        break;
    case 'posts/edit':
        require 'includes/posts/edit.php';
        break;
    case 'posts/delete':
        require 'includes/posts/delete.php';
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
    //orders
    case 'orders/add':
        require 'includes/orders/add.php';
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
    case 'post':
        require 'pages/post.php';
        break;
    //post
    case 'manage-posts':
        require 'pages/posts/manage-posts.php';
        break;
    case 'manage-posts-add':
        require 'pages/posts/manage-posts-add.php';
        break;
    case 'manage-posts-edit':
        require 'pages/posts/manage-posts-edit.php';
        break;
    case 'add-comment':
        require 'pages/posts/add-comment.php';
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
    case 'order-form':
        require 'pages/order&cart/order-form.php';
        break;
    default:
        require 'pages/home.php';
        break;
}