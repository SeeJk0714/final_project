<?php
if ( isset( $_GET['id'] ) ) {

  $database = connectToDB();

  $sql = "SELECT * FROM posts WHERE id = :id AND status = 'publish'";
  $query = $database->prepare( $sql );
  $query->execute([
    'id' => $_GET['id']
  ]);
  $posts = $query->fetch();

  if ( !$posts ) {
    header("Location: /");
    exit;
}

}else{
  header("Location: /");
  exit;
}

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" >
      <h1 class="h1 mb-4"><?= $posts['title']; ?></h1>
      <div class='d-flex'>
        <div>
          <img src="<?= $posts['image_url']; ?>" alt="<?= $posts['title']; ?>.image">
          <p class="fs-1"><?= $posts['content']; ?></p>
          <div class=" mt-3">
            <a href="/" class="btn btn-primary btn-sm"
              ><i class="bi bi-arrow-left"></i> Back</a
            >
          </div>
        </div>
        <div class=" mx-auto">
          <h4>Comments</h4>
          <div class="overflow-scroll" style="height: 400px;">
            <?php 
            $sql = "SELECT
              comments.*,
              users.name
              FROM comments
              JOIN users
              ON comments.user_id = users.id
              WHERE post_id = :post_id ORDER BY id DESC";
            $query = $database->prepare($sql);
            $query->execute([
              "post_id" => $posts["id"]
            ]);
            $comments = $query->fetchAll();
            
            foreach($comments as $comment): ?>
              <div class="card mb-3" style="width: 500px;">
                <div class="card-header">
                  <?= $comment['name']; ?>
                </div>
                <div class="card-body">
                  <blockquote class="blockquote mb-0">
                    <p><?= $comment['comment']; ?></p>
                    <!-- <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer> -->
                  <?php if ( isUserLoggedIn() ) : ?>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $comment['user_id']; ?>">
                      <i class="bi bi-trash"></i>
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="delete-modal-<?= $comment['user_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure you want to delete this user: <?= $comment['name']; ?>?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            You're currently deleting "<?= $comment['name']; ?>"
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form method="POST" action="/comments/delete?=<?= $comment["post_id"]; ?>">
                              <input type="hidden" name="post_id" value="<?= $comment["post_id"]; ?>" />
                              <input type="hidden" name="user_id" value= "<?= $comment['user_id']; ?>"/>
                              <input type="hidden" name="id" value="<?= $comment['id']; ?>" />
                              <button type="submit" class="btn btn-danger">Yes, please delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  </blockquote>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <?php if ( isUserLoggedIn() ) : ?>
          <?php require "parts/message_error.php"; ?>
            <form
                action="/comments/add"
                method="POST"    
                >
                <div class="mt-3">
                    <label for="comments" class="form-label">Enter your comment below:</label>
                    <textarea class="form-control" id="comments" rows="3" name="comments"></textarea>
                </div>
                <input type="hidden" name="post_id" value="<?= $posts['id']; ?>" />
                <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id']; ?>" />
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
<?php
  require "parts/footer.php";
