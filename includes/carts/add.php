<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    $database = connectToDB();

    $product_id = $_POST['product_id'];
    $amount = $_POST['amount'];
    $editor_by = $_POST['editor_by'];

    $sql = "INSERT INTO carts (`product_id`,`quantity`,`editor_by`,`user_id`) VALUES (:product_id, :quantity, :editor_by, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'product_id' => $product_id,
        'quantity' => $amount,
        'editor_by' => $editor_by,
        'user_id' => $_SESSION['user']['id']
    ]);

    header("Location: /cart-form");
    exit;