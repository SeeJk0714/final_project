<?php
// make sure the user is logged in
if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

$title = $_POST["title"];
$content = $_POST["content"];
$image_url = $_POST["image_url"];
$status = $_POST["status"];
$id =$_POST["id"];

if(empty($title) || empty($content) || empty($image_url) || empty($status) || empty($id)){
    $error = "All fields is requrired";
}

if(isset($error)){
    $_SESSION['error'] = $error;
    header("Location: /manage-posts-edit?id=$id");
    exit;
}

$sql = "UPDATE posts set title = :title, content = :content, image_url = :image_url, modified_by = :modified_by, status = :status WHERE id = :id";
$query = $database->prepare($sql);
$query->execute([
    'title' => $title,
    'content' => $content,
    'image_url' => $image_url,
    'status' => $status,
    'id' => $id,
    'modified_by' => $_SESSION['user']['id']
]);

$_SESSION["success"] = "Post has been edited.";

header("Location: /manage-posts");
exit;