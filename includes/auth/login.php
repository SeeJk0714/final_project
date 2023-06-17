<?php

$database = connectToDB();

$email = $_POST['email'];
$password = $_POST['password'];

if(empty($email) || empty($password)){
    $error = "All fields are required";
}else{
    $sql = "SELECT * FROM users where email = :email";
    $query = $database->prepare($sql);
    $query->execute([
        'email' => $email
    ]);
    $user = $query->fetch();

    if ( empty( $user ) ) {
        $error = "The email provided does not exists";
    } else {
        if ( password_verify( $password, $user["password"] ) ) {
            $_SESSION["user"] = $user;

            header("Location: /");
            exit;
        } else {
            $error = "The password provided is not match";
        }
    }

}
if ( isset( $error ) ) {
    $_SESSION['error'] = $error;
    header("Location: /login");
    exit;
}