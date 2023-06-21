<?php

    // call db class
    $database = connectToDB();

    $sql = "SELECT * FROM order2 WHERE user_id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
        'user_id' => $_SESSION['user']['id']
    ]);

    $orders = $query->fetchAll();

    // require the header part
    require "parts/header.php";

?>
    <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h1">My Orders</h1>
        </div>

        <!-- List of orders placed by user in table format -->
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
                <td><?= $order['added_on']; ?></td>
                <td>
                    <ul class="list-unstyled">
                    <?php
                        $sql = "SELECT
                        cart.*,
                        products.title,
                        products.price 
                        FROM cart
                        JOIN products
                        ON cart.product_id = products.id
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

<?php

    require "parts/footer.php";