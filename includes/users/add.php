<?php
     // check if the current user is an admin or not
    if(! isAdmin()){
    // if current user is not an admin, redirect to dashboard
      header("Location: /dashboard");
      exit;
    }

    $database = connectToDB();

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = $_POST["role"];
        
    $sql = "SELECT * FROM users WHERE email = :email";
    $query = $database->prepare($sql);
    $query->execute([
        'email'=>$email
    ]);
    $user = $query->fetch();

    if ( empty( $name ) || empty($email) || empty($password) || empty($confirm_password) || empty($role)  ) {
        $error = 'All fields are required';
    } else if ( $password !== $confirm_password ) {
        $error = 'The password is not match.';
    } else if ( strlen( $password ) < 8 ) {
        $error = "Your password must be at least 8 characters";
    } else if ( $user ) {
        $error = "The email you inserted has already been used by another user. Please insert another email.";
    }

    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /manage-users-add");    
    } else {
        $sql = "INSERT INTO users (`name`, `email`, `password`,`role` )
        VALUES(:name, :email, :password, :role)";
        $query = $database->prepare( $sql );
        $query->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash( $password, PASSWORD_DEFAULT),
            'role' => $role
        ]);

        $_SESSION["success"] = "New user has been created.";
        $_SESSION['new_user_email'] = $email;
        header("Location: /manage-users");
        exit;
    }