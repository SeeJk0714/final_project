<?php
function connectToDB(){
    $host = 'devkinsta_db';
    $dbname = 'Final_Project';
    $dbuser = 'root';
    $dbpassword = 'GObT0SaYlthXkrat';
    $database = new PDO (
        "mysql:host=$host;dbname=$dbname",
        $dbuser,
        $dbpassword
    );

    return $database;
}

function isUserLoggedIn() {
    return isset( $_SESSION['user'] ) ? true : false;
}


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
    return isAdmin() || isEditor() ? true : false;

}

function isEditorOrUser() {
    return isUser() || isEditor() ? true : false;
}