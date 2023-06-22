<?php
  if ( !isEditorOrAdmin() ) {
    header("Location: /");
    exit;
  }

  require "parts/header.php";
?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Product</h1>
      </div>
      <div class="card mb-2 p-4">
        <form method="POST" action="products/add">
        <?php require "parts/message_error.php";?>
          <div class="mb-3">
            <label for="product-title" class="form-label">Title</label>
            <input type="text" class="form-control" id="product-title" name="title"/>
          </div>
          <div class="mb-3">
            <label for="product-price" class="form-label">Price (RM)</label>
             <input type="text" class="form-control" id="product-price" name="price" />
          </div>
          <div class="mb-3">
            <label for="product-image_url" class="form-label">Image_url</label>
            <input type="text" class="form-control" id="product-image_url" name="image_url"/>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-products" class="btn btn-link btn-sm"
          ><i class="bi bi-arrow-left"></i> Back to products</a
        >
      </div>
    </div>

<?php
  require "parts/footer.php";
