<?php
    $database = connectToDB();

    $post_id = $_POST["post_id"];
    $id = $_POST["id"];

    if(empty($post_id || $user_id)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /post?id=$post_id");
        exit;
    }

    $sql = "DELETE FROM comments WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "user has been deleted.";

    header("Location: /post?id=$post_id");
    exit;

