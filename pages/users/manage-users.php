<?php
  
  // check if the current user is an admin or not
  if(!isAdmin()){
  // if current user is not an admin, redirect to dashboard
    header("Location: /dashboard");
    exit;
  }

  $database = connectToDB();
  
  $sql = 'SELECT * FROM users';
  $query = $database->prepare($sql);
  $query->execute();
  $users = $query->fetchAll();

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Users</h1>
        <div class="text-end">
          <a href="/manage-users-add" class="btn btn-primary btn-sm"
            >Add New User</a
          >
        </div>
      </div>
      <div class="card mb-2 p-4">
        <?php require "parts/message_success.php"; ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $user):?>
            <tr class="<?php
                if ( 
                  isset( $_SESSION['new_user_email'] ) && 
                  $_SESSION['new_user_email'] == $user['email'] ) {
                    echo "table-success";
                    unset( $_SESSION['new_user_email'] );
                }
              ?>">
              <th scope="row"><?= $user['id']; ?></th>
              <td><?= $user['name']; ?></td>
              <td><?= $user['email']; ?></td>
              <td><span class='<?php 
                if($user["role"] == "user"){
                  echo "badge bg-success";
                } else if($user["role"] == "editor"){
                  echo "badge bg-info";
                } else if($user["role"] == "admin"){
                  echo "badge bg-primary";
                }
                ?>'><?=$user['role']; ?></span></td>
              <td class="text-end">
                <div class="buttons">
                  <a
                    href="/manage-users-edit?id=<?= $user['id']; ?>"
                    class="btn btn-success btn-sm me-2"
                    ><i class="bi bi-pencil"></i>
                  </a>
                  <a
                  href="/manage-users-changepwd?id=<?= $user['id']; ?>"
                  class="btn btn-warning btn-sm me-2"
                  ><i class="bi bi-key"></i>
                  </a>
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $user['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="delete-modal-<?= $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user: <?= $user['name']; ?>?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          You're currently deleting "<?= $user['name']; ?>"
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/users/delete">
                            <input type="hidden" name="id" value= "<?= $user['id']; ?>"/>
                            <button type="submit" class="btn btn-danger">Yes, please delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <a href="/dashboard" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

<?php
  require "parts/footer.php";

