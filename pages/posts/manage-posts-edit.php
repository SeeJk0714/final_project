<?php
  // make sure the user is logged in
  if ( !isUserLoggedIn() ) {
    header("Location: /");
    exit;
  }

  if ( isset( $_GET['id'] ) ) {
    // load database
    $database = connectToDB();

    // load the post data based on the id
    $sql = "SELECT
    posts.*,
    users.name
    FROM posts 
    JOIN users
    ON posts.modified_by = users.id
    WHERE posts.id = :id";
    // $sql = "SELECT * FROM posts WHERE id = :id";
    $query = $database->prepare( $sql );
    $query->execute([
      'id' => $_GET['id']
    ]);

    // fetch
    $post = $query->fetch();

  }else{
    header("Location: /manage-posts");
    exit;
  }

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Edit Post</h1>
      </div>
      <div class="card mb-2 p-4">
        <?php require "parts/message_error.php";?>
        <form method="POST" action="posts/edit">
          <div class="mb-3">
            <label for="post-title" class="form-label">Title</label>
            <input
              type="text"
              class="form-control"
              id="post-title"
              name="title"
              value="<?= $post['title']; ?>"
            />
          </div>
          <div class="mb-3">
            <label for="post-price" class="form-label">Price</label>
            RM
            <textarea class="form-control" id="post-price" rows="10" name="price"><?= $post["price"]; ?></textarea>
          </div>
          <div class="mb-3">
            <label for="post-image_url" class="form-label">Image_url</label>
            <input
              type="text"
              class="form-control"
              id="post-image_url"
              name="image_url"
              value="<?= $post['image_url']; ?>"
            />
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Status</label>
            <select class="form-control" id="post-status" name="status">
              <option value="pending" <?= $post['status'] === 'pending' ? 'selected' : ''; ?>>Pending for Review</option>
              <option value="publish" <?= $post['status'] === 'publish' ? 'selected' : ''; ?>>Publish</option>
            </select>
          </div>
          <div class="mb-3">
            Last modified by: 
              <?php 
                // $sql = "SELECT * FROM users where id = :id";
                // $query = $database->prepare( $sql );
                // $query->execute([
                //   'id' => $post["modified_by"]
                // ]);
                // $user = $query->fetch();
                // echo $user["name"];

                echo $post["name"];
              ?> 
              on ( <?= $post["modified_at"]; ?> )
          </div>
          <div class="text-end">
            <input type="hidden" name="id" value="<?= $post['id']; ?>"/>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-posts" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Posts</a
        >
      </div>
    </div>

<?php
  require "parts/footer.php";
?>
