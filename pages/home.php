<?php

  $database = connectToDB();

  $sql = "SELECT * FROM products where status = 'publish' ORDER BY id DESC";
  $query = $database->prepare($sql);
  $query->execute();
  $products = $query->fetchAll();

  require "parts/header.php";
?>
  <nav class="d-flex">
    <h1 class="h1 mb-4">My Shop</h1>
    <div class="mt-4 ms-auto gap-3">
      <?php if ( isUserLoggedIn() ) : ?>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        <a href="/dashboard" class="btn btn-success btn-sm">dashboard</a>
      <?php else : ?>
        <a href="/login" class="btn btn-primary btn-sm">Login</a>
        <a href="/signup" class="btn btn-success btn-sm">Sign Up</a>
      <?php endif; ?>
    </div>
  </nav>
  <div class="container mx-auto mb-5" style="max-width: 900px;">
    <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($products as $product):?>
      <div class="col">
        <div class="card h-100">
          <a href="/product?id=<?= $product['id']; ?>" class="btn text-start">
            <img
              src="<?= $product['image_url']; ?>"
              class="card-img-top"
              alt="Product <?= $product['id']; ?>"
              style="height: 250px;"
            />
            <div class="container">
              <h5 class="card-title"><?= $product['title']; ?></h5>
              <p class="card-text">
                RM<?= $product["price"]; ?>
              </p>
            </div>
          </a>
        </div>
      </div>
    <?php endforeach ;?>
    </div>
  </div>
<?php
  require "parts/footer.php";