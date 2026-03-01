Editstats · PHP
Copy

<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?> -->

<?php
require_once __DIR__ . '/../config/db.php';

// Fetch existing stats data (row id = 1)
$result = $conn->query("SELECT * FROM stats WHERE id = 1");
$stats_data = $result && $result->num_rows > 0 ? $result->fetch_assoc() : [];

// Fetch testimonials BEFORE any POST handling so $row is always available
$test_result = $conn->query("SELECT * FROM testimonial ORDER BY id DESC");
$testimonials = $test_result ? $test_result->fetch_all(MYSQLI_ASSOC) : [];
$row = $testimonials[0] ?? [];

// ── Handle "Update Stats" submission ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact'])) {

    $item        = (int)$_POST['item'];
    $product     = (int)$_POST['product'];
    $experience  = (int)$_POST['experience'];
    $client      = $_POST['client'];

    if (!empty($stats_data)) {
        $stmt = $conn->prepare("
            UPDATE stats SET
                item=?, product=?, experience=?, client=?
            WHERE id=1
        ");
        // FIX: removed trailing comma from bind_param
        $stmt->bind_param("iiis", $item, $product, $experience, $client);
    } else {
        $stmt = $conn->prepare("
            INSERT INTO stats (id, item, product, experience, client)
            VALUES (1, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiis", $item, $product, $experience, $client);
    }

    $stmt->execute();
    header("Location: editStats.php?success=1");
    exit;
}

// ── Handle "Add Testimonial" submission ─────────────────────────────────────
if (isset($_POST['add_quote'])) {
    // echo "asdasd";
    // die();
    $quote       = $_POST['quote'];
    $name        = $_POST['name'];
    $designation = $_POST['designation'];

    // FIX: use $row (the fetched testimonial) instead of undefined $test_data
    $image_path = $row['image'] ?? '';

    // FIX: file input name is 'image_path' in HTML, so check $_FILES['image_path']
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {

        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        $file_ext = strtolower(pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed)) {
            die("Only JPG, JPEG, PNG & WEBP allowed.");
        }

        $new_name    = time() . "_" . uniqid() . "." . $file_ext;
        $upload_path = __DIR__ . "/../uploads/" . $new_name;

        if (move_uploaded_file($_FILES['image_path']['tmp_name'], $upload_path)) {

            // Delete old image if it exists
            // FIX: use $row['image'] instead of undefined $test_data['image']
            if (!empty($row['image']) && file_exists(__DIR__ . "/../uploads/" . basename($row['image']))) {
                unlink(__DIR__ . "/../uploads/" . basename($row['image']));
            }

            $image_path = "uploads/" . $new_name;
        }
    }
    $stmt = $conn->prepare("
        INSERT INTO testimonial (quote, name, designation, image)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $quote, $name, $designation, $image_path);
    $stmt->execute();

    header("Location: editStats.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Starlight Responsive Bootstrap 4 Admin Template</title>

    <base href="/MyPortfolio/AdminView/template/">

    <!-- vendor css -->
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
        Updated successfully!
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
          <div class="card pd-15 pd-sm-30 form-layout form-layout-5">
            <div class="col-12 col-lg-8 col-xl-6">

              <!-- Stats fields -->
              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Item</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="number" name="item" class="form-control"
                         value="<?= htmlspecialchars($stats_data['item'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Product</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="number" name="product" class="form-control"
                         value="<?= htmlspecialchars($stats_data['product'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Experience</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="number" name="experience" class="form-control"
                         value="<?= htmlspecialchars($stats_data['experience'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Clients</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" name="client" class="form-control"
                         value="<?= htmlspecialchars($stats_data['client'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-30">
                <div class="col-12 col-sm-8 mg-sm-l-auto text-right">
                  <button type="submit" name="contact" class="btn btn-info">
                    Update Stats
                  </button>
                </div>
              </div>

              <hr class="mg-t-30">

              <!-- Testimonial fields -->
              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Quote</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <textarea rows="3" name="quote" class="form-control"><?= htmlspecialchars($row['quote'] ?? '') ?></textarea>
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Name</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" name="name" class="form-control"
                         value="<?= htmlspecialchars($row['name'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Designation</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
                  <input type="text" name="designation" class="form-control"
                         value="<?= htmlspecialchars($row['designation'] ?? '') ?>">
                </div>
              </div>

              <div class="row row-xs mg-t-20 align-items-center">
                <label class="col-12 col-sm-4 form-control-label">Image</label>
                <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">

                  <?php if (!empty($row['image'])): ?>
                    <div class="mg-b-10">
                      <!-- FIX: use $row['image'] (correct column name) -->
                      <img src="../<?= htmlspecialchars($row['image']) ?>" width="120" class="img-thumbnail">
                    </div>
                  <?php endif; ?>

                  <label class="custom-file">
                    <!-- FIX: name matches $_FILES['image_path'] checked in PHP -->
                    <input type="file" name="image_path" class="custom-file-input" accept="image/*">
                    <span class="custom-file-control"></span>
                  </label>
                </div>
              </div>

              <div class="row row-xs mg-t-20">
                <div class="col-12 text-right">
                  <button type="submit" name="add_quote" class="btn btn-info">Add Testimonial</button>
                </div>
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