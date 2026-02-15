
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php
require_once __DIR__ . '/../config/db.php';

// Fetch existing data
$result = $conn->query("SELECT * FROM home_page WHERE id = 1");
$data = $result->fetch_assoc();


// Update data
if (isset($_POST['update_home'])) {

  $hero_small_title = $_POST['hero_small_title'];
  $hero_title = $_POST['hero_title'];
  $hero_description = $_POST['hero_description'];
  $about_text = $_POST['about_text'];
  $linkedin = $_POST['linkedin'];
  $facebook = $_POST['facebook'];
  $instagram = $_POST['instagram'];
  $x_account = $_POST['x_account'];


  $image_path = $data['hero_image'];
  $image2_path = $data['hero_image2']; // keep old image by default

  // ===== IMAGE VALIDATION START =====
  if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] == 0) {

    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    $file_name = $_FILES['hero_image']['name'];
    $file_tmp = $_FILES['hero_image']['tmp_name'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check extension only
    if (!in_array($file_ext, $allowed_types)) {
      die("Only JPG, JPEG, PNG & WEBP allowed.");
    }

    // Generate unique name
    $new_file_name = time() . "_" . uniqid() . "." . $file_ext;
    $upload_path = __DIR__ . "/../uploads/" . $new_file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {

      // Delete old image (optional but recommended)
      if (!empty($data['hero_image']) && file_exists(__DIR__ . "/../" . $data['hero_image'])) {
        unlink(__DIR__ . "/../" . $data['hero_image']);
      }

      $image_path = "uploads/" . $new_file_name;
    } else {
      echo "Upload Error Code: " . $_FILES['hero_image']['error'];
      exit;
    }
  }
  // ===== IMAGE VALIDATION END =====


// ===== IMAGE VALIDATION START =====
  if (isset($_FILES['hero_image2']) && $_FILES['hero_image2']['error'] == 0) {

    $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
    $file_name = $_FILES['hero_image2']['name'];
    $file_tmp = $_FILES['hero_image2']['tmp_name'];

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check extension only
    if (!in_array($file_ext, $allowed_types)) {
      die("Only JPG, JPEG, PNG & WEBP allowed.");
    }

    // Generate unique name
    $new_file_name = time() . "_" . uniqid() . "." . $file_ext;
    $upload_path = __DIR__ . "/../uploads/" . $new_file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {

      // Delete old image (optional but recommended)
      if (!empty($data['hero_image2']) && file_exists(__DIR__ . "/../" . $data['hero_image2'])) {
        unlink(__DIR__ . "/../" . $data['hero_image2']);
      }

      $image2_path = "uploads/" . $new_file_name;
    } else {
      echo "Upload Error Code: " . $_FILES['hero_image2']['error'];
      exit;
    }
  }
  // ===== IMAGE VALIDATION END =====


  $stmt = $conn->prepare("
    UPDATE home_page 
    SET hero_small_title = ?, 
        hero_title = ?, 
        hero_description = ?, 
        about_text = ?, 
        hero_image = ?, 
        hero_image2 = ?, 
        linkedin = ?, 
        facebook = ?, 
        instagram = ?, 
        x_account = ?
    WHERE id = 1
");

$stmt->bind_param(
    "ssssssssss",
    $hero_small_title,
    $hero_title,
    $hero_description,
    $about_text,
    $image_path,
    $image2_path,
    $linkedin,
    $facebook,
    $instagram,
    $x_account
);



  $stmt->execute();

  header("Location: editHome.php?success=1");
  exit;
}


?>


<?php
// Fetch all education entries
$result2 = $conn->query("SELECT * FROM about ORDER BY year DESC");
$row = $result2->fetch_assoc();

if (isset($_POST['add_education'])) {
    $year = $_POST['year'];
    $description = $_POST['description'];

    if (!empty($year) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO about (year, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $year, $description);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER['PHP_SELF']); // refresh page
        exit;
    } else {
        echo "<div class='alert alert-danger'>Both fields are required.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@themepixels">
    <meta name="twitter:creator" content="@themepixels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Starlight">
    <meta name="twitter:description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="twitter:image" content="http://themepixels.me/starlight/img/starlight-social.png">

    <!-- Facebook -->
    <meta property="og:url" content="http://themepixels.me/starlight">
    <meta property="og:title" content="Starlight">
    <meta property="og:description" content="Premium Quality and Responsive UI for Dashboard.">

    <meta property="og:image" content="http://themepixels.me/starlight/img/starlight-social.png">
    <meta property="og:image:secure_url" content="http://themepixels.me/starlight/img/starlight-social.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">

    <title>Starlight Responsive Bootstrap 4 Admin Template</title>

    <base href="/MyPortfolio/AdminView/template/">


    <!-- vendor css -->
    <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link href="lib/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="lib/highlightjs/github.css" rel="stylesheet">
    <link href="lib/select2/css/select2.min.css" rel="stylesheet">

    <!-- Starlight CSS -->
    <link rel="stylesheet" href="css/starlight.css">
  </head>

<body>
    <?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">
    Home page updated successfully!
  </div>
<?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row justify-content-center">

  <div class="col-12 col-lg-8 col-xl-6">
    <div class="card pd-15 pd-sm-30 form-layout form-layout-5">

      <div class="row row-xs align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Hero Small Title</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="hero_small_title" class="form-control"
                 value="<?= htmlspecialchars($data['hero_small_title']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Hero Title</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="hero_title" class="form-control"
                 value="<?= htmlspecialchars($data['hero_title']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Hero Description</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="hero_description" class="form-control"><?= htmlspecialchars($data['hero_description']) ?></textarea>
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">About Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="4" name="about_text" class="form-control"><?= htmlspecialchars($data['about_text']) ?></textarea>
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
    <label class="col-12 col-sm-4 form-control-label">LinkedIn</label>
    <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
        <input type="text" name="linkedin" class="form-control"
               value="<?= htmlspecialchars($data['linkedin'] ?? '') ?>">
    </div>
</div>

<div class="row row-xs mg-t-20 align-items-center">
    <label class="col-12 col-sm-4 form-control-label">Facebook</label>
    <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
        <input type="text" name="facebook" class="form-control"
               value="<?= htmlspecialchars($data['facebook'] ?? '') ?>">
    </div>
</div>

<div class="row row-xs mg-t-20 align-items-center">
    <label class="col-12 col-sm-4 form-control-label">Instagram</label>
    <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
        <input type="text" name="instagram" class="form-control"
               value="<?= htmlspecialchars($data['instagram'] ?? '') ?>">
    </div>
</div>

<div class="row row-xs mg-t-20 align-items-center">
    <label class="col-12 col-sm-4 form-control-label">X Account</label>
    <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
        <input type="text" name="x_account" class="form-control"
               value="<?= htmlspecialchars($data['x_account'] ?? '') ?>">
    </div>
</div>

      <!-- Image Upload Field -->
      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Hero Image</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          
          <?php if (!empty($data['hero_image'])): ?>
            <div class="mg-b-10">
              <img src="../<?= $data['hero_image']; ?>" width="120" class="img-thumbnail">
            </div>
          <?php endif; ?>

          <label class="custom-file">
            <input type="file" name="hero_image" class="custom-file-input" accept="image/*">
            <span class="custom-file-control"></span>
          </label>

        </div>
      </div>

      <!-- Image Upload Field -->
      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Hero Image2</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          
          <?php if (!empty($data['hero_image2'])): ?>
            <div class="mg-b-10">
              <img src="../<?= $data['hero_image2']; ?>" width="120" class="img-thumbnail">
            </div>
          <?php endif; ?>

          <label class="custom-file">
            <input type="file" name="hero_image2" class="custom-file-input" accept="image/*">
            <span class="custom-file-control"></span>
          </label>

        </div>
      </div>


      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Year</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
            <input type="text" name="year" class="form-control" value="<?= htmlspecialchars($row['year'] ?? '') ?>">
        </div>

        <label class="col-12 col-sm-4 form-control-label">Description</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
            <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($row['description'] ?? '') ?>">
        </div>
    </div>
            <div class="row row-xs mg-t-20">
        <div class="col-12 text-right">
            <button type="submit" name="add_education" class="btn btn-info">Add Education</button>
        </div>

      <div class="row row-xs mg-t-30">
        <div class="col-12 col-sm-8 mg-sm-l-auto text-right">
          <button type="submit" name="update_home" class="btn btn-info">
            Update Home
          </button>
        </div>
      </div>

    </div>
  </div>
    </div>
</form>

</body>

  <script src="lib/jquery/jquery.js"></script>
  <script src="lib/popper.js/popper.js"></script>
  <script src="lib/bootstrap/bootstrap.js"></script>
  <script src="lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js"></script>
  <script src="js/starlight.js"></script>

</html>
