<?php

    // make sure the user is logged in
    if ( !isUserLoggedIn() ) {
        header("Location: /");
        exit;
    }

    // load data from database
    $database = connectToDB();

    if ( isAdmin() || isEditor() ){
        // * means get all the columns from the selected table
        $sql = "SELECT 
            products.*, 
            users.name AS user_name,
            users.email AS user_email 
            FROM products 
            JOIN users 
            ON products.user_id = users.id";
        $query = $database->prepare($sql);
        $query->execute();
    } else {
        $sql = "SELECT 
        products.id, 
        products.title, 
        products.status, 
        users.name AS user_name,
        users.email AS user_email  
        FROM products 
        JOIN users 
        ON products.user_id = users.id 
        where products.user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute(
        [
            'user_id' => $_SESSION["user"]["id"]
        ]
        );
    }

    // fetch the data from query
    $products = $query->fetchAll();

    require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Manage products</h1>
            <div class="text-end">
                <a href="/manage-products-add" class="btn btn-primary btn-sm"
                >Add New Post</a
                >
            </div>
        </div>
        <div class="card mb-2 p-4">
            <table class="table">
                <thead>
                <?php require "parts/message_success.php"; ?>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col" style="width: 40%;">Title</th>
                    <th scope="col">Comments Num</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Status</th>
                    <th scope="col" style="width: 25%;" class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product) { ?>
                    <tr>
                        <th scope="row"><?= $product['id']; ?></th>
                        <td><?= $product['title']; ?></td>
                        <td>
                            <?php
                                // load the comments from database
                                $sql = "SELECT
                                    comments.*,
                                    users.name
                                    FROM comments
                                    JOIN users
                                    ON comments.user_id = users.id
                                    WHERE product_id = :product_id";
                                $query = $database->prepare($sql);
                                $query->execute([
                                    "product_id" => $product["id"]
                                ]);

                                $comments = $query->fetchAll();
                                // foreach ($comments as $comment) {
                                //     echo $comment['comments']. '<br />';
                                // }
                                echo count( $comments );
                            ?>
                        </td>
                        <td><?= $product['user_name']; ?> <?= $product['user_email']; ?></td>
                        <td>
                <span class="
                <?php
                if($product["status"] == "pending"){
                    echo "badge bg-warning";
                } else if($product['status'] == "publish"){
                    echo "badge bg-success";
                }
                ?>"><?= $product['status']; ?></span>
                        </td>
                        <td class="text-end">
                            <div class="buttons">
                                <a
                                    href="/product?id=<?= $product['id']; ?>"
                                    target="_blank"
                                    class="btn btn-primary btn-sm me-2 <?= $product['status'] === 'pending' ? 'disabled' : ''?>"
                                ><i class="bi bi-eye"></i
                                    ></a>
                                <a
                                        href="/manage-products-edit?id=<?= $product['id']; ?>"
                                        class="btn btn-secondary btn-sm me-2"
                                ><i class="bi bi-pencil"></i
                                    ></a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $product['id']; ?>">
                                    <i class="bi bi-trash"></i
                                    >
                                </button>
                                <div class="modal fade" id="delete-modal-<?= $product['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user: <?= $product['title']; ?>?</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body me-auto">
                                                Are you sure you want to <?= $product['title']; ?>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <form method= "POST" action="/products/delete">
                                                    <input type="hidden" name="id" value= "<?= $product['id']; ?>" />
                                                    <button type="submit" class="btn btn-danger">Yes, I am sure.</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="/dashboard" class="btn btn-link btn-sm"
            ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
            >
        </div>
    </div>
<?php
    require "parts/footer.php";
