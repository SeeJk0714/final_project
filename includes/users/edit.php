<?php

$database = connectToDB();

$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];
$id = $_POST['id'];

if(empty($name) || empty($email) || empty($role) || empty($id)){
    $error = "Please enter fields";
}else{
    $sql = "SELECT * FROM users WHERE email = :email AND id != :id";
    $query = $database->prepare($sql);
    $query->execute([
        'email' => $email,
        'id' => $id
    ]);
    $user = $query->fetch();

    if ($user){
        $error = "The email provided does not exists";
    }
}
if(isset($error)){
    $_SESSION['error'] = $error;
    header("Location: /manage-users-edit?id=$id");
    exit;
}

$sql = "UPDATE users set name = :name,email = :email,role = :role WHERE id = :id";
$query = $database->prepare($sql);
$query->execute([
    'name' => $name,
    'email' => $email,
    'role' => $role,
    'id' => $id
]);

header("Location: /manage-users");
exit;