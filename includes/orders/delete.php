<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }
    
    $database = connectToDB();

    $id = $_POST["id"];

    if(empty($id)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /order-form");
        exit;
    }

    $sql = "DELETE FROM orders WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "The product has been cancel.";

    header("Location: /order-form");
    exit;
