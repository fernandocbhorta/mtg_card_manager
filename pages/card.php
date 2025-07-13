<?php require_once '../includes/db.php'; ?>
<?php include '../includes/header.php'; ?>

<?php
// === Display Cards
$where = '';
$params = [];
if (!empty($_GET['id'])) {
    $where = "WHERE id LIKE ?";
    $params[] = '%' . $_GET['id'] . '%';
}
else {
    echo "<h2>No cards found.</h2>";
    return http_response_code();
}

$stmt = $conn->prepare("SELECT * FROM mtg_cards $where");
$stmt->execute($params);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get the total number of cards
$totalCards = count($cards);
switch ($totalCards) {
    case 0:
        echo "<h2>No cards found.</h2>";
        break;
    default:
        echo "<h2>".$cards[0]['name']."</h2>";
        break;    
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
    echo "<a href='../assets/images/png/$id.png'><img src='../assets/images/png/$id.png' alt='$name'></a>";    
    echo "</div>";

    echo "<div class='cardinfo'>";
    foreach ($row as $key => $value) {        
        if(in_array ($key, ['lang', 'released_at', 'mana_cost', 'type_line', 'colors', 'oracle_text', 'power', 'toughness', 'set_name', 'keywords', 'collector_number', 'rarity', 'quantity'])) {
            if (is_array($value)) {
            $value = implode(', ', $value);
        }
        echo "<strong>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . ":</strong> " . htmlspecialchars($value) . "<br>";
        }            
    }
    echo "<br>Price: <strong>$$price_usd</strong><br>";
    echo "</div>";    
}
?>

<?php include '../includes/footer.php'; ?>
