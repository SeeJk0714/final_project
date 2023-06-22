<?php
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }
  
  $database = connectToDB();
  
  if ( isAdmin()){
    $sql = "SELECT 
    carts.*, 
    users.name AS user_name,
    users.email AS user_email,
    products.title,
    products.price
    FROM carts 
    JOIN users 
    ON carts.user_id = users.id
    JOIN products
    ON carts.product_id = products.id";
    $query = $database->prepare($sql);
    $query->execute();

  }else if( isEditor() ){   
    $sql = "SELECT 
    carts.*, 
    users.name AS user_name,
    users.email AS user_email,
    products.title,
    products.price
    FROM carts 
    JOIN users 
    ON carts.user_id = users.id
    JOIN products
    ON carts.product_id = products.id
    WHERE carts.editor_by = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }else{
    $sql = "SELECT
    carts.*,
    products.title,
    products.price
    FROM carts
    JOIN products
    ON carts.product_id = products.id
    WHERE carts.user_id = :user_id AND order_id IS NULL";
    $query = $database->prepare($sql);
    $query->execute([
        'user_id' => $_SESSION['user']['id']
    ]);
  }

  $products_in_cart = $query->fetchAll();

  $total_in_cart = 0;

  require "parts/header.php";
?>
    <div class=" mt-3 position-absolute">
      <a href="/dashboard" class="btn fs-1 "
        ><i class="bi bi-arrow-left-circle"></i></a
      >
    </div>
    <?php if(isEditorOrAdmin()) :?>
      <div class="container mx-auto my-5" style="max-width: 1000px;">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h1 class="h1">Cart Form</h1>
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
              <?php if ( empty( $products_in_cart ) ) : ?>
                  <tr>
                      <td colspan="5">The Cart Form is empty.</td>
                  </tr>
              <?php else : ?>
              <?php foreach($products_in_cart as $cart): ?>
              <tr>
                <?php if($cart['order_id'] === NULL) :?>
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
                <td><?= $cart["quantity"]; ?></td>
                <td><?= $cart['user_name']; ?><br/><?= $cart['user_email']; ?></td>
                <td>RM<?= $cart["price"] * $cart["quantity"] ;?></td>
                <td><?= $cart["create_at"]; ?></td>
                <td>
                  <form
                      method="POST"
                      action="/carts/delete"
                      >
                      <input 
                          type="hidden"
                          name="cart_id"
                          value="<?= $cart['id']; ?>"
                          />
                      <button type="submit" class="btn btn-danger btn-sm">
                          <i class="bi bi-trash"></i>
                      </button>
                  </form>
                </td>
                <?php endif ;?>
              </tr>
            <?php endforeach; ?>
            <?php endif ;?>
            </tbody>
          </table>
        </div>
        
      </div>
    <?php else :?>
      <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
            
            <div class="min-vh-100">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h1">My Cart</h1>
                </div>
    
                <table class="table table-hover table-bordered table-striped table-light">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ( empty( $products_in_cart ) ) : ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach( $products_in_cart as $product ) : 
                            // get the total price of the product
                            $product_total =  $product['price'] * $product['quantity'];
                            // add the total price to the total in cart
                            $total_in_cart += $product_total;
                            ?>
                            <tr>
                                <td><?= $product['title']; ?></td>
                                <td>RM<?= $product['price']; ?></td>
                                <td><?= $product['quantity']; ?></td>
                                <td>RM<?= $product_total; ?></td>
                                <td>
                                    <form
                                        method="POST"
                                        action="/carts/delete"
                                        >
                                        <input 
                                            type="hidden"
                                            name="cart_id"
                                            value="<?= $product['id']; ?>"
                                            />
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" class="text-end">Total</td>
                            <td>RM<?= $total_in_cart; ?></td>
                            <td></td>
                        </tr>
                    <?php endif;?>
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-between align-items-center my-3">
                    <a href="/" class="btn btn-light btn-sm">Continue Shopping</a>
                    <?php if ( !empty( $products_in_cart ) ) : ?>
                        <form
                            method="POST"
                            action="/carts/checkout"
                            >
                            <input type="hidden" name="total_amount" value="<?= $total_in_cart; ?>" />
                            <button type="submit" class="btn btn-primary">Checkout</a>
                        </form>
                    <?php endif; ?>
                </div>    
            </div>
        </div>
    <?php endif ;?>

<?php
  require "parts/footer.php";