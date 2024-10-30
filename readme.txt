=== Better Sharing ===
Contributors: cloudsponge
Tags: cloudsponge, woocommerce, contact picker, sharing
Requires at least: 5.0.0
Tested up to: 6.6
Requires PHP: 7.0
Stable tag: 2.6.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add essential viral sharing functionality to any WordPress site with Better Sharing.

== Description ==

Better Sharing includes everything your site needs to create user-friendly sharing functionality that is essential for viral growth in today's online market.

A [live demonstration with full administrator access](https://app.instawp.io/launch?t=better-sharing-plain) is available to you via InstaWP.

* Allow your users to copy a hyperlink of your choice to their clipboard for quick and easy sharing anywhere that's convenient for them.
* Provide a "Share via Email" form on any page that allows your users to input a comma-separated list of email recipients with whom they would like to share your hyperlink.
* Optionally enable the [CloudSponge Contact Picker](https://www.cloudsponge.com/contact-picker/?utm_source=wp&utm_medium=integrations&utm_campaign=bswp) to allow your users to select contacts directly from their address books rather than typing email addresses manually.
* Display pre-filled social media sharing buttons to make it easy for your users to share your hyperlink on their favorite social platforms.

### Better Sharing IgnitionDeck Addon

Social sharing is crucial for the success of crowdfunding campaigns, as it extends their reach and increases visibility. The [Better Sharing for WordPress IgnitionDeck Addon](https://www.ignitiondeck.com/blog/elevate-crowdfunding-with-social-sharing/) provides tools for project creators, supporters, and site visitors to share campaigns via social media and email, enhancing the potential for funding. Personalized, dynamic sharing messages are designed to boost engagement and credibility, thereby fostering a sense of ownership and advocacy among supporters.

Learn more about [enabling and customizing the addon here](https://docs.ignitiondeck.com/article/165-ignitiondeck-better-sharing).

### Better Sharing for WooCommerce

Also, if you're using any of the following plugins or add-ons for WooCommerce then Better Sharing is a no-brainer.

* [AutomateWoo's Refer A Friend add-on](https://woocommerce.com/products/automatewoo-refer-a-friend/) - Rather than forcing users to manually type up to 5 email addresses into separate text fields, Better Sharing modifies this interface to allow a comma-separated list of email addresses with a preview of the subject and body, as well as an optional [contact picker](https://www.cloudsponge.com/contact-picker/) so that users never have to type anything manually.
* [WooCommerce Wishlists](https://woocommerce.com/products/woocommerce-wishlists/) - Override the wishlist's "mailto" link with a beautiful modal dialog for sharing via email with a message preview and [contact picker](https://www.cloudsponge.com/contact-picker/).
* [Coupon Referral Program](https://woocommerce.com/products/coupon-referral-program/) - Let your users easily copy their coupon link to their clipboard or share it via email with a message preview and [contact picker](https://www.cloudsponge.com/contact-picker/).

== Frequently Asked Questions ==

= How do I install Better Sharing? =

Upload the directory `/better-sharing-wp/` to your WP plugins directory and activate from the Dashboard. Configure the plugin at Dashboard > Better Sharing, where you can enable AddOns and manage General Settings like your CloudSponge license key and OAuth Proxy URL.

= How do I get support? =

This plugin is provided by CloudSponge. The best way to get support is to [send an email to support@cloudsponge.com](mailto:support@cloudsponge.com) or find us in [our Slack workspace](https://slack.cloudsponge.com).

= Where do I report bugs? =

If you encounter an issue that you believe is a bug, [search on our support page](https://wordpress.org/support/plugin/better-sharing/) for an existing topic before submitting a new one.

= Do I need a CloudSponge license to use this plugin? =

No. We've made sure that the core functionality of the plugin itself and of each addon will help you build virality into your website even if you don't have a CloudSponge license. Of course, enabling our contact picker will make this even better, but it's not required.

= Do you have an AddOn for XYZ plugin? =

If it's not mentioned in this documentation then we haven't built it yet, but we desperately want to hear from you about it. Please [get in touch with us](https://www.cloudsponge.com/contact/) and let us know what AddOn you want us to build next.

== Screenshots ==

1. Compact view can be added easily to any web page.
2. Our Better Sharing interface is simple and easy to use.
3. Add the Universal Contact Picker to your Better Sharing to increase the number of emails each person sends to their friends.
4. Add a Better Sharing block to any page on your site using Gutenberg block editor.
5. The compact view is the default way Better Sharing will add itself to your page.
6. Customize the Better Sharing block to expand inline on your page using our UI Templates custom post type.
7. Our UI Templates let you include a detail how you want the social links to appear.
8. Emails sent using the Better Sharing plugin can be completely customized. Our Perfect Personalization templates make sure that the emails look familiar to recipients.
9. You can create multiple UI Templates for use in multiple places on your site or A/B test and keep improving them.
10. One-click integration with IgnitinoDeck's powerful crowdfunding platform.
11. Using any WooCommerce add-ons? We boost the performance of several of them by making their sharing interfaces easier to use with our Universal Contact Picker.
12. The Better Sharing general settings screen gives you a simple way to enable the CloudSponge Contact Picker and set some global options for the plugin.

== Changelog ==

= 2.6.7 =
* Fixed a UI issue when the compact view is displayed in a modal.

= 2.6.6 =
* Bumping supported WordPress version to 6.6.

= 2.6.5 =
* Fix for translating strings in js files.

= 2.6.4 =
* Introducing translation support!

= 2.6.3 =
* Fixes extra parameter causing conflict with the IgnitionDeck AddOn.

= 2.6.2 =
* Fix successful email sent count when caching gets in the way.

= 2.6.1 =
* Create only one copy of the default IgnitionDeck UI block - fixes an issue where multiple copies of the block were being created.

= 2.6.0 =
* IgnitionDeck Crowdfunding Addon - Add Better Sharing functionality to your IgnitionDeck Crowdfunding projects.

= 2.5.2 =
* Fixed conflict with "Simple Custom CSS and JS" plugin

= 2.5.1 =
* Restored CSS override classes that had been clobbered by a merge.

= 2.5.0 =
* New admin main screen
* Email sending success screen added
* Improved Reply To configuration. Moved to Settings
* Other enhancements

= 2.4.0 =
* Made template variables that can display dynamic data
* Created overrides for UI template attributes and email template variables
* Now creating a default UI template and Email template when the plugin is installed

= 2.3.1 =
* Fixed an issue that occurred when the Referral Link option was turned off in a UI Template
* Fixed an issue with the Referral Link when the UI Template is used on an archive page
* Fixed a minor visual issue with sharing buttons
* Improved the validation on the front-end "To" field that is expecting a comma-separated list of valid emails
* Added the ability to use the {{ greeting }} template variable in the Email Template subject
* Added a unique CSS class for each UI Template to make it easier for developers to style these elements individually

= 2.3.0 =
* Abuse restriction improvements, including RegEx.
 
= 2.2.1 =
* Fixed when adding BetterSharing to any page in AutomateWoo, not only the 'Share' page.
* Fixed reasonable default values for email template variables.
* Only display the custom message input if the email template uses it.

= 2.2.0 =
* Adds an email preview for users to see how their emails will appear to recipients before sending.
* Fixes UI template headings for consistency.

= 2.1.1 =
* Security updates.

= 2.1.0 =
* Added support for multiple instances of the `[better-sharing]` block on a single page.

= 2.0.7 =
* Corrected Freemius SDK version.

= 2.0.6 =
* Version bump.

= 2.0.5 =
* Updated Freemius SDK to 2.5.10.

= 2.0.4 =
* Addons styles improvements

= 2.0.3 =
* Updated screenshots

= 2.0.2 =
* Deactivation script fixes

= 2.0.1 =
* rebuilding plugin

= 2.0.0 =
* Added switchable layout views - Compact & Inline.
* Addons improvements
* Tested up to the latest version of WordPress, 6.1.1.

= 1.7.8 =
* Tested up to the latest version of WordPress, 6.0.2.

= 1.7.7 =
* Addressed escaped output issues.

= 1.7.6 =
* Escaped output.
* Replaced short PHP tags.
* Sanitized input.

= 1.7.5 =
* Remove spurious Freemius plugin sdk.

= 1.7.4 =
* Tested up to v5.9 of WordPress.

= 1.7.3 =
* Revised update of Freemius to fetch it from the hosted repo.

= 1.7.2 =
* Security update of dependent package (Freemius 2.4.3).
* Added a Get Started menu with description and links for the new admin of a Better Sharing plugin.
* Reorganized menus into the sidebar, adding Contact Picker, and eliminated tabs from the general settings.
* Adds notices to the admin UI to provide context for each page.

= 1.7.1 =
* Bugfix to UI elements.

= 1.7.0 =
* Updated Better Sharing settings page to provide an introduction and resources on how to get started.
* Fixed email sending via AutomateWoo Refer A Friend.

= 1.6.5 =
* Fixed a bug in using the AutomateWoo extension without the CloudSponge Contact Picker.

= 1.6.4 =
* Enable a default UI Template before creating one.

= 1.6.3 =
* Fixed a couple UI Template bugs when only one of the sections is displayed at a time.
* Fixed a bug related to initializing with default values.

= 1.6.2 =
* Fixed an issue where the single quotes were not being saved to the UI Templates.

= 1.6.1 =
* Adds the ability to configure your emails append your brand name to the from line of the email. This improves recognition and transparency when sending emails on behalf of your advocate.

= 1.6.0 =
* Introducing a new UI Template custom post type to customize the look and behaviour of the Better Sharing block and shortcode.

= 1.5.7 =
* Updated screenshots for readme.txt

= 1.5.6 =
* Supporting WordPress 5.8.
* Updated to the Gutenberg block editor.
* Fixes loading CloudSponge in add-ons.

= 1.5.5 =
* Added Freemius SDK.

= 1.5.4 =
* Added Freemius integration.
* Updated AutomateWoo dependency to 5.4.2.
* Added button to connected to address books next to the 'to' field.
* Fixed applying options to the cloudsponge object before launching the Contact Picker so that custom CSS is applied properly.
* Replaced social icons with SVG.
* Fixed validation and feedback on the email form.
* Overhauled the Better Sharing Block to allow reordering of the form sections.
* Enabled setting a custom referral url to better-sharing short code
* Added a fallback template to be used for emails when a templateId is not provided.
* Cleaned up code: unused styles, outdated short code attribute, and nonce attribute.

= 1.5.3 =
* Enhanced RestAPI authentication with nonce

= 1.5.2 =
* Added form validations for sending emails.
* Added limit to the number of emails permitted to be sent.

= 1.5.1 =
* Fixed email template custom post type so that it displays even if the Contact Picker is not enabled.

= 1.5.0 =
* Rebuilt the Gutenberg block admin presentation to make it simpler to configure.
* Added email templates get you started with a very good email.
* Enabled Perfect Personalization for emails sent via the Gutenberg block or shortcode.

= 1.4.2 =
* Fixes error by checking for the api key before enqueuing cloudsponge.
* Updates the icon in the Gutenberg block.

= 1.4.1 =
* Fixes unenqueued script for shortcode & Gutenberg block.

= 1.4.0 =
* Beautification of addon dashboard area
* Added user feedback when email sends
* Tested for PHP 7.0 compatibility and updated readme accordingly
* Tested for WP 5.7 compatibility

= 1.3.6 =
* Marketing assets.
* New admin icon.
* Menu redesign
* Shortcode
* Other UI/UX updates

= 1.3.5 =
* Fixes dependency issues.

= 1.3.4 =
* Fixes to AddOn classes, update variable checking.

= 1.3.3 =
* Security, nonce, and variable name fixes.

= 1.3.0 =
* Added Gutenberg block and shortcode for core sharing functionality.

= 1.2.2 =
* Security fixes, nonces.

= 1.2.1 =
* Security fixes, query string sanitizing.

= 1.2.0 =
* Initial release
