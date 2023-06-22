<?php
  if(!isUserLoggedIn()){
    header("Location: /");
    exit;
  }

  if ( isAdmin()){
    $_SESSION['usertitle'] = "Manage Users";
    $_SESSION['usericon'] = "bi bi-people";
  }else{
    $_SESSION['usertitle'] = "User Edit";
    $_SESSION['usericon'] = "bi bi-person-fill-gear";
  }
  require "parts/header.php";
?>  
    <div class=" mt-3 position-absolute">
      <a href="/" class="btn fs-1 "
        ><i class="bi bi-arrow-left-circle"></i></a
      >
    </div>
    <div class="container mx-auto my-5" style="max-width: 800px;">
      <h1 class="h1 mb-4 text-center">Dashboard</h1>
      <div class="row">
        <div class="col-6 ">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="<?= $_SESSION['usericon']; ?>" style="font-size: 3rem;"></i>
                </div>
                <?= $_SESSION['usertitle']; ?>
              </h5>
              <div class="text-center mt-3">
                <a href="/manage-users" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
        <?php if(!isUser()):?>
          <div class="col-6">
            <div class="card mb-2">
              <div class="card-body">
                <h5 class="card-title text-center">
                  <div class="mb-1">
                    <i class="bi bi-pencil-square" style="font-size: 3rem;"></i>
                  </div>
                  Manage Products
                </h5>
                <div class="text-center mt-3">
                  <a href="/manage-products" class="btn btn-primary btn-sm"
                    >Access</a
                  >
                </div>
              </div>
            </div>
          </div>
        <?php endif ;?>
        <div class="col-6">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-cart4" style="font-size: 3rem;"></i>
                </div>
                Cart Form
              </h5>
              <div class="text-center mt-3">
                <a href="/cart-form" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card mb-2">
            <div class="card-body">
              <h5 class="card-title text-center">
                <div class="mb-1">
                  <i class="bi bi-clipboard2-check" style="font-size: 3rem;"></i>
                </div>
                  Order Form
              </h5>
              <div class="text-center mt-3">
                <a href="/order-form" class="btn btn-primary btn-sm"
                  >Access</a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
  require "parts/footer.php";
?>
