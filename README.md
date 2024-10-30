# Better Sharing WP

This WordPress plugin is to be used with your CloudSponge account.

## Requirements

- WordPress
- Node / NPM
- Composer

Tested with PHP 7.0

## Installation

- Install using one our pre-zipped releases
  \- **OR** -
- Clone repo into `wp-content/plugins`
- Run `npm install && composer install && npm run build`
- Activate plugin via WordPress admin

## Plugin Build

Need to build the plugin to install on a WordPress site? run `npm run build:plugin` and follow the prompts

## Development

Follow Installation instructions then run `npm run start`

## AddOns

Instructions in [Wiki](https://github.com/cloudsponge/better-sharing-wp/wiki/Creating-an-AddOn)

## Shortcode

Add `[better-sharing]` to a shortcode block to render the Share-via-Email block. This will render the block with default attributes.

You may customize the output with the following arguments:
- `id` - UI Template ID
- `referrallink` - set a custom referral link 
All arguments are case *insensitive*.

Example: `[better-sharing id="1" referrallink="http://your-custom-url"]`

## Unit Testing

We use PHPUnit and Composer to run our unit tests for PHP. To initialize your environment you'll need to run the following:

    npm install
    npm run test

    composer install
    bin/install-wp-tests.sh bswp-test root '' 127.0.0.1
    vendor/bin/phpunit

    https://phpunit.de/getting-started/phpunit-7.html

## Generate translation base template file
- trimmed version with directories and files to exclude from translation
```shell
wp i18n make-pot . languages/trimmed-version/better_sharing_wp.pot --exclude=.github,includes/AdminScreens,includes/AddOns/AutomateWoo,includes/AddOns/CouponReferralProgram,includes/AddOns/WooWishlists,includes/Admin.php,includes/templates/bswp-form-addons.php
```