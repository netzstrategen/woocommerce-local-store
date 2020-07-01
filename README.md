![GitHub](https://img.shields.io/github/license/netzstrategen/woocommerce-multi-stock?color=6AAAE9&style=for-the-badge)

# Multi Stock

This plugin make possible to manage WooCommerce products stock in multiple locations

## :gear: How to Install

:warning: The plugin requires WooCommerce to be installed.

1. Open your WordPress installation root path in the terminal.
2. `git submodule add --name multi-stock -- git@github.com:netzstrategen/woocommerce-multi-stock.git wp-content/plugins/multi-stock`
3. Activate the plugin: `wp plugin activate multi-stock`

## :building_construction: How to Contribute

Any kind of contribution is very welcome!

Please, be sure to read our [Code of Conduct](https://github.com/netzstrategen/woocommerce-multi-stock/blob/master/CODE_OF_CONDUCT) before start contributing.

If you notice something wrong please [open an issue](https://github.com/netzstrategen/woocommerce-multi-stock/issues) or create a [Pull Request](https://github.com/netzstrategen/woocommerce-multi-stock/pulls) or just send an email to [tech@netztsrategen.com](mailto:tech@netztsrategen.com).
If you want to warn me about an important security vulnerability, please use [the GitHub Security page](https://github.com/netzstrategen/woocommerce-multi-stock/network/alerts).

## :hammer_and_wrench: Dev Setup

Requirements:

- [PHP](https://www.php.net/) >= 7.x
- [Node.js](https://nodejs.org/en/) >= 12.x
- [Gulp](https://gulpjs.com/) >= 4.x

Setup steps:

1. `git clone git@github.com:netzstrategen/woocommerce-multi-stock.git`
2. `cd woocommerce-multi-stock`
3. `npm install`
4. `npm run build`

### Available Scripts

- `npm run serve` ·Builds all assets and watches for changes. Alias for `gulp watch`.
- `npm run build` · Builds all the assets. Alias for `gulp build`.

## :page_facing_up: License

[GPL-2.0 License](https://github.com/netzstrategen/woocommerce-multi-stock/blob/master/LICENSE) © [netzstrategen](https://netzstrategen.com)
