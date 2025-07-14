<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Magic: the Gathering Card Collection Manager</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basiclightbox@5/dist/basicLightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/basiclightbox@5/dist/basicLightbox.min.js"></script>
</head>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <ul>
        <li><a href="../pages/index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Manager</a></li>
        <li><a href="../pages/list.php" class="<?= $currentPage === 'list.php' ? 'active' : '' ?>">Card List</a></li>        
    </ul>
</nav>
<body>
<h1>Magic: the Gathering</h1>
<h2>Card Collection Manager</h2>
