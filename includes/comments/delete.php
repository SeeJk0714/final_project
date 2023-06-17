<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }
    
    $database = connectToDB();

    $product_id = $_POST["product_id"];
    $id = $_POST["id"];

    if(empty($product_id || $user_id)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /product?id=$product_id");
        exit;
    }

    $sql = "DELETE FROM comments WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "The comment has been deleted.";

    header("Location: /product?id=$product_id");
    exit;

