<?php
  // make sure the user is logged in
  if ( !isEditorOrAdmin() ) {
    header("Location: /");
    exit;
  }
  
  $database = connectToDB();
  
  if ( isAdmin()){
    // $sql = "SELECT * FROM products";
    $sql = "SELECT 
    products.*, 
    users.name AS user_name,
    users.email AS user_email 
    FROM products 
    JOIN users 
    ON products.user_id = users.id";
    $query = $database->prepare($sql);
    $query->execute();
  }else{
    // $sql = "SELECT * FROM products where user_id = :user_id";
    $sql = "SELECT 
        products.id, 
        products.title, 
        products.status, 
        users.name AS user_name, 
        users.email as user_email
        FROM products 
        JOIN users 
        ON products.user_id = users.id 
        where products.user_id = :user_id";
    $query = $database->prepare($sql);
    $query->execute([
      'user_id' => $_SESSION["user"]["id"]
    ]);
  }
  //fetch the data from query
  $products = $query->fetchAll();

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Manage Products</h1>
        <div class="text-end">
          <a href="/manage-products-add" class="btn btn-primary btn-sm"
            >Add New Product</a
          >
        </div>
      </div>
      <div class="card mb-2 p-4">
      <?php require "parts/message_success.php"; ?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 20%;">Title</th>
              <th scope="col">Created By</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($products as $product): ?>
            <tr>
              <th scope="row"><?= $product['id']; ?></th>
              <td>
                <?php
                  $excerpt = str_split($product['title'],10);
                  if(strlen($excerpt[0])<10){
                    echo $excerpt[0];
                  }else{
                    echo $excerpt[0]."...";
                  }
                ?> 
              </td>
              <td><?= $product['user_name']; ?><br/><?= $product['user_email']; ?></td>
              <td><span class="<?php 
                if($product['status'] == "pending"){
                  echo "badge bg-warning";
                } else if($product['status'] == "publish"){
                  echo "badge bg-success";
                } 
                ?>"><?= $product['status']; ?></span></td>
              <td class="text-end">
                <div class="buttons">
                  <a
                    href="/product?id=<?= $product['id']; ?>"
                    target="_blank"
                    class="btn btn-primary btn-sm me-2 
                    <?php 
                      if($product['status'] == "pending"){
                        echo " disabled";
                      } else if($product['status'] == "publish"){
                        echo " ";
                      } 
                      ?>"><i class="bi bi-eye"></i
                  ></a>
                  <a
                    href="/manage-products-edit?id=<?= $product['id']; ?>"
                    class="btn btn-secondary btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete-modal-<?= $product['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- Modal -->
                  <div class="modal fade" id="delete-modal-<?= $product['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5 text-start" id="exampleModalLabel">Are you sure you want to delete this product: <br/> "<?= $product['title']; ?>"?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          You're currently deleting "<?= $product['title']; ?>"
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form method="POST" action="/products/delete">
                            <input type="hidden" name="id" value= "<?= $product['id']; ?>"/>
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
