<?php

    // make sure there is an id query string in the url
    if ( isset( $_GET['id'] ) ) {

        $database = connectToDB();

        // make sure the product is published
        $sql = "SELECT * FROM products WHERE id = :id AND status = 'publish'";
        $query = $database->prepare( $sql );
        $query->execute([
            'id' => $_GET['id']
        ]);

        // fetch
        $product = $query->fetch();

        if ( !$product ) {
            // if product don't exists, then we redirect back to home
            header("Location: /");
            exit;
        }

    } else {
        // if $_GET['id'] is not available, then redirect the user back to home
        header("Location: /");
        exit;
    }

    require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 500px;">
        <h1 class="h1 mb-4 text-center"><?= $product['title']; ?></h1>
        <?php  
            echo nl2br( $product["price"] );
            // // turn this content to an array
            // $paragraphs_array = preg_split( '/\n\s*\n/', $product["price"] );
            
            // // once we have the array, we'll use foreach to print out each line using <p>
            // foreach( $paragraphs_array as $paragraph ) {
            //     echo "<p>";
            //     // split by breakline
            //     $lines_array = preg_split( '/\n/',$paragraph );
            //     foreach( $lines_array as $line ) {
            //         echo "$line<br />";
            //     }
            //     echo "</p>";
            // }
        ?>
        <!-- comments -->
        <div class="mt-3">
            <h4>Comments</h4>
            <div class="overflow-scroll" style="height: 300px;">
            <?php
                // load the comments from database
                $sql = "SELECT
                    comments.*,
                    users.name
                    FROM comments
                    JOIN users
                    ON comments.user_id = users.id
                    WHERE product_id = :product_id ORDER BY id DESC";
                $query = $database->prepare($sql);
                $query->execute([
                    "product_id" => $product["id"]
                ]);

                $comments = $query->fetchAll();

                foreach ($comments as $comment) :
            ?>
            <div class="card mt-2 <?php echo ( $comment["user_id"] === $_SESSION['user']['id'] ? "bg-info" : '' ); ?>">
                <div class="card-body">
                    <p class="card-text">
                        <?php
                            for ( $i = 0; $i < $comment['rating']; $i++ ) {
                                echo '<i class="bi bi-star-fill"></i>';
                            }
                        ?>
                    </p>
                    <p class="card-text"><?= $comment['comments']; ?></p>
                    <p class="card-text"><small class="text-muted">Commented By <?= $comment['name']; ?></small></p>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
            <?php if ( isUserLoggedIn() ) : ?>
            <form
                action="/comments/add"
                method="POST"    
                >
                <div class="mt-3">
                    <select>
                        <option>1 Star</option>
                        <option>2 Stars</option>
                        <option>3 Stars</option>
                        <option>4 Stars</option>
                        <option>5 Stars</option>
                    </select>
                </div>
                <div class="mt-3">
                    <label for="comments" class="form-label">Enter your comment below:</label>
                    <textarea class="form-control" id="comments" rows="3" name="comments"></textarea>
                </div>
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>" />
                <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id']; ?>" />
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
            <?php endif; ?>
        </div>

        <div class="text-center mt-3">
            <a href="/" class="btn btn-link btn-sm"
            ><i class="bi bi-arrow-left"></i> Back</a
            >
        </div>
    </div>

    <?php

require "parts/footer.php";