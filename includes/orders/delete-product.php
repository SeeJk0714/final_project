<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }    

    $database = connectToDB();

    $id = $_POST["cart_id"];
    $price = $_POST["price"];
    $total_amount = $_POST["total_amount"];
    $order_id = $_POST["order_id"];
    $total = 0;

    if(empty($id) || empty($price)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /order-form");
        exit;
    }

    $sql = "DELETE FROM carts WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $total = $total_amount - $price;

    $sql = "UPDATE orders SET total_amount = :total_amount WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'total_amount' => $total,
        'id' => $order_id
    ]);

    $_SESSION["success"] = "The product has been deleted.";

    header("Location: /order-form");
    exit;
