<?php require_once '../includes/db.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="top-controls">
<?php if (!isset($_GET['search']) && !isset($_GET['sort'])) { ?>
    <p>Use the search box to find cards by name, set, or ID.</p>
    <p>Sort cards by name, quantity, or set</p>

<?php 
}
else {
    echo "<p><a href='index.php'>Reset Search/Sort</a></p>";
}
?>
</div>

<!-- Search & Add New Card Forms here -->
<form method="get">
<input type="text" name="search" placeholder="Search name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
<select name="sort">
    <option value="name" <?= ($_GET['sort'] ?? '') === 'name' ? 'selected' : '' ?>>Sort by Name</option>
    <option value="type_line" <?= ($_GET['sort'] ?? '') === 'type_line' ? 'selected' : '' ?>>Sort by Type</option>        
    <option value="colors" <?= ($_GET['sort'] ?? '') === 'colors' ? 'selected' : '' ?>>Sort by Color</option>
    <option value="set_name" <?= ($_GET['sort'] ?? '') === 'set_name' ? 'selected' : '' ?>>Sort by Set</option>
    <option value="quantity" <?= ($_GET['sort'] ?? '') === 'quantity' ? 'selected' : '' ?>>Sort by Quantity</option>
    <option value="prices" <?= ($_GET['sort'] ?? '') === 'prices' ? 'selected' : '' ?>>Sort by Price</option>
</select>
<button type="submit">Search / Sort</button>
</form>

<!-- Add New Card -->
<h3>Add New Card</h3>
<form method="post" action="../actions/add_card.php">
    <label>Card ID:</label><br>
    <input type="text" name="new_id"><br>
    <label>Card Code:</label><br>
    <input type="text" name="new_code"><br>
    <label>Card Number:</label><br>
    <input type="number" name="new_number"><br>
    <label>Quantity:</label><br>
    <input type="number" name="new_quantity" min="1" value="1" required><br>
    <button type="submit" name="add_card">Add Card</button>
</form>
<!-- List cards here -->
 <?php
 $where = '';
$params = [];
if (!empty($_GET['search'])) {
    $where = "WHERE name LIKE ? OR set_name LIKE ? OR id LIKE ? OR type_line LIKE ?";    
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
    $params[] = '%' . $_GET['search'] . '%';
}

$orderMode = [
    'name' => 'name, set_name',
    'quantity' => 'quantity DESC, name, set_name',
    'set_name' => 'set_name, name',
    'type_line' => 'type_line, name',
    'colors' => "JSON_UNQUOTE(JSON_EXTRACT(colors, '$')), name",
    'prices' => "CAST(JSON_UNQUOTE(JSON_EXTRACT(prices, '$.usd')) AS DECIMAL(10,2)) DESC, name"
];

if(!empty($_GET['sort']) && array_key_exists($_GET['sort'], $orderMode)) {
    $orderBy = $orderMode[$_GET['sort']];
} else {
    $orderBy = 'name';
}

$stmt = $conn->prepare("SELECT * FROM mtg_cards $where ORDER BY $orderBy");
$stmt->execute($params);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get the total number of cards
$totalCards = count($cards);
if ($totalCards > 0) {
    echo "<h3>Total Cards: $totalCards</h3>";
} 
else {
    echo "<h3>No cards found.</h3>";
}
foreach ($cards as $row) {
    $id = $row['id'];
    $name = htmlspecialchars($row['name']);
    $quantity = (int) $row['quantity'];
    $set = htmlspecialchars($row['set_name']);
    $prices = json_decode($row['prices'], true) ?? [];
    $oracleText = htmlspecialchars($row['oracle_text'] ?? '');
    $rarity = htmlspecialchars($row['rarity'] ?? 'Unknown');
    $price_usd = $prices['usd'] ?? 'N/A';
    $shortcode = $row['set_code'].'/'.$row['collector_number'] ?? '';    

    echo "<div class='card'>";
    if ($totalCards > 0 && file_exists("../assets/images/png/$id.png")) {
        echo "<a href='card.php?id=$id'><img src='../assets/images/png/$id.png' alt='$name'></a>";
    }	
    ?>
    
    <hr><form method='post' action='../actions/update_quantity.php'>
    <input type='hidden' name='id' value='<?php echo $id; ?>'>
    <label>Adjust Quantity:</label><br>
    <input type='number' name='quantity' value='<?php echo $quantity; ?>' min='0' required>
    <button type='submit' name='update_quantity'>Update</button>
    </form>
    
    <form method='post' action='../actions/delete_card.php' onsubmit='return confirm(\"Delete this card?\");'>
    <input type='hidden' name='id' value='<?php echo $id; ?>'>
    <button type='submit' name='delete'>Delete Card</button>
    </form>
    </div>
<?php 
}
include '../includes/footer.php'; ?>