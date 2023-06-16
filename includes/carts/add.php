<?php
if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

// get all the POST data
$id = $_POST['id'];
$user_id = $_POST['user_id'];
$post_title = $_POST['post_title'];
$post_content = $_POST['post_content'];
$editor_by = $_POST['editor_by'];
$post_image_url =$_POST['post_image_url'];


// do error checking
if ( empty( $id ) || empty( $editor_by ) || empty($user_id ) || empty($post_title) || empty($post_content) || empty($post_image_url)) {
    $error = "Please fill out the comment";
}

if( isset ($error)){
    $_SESSION['error'] = $error;
    header("Location: /post?id=$id" ); 
    exit;
}

// insert the comment into database
$sql = "INSERT INTO carts (`title`, `price`, `post_id`, `user_id`, `editor_by`,`image_url`)
VALUES(:title, :price, :post_id, :user_id, :editor_by, :image_url)";
$query = $database->prepare( $sql );
$query->execute([
    'title' => $post_title,
    'price' => $post_content,
    'post_id' => $id,
    'user_id' => $user_id,
    'editor_by' => $editor_by,
    'image_url' => $post_image_url
    
    
]);

$_SESSION["success"] = "The product has been add to your cart.";

header("Location: /cart-form" );
exit;
