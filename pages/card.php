<?php require_once '../includes/db.php'; ?>
<?php include '../includes/header.php'; ?>

<?php
// === Prepare query
$where = '';
$params = [];

if (!empty($_GET['id'])) {
    $where = "WHERE id LIKE ?";
    $params[] = '%' . $_GET['id'] . '%';
} else {
    echo "<h2>No cards found.</h2>";
    return http_response_code(404);
}

$stmt = $conn->prepare("SELECT id, oracle_id, name, DATE_FORMAT(released_at, '%M %e, %Y') 'release date', set_name, collector_number, rarity, type_line, oracle_text, prices, quantity FROM mtg_cards $where");
$stmt->execute($params);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === Display heading
$totalCards = count($cards);

if ($totalCards === 0) {
    echo "<h2>No cards found.</h2>";
} else {
    echo "<h2>" . htmlspecialchars($cards[0]['name']) . "</h2>";
}

// === Render card results
foreach ($cards as $row):
    $id = $row['id'];
    $name = htmlspecialchars($row['name']);
    $oracle_id = $row['oracle_id'];
    $imgPath = "../assets/images/png/$id.png";
    $prices = json_decode($row['prices'], true) ?? [];
    $price_usd = $prices['usd'] ?? 'N/A';
    ?>

    <div class="card-wrapper">
        <div class="card-image">
            <img src="<?= $imgPath ?>" alt="<?= $name ?>" onclick='openLightbox("<?= $imgPath ?>")' style="cursor: zoom-in;">
        </div>

        <div class="cardinfo">
            <?php
            foreach ($row as $key => $value) {
                if (!in_array($key, ['id', 'oracle_id', 'prices', 'name'])) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    echo "<strong>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . ":</strong> " . htmlspecialchars($value) . "<br>";
                }
            }
            ?>
            <strong>Price:</strong> $<?= htmlspecialchars($price_usd) ?>
        </div>
    </div>
<?php endforeach;

// === Render additional cards
$stmt2 = $conn->prepare("SELECT id, set_name, quantity FROM mtg_cards WHERE oracle_id LIKE '$oracle_id' AND id NOT LIKE '$id' ORDER BY released_at, collector_number");
$stmt2->execute();
$extras = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$totalExtras = count($extras);

if ($totalExtras > 0) {
    echo '<h3>See also </h3>';
    // === Render additional results
    foreach ($extras as $row):
        $id = $row['id'];
        $imgPath = "../assets/images/png/$id.png";
        ?>
        <a href="card.php?id=<?= $id ?>">
            <img src="<?= $imgPath ?>" title="<?= $row['set_name'].' - x'.$row['quantity'] ?>" alt="<?= $row['set_name'].' - x'.$row['quantity'] ?>" style="width: 15%;">
        </a>
    <?php endforeach;
}?>

<!-- === Lightbox Logic -->
<script>
function openLightbox(src) {
    basicLightbox.create(`<img src="${src}" style="max-width: 100%; max-height: 90vh;">`).show();
}
</script>

<?php include_once '../includes/footer.php'; ?>
