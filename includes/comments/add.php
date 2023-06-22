<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $comments = $_POST['comments'];
    $product_id = $_POST['product_id'];
    $user_id = $_POST['user_id'];

    if ( empty( $comments ) || empty( $product_id ) || empty( $user_id ) ) {
        $error = "Please fill out the comment";
    }
    
    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /product?id=$product_id" ); 
        exit;
    }

    $sql = "INSERT INTO comments (`comment`, `product_id`, `user_id`)
    VALUES(:comment, :product_id, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'comment' => $comments,
        'product_id' => $product_id,
        'user_id' => $user_id
    ]);

    $_SESSION["success"] = "The comment uploaded successfully.";

    header("Location: /product?id=$product_id" );
    exit;