<!DOCTYPE html>
<html>
  <head>
    <title>Final_Project</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
    />
    <style type="text/css">
      body {
        background: #A0C0DE;
        /* background: #2570B6; */
      }
      nav{
        /* background: #5A8AB6; */
        background: #000000; 
        color: #C6C6C6;

      }
      .product-card:hover {
            border-color: gray;
            border-width: 3px;
            size:150%;
            transform: scale(1.05);
          }
    </style>
  </head>
  <body>
  <nav class="d-flex mb-3">
    <h2 class="h1 mb-4 fw-light">My Shop</h1>
    <div class="mt-4 ms-auto gap-3">
      <?php if ( isUserLoggedIn() ) : ?>
        <a href="/manage-users" class="btn btn-light btn-sm"><?= $_SESSION['user']['name']?></a>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        <a href="/dashboard" class="btn btn-success btn-sm">dashboard</a>
      <?php else : ?>
        <a href="/login" class="btn btn-primary btn-sm">Login</a>
        <a href="/signup" class="btn btn-success btn-sm">Sign Up</a>
      <?php endif; ?>
    </div>
  </nav>
 