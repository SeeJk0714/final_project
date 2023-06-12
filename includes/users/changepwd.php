<?php

    $database = connectToDB();

    $password = $_POST['password'];
    $confirm_password = $_POST["confirm_password"];
    $id  = $_POST['id'];

    if(empty($password) || empty($confirm_password) || empty($id)){
        $error = "Make sure all the fields are filled.";
    } else if ( $password !== $confirm_password ) {
        
        $error = 'The password is not match.';
    }else if ( strlen( $password ) < 8 ) {
        $error = "Your password must be at least 8 characters";
    }

    if ( isset( $error ) ) {
        $_SESSION['error'] = $error;
        header("Location: /manage-users-changepwd?id=$id");
        exit;
    }

    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'id' => $id
    ]);


    $_SESSION["success"] = "Password has been changed.";

    header("Location: /manage-users");
    exit;