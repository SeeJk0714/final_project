<?php
     // make sure the user is logged in
     if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $title = $_POST["title"];
    $content = $_POST["content"];
    $image_url = $_POST["image_url"];
    
    if(empty($title) || empty($content) || empty($image_url)){
        $error = "All fields are required";
    }

    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /manage-posts-add");    
        exit;
    }
    $sql = "INSERT INTO posts (`title`, `content`,`image_url`, `user_id`)
    VALUES(:title, :content, :image_url, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'title' => $title,
        'content' => $content,
        'image_url' => $image_url,
        'user_id' => $_SESSION['user']['id']
    ]);

    $_SESSION["success"] = "New post has been created.";
    header("Location: /manage-posts");
    exit;
    