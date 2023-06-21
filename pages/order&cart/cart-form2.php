<?php

    // call db class
    $database = connectToDB();

    // get the cart from the database based on the current logged in user
    $sql = "SELECT
    cart.*,
    products.title,
    products.price
    FROM cart
    JOIN products
    ON cart.product_id = products.id
    WHERE cart.user_id = :user_id AND order_id IS NULL";
    $query = $database->prepare($sql);
    $query->execute([
        'user_id' => $_SESSION['user']['id']
    ]);

    $products_in_cart = $query->fetchAll();

    $total_in_cart = 0;

    require 'parts/header.php';
?>

        <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
            
            <div class="min-vh-100">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h1">My Cart</h1>
                </div>
    
                <!-- List of products user added to cart -->
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
                    <!-- if no products in the cart -->
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
                                        action="/products/remove_from_cart"
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
                    <!-- if there is product in cart, then only display the checkout button -->
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
        
<?php

    require "parts/footer.php";