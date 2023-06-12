<?php
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
        header("Location: /");
        exit;
      }

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Comment</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" action="posts/add-comment">
        <?php require "parts/message_error.php";?>
          <div class="mb-3">
            <label for="post-comment" class="form-label">Comment</label>
            <textarea
              class="form-control"
              id="post-comment"
              rows="10"
              name="comment"
            ></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/post?id=<?= $post['id']; ?>" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to Posts</a
        >
      </div>
    </div>

<?php
  require "parts/footer.php";