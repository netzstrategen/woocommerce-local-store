# WooCommerce Local Store

Allows to manage WooCommerce products stock in multiple local stores.

## How to Install

Requirements

- WooCommerce

Procedure

1. Add the plugin as a Git submodule.
    ```sh
    git submodule add --name woocommerce-local-store git@github.com:netzstrategen/woocommerce-local-store.git wp-content/plugins/woocommerce-local-store
    ```

2. Activate the plugin.
    ```sh
    wp plugin activate woocommerce-local-store
    ```

## How to Contribute

Any kind of contribution is very welcome!

Please, be sure to read our [Code of Conduct](https://github.com/netzstrategen/woocommerce-local-store/blob/master/CODE_OF_CONDUCT) before start contributing.

If you notice something wrong please [open an issue](https://github.com/netzstrategen/woocommerce-local-store/issues) or create a [Pull Request](https://github.com/netzstrategen/woocommerce-local-store/pulls) or just send an email to [tech@netztsrategen.com](mailto:tech@netztsrategen.com).
If you want to warn me about an important security vulnerability, please use [the GitHub Security page](https://github.com/netzstrategen/woocommerce-local-store/network/alerts).

## Dev Setup

Requirements

- [PHP](https://www.php.net/) >= 7.3
- [Node.js](https://nodejs.org/en/) >= 12.x
- [Gulp](https://gulpjs.com/) >= 4.x

Procedure

```sh
git clone git@github.com:netzstrategen/woocommerce-local-store.git
cd woocommerce-local-store
npm install
npm run build
```

### Available Scripts

- `npm run serve` ·Builds all assets and watches for changes. Alias for `gulp watch`.
- `npm run build` · Builds all the assets. Alias for `gulp build`.


## License

[GPL-2.0 License](https://github.com/netzstrategen/woocommerce-local-store/blob/master/LICENSE) © [netzstrategen](https://netzstrategen.com)
