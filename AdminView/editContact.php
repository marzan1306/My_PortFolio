<?php
require_once __DIR__ . '/../config/db.php';

// Fetch existing contact data
$contact_result = $conn->query("SELECT * FROM contact WHERE id = 1");

if ($contact_result && $contact_result->num_rows > 0) {
    $contact_data = $contact_result->fetch_assoc();
} else {
    // default empty values if no row exists
    $contact_data = [
        'description' => '',
        'office_city' => '',
        'address' => '',
        'phone' => '',
        'email' => ''
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get user input safely (optional trim)
    $description = $_POST['description'] ?? '';
    $office_city = $_POST['office_city'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';

    // Check if row exists
    $check = $conn->query("SELECT id FROM contact WHERE id = 1");

    if ($check && $check->num_rows > 0) {
        // Row exists → UPDATE
        $stmt = $conn->prepare("
            UPDATE contact
            SET description=?, office_city=?, address=?, phone=?, email=?
            WHERE id=1
        ");

        $stmt->bind_param(
            "sssss",
            $description,
            $office_city,
            $address,
            $phone,
            $email
        );

    } else {
        // Row does not exist → INSERT
        $stmt = $conn->prepare("
            INSERT INTO contact (id, description, office_city, address, phone, email)
            VALUES (1, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssss",
            $description,
            $office_city,
            $address,
            $phone,
            $email
        );
    }

    $stmt->execute();

    // Optional: update $contact_data to show updated values in form immediately
    $contact_data = [
        'description' => $description,
        'office_city' => $office_city,
        'address' => $address,
        'phone' => $phone,
        'email' => $email
    ];

    header("Location: editContact.php?success=1");
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
    Contact page updated successfully!
  </div>
<?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="row justify-content-center">

      <div class="col-12 col-lg-8 col-xl-6">
    <div class="card pd-15 pd-sm-30 form-layout form-layout-5">

  <div class="col-12 col-lg-8 col-xl-6">

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Description</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="description" class="form-control"
                 value="<?= htmlspecialchars($contact_data['description']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Office city</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <textarea rows="3" name="office_city" class="form-control"><?= htmlspecialchars($contact_data['office_city']) ?></textarea>
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Address</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="address" class="form-control"
                 value="<?= htmlspecialchars($contact_data['address']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Phone</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="phone" class="form-control"
                 value="<?= htmlspecialchars($contact_data['phone']) ?>">
        </div>
      </div>

      <div class="row row-xs mg-t-20 align-items-center">
        <label class="col-12 col-sm-4 form-control-label">Email</label>
        <div class="col-12 col-sm-8 mg-t-10 mg-sm-t-0">
          <input type="text" name="email" class="form-control"
                 value="<?= htmlspecialchars($contact_data['email']) ?>">
        </div>
      </div>

    
      <div class="row row-xs mg-t-30">
        <div class="col-12 col-sm-8 mg-sm-l-auto text-right">
          <button type="submit" name="contact" class="btn btn-info">
            Update contact
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