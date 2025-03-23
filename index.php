<?php
$page = $_GET['page'] ?? 'home';
//$safe_page = preg_replace("/[^a-zA-Z0-9_-]/", "", $page);
//$file_html = "pages/$safe_page.html";
//$file_php = "pages/$safe_page.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Best Hotel in Missouri!" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mirage Hotel</title>
  <script src="script.js" defer></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="icon" type="image/png" href="/assets/favicon.png">
<link rel="stylesheet" href="style.css">
</head>
<body class="d-flex flex-column min-vh-100">
  <div id="header-container"><?php include "templates/header.html"; ?></div>

  <div class="container-fluid flex-grow-1 d-flex flex-column flex-lg-row p-0">
    <main id="content-container" class="flex-grow-1"></main>

    <aside id="aside-container" class="d-none p-3"></aside>
  </div>

  <div id="footer-container"><?php include "templates/footer.html"; ?></div>
  <script src="bootstrap.bundle.min.js"></script>
</body>
</html>

