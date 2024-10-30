import './styles.scss';

export default class AutomateWoo {
  init($) {
    this.$ = $;
    const $body = $('body');

    if (typeof cloudsponge !== 'undefined') {
      cloudsponge.init({
        referrer: 'better-sharing-wp:automate-woo',
        afterSubmitContacts: this.afterSelect,
      });

      $body.on('click', '.add-from-address-book-init', this.clickInit);
    }

    $body.on('click', '.bswp-submit', this.formSubmit);
    $body.on('click', '.bswp-automatewoo-share-link-key-copy', this.copyLink);
  }

  /**
   * Init Address Book
   *
   * @param e
   */
  clickInit(e) {
    e.preventDefault();

    const $emailWrapper = jQuery('#referral-emails-wrapper');

    // how many referrals should be allowed
    let maxRef = $emailWrapper.data('max')
      ? parseInt($emailWrapper.data('max'))
      : 5;
    const maxRefTotal = maxRef;
    let valid = 0;

    jQuery('input[name="emails[]"]').each((key, value) => {
      if (jQuery(value).val() || '' !== jQuery(value).val()) {
        valid++;
      }
    });
    maxRef = maxRef - valid;

    cloudsponge.init({
      displaySelectAllNone: false,
      selectionLimit: maxRef,
      selectionLimitMessage:
        'You are only allowed to refer a total of ' +
        maxRefTotal +
        ' individuals at a time.',
    });

    cloudsponge.launch();
  }

  /**
   * Submit Form
   *
   * @param e
   */
  formSubmit = (e) => {
    e.preventDefault();
    let contacts = [];
    const $wrapper = jQuery('#referral-emails-wrapper');
    const $emailField = jQuery('#bswp-share-email-input');
    const emails = $emailField.val();

    this.clearEmails();

    emails.split(', ').forEach((email, key) => {
      if (email) {
        $wrapper.append(
          '<p class="form-row form-row-wide">' +
            '<input type="hidden" name="emails[]" class="woocommerce-Input input-text" value="' +
            email +
            '">' +
            '</p>'
        );
      }
    });

    jQuery('form.aw-email-referral-form').submit();
  };

  /**
   * After Contacts Selected
   *
   * @param contacts
   * @param source
   * @param owner
   */
  afterSelect = (contacts, source, owner) => {
    const $emailField = jQuery('#bswp-share-email-input');
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

    const copyText = document.getElementById(
      'bswp-automatewoo-share-link-key-to-copy'
    );
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand('copy');

    const $confirmation = jQuery(
      '.bswp-automatewoo-share-link-key-copy-confirm'
    );

    $confirmation.show();

    setTimeout(() => {
      $confirmation.hide();
    }, 1500);
  };
}

(function ($) {
  $(document).ready(function () {
    var autoWoo = new AutomateWoo();
    autoWoo.init($);
  });
})(jQuery);
