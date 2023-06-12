<?php
    $database = connectToDB();

    $id = $_POST["id"];

    if(empty($id)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /manage-users");
        exit;
    }

    $sql = "DELETE FROM users WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "user has been deleted.";

    header("Location: /manage-users");
    exit;

