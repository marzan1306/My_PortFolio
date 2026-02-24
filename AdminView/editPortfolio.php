<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php
require_once __DIR__ . '/../config/db.php';

// Fetch existing data if updating
$data_result = $conn->query("SELECT * FROM work WHERE id = 1");
$data = $data_result && $data_result->num_rows > 0 ? $data_result->fetch_assoc() : [];

// Only run this if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $image_paths = [];

    // ===== IMAGE UPLOAD & VALIDATION =====
    for ($i = 1; $i <= 6; $i++) {
        $file_key = "image{$i}_path";

        if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == 0) {

            $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
            $file_name = $_FILES[$file_key]['name'];
            $file_tmp  = $_FILES[$file_key]['tmp_name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_types)) {
                die("Only JPG, JPEG, PNG & WEBP allowed for $file_key.");
            }

            $new_file_name = time() . "_" . uniqid() . "." . $file_ext;
            $upload_path = __DIR__ . "/../uploads/" . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {

                if (!empty($data[$file_key]) && file_exists(__DIR__ . "/../" . $data[$file_key])) {
                    unlink(__DIR__ . "/../" . $data[$file_key]);
                }

                $image_paths[$i] = "uploads/" . $new_file_name;

            } else {
                die("Upload Error Code ({$_FILES[$file_key]['error']}) for $file_key");
            }
        } else {
            $image_paths[$i] = $data["image{$i}_path"] ?? '';
        }
    }

    // ===== GET TEXT FIELDS =====
    $image_texts = [];
    for ($i = 1; $i <= 6; $i++) {
        $text_field = "image{$i}_text";
        $image_texts[$i] = $_POST[$text_field] ?? ($data[$text_field] ?? '');
    }

    // ===== PREPARE INSERT/UPDATE =====
    $check = $conn->query("SELECT id FROM work WHERE id = 1");

    if ($check && $check->num_rows > 0) {
        $stmt = $conn->prepare("
            UPDATE work SET
                image1_path=?, image1_text=?,
                image2_path=?, image2_text=?,
                image3_path=?, image3_text=?,
                image4_path=?, image4_text=?,
                image5_path=?, image5_text=?,
                image6_path=?, image6_text=?
            WHERE id=1
        ");

        $stmt->bind_param(
            "ssssssssssss",
            $image_paths[1], $image_texts[1],
            $image_paths[2], $image_texts[2],
            $image_paths[3], $image_texts[3],
            $image_paths[4], $image_texts[4],
            $image_paths[5], $image_texts[5],
            $image_paths[6], $image_texts[6]
        );

    } else {
        $stmt = $conn->prepare("
            INSERT INTO work (
                id,
                image1_path, image1_text,
                image2_path, image2_text,
                image3_path, image3_text,
                image4_path, image4_text,
                image5_path, image5_text,
                image6_path, image6_text
            ) VALUES (
                1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->bind_param(
            "ssssssssssss",
            $image_paths[1], $image_texts[1],
            $image_paths[2], $image_texts[2],
            $image_paths[3], $image_texts[3],
            $image_paths[4], $image_texts[4],
            $image_paths[5], $image_texts[5],
            $image_paths[6], $image_texts[6]
        );
    }

    $stmt->execute();

    // Redirect only after form submission
    header("Location: editPortfolio.php?success=1");
    exit;
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
    Portfolio updated successfully!
  </div>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
      <div class="row justify-content-center">
 <?php for ($i = 1; $i <= 6; $i++): ?>
  <div class="form-group mg-t-20">
      <label>Image <?= $i ?></label>

      <?php $imgField = "image{$i}_path"; ?>
      <?php if (!empty($data[$imgField])): ?>
        <div class="mg-b-10">
          <img src="../<?= htmlspecialchars($data[$imgField]); ?>" width="120" class="img-thumbnail">
        </div>
      <?php endif; ?>

      <input type="file" name="<?= $imgField ?>" class="form-control-file" accept="image/*">

      <!-- Text field for image description -->
      <?php $textField = "image{$i}_text"; ?>
      <textarea name="<?= $textField ?>" class="form-control mg-t-10" rows="3" placeholder="Enter Image <?= $i ?> description"><?= htmlspecialchars($data[$textField] ?? '') ?></textarea>
  </div>
<?php endfor; ?>

<!-- Submit button -->
<div class="form-group mg-t-20">
    <button type="submit" class="btn btn-info">Update Portfolio</button>
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