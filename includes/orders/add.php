<?php
if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

// get all the POST data
$product_id = $_POST['product_id'];
$editor_by = $_POST['editor_by'];
$price = $_POST['price'];
$amount =$_POST['amount'];
$total = 0;

// do error checking
if ( empty( $product_id ) || empty( $amount )) {
    $error = "ERROR";
}

if( isset ($error)){
    $_SESSION['error'] = $error;
    header("Location: /product?id=$id" ); 
    exit;
}

$sql = "INSERT INTO carts (`product_id`,`quantity`,`editor_by`,`user_id`) VALUES (:product_id, :quantity, :editor_by, :user_id)";
$query = $database->prepare( $sql );
$query->execute([
    'product_id' => $product_id,
    'quantity' => $amount,
    'editor_by' => $editor_by,
    'user_id' => $_SESSION['user']['id']
]);

$total = $price * $amount;

$sql = "INSERT INTO orders (`user_id`, `total_amount`)
VALUES(:user_id, :total_amount)";
$query = $database->prepare( $sql );
$query->execute([
    'user_id' => $_SESSION['user']['id'],
    'total_amount' => $total

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

$_SESSION["success"] = "The product has been added to the order.";
header("Location: /order-form" );
exit;
