<?php

    // call db class
    $database = connectToDB();

    // get the product id
    $product_id = $_POST['product_id'];
    $amount = $_POST['amount'];
    $editor_by = $_POST['editor_by'];

    // add the product into cart table
    $sql = "INSERT INTO cart (`product_id`,`quantity`,`editor_by`,`user_id`) VALUES (:product_id, :quantity, :editor_by, :user_id)";
    $query = $database->prepare( $sql );
    $query->execute([
        'product_id' => $product_id,
        'quantity' => $amount,
        'editor_by' => $editor_by,
        'user_id' => $_SESSION['user']['id']
    ]);

    // redirect to cart page
    header("Location: /cart-form2");
    exit;
