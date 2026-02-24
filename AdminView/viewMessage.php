<!-- <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?> -->

<?php
require_once "../config/db.php";
$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = [];
if ($result && $result->num_rows > 0) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
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
        

        <div class="card pd-20 pd-sm-40 mg-t-50">
          

          <div class="table-responsive">
            <table class="table table-hover table-bordered mg-b-0">
              <thead class="bg-info">
                <tr>
                  <th>
                    <!-- <label class="ckbox mg-b-0">
                      <input type="checkbox"><span></span>
                    </label> -->
                  </th>
                  <th>Name</th>
                  <th>Message</th>
                  <th>Sent At</th>
                </tr>
              </thead>
              <tbody>
                 <?php foreach ($messages as $msg):
                  //print_r($msg);
                   ?> 
                <tr>
                  <td>
                    <label class="ckbox mg-b-0">
                      <input type="checkbox"><span></span>
                    </label>
                  </td>
                 
                   
                  <td><?= htmlspecialchars($msg['name']) ?></td>
                  <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                  <td><?= $msg['created_at'] ?></td>
                  </tr>
                <?php endforeach; ?>

              </tbody>
            </table>
          </div><!-- table-responsive -->
</body>