<?php
  // make sure the user is logged in
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }
  
  $database = connectToDB();
  
  if ( isAdmin()){
    // $sql = "SELECT * FROM posts";
    $sql = "SELECT 
    orders.*, 
    users.name AS user_name,
    users.email AS user_email 
    FROM orders 
    JOIN users 
    ON orders.user_id = users.id";
    $query = $database->prepare($sql);
    $query->execute();

  }else if( isEditor() ){   
    $sql = "SELECT 
    orders.*, 
    users.name AS user_name,
    users.email AS user_email 
    FROM orders 
    JOIN users 
    ON orders.user_id = users.id
    WHERE orders.editor_by = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }else{
    $sql = "SELECT 
        orders.*,
        users.name AS user_name, 
        users.email as user_email
        FROM orders 
        JOIN users 
        ON orders.user_id = users.id 
        where orders.user_id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }
  //fetch the data from query
  $orders = $query->fetchAll();

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 1000px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Order Form</h1>
      </div>
      <div class="card mb-2 p-4">
      <?php require "parts/message_success.php"; ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 20%;">Title</th>
              <th scope="col">Consumer</th>
              <th scope="col">Price</th>
              <th scope="col">Time</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $cart): ?>
            <tr>
              <th scope="row"><?= $cart['id']; ?></th>
              <td>
                <?php
                  $excerpt = str_split($cart['title'],11);
                  if(strlen($excerpt[0])<11){
                    echo $excerpt[0];
                  }else{
                    echo $excerpt[0]."...";
                  }
                ?> 
              </td>
              <td><?= $cart['user_name']; ?><br/><?= $cart['user_email']; ?></td>
              <td><?= $cart["price"]; ?></td>
              <td><?= $cart["create_at"]; ?></td>

              <td class="text-end">
                <div class="buttons">
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#order-modal-<?= $cart['id']; ?>">
                    <i class="bi bi-clipboard2-check"></i>
                  </button>
                  <div class="modal fade" id="order-modal-<?= $cart['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5 text-start" id="exampleModalLabel">Are you sure you want to delete this order from your cart: <br/> "<?= $cart['title']; ?>"?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          You're currently deleting "<?= $cart['title']; ?>".
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/carts/delete">
                            <input type="hidden" name="id" value= "<?= $cart['id']; ?>"/>
                            <button type="submit" class="btn btn-danger">Yes, please delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $cart['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <div class="modal fade" id="delete-modal-<?= $cart['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5 text-start" id="exampleModalLabel">Are you sure you want to delete this product from your cart: <br/> "<?= $cart['title']; ?>"?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          You're currently deleting "<?= $cart['title']; ?>".
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/carts/delete">
                            <input type="hidden" name="id" value= "<?= $cart['id']; ?>"/>
                            <button type="submit" class="btn btn-danger">Yes, please delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
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