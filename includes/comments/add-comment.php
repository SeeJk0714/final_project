<?php
     // make sure the user is logged in
     if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $comment = $_POST["comment"];
    
    if(empty($comment)){
        $error = "All fields are required";
    }

    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /add-comment");    
        exit;
    }
    $sql = "INSERT INTO comments (`comment`)
    VALUES(:comment)";
    $query = $database->prepare( $sql );
    $query->execute([
        'comment' => $comment
    ]);

    $_SESSION["success"] = "New comment has been created.";
    header("Location: /");
    exit;
    