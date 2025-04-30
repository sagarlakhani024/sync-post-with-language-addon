# Sync Post with Language Addon

This WordPress plugin is an add-on for the **Sync Post With Other Site** plugin. It uses the OpenAI GPT-4o model to automatically translate Gutenberg block-based content into different languages while preserving the original HTML structure and block hierarchy.

---

## 🧩 Features

- 🧠 Translates full post content using OpenAI GPT-4o
- 🔤 Supports multiple languages (Italian, German, Spanish, French, Dutch)
- 🧱 Maintains original Gutenberg block structure
- 🌐 Language selection via WordPress admin settings
- 📤 Integrates with the "Sync Post With Other Site" plugin to sync translated content
- 🔐 Secures API access using stored OpenAI API Key

---

## ⚙️ Requirements

- PHP 7.4 or higher
- WordPress 5.8+
- [Sync Post With Other Site](https://wordpress.org/plugins/sync-post-with-other-site/) plugin
- OpenAI API key ([Get one here](https://platform.openai.com/account/api-keys))

---

## 🛠️ Installation

1. Upload the plugin to your `/wp-content/plugins/` directory or install via the Plugins menu.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **Settings > OpenAI Translation Settings** to:
   - Enter your OpenAI API key
   - Choose the target translation language
4. Posts synced via "Sync Post With Other Site" will now be translated before sending.

---

## 🧪 Supported Block Types

All block types that use `innerHTML` or `innerContent`, including:

- Paragraphs
- Headings
- HTML blocks
- Lists
- Custom blocks with textual content

---

## 🔒 Security

- Uses WordPress nonces for settings forms
- Sanitizes and validates all inputs
- API key is stored securely via `update_option`

---

## 📦 Customization

You can extend or customize:

- The list of supported languages
- The translation model used (currently `gpt-4o`)
- Filters/hooks to modify how blocks are processed

---

## 🛠 Development

This plugin adheres to WordPress coding standards. To check coding quality:

```bash
composer install
vendor/bin/phpcs --standard=WordPress .
vendor/bin/phpcbf --standard=WordPress .