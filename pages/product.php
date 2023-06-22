<?php
if ( isset( $_GET['id'] ) ) {

  $database = connectToDB();

  $sql = "SELECT * FROM products WHERE id = :id AND status = 'publish'";
  $query = $database->prepare( $sql );
  $query->execute([
    'id' => $_GET['id']
  ]);
  $products = $query->fetch();

  if ( !$products ) {
    header("Location: /");
    exit;
}

}else{
  header("Location: /");
  exit;
}

  require "parts/header.php";
?>
    <div class=" mt-3 position-absolute">
      <a href="/" class="btn fs-1 "
        ><i class="bi bi-arrow-left-circle"></i></a
      >
    </div>
    <div class="container mx-auto my-5" >
      <h1 class="h1 mb-4"><?= $products['title']; ?></h1>
      <div class='d-flex'>
        <div>
          <img src="<?= $products['image_url']; ?>" alt="<?= $products['title']; ?>.image" style="height: 400px;">
          <p class="fs-1">RM<?= $products["price"]; ?></p>
          <?php if ( isUserLoggedIn()) :?>
            <div class=" mt-3">
              <!-- Add to cart -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addtocart-modal-<?= $products["id"];?>">
                Add to cart
              </button>

              <!-- Modal -->
              <div class="modal fade" id="addtocart-modal-<?= $products["id"];?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">Add to Cart</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="/carts/add">
                      <div class="modal-body">
                        Are you comfirm add "<?= $products["title"]; ?>" to your Cart.
                        <img src="<?= $products['image_url']; ?>" alt="<?= $products['title']; ?>.image" style="height: 400px;"><br/>
                        <label for="amount">Amount(limit 10) :</label>
                        <input type="number" id="amount" name="amount" style="width: 50px;" min="0" max="10">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <!-- <form method="POST" action="/carts/add"> -->
                        <input type="hidden" name="product_id" value="<?= $products["id"]; ?>" />
                        <input type="hidden" name="editor_by" value="<?= $products["user_id"]; ?>" />
                        <button type="submit" class="btn btn-primary">Add</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endif ;?>
        </div>
        <div class=" mx-auto">
          <h4>Comments</h4>
          <div class="overflow-scroll" style="height: 400px;">
          <?php 
          $sql = "SELECT
            comments.*,
            users.name 
            FROM comments
            JOIN users
            ON comments.user_id = users.id
            WHERE product_id = :product_id ORDER BY id DESC";
          $query = $database->prepare($sql);
          $query->execute([
            "product_id" => $products["id"]
            
          ]);
          $comments = $query->fetchAll();
          
          foreach($comments as $comment): ?>
            
              <div class="card mb-3" style="width: 500px;">
                <div class="card-header">
                  <?= $comment['name']; ?>
                </div>
                <div class="card-body">
                  <blockquote class="blockquote mb-0">
                    <p><?= $comment['comment']; ?></p>
                  <?php if(isUserLoggedIn()):?>
                    <?php if ( isEditorOrAdmin() OR $_SESSION["user"]["id"] === $comment['user_id']) : ?>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $comment['user_id']; ?>">
                      <i class="bi bi-trash"></i>
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="delete-modal-<?= $comment['user_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user: <?= $comment['name']; ?>?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            You're currently deleting "<?= $comment['name']; ?>"
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <form method="POST" action="/comments/delete">
                              <input type="hidden" name="product_id" value="<?= $comment["product_id"]; ?>" />
                              <input type="hidden" name="user_id" value= "<?= $comment['user_id']; ?>"/>
                              <input type="hidden" name="id" value="<?= $comment['id']; ?>" />
                              <button type="submit" class="btn btn-danger">Yes, please delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>
                  <?php endif; ?>
                  </blockquote>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if ( isUserLoggedIn() ) : ?>
          <?php require "parts/message_error.php"; ?>
            <form
                action="/comments/add"
                method="POST"    
                >
                <div class="mt-3">
                    <label for="comments" class="form-label">Enter your comment below:</label>
                    <textarea class="form-control" style="width: 500px;" id="comments" rows="3" name="comments"></textarea>
                </div>
                <input type="hidden" name="product_id" value="<?= $products['id']; ?>" />
                <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id']; ?>" />
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
<?php
  require "parts/footer.php";
