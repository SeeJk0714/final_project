<?php
  // make sure the user is logged in
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }
  
  $database = connectToDB();
  
  if ( isAdmin()){
    // $sql = "SELECT * FROM posts";
    $sql = "SELECT 
    posts.*, 
    users.name AS user_name,
    users.email AS user_email 
    FROM posts 
    JOIN users 
    ON posts.user_id = users.id";
    $query = $database->prepare($sql);
    $query->execute();
  }else{
    // $sql = "SELECT * FROM posts where user_id = :user_id";
    $sql = "SELECT 
        posts.id, 
        posts.title, 
        posts.status, 
        users.name AS user_name, 
        users.email as user_email
        FROM posts 
        JOIN users 
        ON posts.user_id = users.id 
        where posts.user_id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }
  //fetch the data from query
  $posts = $query->fetchAll();

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Order List</h1>
      </div>
      <div class="card mb-2 p-4">
      <?php require "parts/message_success.php"; ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 20%;">Title</th>
              <th scope="col"></th>
              <th scope="col"></th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($posts as $post): ?>
            <tr>
              <th scope="row"><?= $post['id']; ?></th>
              <td>
                <?php
                  $excerpt = str_split($post['title'],10);
                  if(strlen($excerpt[0])<10){
                    echo $excerpt[0];
                  }else{
                    echo $excerpt[0]."...";
                  }
                ?> 
              </td>
              <td><?= $post['user_name']; ?><br/><?= $post['user_email']; ?></td>
              <td><span class="<?php 
                if($post['status'] == "pending"){
                  echo "badge bg-warning";
                } else if($post['status'] == "publish"){
                  echo "badge bg-success";
                } 
                ?>"><?= $post['status']; ?></span></td>
              <td class="text-end">
                <div class="buttons">
                  <a
                    href="/post?id=<?= $post['id']; ?>"
                    target="_blank"
                    class="btn btn-primary btn-sm me-2 
                    <?php 
                      if($post['status'] == "pending"){
                        echo " disabled";
                      } else if($post['status'] == "publish"){
                        echo " ";
                      } 
                      ?>"><i class="bi bi-eye"></i
                  ></a>
                  <a
                    href="/manage-posts-edit?id=<?= $post['id']; ?>"
                    class="btn btn-secondary btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $post['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="delete-modal-<?= $post['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5 text-start" id="exampleModalLabel">Are you sure you want to delete this post: <br/> "<?= $post['title']; ?>"?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          You're currently deleting "<?= $post['title']; ?>"
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/posts/delete">
                            <input type="hidden" name="id" value= "<?= $post['id']; ?>"/>
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