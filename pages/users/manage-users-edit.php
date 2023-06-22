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
        <h1 class="h1">Edit User</h1>
      </div>
      <div class="card mb-2 p-4">
      
        <form action="users/edit" method="POST">
          <?php require "parts/message_error.php"; ?>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $user['name']; ?>"/>
              </div>
              <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email']; ?>"/>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
              <option value="">Select an option</option>
              <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
              <option value="editor"<?= $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
              <?php if(isAdmin()) :?>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
              <?php endif ;?>
            </select>
          </div>
          <div class="d-grid">
            <input type="hidden" name="id" value="<?= $user['id']; ?>"/>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>

<?php
  require "parts/footer.php";

