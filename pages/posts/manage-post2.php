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
            posts.*, 
            users.name AS user_name,
            users.email AS user_email 
            FROM posts 
            JOIN users 
            ON posts.user_id = users.id";
        $query = $database->prepare($sql);
        $query->execute();
    } else {
        $sql = "SELECT 
        posts.id, 
        posts.title, 
        posts.status, 
        users.name AS user_name,
        users.email AS user_email  
        FROM posts 
        JOIN users 
        ON posts.user_id = users.id 
        where posts.user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute(
        [
            'user_id' => $_SESSION["user"]["id"]
        ]
        );
    }

    // fetch the data from query
    $posts = $query->fetchAll();

    require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h1 class="h1">Manage Posts</h1>
            <div class="text-end">
                <a href="/manage-posts-add" class="btn btn-primary btn-sm"
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
                <?php foreach ($posts as $post) { ?>
                    <tr>
                        <th scope="row"><?= $post['id']; ?></th>
                        <td><?= $post['title']; ?></td>
                        <td>
                            <?php
                                // load the comments from database
                                $sql = "SELECT
                                    comments.*,
                                    users.name
                                    FROM comments
                                    JOIN users
                                    ON comments.user_id = users.id
                                    WHERE post_id = :post_id";
                                $query = $database->prepare($sql);
                                $query->execute([
                                    "post_id" => $post["id"]
                                ]);

                                $comments = $query->fetchAll();
                                // foreach ($comments as $comment) {
                                //     echo $comment['comments']. '<br />';
                                // }
                                echo count( $comments );
                            ?>
                        </td>
                        <td><?= $post['user_name']; ?> <?= $post['user_email']; ?></td>
                        <td>
                <span class="
                <?php
                if($post["status"] == "pending"){
                    echo "badge bg-warning";
                } else if($post['status'] == "publish"){
                    echo "badge bg-success";
                }
                ?>"><?= $post['status']; ?></span>
                        </td>
                        <td class="text-end">
                            <div class="buttons">
                                <a
                                    href="/post?id=<?= $post['id']; ?>"
                                    target="_blank"
                                    class="btn btn-primary btn-sm me-2 <?= $post['status'] === 'pending' ? 'disabled' : ''?>"
                                ><i class="bi bi-eye"></i
                                    ></a>
                                <a
                                        href="/manage-posts-edit?id=<?= $post['id']; ?>"
                                        class="btn btn-secondary btn-sm me-2"
                                ><i class="bi bi-pencil"></i
                                    ></a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $post['id']; ?>">
                                    <i class="bi bi-trash"></i
                                    >
                                </button>
                                <div class="modal fade" id="delete-modal-<?= $post['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user: <?= $post['title']; ?>?</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body me-auto">
                                                Are you sure you want to <?= $post['title']; ?>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <form method= "POST" action="/posts/delete">
                                                    <input type="hidden" name="id" value= "<?= $post['id']; ?>" />
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
