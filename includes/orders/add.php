<?php
if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

// get all the POST data
$id = $_POST['id'];
$user_id = $_POST['user_id'];
$product_title = $_POST['product_title'];
$product_price = $_POST['product_price'];
$editor_by = $_POST['editor_by'];
$product_image_url =$_POST['product_image_url'];


// do error checking
if ( empty( $id ) || empty( $editor_by ) || empty($user_id ) || empty($product_title) || empty($product_price) || empty($product_image_url)) {
    $error = "Please fill out the comment";
}

if( isset ($error)){
    $_SESSION['error'] = $error;
    header("Location: /product?id=$id" ); 
    exit;
}

// insert the comment into database
$sql = "INSERT INTO orders (`title`, `price`, `product_id`, `user_id`, `editor_by`,`image_url`)
VALUES(:title, :price, :product_id, :user_id, :editor_by, :image_url)";
$query = $database->prepare( $sql );
$query->execute([
    'title' => $product_title,
    'price' => $product_price,
    'product_id' => $id,
    'user_id' => $user_id,
    'editor_by' => $editor_by,
    'image_url' => $product_image_url
]);

$_SESSION["success"] = "The product has been added to the order.";
header("Location: /order-form" );
exit;
