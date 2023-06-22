<?php
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }

  if ( isset( $_GET['id'] ) ) {
    $database = connectToDB();
  
    $sql = "SELECT * FROM users WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
      'id' => $_GET['id']
    ]);
  
    $user = $query->fetch();
  
    if(! $user){
      header("Location: /manage-users");
    }
  }else{
    header("Location: /manage-users");
    exit;
  }

  require "parts/header.php";
?>
    <div class=" mt-3 position-absolute">
      <a href="/manage-users" class="btn fs-1 "
        ><i class="bi bi-arrow-left-circle"></i></a
      >
    </div>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Change Password</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" action="users/changepwd">
          <?php require "parts/message_error.php"; ?>
          <h1>User: <?= $user['name']; ?></h1>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" />
              </div>
              <div class="col">
                <label for="confirm-password" class="form-label"
                  >Confirm Password</label
                >
                <input
                  type="password"
                  class="form-control"
                  id="confirm-password"
                  name="confirm_password"
                />
              </div>
            </div>
          </div>
          <div class="d-grid">
            <input type="hidden" name="id" value="<?= $user['id']; ?>"/>
            <button type="submit" class="btn btn-primary">
              Change Password
            </button>
          </div>
        </form>
      </div>
    </div>

<?php
  require "parts/footer.php";
