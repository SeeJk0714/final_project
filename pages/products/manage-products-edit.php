<?php
  // make sure the user is logged in
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }

  if ( isset( $_GET['id'] ) ) {
    // load database
    $database = connectToDB();

    // load the product data based on the id
    $sql = "SELECT
    products.*,
    users.name
    FROM products 
    JOIN users
    ON products.modified_by = users.id
    WHERE products.id = :id";
    // $sql = "SELECT * FROM products WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
      'id' => $_GET['id']
    ]);

    // fetch
    $product = $query->fetch();

  }else{
    header("Location: /manage-products");
    exit;
  }

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit Product</h1>
      </div>
      <div class="card mb-2 p-4">
        <?php require "parts/message_error.php";?>
        <form method="POST" action="products/edit">
          <div class="mb-3">
            <label for="product-title" class="form-label">Title</label>
            <input
              type="text"
              class="form-control"
              id="product-title"
              name="title"
              value="<?= $product['title']; ?>"
            />
          </div>
          <div class="mb-3">
            <label for="product-price" class="form-label">Price (RM)</label>
            <input type="text" class="form-control" id="product-price" name="price" value="<?= $product["price"]; ?>"/>
          </div>
          <div class="mb-3">
            <label for="product-image_url" class="form-label">Image_url</label>
            <input
              type="text"
              class="form-control"
              id="product-image_url"
              name="image_url"
              value="<?= $product['image_url']; ?>"
            />
          </div>
          <div class="mb-3">
            <label for="product-status" class="form-label">Status</label>
            <select class="form-control" id="product-status" name="status">
              <option value="pending" <?= $product['status'] === 'pending' ? 'selected' : ''; ?>>Pending for Review</option>
              <option value="publish" <?= $product['status'] === 'publish' ? 'selected' : ''; ?>>Publish</option>
            </select>
          </div>
          <div class="mb-3">
            Last modified by: 
              <?php 
                // $sql = "SELECT * FROM users where id = :id";
                // $query = $database->prepare( $sql );
                // $query->execute([
                //   'id' => $product["modified_by"]
                // ]);
                // $user = $query->fetch();
                // echo $user["name"];

                echo $product["name"];
              ?> 
              on ( <?= $product["modified_at"]; ?> )
          </div>
          <div class="text-end">
            <input type="hidden" name="id" value="<?= $product['id']; ?>"/>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-products" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to products</a
        >
      </div>
    </div>

<?php
  require "parts/footer.php";
?>
