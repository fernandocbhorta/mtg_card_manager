SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `mtg_cards`;
CREATE TABLE IF NOT EXISTS `mtg_cards` (
  `id` char(36) NOT NULL,
  `oracle_id` char(36) DEFAULT NULL,
  `multiverse_ids` text,
  `mtgo_id` int DEFAULT NULL,
  `mtgo_foil_id` int DEFAULT NULL,
  `tcgplayer_id` int DEFAULT NULL,
  `cardmarket_id` int DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lang` varchar(10) DEFAULT NULL,
  `released_at` date DEFAULT NULL,
  `layout` varchar(50) DEFAULT NULL,
  `highres_image` tinyint(1) DEFAULT NULL,
  `image_status` varchar(50) DEFAULT NULL,
  `mana_cost` varchar(50) DEFAULT NULL,
  `cmc` decimal(5,2) DEFAULT NULL,
  `type_line` varchar(255) DEFAULT NULL,
  `oracle_text` text,
  `power` varchar(10) DEFAULT NULL,
  `toughness` varchar(10) DEFAULT NULL,
  `colors` json DEFAULT NULL,
  `color_identity` json DEFAULT NULL,
  `keywords` json DEFAULT NULL,
  `legalities` json DEFAULT NULL,
  `games` json DEFAULT NULL,
  `reserved` tinyint(1) DEFAULT NULL,
  `game_changer` tinyint(1) DEFAULT NULL,
  `foil` tinyint(1) DEFAULT NULL,
  `nonfoil` tinyint(1) DEFAULT NULL,
  `finishes` json DEFAULT NULL,
  `oversized` tinyint(1) DEFAULT NULL,
  `promo` tinyint(1) DEFAULT NULL,
  `reprint` tinyint(1) DEFAULT NULL,
  `variation` tinyint(1) DEFAULT NULL,
  `set_id` char(36) DEFAULT NULL,
  `set_code` varchar(10) DEFAULT NULL,
  `set_name` varchar(100) DEFAULT NULL,
  `set_type` varchar(50) DEFAULT NULL,
  `collector_number` varchar(20) DEFAULT NULL,
  `digital` tinyint(1) DEFAULT NULL,
  `rarity` varchar(20) DEFAULT NULL,
  `card_back_id` char(36) DEFAULT NULL,
  `artist` varchar(100) DEFAULT NULL,
  `artist_ids` json DEFAULT NULL,
  `illustration_id` char(36) DEFAULT NULL,
  `border_color` varchar(20) DEFAULT NULL,
  `frame` varchar(10) DEFAULT NULL,
  `full_art` tinyint(1) DEFAULT NULL,
  `textless` tinyint(1) DEFAULT NULL,
  `booster` tinyint(1) DEFAULT NULL,
  `story_spotlight` tinyint(1) DEFAULT NULL,
  `edhrec_rank` int DEFAULT NULL,
  `penny_rank` int DEFAULT NULL,
  `prices` json DEFAULT NULL,
  `image_uris` json DEFAULT NULL,
  `related_uris` json DEFAULT NULL,
  `purchase_uris` json DEFAULT NULL,
  `uri` text,
  `scryfall_uri` text,
  `set_uri` text,
  `set_search_uri` text,
  `scryfall_set_uri` text,
  `rulings_uri` text,
  `prints_search_uri` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `quantity` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;
