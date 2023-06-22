<?php
     if ( !isEditorOrAdmin()) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $title = $_POST["title"];
    $price = $_POST["price"];
    $image_url = $_POST["image_url"];
    
    if(empty($title) || empty($price) || empty($image_url)){
        $error = "All fields are required";
    }

    if( isset ($error)){
        $_SESSION['error'] = $error;
        header("Location: /manage-products-add");    
        exit;
    }
    $sql = "INSERT INTO products (`title`, `price`,`image_url`, `user_id`)
    VALUES(:title, :price, :image_url, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'title' => $title,
        'price' => $price,
        'image_url' => $image_url,
        'user_id' => $_SESSION['user']['id']
    ]);

    $_SESSION["success"] = "New product has been created.";
    header("Location: /manage-products");
    exit;
    