<?php
require_once '../includes/db.php';

$id = mb_strtolower(strtoupper($_POST['new_id'])) ?? '';
$code = mb_strtolower(strtoupper($_POST['new_code'])) ?? '';
$number = $_POST['new_number'] ?? '';
$quantity = (int)$_POST['new_quantity'];

if (empty($id) && $code && $number) {
    $url = "https://api.scryfall.com/cards/$code/$number";
} else {
    $url = "https://api.scryfall.com/cards/$id";
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: MTGCardCollector/1.0 (+https://yourdomain.example)",
    "Accept: */*"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$card = json_decode($response, true);
if (!$card || !isset($card['id'])) {
    http_response_code(404);
    return "Card not found or invalid data.";
}

$db = new PDO('mysql:host=localhost;dbname=class869_horta;charset=utf8mb4', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$images = $card['image_uris'] ?? [];
if (isset($card['card_faces']) && is_array($card['card_faces'])) {
    foreach ($card['card_faces'] as $face) {
        if (isset($face['image_uris'])) {
            $images = $face['image_uris'];
        }
    }
}

foreach (['large', 'png', 'art_crop'] as $size) {
    if (isset($images[$size])) {
        $folder = "../assets/images/$size";
        if (!is_dir($folder)) mkdir($folder, 0755, true);
        $imgUrl = strtok($images[$size], '?');
        $ext = ($size === 'png') ? 'png' : 'jpg';
        $imgPath = "$folder/{$card['id']}.$ext";
        file_put_contents($imgPath, file_get_contents($imgUrl));
    }
}

// Insert or update card in database
$stmt = $db->prepare("
    INSERT INTO mtg_cards (id, oracle_id, multiverse_ids, mtgo_id, mtgo_foil_id, tcgplayer_id, cardmarket_id,
        name, lang, released_at, layout, highres_image, image_status, mana_cost, cmc, type_line, oracle_text, power,
        toughness, colors, color_identity, keywords, legalities, games, reserved, game_changer, foil, nonfoil, finishes,
        oversized, promo, reprint, variation, set_id, set_code, set_name, set_type, collector_number, digital, rarity,
        card_back_id, artist, artist_ids, illustration_id, border_color, frame, full_art, textless, booster, story_spotlight,
        edhrec_rank, penny_rank, prices, image_uris, related_uris, purchase_uris, uri, scryfall_uri, set_uri, set_search_uri,
        scryfall_set_uri, rulings_uri, prints_search_uri, quantity
    ) VALUES (
        :id, :oracle_id, :multiverse_ids, :mtgo_id, :mtgo_foil_id, :tcgplayer_id, :cardmarket_id,
        :name, :lang, :released_at, :layout, :highres_image, :image_status, :mana_cost, :cmc, :type_line, :oracle_text, :power,
        :toughness, :colors, :color_identity, :keywords, :legalities, :games, :reserved, :game_changer, :foil, :nonfoil, :finishes,
        :oversized, :promo, :reprint, :variation, :set_id, :set_code, :set_name, :set_type, :collector_number, :digital, :rarity,
        :card_back_id, :artist, :artist_ids, :illustration_id, :border_color, :frame, :full_art, :textless, :booster, :story_spotlight,
        :edhrec_rank, :penny_rank, :prices, :image_uris, :related_uris, :purchase_uris, :uri, :scryfall_uri, :set_uri, :set_search_uri,
        :scryfall_set_uri, :rulings_uri, :prints_search_uri, :quantity
    ) ON DUPLICATE KEY UPDATE
        quantity = quantity + :quantity
");

$stmt->execute([
    ':id' => $card['id'],
    ':oracle_id' => $card['oracle_id'],
    ':multiverse_ids' => json_encode($card['multiverse_ids']),
    ':mtgo_id' => $card['mtgo_id'] ?? null,
    ':mtgo_foil_id' => $card['mtgo_foil_id'] ?? null,
    ':tcgplayer_id' => $card['tcgplayer_id'] ?? null,
    ':cardmarket_id' => $card['cardmarket_id'] ?? null,
    ':name' => $card['name'],
    ':lang' => $card['lang'],
    ':released_at' => $card['released_at'] ?? null,
    ':layout' => $card['layout'],
    ':highres_image' => (int) $card['highres_image'],
    ':image_status' => $card['image_status'] ?? null,
    ':mana_cost' => $card['mana_cost'] ?? '',
    ':cmc' => $card['cmc'] ?? 0,
    ':type_line' => $card['type_line'] ?? '',
    ':oracle_text' => $card['oracle_text'] ?? '',
    ':power' => $card['power'] ?? '',
    ':toughness' => $card['toughness'] ?? '',
    ':colors' => json_encode($card['colors'] ?? []),
    ':color_identity' => json_encode($card['color_identity'] ?? []),
    ':keywords' => json_encode($card['keywords'] ?? []),
    ':legalities' => json_encode($card['legalities'] ?? []),
    ':games' => json_encode($card['games'] ?? []),
    ':reserved' => (int) ($card['reserved'] ?? false),
    ':game_changer' => (int) ($card['game_changer'] ?? false),
    ':foil' => (int) ($card['foil'] ?? false),
    ':nonfoil' => (int) ($card['nonfoil'] ?? false),
    ':finishes' => json_encode($card['finishes'] ?? []),
    ':oversized' => (int) ($card['oversized'] ?? false),
    ':promo' => (int) ($card['promo'] ?? false),
    ':reprint' => (int) ($card['reprint'] ?? false),
    ':variation' => (int) ($card['variation'] ?? false),
    ':set_id' => $card['set_id'],
    ':set_code' => $card['set'],
    ':set_name' => $card['set_name'],
    ':set_type' => $card['set_type'],
    ':collector_number' => $card['collector_number'],
    ':digital' => (int) ($card['digital'] ?? false),
    ':rarity' => $card['rarity'],
    ':card_back_id' => $card['card_back_id'],
    ':artist' => $card['artist'],
    ':artist_ids' => json_encode($card['artist_ids'] ?? []),
    ':illustration_id' => $card['illustration_id'],
    ':border_color' => $card['border_color'],
    ':frame' => $card['frame'] ?? '',
    ':full_art' => (int) ($card['full_art'] ?? false),
    ':textless' => (int) ($card['textless'] ?? false),
    ':booster' => (int) ($card['booster'] ?? false),
    ':story_spotlight' => (int) ($card['story_spotlight'] ?? false),
    ':edhrec_rank' => $card['edhrec_rank'] ?? null,
    ':penny_rank' => $card['penny_rank'] ?? null,
    ':prices' => json_encode($card['prices'] ?? []),
    ':image_uris' => json_encode($card['image_uris'] ?? []),
    ':related_uris' => json_encode($card['related_uris'] ?? []),
    ':purchase_uris' => json_encode($card['purchase_uris'] ?? []),
    ':uri' => $card['uri'],
    ':scryfall_uri' => $card['scryfall_uri'],
    ':set_uri' => $card['set_uri'],
    ':set_search_uri' => $card['set_search_uri'],
    ':scryfall_set_uri' => $card['scryfall_set_uri'],
    ':rulings_uri' => $card['rulings_uri'],
    ':prints_search_uri' => $card['prints_search_uri'],
    ':quantity' => $quantity
]);

// Redirect back
header("Location: ../pages/index.php");
exit;
?>
