<?php
function connectToDB(){
    $host = 'devkinsta_db';
    $dbname = 'Final_Project';
    $dbuser = 'root';
    $dbpassword = 'GObT0SaYlthXkrat';
    //connext to database (PDO - PHP database Object)
    $database = new PDO (
        "mysql:host=$host;dbname=$dbname",
        $dbuser,//username
        $dbpassword//password
    );

    return $database;
}

// function to check if the user is currently logged in or not
function isUserLoggedIn() {
    return isset( $_SESSION['user'] ) ? true : false;
}


//function to check if the user is an admin
function isAdmin(){
    if( isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'){
        return true;
    }else{
        return false;
    }
}

function isEditor() {
    if ( isset( $_SESSION['user']['role'] ) && $_SESSION['user']['role'] === 'editor' ) {
        return true;
    } else {
        return false;
    }
}

function isUser() {
    if ( isset( $_SESSION['user']['role'] ) && $_SESSION['user']['role'] === 'user' ) {
        return true;
    } else {
        return false;
    }
}

function isEditorOrAdmin() {
    // shorthand
    return isAdmin() || isEditor() ? true : false;
    // long method
    // if ( isUser() || isEditor() ) {
    //     return true;
    // } else {
    //     return false;
    // }
}

function isEditorOrUser() {
    return isUser() || isEditor() ? true : false;
}