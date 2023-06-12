<?php

  $database = connectToDB();

  $sql = "SELECT * FROM posts where status = 'publish' ORDER BY id DESC";
  $query = $database->prepare($sql);
  $query->execute();
  $posts = $query->fetchAll();

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
    <?php foreach ($posts as $post):?>
      <div class="col">
        <div class="card h-100">
          <img
            src="<?= $post['image_url']; ?>"
            class="card-img-top"
            alt="Product <?= $post['id']; ?>"
            style="height: 250px;"
          />
          <div class="container">
            <h5 class="card-title"><?= $post['title']; ?></h5>
            <p class="card-text">
              <?= $post['content']; ?>
            </p>
          </div>
          <div class="text-end">
            <a href="/post?id=<?= $post['id']; ?>" class="btn btn-sm">Read More</a>
          </div>
        </div>
      </div>
    <?php endforeach ;?>
    </div>
  </div>
<?php
  require "parts/footer.php";