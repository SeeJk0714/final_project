<?php
// make sure the user is logged in
if ( !isEditorOrAdmin() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

$title = $_POST["title"];
$price = $_POST["price"];
$image_url = $_POST["image_url"];
$status = $_POST["status"];
$id =$_POST["id"];

if(empty($title) || empty($price) || empty($image_url) || empty($status) || empty($id)){
    $error = "All fields is requrired";
}

if(isset($error)){
    $_SESSION['error'] = $error;
    header("Location: /manage-products-edit?id=$id");
    exit;
}

$sql = "UPDATE products set title = :title, price = :price, image_url = :image_url, modified_by = :modified_by, status = :status WHERE id = :id";
$query = $database->prepare($sql);
$query->execute([
    'title' => $title,
    'price' => $price,
    'image_url' => $image_url,
    'status' => $status,
    'id' => $id,
    'modified_by' => $_SESSION['user']['id']
]);

$_SESSION["success"] = "The product has been edited.";

header("Location: /manage-products");
exit;