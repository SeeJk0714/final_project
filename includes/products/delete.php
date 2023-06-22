<?php
if ( !isEditorOrAdmin() ) {
    header("Location: /");
    exit;
}

$database = connectToDB();

$id = $_POST["id"];

if(empty($id)){
    $error = "ERROR!";
}

if(isset($error)){
    $_SESSION['error'] = $error;
    header("Location: /manage-products");
    exit;
}

$sql = "DELETE FROM products WHERE id = :id";
$query = $database->prepare($sql);
$query->execute([
    'id' => $id
]);

$_SESSION["success"] = "The product has been deleted.";

header("Location: /manage-products");
exit;
