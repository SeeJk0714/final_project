<?php

    // make sure the user is logged in
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }


    $database = connectToDB();

    // get all the POST data
    $comments = $_POST['comments'];
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // do error checking
    if ( empty( $comments ) || empty( $post_id ) || empty( $user_id ) ) {
        $error = "Please fill out the comment";
    }
    
    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /post?id=$post_id" ); 
        exit;
    }

    // insert the comment into database
    $sql = "INSERT INTO comments (`comment`, `post_id`, `user_id`)
    VALUES(:comment, :post_id, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'comment' => $comments,
        'post_id' => $post_id,
        'user_id' => $user_id
    ]);

    header("Location: /post?id=$post_id" );
    exit;