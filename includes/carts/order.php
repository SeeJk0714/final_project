<?php
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }    

    $database = connectToDB();

    $id = $_POST["id"];
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $cart_title = $_POST['cart_title'];
    $cart_price = $_POST['cart_price'];
    $editor_by = $_POST['editor_by'];
    $cart_image_url =$_POST['cart_image_url'];

    if(empty($id) || empty($post_id) || empty($user_id ) || empty($cart_title) || empty($cart_price) || empty( $editor_by )|| empty($cart_image_url)){
        $error = "ERROR!";
    }

    if(isset($error)){
        $_SESSION['error'] = $error;
        header("Location: /cart-form");
        exit;
    }
    
    $sql = "INSERT INTO orders (`title`, `price`, `post_id`, `user_id`, `editor_by`,`image_url`)
    VALUES(:title, :price, :post_id, :user_id, :editor_by, :image_url)";
    $query = $database->prepare( $sql );
    $query->execute([
        'title' => $cart_title,
        'price' => $cart_price,
        'post_id' => $post_id,
        'user_id' => $user_id,
        'editor_by' => $editor_by,
        'image_url' => $cart_image_url
        
        
    ]);

    $sql = "DELETE FROM carts WHERE id = :id";
    $query = $database->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $_SESSION["success"] = "The Product Purchase Success.";

    header("Location: /order-form");
    exit;