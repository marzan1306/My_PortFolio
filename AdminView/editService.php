<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?> -->


<?php
require_once __DIR__ . '/../config/db.php';

// Fetch existing data
$sv_result = $conn->query("SELECT * FROM services WHERE id = 1");

if ($sv_result && $sv_result->num_rows > 0) {
    $sv_data = $sv_result->fetch_assoc();
} else {
    $sv_data = [
        'title1' => '',
        'title1_text' => '',
        'title2' => '',
        'title2_text' => '',
        'title3' => '',
        'title3_text' => '',
        'title4' => '',
        'title4_text' => '',
        'title5' => '',
        'title5_text' => '',
        'title6' => '',
        'title6_text' => ''
    ];
}

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title1 = $_POST['title1'] ?? '';
    $title1_text = $_POST['title1_text'] ?? '';

    $title2 = $_POST['title2'] ?? '';
    $title2_text = $_POST['title2_text'] ?? '';

    $title3 = $_POST['title3'] ?? '';
    $title3_text = $_POST['title3_text'] ?? '';

    $title4 = $_POST['title4'] ?? '';
    $title4_text = $_POST['title4_text'] ?? '';

    $title5 = $_POST['title5'] ?? '';
    $title5_text = $_POST['title5_text'] ?? '';

    $title6 = $_POST['title6'] ?? '';
    $title6_text = $_POST['title6_text'] ?? '';

    // Check if row exists
    $check = $conn->query("SELECT id FROM services WHERE id = 1");

    if ($check && $check->num_rows > 0) {

        // UPDATE
        $stmt = $conn->prepare("
            UPDATE services 
            SET title1=?, title1_text=?,
                title2=?, title2_text=?,
                title3=?, title3_text=?,
                title4=?, title4_text=?,
                title5=?, title5_text=?,
                title6=?, title6_text=?
            WHERE id=1
        ");

        $stmt->bind_param(
            "ssssssssssss",
            $title1, $title1_text,
            $title2, $title2_text,
            $title3, $title3_text,
            $title4, $title4_text,
            $title5, $title5_text,
            $title6, $title6_text
        );

    } else {

        // INSERT
        $stmt = $conn->prepare("
            INSERT INTO services 
            (id, title1, title1_text,
             title2, title2_text,
             title3, title3_text,
             title4, title4_text,
             title5, title5_text,
             title6, title6_text)
            VALUES
            (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssssss",
            $title1, $title1_text,
            $title2, $title2_text,
            $title3, $title3_text,
            $title4, $title4_text,
            $title5, $title5_text,
            $title6, $title6_text
        );
    }

    $stmt->execute();
    header("Location: editService.php?success=1");
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
    Service page updated successfully!
  </div>
<?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row justify-content-center">

      <div class="col-12 col-lg-8 col-xl-6">
    <div class="card pd-15 pd-sm-30 form-layout form-layout-5">

  <div class="col-12 col-lg-8 col-xl-6">

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 1</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title1" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title1']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 1 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title1_text" class="form-control"><?= htmlspecialchars($sv_data['title1_text']) ?></textarea>
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 2</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title2" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title2']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 2 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title2_text" class="form-control"><?= htmlspecialchars($sv_data['title2_text']) ?></textarea>
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 3</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title3" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title3']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 3 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title3_text" class="form-control"><?= htmlspecialchars($sv_data['title3_text']) ?></textarea>
        </div>
      </div>


      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 4</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title4" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title4']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 4 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title4_text" class="form-control"><?= htmlspecialchars($sv_data['title4_text']) ?></textarea>
        </div>
      </div>


      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 5</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title5" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title5']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 5 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title5_text" class="form-control"><?= htmlspecialchars($sv_data['title5_text']) ?></textarea>
        </div>
      </div>


      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 6</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="title6" class="form-control"
                 value="<?= htmlspecialchars($sv_data['title6']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Title 6 Text</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="title6_text" class="form-control"><?= htmlspecialchars($sv_data['title6_text']) ?></textarea>
        </div>
      </div>



      <div class="row row-xs mg-t-30">
        <div class="col-12 col-sm-8 mg-sm-l-auto text-right">
          <button type="submit" name="services" class="btn btn-info">
            Update Service 
          </button>
        </div>
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