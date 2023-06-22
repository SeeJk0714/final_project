<?php
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }
  
  $database = connectToDB();
  
  if ( isAdmin()){
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
    carts.*, 
    users.name AS user_name,
    users.email AS user_email,
    products.title,
    products.price,
    orders.total_amount
    FROM carts 
    JOIN users 
    ON carts.user_id = users.id
    JOIN products
    ON carts.product_id = products.id
    JOIN orders
    ON carts.order_id = orders.id
    WHERE carts.editor_by = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }else{
    $sql = "SELECT * FROM orders WHERE user_id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
        'user_id' => $_SESSION['user']['id']
    ]);
  }
  
  $orders = $query->fetchAll();

  require "parts/header.php";
?> 
  <div class=" mt-3 position-absolute">
  <a href="/dashboard" class="btn fs-1 "
    ><i class="bi bi-arrow-left-circle"></i></a
  >
  </div>
  <?php if(isAdmin()) :?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Order Form</h1>
      </div>
      <div class="card mb-2 p-4">
      <?php require "parts/message_success.php"; ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 30%;">Consumer</th>
              <th scope="col">Total</th>
              <th scope="col">Time</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $order): ?>
              <tr>
                <th scope="row"><?= $order['id']; ?></th>
                <td><?= $order['user_name']; ?><br/><?= $order['user_email']; ?></td>
                <td>RM<?= $order["total_amount"]; ?></td>
                <td><?= $order["create_at"]; ?></td>
                <td></td>
                <td class="text-center">
                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#order-modal-<?= $order['id']; ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <div class="modal fade" id="order-modal-<?= $order['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Order History</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <h3><?= $order['user_name']; ?></h3>
                        <table
                          class="table table-hover table-bordered table-striped table-light"
                        >
                          <thead>
                            <tr>
                              <th scope="col">Order ID</th>
                              <th scope="col">Date</th>
                              <th scope="col">Products</th>
                              <th scope="col">Total Amount</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                            <th scope="row"><?= $order['id']; ?></th>
                            <td><?= $order['create_at']; ?></td>
                            <td>
                              <ul class="list-unstyled">
                              <?php 
                                $sql = "SELECT
                                carts.*,
                                products.title
                                FROM carts
                                JOIN products
                                ON carts.product_id = products.id
                                WHERE order_id = :order_id";
                                $query = $database->prepare($sql);
                                $query->execute([
                                    'order_id' => $order['id']
                                ]);
        
                                $products_in_cart = $query->fetchAll();
        
                                foreach( $products_in_cart as $product ) {
                                    echo "<li>{$product['title']} ({$product['quantity']})</li>";
                                }

                              ?>
                              </ul>
                            </td>
                            <td>RM<?= $order['total_amount']; ?></td>
                          </tr>
                        </tbody>
                        </table>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <form
                      method="POST"
                      action="/orders/delete"
                      class="d-inline"
                      >
                      <input 
                          type="hidden"
                          name="order_id"
                          value="<?= $order['id']; ?>"
                          />
                      <button type="submit" class="btn btn-danger btn-sm">
                          <i class="bi bi-trash"></i>
                      </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div> 
    </div>
  <?php elseif(isEditor()) :?>
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
              <th scope="col">Amount</th>
              <th scope="col">Consumer</th>
              <th scope="col">Price</th>
              <th scope="col">Time</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
              <th scope="row"><?= $order['id']; ?></th>
              <td>
                <?php
                  $excerpt = str_split($order['title'],11);
                  if(strlen($excerpt[0])<11){
                    echo $excerpt[0];
                  }else{
                    echo $excerpt[0]."...";
                  }
                ?> 
              </td>
              <td><?= $order['quantity']; ?></td>
              <td><?= $order['user_name']; ?><br/><?= $order['user_email']; ?></td>
              <td>RM<?= $order['price'] * $order['quantity'] ?></td>
              <td><?= $order["create_at"]; ?></td>
              
              <td>
                <form method="POST" action="/orders/delete-product">
                  <input 
                      type="hidden"
                      name="cart_id"
                      value="<?= $order['id']; ?>"
                      />
                  <input type="hidden" name="price" value="<?= $order['price'] * $order['quantity'] ?>">
                  <input type="hidden" name="total_amount" value="<?= $order['total_amount']?>">
                  <input type="hidden" name="order_id" value="<?= $order['order_id']?>">
                  <button type="submit" class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div> 
    </div>
  <?php else :?>
      <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h1">My Orders</h1>
        </div>

        <table
          class="table table-hover table-bordered table-striped table-light"
        >
          <thead>
            <tr>
              <th scope="col">Order ID</th>
              <th scope="col">Date</th>
              <th scope="col">Products</th>
              <th scope="col">Total Amount</th>
            </tr>
          </thead>
          <tbody>
          <?php if ( isset( $orders ) ) : ?>
            <?php foreach( $orders as $order ) : ?>
                <tr>
                <th scope="row"><?= $order['id']; ?></th>
                <td><?= $order['create_at']; ?></td>
                <td>
                    <ul class="list-unstyled">
                    <?php
                        $sql = "SELECT
                        carts.*,
                        products.title,
                        products.price 
                        FROM carts
                        JOIN products
                        ON carts.product_id = products.id
                        WHERE order_id = :order_id";
                        $query = $database->prepare($sql);
                        $query->execute([
                            'order_id' => $order['id']
                        ]);

                        $products_in_cart = $query->fetchAll();

                        foreach( $products_in_cart as $product ) {
                            echo "<li>{$product['title']} ({$product['quantity']})</li>";
                        }
                    ?>
                    </ul>
                </td>
                <td>RM<?= $order['total_amount']; ?></td>
                </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="4">You have not placed any orders.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center my-3">
          <a href="/" class="btn btn-light btn-sm"
            >Continue Shopping</a
          >
        </div>
      </div>
    </div>
  <?php endif ;?>
<?php
  require "parts/footer.php";


  