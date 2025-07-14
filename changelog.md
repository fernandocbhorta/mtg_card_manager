# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),  
and this project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.0.0] - 2025-07-13
### Added
- Initial public release of **MagicCardManager** 🎉
- Full CRUD support for managing your Magic: The Gathering card collection.
- Automatic card data fetching from the [Scryfall API](https://scryfall.com/docs/api).
- JSON-based storage for metadata fields (e.g. prices, colors, keywords).
- Local caching of card images in multiple sizes (`png`, `large`, `art_crop`).
- Filtering, sorting, and search UI for cards by name, set, color, etc.
- Interactive quantity adjuster and delete functions.
- One-card detail view with expanded information.
- Responsive layout and light CSS styling.

### Changed
- Internal structure refactored to separate concerns: UI, logic, database connection, and card rendering.
- Database access converted to secure, parameterized PDO with error handling.
- Directory layout organized for maintainability and public contribution.

### Fixed
- Broken image fallback support and redundant API calls removed.
- Issues with some multiface cards and non-standard card images resolved.
- UTF-8 encoding bugs in card names and text corrected.

---

## [1.1.0] - 2025-07-14
### Added
- Lightbox-style image previews for individual cards.
- Improved `cardinfo` class for better visuals
- Added a navigation bar in preparation for future releases
- Placeholder text on input fields

---

### Ideas for the future
- Browse list of sets, and add cards directly from them
- CSV/JSON import/export of personal collection.
- Statistics and collection value summary view.
- Basic user authentication
- Database normalization
- Docker Compose support for full app + MySQL stack with persistent volumes.
- Sample `.env` configuration and secure credential management.