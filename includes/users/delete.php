<?php
    if ( !isUserLoggedIn() ) {
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
        header("Location: /manage-users");
        exit;
    }

    $sql = "DELETE FROM users WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "The user has been deleted.";

    if(isAdmin()){
        header("Location: /manage-users");
        exit;
    }else{
        header("Location: /logout");
        exit;
    }
        

