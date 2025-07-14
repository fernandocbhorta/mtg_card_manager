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
    return http_response_code();
}

$stmt = $conn->prepare("SELECT * FROM mtg_cards $where");
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
                if (in_array($key, [
                    'lang', 'released_at', 'mana_cost', 'type_line', 'colors', 'oracle_text',
                    'power', 'toughness', 'set_name', 'keywords', 'collector_number', 'rarity', 'quantity'
                ])) {
                    if (is_array($value)) {
                        $value = implode(', ', $value);
                    }
                    echo "<strong>" . htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) . ":</strong> " . htmlspecialchars($value) . "<br>";
                }
            }
            ?>
            <br><strong>Price:</strong> $<?= htmlspecialchars($price_usd) ?><br>
        </div>
    </div>

<?php endforeach; ?>

<!-- === Lightbox Logic -->
<script>
function openLightbox(src) {
    basicLightbox.create(`<img src="${src}" style="max-width: 100%; max-height: 90vh;">`).show();
}
</script>

<?php include '../includes/footer.php'; ?>
