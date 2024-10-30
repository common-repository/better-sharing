import './styles.scss';

export default class WooWishlist {
  constructor() {
    this.emailWrapper = jQuery('#referral-emails-wrapper');
    // how many referrals should be allowed
    this.maxRef = this.emailWrapper.data('max')
      ? parseInt(this.emailWrapper.data('max'))
      : 5;
    this.message =
      'You are only allowed to refer a total of ' +
      this.maxRef +
      ' individuals at a time!';
  }

  init($) {
    this.$ = $;
    const $body = $('body');

    if (typeof cloudsponge !== 'undefined') {
      cloudsponge.init({
        displaySelectAllNone: false,
        selectionLimit: this.maxRef,
        selectionLimitMessage: this.message,
        referrer: 'better-sharing-wp:woo-wishlists',
        afterSubmitContacts: this.afterSelect,
      });

      $body.on('click', '.add-from-address-book-init', this.clickInit);
    }
  }

  /**
   * Init Address Book
   *
   * @param e
   */
  clickInit(e) {
    e.preventDefault();
    //check how many emails are left to select
    this.maxRef = this.maxRef - 1;
    cloudsponge.launch();
  }

  /**
   * After Contacts Selected
   *
   * @param contacts
   * @param source
   * @param owner
   */
  afterSelect = (contacts, source, owner) => {
    const $emailField = jQuery('textarea.wl-em-to');
    let csv = $emailField.val();

    contacts.forEach((contact, key) => {
      let email =
        contact.email && contact.email[0] ? contact.email[0].address : false;

      if (email && '' !== csv) {
        csv += ', ' + email;
      }

      if (email && '' === csv) {
        csv += email;
      }
    });

    $emailField.val(csv);
  };

  /**
   * Clear Emails
   *
   */
  clearEmails = () => {
    jQuery('input[name="emails[]"]').each((key, value) => {
      if (!jQuery(value).val() || '' === jQuery(value).val()) {
        jQuery(value).parent('p.form-row').remove();
      }
    });
  };

  /**
   * Copy Link - for Share Your Link section
   *
   * @param e
   */
  copyLink = (e) => {
    e.preventDefault();

    const copyText = document.getElementById('bswp-coupon-referral-copy');

    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand('copy');

    const $confirmation = jQuery('.bswp-copy-confirm');
    $confirmation.show();

    setTimeout(() => {
      $confirmation.hide();
    }, 1500);
  };
}

(function ($) {
  $(document).ready(function () {
    const wishlist = new WooWishlist();
    wishlist.init($);
  });
})(jQuery);
