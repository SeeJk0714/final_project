<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $total_amount = $_POST['total_amount'];

    $sql = "INSERT INTO orders (`user_id`,`total_amount`) VALUES (:user_id, :total_amount)";
    $query = $database->prepare( $sql );
    $query->execute([
        'total_amount' => $total_amount,
        'user_id' => $_SESSION['user']['id']
    ]);

    $order_id = $database->lastInsertId();

    $sql = "SELECT * FROM carts WHERE user_id = :user_id AND order_id IS NULL";
    $query = $database->prepare($sql);
    $query->execute([
        'user_id' => $_SESSION['user']['id']
    ]);
    $products_in_cart = $query->fetchAll();

    foreach( $products_in_cart as $cart ) {
    $sql = "UPDATE carts SET order_id = :order_id WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'order_id' => $order_id,
        'id' => $cart['id']
    ]);
    }

    header("Location: /order-form");
    exit;

