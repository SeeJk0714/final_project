<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }    

    $database = connectToDB();

    $id = $_POST["cart_id"];

    if(empty($id)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /cart-form");
        exit;
    }

    $sql = "DELETE FROM carts WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "The product has been deleted.";

    header("Location: /cart-form");
    exit;
