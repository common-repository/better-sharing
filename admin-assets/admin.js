import "./bs-wp-admin.scss";

const $ = jQuery;

const shadowStyles = {
  emailTemplateUI: `.bswp-email-preview-label {
    margin: 1rem 0 0.5rem 0;
    text-decoration: underline;
  }
  .bswp-email-preview {
    background-color: #f1f2f3;
    padding: 1rem;
  }`,
  templateUI: `.bswp-email-preview-label {
    margin: 1rem 0 0.5rem 0;
  }
  .bswp-email-preview {
    background-color: #f1f2f3;
    padding: 1rem;
  }`,
};

class BSWPAdminJS {
  init = () => {
    $("body").on("click", ".bswp__addon__status-indicator", this.toggleAddOn);
    $("body").on("click", ".bswp__addon__settings-toggle", this.toggleSettings);
    $("body").on("click", ".copyText", this.copyText);
    $("body").on("click", "#bswp-limit-emails", this.toggleEmailsLimit);
    $("body").on("click", "#bswp-spam-detection", this.toogleSpamDetection);

    //toggle view style
    $(".bswp-style-view input").on("change", this.toggleViewStyle);
    $(".bswp-module-enable input").on("click", this.handleModuleEnable);
    $(".bswp-handle-module").on("click", "a", this.handleBlockModule);

    //take care for the proper visualisation of reorder modules handle controls
    this.toggleReorderControls();

    $(".bswp-shortcode-copy").on("click", this.copyShortcode);
    $(".bswp-social-networks-toggle").on(
      "change",
      this.handleSocialShareToggle
    );
    $(".bswp-share-url input").on("change", this.handleReferalLinkToggle);
    $(".bswp-custom-message input").on(
      "change",
      this.handleCustomMessageToggle
    );
    // toggles email preview in Template UI
    $(".bswp-email-preview input").on("change", this.handleEmailPreviewToggle);
    $(".bswp-text-update").on("input", this.moduleTextUpdateHandler);

    //prevent user from accidentally hide the metabox hide control from CPT's screen option
    this.protectBswpCptsMetaboxes();
    //shadow DOM init
    this.initEmailTemplateShadowDOM(); // post EmailTemplate
    this.initUITemplateShadowDOM(); // post UITemplate

    $("#bswp-email-template").on("change", this.getEmailTemplateData);
    $(".bswp__helper-block-wrapper .toggle").on(
      "click",
      this.toggleHelperBlock
    );
    $(".bswp-emails-replyto").on(
      "click",
      ".checkbox-group label, .checkbox-group input",
      (e) => this.handleTextInputInCheckboxGroup(e)
    );
  };
  /**
   * Define shadow DOM's
   * style object
   *
   * @param {string} styleRulesStr
   * @returns style object
   */
  createShadowStyle = (styleRulesStr) => {
    // style rules can be defined here
    const style = document.createElement("style");
    // style rules
    style.textContent = styleRulesStr;

    return style;
  };
  /**
   * Generates an html element
   *
   * @param {String} el
   * @param {Array} classNames
   * @param {String} id
   * @param {String} text
   * @return {Object} shadowElement
   */
  createHtmlElement = (el, classNames = [], id = "", text = "") => {
    const shadowElement = document.createElement(el);
    if (id) shadowElement.setAttribute("id", id);
    if (classNames.length) {
      classNames.forEach((className) => {
        shadowElement.classList.add(className);
      });
    }
    //allow span elements f.ex.
    if (text) shadowElement.innerHTML = text;
    return shadowElement;
  };

  initEmailTemplateShadowDOM = () => {
    const wrapper = document.getElementById("bswp-email-preview-wrapper"),
      toggleButtons = document.querySelectorAll(".bswp__email-preview-toggle"),
      modalTriggerBtn = document.getElementById(
        "bswp-email-test-modal-trigger"
      ),
      modalCloseBtn = document.getElementById("bswp-close-test-email-modal"),
      sendTestMailBtn = document.querySelector("#bswp-email-modal button"),
      subjectInput = document.getElementById("email-subject");
    if (wrapper) {
      // 'open' will allow to access the shadow DOM
      // with JavaScript written in the main page contex
      const shadowDom = wrapper.attachShadow({ mode: "open" });
      //shadow DOM's style rules
      const style = this.createShadowStyle(shadowStyles.emailTemplateUI);
      shadowDom.appendChild(style);

      // preview email subject
      const subjectPreviewLabel = this.createHtmlElement(
        "p",
        ["bswp-email-preview-label"],
        null,
        "Email Subject:"
      );
      const emailSubjectPreview = this.createHtmlElement(
        "div",
        [],
        "bswp-email-subject-preview"
      );

      // preview email content
      const contentPreviewLabel = this.createHtmlElement(
        "p",
        ["bswp-email-preview-label"],
        null,
        "Email Message:"
      );
      const emailContentPreview = this.createHtmlElement(
        "div",
        [],
        "bswp-email-content-preview"
      );
      //add to shadow DOM
      shadowDom.appendChild(subjectPreviewLabel);
      shadowDom.appendChild(emailSubjectPreview);
      shadowDom.appendChild(contentPreviewLabel);
      shadowDom.appendChild(emailContentPreview);

      if (toggleButtons) {
        toggleButtons.forEach((element) => {
          element.addEventListener("click", (e) =>
            this.toggleEmailPreview(e, shadowDom)
          );
        });
      }

      if (modalTriggerBtn) {
        modalTriggerBtn.addEventListener("click", (e) => {
          const modal = document.getElementById("bswp-email-modal");
          if (modal) {
            modal.classList.remove("bswp-email-modal-hidden");
          }
        });
      }
      if (modalCloseBtn) {
        modalCloseBtn.addEventListener("click", (e) => {
          e.preventDefault();
          const modal = document.getElementById("bswp-email-modal");
          if (modal) {
            modal.classList.add("bswp-email-modal-hidden");
            if (modalTriggerBtn) modalTriggerBtn.classList.remove("active");
          }
        });
      }
      if (sendTestMailBtn) {
        sendTestMailBtn.addEventListener("click", this.sendTestEmail);
      }
      if (subjectInput) {
        subjectInput.addEventListener("input", (e) =>
          this.updateEmalSubjectPreview(e, shadowDom)
        );
      }
      window.addEventListener("resize", this.handleEmailPreviewSize);
    }
  };

  initUITemplateShadowDOM = () => {
    const shadowContainer = document.querySelectorAll(
      ".bswp-template-ui-email-preview"
    );

    if (shadowContainer.length) {
      const refLinkToggle = document.querySelector(".bswp-share-url input");
      const refLinkInput = document.querySelector("#bswp-custom-url-content");
      shadowContainer.forEach((element) => {
        const shadowContent = JSON.parse(element.dataset.emailPreview);
        const emailMessageFallbackInput = document.getElementById(
          "bswp-email-message-fallback"
        );

        const shadowDom = element.attachShadow({ mode: "open" });
        //shadow DOM's style rules
        const style = this.createShadowStyle(shadowStyles.templateUI);
        shadowDom.appendChild(style);

        // preview email subject
        const subjectPreviewLabel = this.createHtmlElement(
          "p",
          ["bswp-email-preview-label"],
          null,
          "Email Subject Preview:"
        );
        const emailSubjectPreview = this.createHtmlElement(
          "div",
          ["bswp-email-preview", "bswp-email-preview-subject"],
          "",
          shadowContent.email_subject
        );

        const contentPreviewLabel = this.createHtmlElement(
          "p",
          ["bswp-email-preview-label"],
          null,
          "Email Message Preview:"
        );
        const emailContentPreview = this.createHtmlElement(
          "div",
          ["bswp-email-preview", "bswp-email-preview-body"],
          "",
          shadowContent.email_body
        );

        shadowDom.appendChild(subjectPreviewLabel);
        shadowDom.appendChild(emailSubjectPreview);
        shadowDom.appendChild(contentPreviewLabel);
        shadowDom.appendChild(emailContentPreview);

        if (refLinkToggle) {
          refLinkToggle.addEventListener("change", (e) => {
            this.updateEmailPreviewReferralLink(e, shadowDom);
          });
        }
        if (refLinkInput) {
          refLinkInput.addEventListener("input", (e) => {
            this.updateEmailPreviewReferralLink(e, shadowDom);
          });
        }
        if (emailMessageFallbackInput) {
          emailMessageFallbackInput.addEventListener("input", (e) => {
            this.emailMessageFallbackHandler(e, shadowDom);
          });
        }
      });
    }
  };

  toggleAddOn = (e) => {
    e.preventDefault();
    const $btn = $(e.currentTarget);
    const addOn = $btn.data("addon")
      ? $btn.data("addon")
      : $btn.attr("data-addon");
    const currentStatus = $btn.data("status")
      ? $btn.data("status")
      : $btn.attr("data-status");
    const nonce = $btn.data("nonce")
      ? $btn.data("nonce")
      : $btn.attr("data-nonce");
    const pluginActive = $btn.data("plugin");

    if (!addOn) {
      return false;
    }

    if ("plugin-unavailable" === pluginActive) {
      alert(
        "Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin"
      );
      return false;
    }

    window.location.href = `${window.location.href}&toggleAddOn=true&addOn=${addOn}&n=${nonce}`;
  };

  toggleSettings = (e) => {
    e.preventDefault();
    const addOn = $(e.currentTarget).data("addon");

    $("." + addOn + "-settings").toggleClass("active");
  };

  /**
   * Copy Text using class copyText and passing the id of the input via data attr "text"
   * ex: <a href="#" class="copyText btn button" data-text="bswp-proxy-url">Copy</a>
   *
   * @param e
   */
  copyText = (e) => {
    e.preventDefault();

    const $btn = $(e.currentTarget);
    const preText = $btn.html();
    const textID = $btn.data("text");
    const copyText = document.getElementById(textID);
    const $copyTextElement = $("#" + textID);

    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");

    $btn.html("Copied!");
    $copyTextElement.css({ background: "rgba(0,255,0,0.2)" });

    setTimeout(() => {
      $btn.html(preText);
      $copyTextElement.css({ background: "#eee" });
    }, 1000);
  };

  /**
   * Show/hide max emails number per form submission input field
   *
   * @param e
   */

  toggleEmailsLimit = (e) => {
    if (e.target.checked) {
      $("#bswp-limit-emails-num").prop("readonly", false);
    } else {
      $("#bswp-limit-emails-num").prop("readonly", true);
    }
  };

  toogleSpamDetection = (e) => {
    if (e.target.checked) {
      $("#bswp_spam_regex").prop("readonly", false);
      $("#bswp-spam-err-msg").prop("disabled", false);
      $("#bswp-spam-err-msg").prop("checked", true);
    } else {
      $("#bswp_spam_regex").prop("readonly", true);
      $("#bswp-spam-err-msg").prop("disabled", true);
    }
  };

  /**
   * Toggle full and compact
   * view style
   * @param e
   */
  toggleViewStyle = (e) => {
    if ("bswp-full-view" === $(e.target).attr("id")) {
      $(".wp-block-cgb-block-ea-better-sharing.full-view").removeClass(
        "view-style-hidden"
      );
      $(".wp-block-cgb-block-ea-better-sharing.compact-view").addClass(
        "view-style-hidden"
      );
      $(".tab").removeClass("compact-view");
    }
    if ("bswp-compact-view" === $(e.target).attr("id")) {
      $(".wp-block-cgb-block-ea-better-sharing.compact-view").removeClass(
        "view-style-hidden"
      );
      $(".wp-block-cgb-block-ea-better-sharing.full-view").addClass(
        "view-style-hidden"
      );
      $(".tab").addClass("compact-view");
    }
  };
  /**
   * Enable/disable UI Template module
   * from its tab
   * @param e
   */

  handleModuleEnable = (e) => {
    let moduleEnableControl = $(e.target).prop("checked"),
      moduleName = $(e.target).data("module"),
      toggledElement;

    toggledElement = $('[data-module="' + moduleName + '"]').parents(
      ".bswp-ui-template-module-container"
    );

    if (moduleEnableControl) {
      toggledElement.removeClass("bswp-ui-template-module-hidden");
      //show the module preview and settings from its tab
      $(e.target)
        .parents(".bswp-module-settings")
        .next()
        .removeClass("bswp-ui-template-module-hidden");
      //set module is enabled to be saved in db
      $("#" + moduleName + "-enabled").val(1);
    } else {
      toggledElement.addClass("bswp-ui-template-module-hidden");
      //hide the module preview and settings from its tab
      $(e.target)
        .parents(".bswp-module-settings")
        .next()
        .addClass("bswp-ui-template-module-hidden");
      //set module is disable to be saved in db
      $("#" + moduleName + "-enabled").val(0);
    }
    //take care for the proper visualisation of reorder modules handle controls
    this.toggleReorderControls();
  };
  /**
   * Get next active
   * BSWP module
   * @param {*} element
   * @returns
   */
  getNextBlockModule = (element) => {
    let placeholder = element.nextElementSibling;

    while (placeholder) {
      if (!placeholder.classList.contains("bswp-ui-template-module-hidden")) {
        return placeholder;
      }

      placeholder = placeholder.nextElementSibling;
    }
  };
  /**
   * Get previous active
   * BSWP module
   * @param {*} element
   * @returns
   */
  getPreviousBlockModule = (element) => {
    let placeholder = element.previousElementSibling;

    while (placeholder) {
      if (!placeholder.classList.contains("bswp-ui-template-module-hidden")) {
        return placeholder;
      }

      placeholder = placeholder.previousElementSibling;
    }
  };
  getShadowHTML = (element) => {
    const shadowContainer = element.querySelector(
      ".bswp-template-ui-email-preview"
    );
    // element has shadow root, visible or not
    if (!shadowContainer) return false;

    if (shadowContainer.shadowRoot) {
      const shadowEl = shadowContainer.shadowRoot;
      const shadowHTML = shadowEl.innerHTML;
      return shadowHTML;
    }
    return false;
  };
  restoreShadowDOM = (element, shadowHTML) => {
    const shadowWrapperElement = element.querySelector(
      ".bswp-template-ui-email-preview"
    );
    if (!shadowWrapperElement) return;
    const shadowDom = shadowWrapperElement.attachShadow({ mode: "open" });
    shadowDom.innerHTML = shadowHTML;
  };
  /**
   * Handling BS Block module state
   * with the handle controls
   * on the BS Block tab
   *
   * @param e
   */
  handleBlockModule = (e) => {
    e.preventDefault();

    const clickedEl = e.target;
    const action = clickedEl.dataset.action;
    const targetModuleParent = clickedEl.closest(
      ".bswp-ui-template-module-container"
    );
    const targetModuleWrapper =
      targetModuleParent.querySelector(".bswp-sortable");
    const targetModule = targetModuleWrapper.querySelector(
      ".bswp-ui-template-module"
    );
    const targetModuleName = targetModule.dataset.module;
    const order = targetModuleParent.dataset.order;

    if ("up" === action || "down" === action) {
      let newOrder;
      if ("up" === action) {
        //find first previos non disabled module's order
        const prevSibling = this.getPreviousBlockModule(targetModuleParent);
        newOrder = prevSibling.dataset.order;
      }
      if ("down" === action) {
        //find first next non disabled module
        const nextSibling = this.getNextBlockModule(targetModuleParent);
        newOrder = nextSibling.dataset.order;
      }
      const moduleContainer = document.querySelector(
        ".wp-block-cgb-block-ea-better-sharing"
      );
      const replaceModuleParent = moduleContainer.querySelector(
        `div[data-order="${newOrder}"]`
      );
      const replaceModuleWrapper =
        replaceModuleParent.querySelector(".bswp-sortable");
      const replaceModule = replaceModuleWrapper.querySelector(
        ".bswp-ui-template-module"
      );
      const replaceModuleName = replaceModule.dataset.module;
      // cache contents
      const targetContent = targetModuleWrapper.innerHTML;
      const replaceContent = replaceModuleWrapper.innerHTML;
      // check for shadow elements
      const targetShadowHTML = this.getShadowHTML(targetModuleWrapper);
      const replaceShadowHTML = this.getShadowHTML(replaceModuleWrapper);

      // switch content
      targetModuleWrapper.innerHTML = replaceContent;
      if (replaceShadowHTML) {
        this.restoreShadowDOM(targetModuleWrapper, replaceShadowHTML);
      }

      replaceModuleWrapper.innerHTML = targetContent;
      if (targetShadowHTML) {
        this.restoreShadowDOM(replaceModuleWrapper, targetShadowHTML);
      }
      //set new modules' order to saved in db
      document.getElementById(`${targetModuleName}-order`).value = newOrder;
      document.getElementById(`${replaceModuleName}-order`).value = order;
    }

    if ("customize" === action) {
      //hide first BS Block tab
      document.getElementById("bswp-ui-template-module").checked = false;
      //show the module tab selected
      document.getElementById(`${targetModuleName}-module`).checked = true;
    }
  };

  /**
   * Responsible for the proper
   * visualisatin of
   * the reorder controls
   * on the First BSBlock tab
   *
   * Called on page load,
   * on click any reorder controls
   *
   * @returns void
   */

  toggleReorderControls = () => {
    //get enabled block modules on BS Block module tab
    let blockModules = $(".bswp-ui-template-module-container").not(
      ".bswp-ui-template-module-hidden"
    );

    if (blockModules.length === 1) {
      //one module, no reorder controls
      blockModules
        .find('[data-action="up"')
        .addClass("bswp-hidden-reorder-control");
      blockModules
        .find('[data-action="down"')
        .addClass("bswp-hidden-reorder-control");
      return;
    }

    blockModules.each(function (ind) {
      //display up, down controls, all modules
      $(blockModules[ind])
        .find('[data-action="up"]')
        .removeClass("bswp-hidden-reorder-control");
      $(blockModules[ind])
        .find('[data-action="down"]')
        .removeClass("bswp-hidden-reorder-control");

      if (ind === 0) {
        //first module, only down control
        $(blockModules[ind])
          .find('[data-action="up"')
          .addClass("bswp-hidden-reorder-control");
      } else if (ind === blockModules.length - 1) {
        //last module, only up control
        $(blockModules[ind])
          .find('[data-action="down"')
          .addClass("bswp-hidden-reorder-control");
      }
    });
  };

  /**
   * Copies BS Block Shortcode
   * to clipboard
   * @param e
   */
  copyShortcode = (e) => {
    e.preventDefault();

    let copyControl = $(e.target),
      shortcode = copyControl
        .parents(".bswp__short-code-container")
        .find("span")
        .text()
        .trim(),
      clipboard = navigator.clipboard,
      preText = copyControl.text();

    clipboard.writeText(shortcode);
    copyControl.text("Copied!");

    setTimeout(() => {
      copyControl.text(preText);
    }, 1000);
  };

  /**
   * Update BS block CPT
   * demo texts
   * on demo module display
   *
   * @param e
   */
  moduleTextUpdateHandler = (e) => {
    let updateModule = $(e.target).parents(".tab").data("tab"),
      updateData = $(e.target).data("update"),
      updateSelector = $(e.target).data("target"),
      updateEl;
    //share link is updated, update it in ref link module
    if (updateModule == "bswp-ui-template") {
      updateModule = "bswp-referral-link";
    }
    updateEl = $(
      ".bswp-ui-template-module[data-module='" +
        updateModule +
        "'] ." +
        updateSelector
    );
    if ("value" === updateData) {
      updateEl.val($(e.target).val());
    }
    if ("placeholder" === updateData) {
      updateEl.attr("placeholder", $(e.target).val());
    }
    if ("text" === updateData) {
      updateEl.text($(e.target).val());
    }
  };

  /**
   * Show/hide social share
   * icons and custom twitter message
   *
   * @param e
   */

  handleSocialShareToggle = (e) => {
    let targetEl = $(e.target),
      isEnabled = targetEl.prop("checked"),
      socialNet = targetEl.data("social-share");
    if (isEnabled) {
      //remove hidden class from demo module social share net
      $("." + socialNet).removeClass("bswp-hidden-social-share-control");

      if ("twitter" == socialNet) {
        $("[data-social-share='twitter-msg']")
          .parents(".bswp__form-group")
          .removeClass("bswp-hidden-social-share-control");
      }
    } else {
      //add hidden class to demo module social share net
      $("." + socialNet).addClass("bswp-hidden-social-share-control");

      if ("twitter" == socialNet) {
        $("[data-social-share='twitter-msg']")
          .parents(".bswp__form-group")
          .addClass("bswp-hidden-social-share-control");
        $("[data-social-share='twitter-msg']").val("Check out this link!");
      }
    }
  };

  /**
   * Toggles custom url input
   * based on referral link type selected
   *
   * @param e
   */

  handleReferalLinkToggle = (e) => {
    if ("bswp-post-url" === $(e.target).attr("id")) {
      $("#bswp-custom-url-content")
        .addClass("bswp-hidden-referral-link-control")
        .val("");
      //restore default value - TO DO GET POST URL FROM THE BACKEND
      $(".bswp-referral-link").val("");
      $("#bswp-custom-url-content").val("");
    }
    if ("bswp-custom-url" === $(e.target).attr("id")) {
      $("#bswp-custom-url-content").removeClass(
        "bswp-hidden-referral-link-control"
      );
    }
  };

  /**
   * Toggles custom message input
   * based on selected custom message on/off
   *
   * @param e
   */
  handleCustomMessageToggle = (e) => {
    //TO DO - TOGGLE CUSTOM MESSAGE CONTAINER ON MODULE DEMO
    if ("bswp-custom-message-off" === $(e.target).attr("id")) {
      $("#bswp-custom-message-placeholder")
        .parents(".bswp__form-group")
        .addClass("bswp-hidden-custom-message-container")
        .val("");
      $(".bswp-email-custom-message").addClass(
        "bswp-hidden-custom-message-container"
      );
      //setdefault placeholder for message container
      $(".bswp-email-message").attr("placeholder", "Message");
      $("#bswp-custom-message-placeholder").val("");
    }
    if ("bswp-custom-message-on" === $(e.target).attr("id")) {
      $("#bswp-custom-message-placeholder")
        .parents(".bswp__form-group")
        .removeClass("bswp-hidden-custom-message-container");
      $(".bswp-email-custom-message").removeClass(
        "bswp-hidden-custom-message-container"
      );
    }
  };
  /**
   * Toggles Email Preview Blocks
   * in Template UI
   *
   * @param e
   */
  handleEmailPreviewToggle = (e) => {
    if ("bswp-email-preview-off" === $(e.target).attr("id")) {
      $(".bswp-template-ui-email-preview").addClass(
        "bswp-hidden-email-preview-container"
      );
    }
    if ("bswp-email-preview-on" === $(e.target).attr("id")) {
      $(".bswp-template-ui-email-preview").removeClass(
        "bswp-hidden-email-preview-container"
      );
    }
  };

  //Protect BSWP CPT's metaboxes
  //from being hidden unintentionally by the user
  protectBswpCptsMetaboxes = () => {
    $("#bswp_ui_template_settings .postbox-header").remove();
    $("#bswp_ui_template_settings-hide").remove();
    $("#bswp_template_variables-hide").parents("label").remove();
    $("#bswp_template_variables-hide").remove();
    $("#bswp_email_subject-hide").parents("label").remove();
    $("#bswp_email_subject-hide").remove();
    $("#bswp_reply_to-hide").parents("label").remove();
    $("#bswp_reply_to-hide").remove();
  };

  /**
   * Displays Email Template CPT's
   * content as an Email Preview
   * @param {*} e
   * @param {*} shadow shadowRoot
   */
  toggleEmailPreview = (e, shadow) => {
    e.preventDefault();
    e.stopPropagation();

    const activeBtn = document.querySelector(
        ".bswp__email-preview-toggle .active"
      ),
      toggleData = e.target.dataset.toggle;
    if (activeBtn) activeBtn.classList.remove("active");

    e.target.classList.add("active");

    if (toggleData === "preview") {
      // handle email subject
      const previewSubject = document.getElementById("email-subject").value;
      const parsedSubject = this.parsePreviewContent(previewSubject);
      const previewSubjectContainer = shadow.getElementById(
        "bswp-email-subject-preview"
      );
      previewSubjectContainer.innerHTML = parsedSubject;
      // handle email body content
      const previewContent = document.getElementById("content").value;
      const parsedContent = this.parsePreviewContent(previewContent);
      const previewContentContainer = shadow.getElementById(
        "bswp-email-content-preview"
      );

      previewContentContainer.innerHTML = parsedContent;

      this.handleEmailPreviewSize();
      const wrapper = document.getElementById("bswp-email-preview-wrapper");
      if (wrapper) wrapper.classList.remove("bswp__hidden-email-template");
    }
    if (toggleData === "source") {
      const wrapper = document.getElementById("bswp-email-preview-wrapper");
      if (wrapper) wrapper.classList.add("bswp__hidden-email-template");
    }
  };

  updateEmalSubjectPreview = (e, shadow) => {
    const subject = e.target.value;
    const parsedSubject = this.parsePreviewContent(subject);
    const previewSubjectContainer = shadow.getElementById(
      "bswp-email-subject-preview"
    );
    previewSubjectContainer.innerHTML = parsedSubject;
  };
  /**
   * Sets Email Preview's block
   * width and height
   * when browser window
   * is resized
   */
  handleEmailPreviewSize = () => {
    var width = $("#postdivrich").outerWidth(),
      height = $("#postdivrich").outerHeight(),
      heightHeadline = $(".postbox.wp-heading-inline").outerHeight(),
      totalHeight = height + heightHeadline;
    $("#bswp-email-preview-wrapper").outerWidth(width).outerHeight(totalHeight);
  };

  /**
   * Replaces Email template variables
   * with default values
   *
   * @param string content
   * @returns string content
   */

  parsePreviewContent = (content) => {
    const {
      greeting,
      sender_first_name,
      referral_link,
      sender_custom_message,
      email_message,
    } = bswpApiSettings.templateVariables;
    content = content.replaceAll("{{ greeting }}", greeting);
    content = content.replaceAll("{{ sender_first_name }}", sender_first_name);
    content = content.replaceAll("{{ referral_link }}", referral_link);
    content = content.replaceAll(
      "{{ sender_custom_message }}",
      sender_custom_message
    );
    content = content.replaceAll("{{ email_message }}", email_message);

    return content;
  };

  /**
   * Send test email handler
   */
  sendTestEmail = (e) => {
    e.preventDefault();
    //validate
    const mailRecipientAddress = $("#bswp-share-email-input").val().trim();
    if (!mailRecipientAddress) {
      this.showStatusMessage("Enter valid email address!", "error-msg");
      return;
    }

    //get email content
    const previewContent = $("#content").val();
    const parsedContent = this.parsePreviewContent(previewContent);
    //prepare test email data
    const data = {
      email: mailRecipientAddress,
      mailBody: parsedContent,
    };

    //send the test mail
    const request = new XMLHttpRequest();

    request.open(
      "POST",
      `${bswpApiSettings.api_root}bswp/v1/bswp_test_email`,
      true
    );

    request.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded; charset=UTF-8"
    );

    request.setRequestHeader("X-WP-Nonce", bswpApiSettings.nonce);
    request.onreadystatechange = () => {
      if (request.readyState === 4) {
        const response = JSON.parse(request.responseText);
        //after submit handler
        //restore initial btn state
        $(e.target)
          .removeClass("bswp-sending-mail")
          .text("Send")
          .attr("disabled", false);

        if (response.result) {
          $("#bswp-share-email-input").val("");

          this.showStatusMessage(response.message, "success-msg");
        } else {
          this.showStatusMessage(response.message, "error-msg");
        }
      }
    };
    request.send(JSON.stringify(data));
    //indicate sending mail started
    $(e.target)
      .addClass("bswp-sending-mail")
      .text("Sending ...")
      .attr("disabled", "disabled");
  };

  showStatusMessage = (msg, msgClass) => {
    $("#coreblock-email-sent-msg").text(msg).addClass(msgClass);
    setTimeout(() => {
      $("#coreblock-email-sent-msg").text("").removeClass(msgClass);
    }, 3000);
  };

  getEmailTemplateData = (e) => {
    const etemplateID = e.target.value;
    const uitemplateID = document.getElementById("post_ID").value;

    let queryStr = etemplateID ? `eid=${etemplateID}` : `eid=`;
    queryStr += uitemplateID ? `&uiid=${uitemplateID}` : `&uiid=`;

    const url = `${bswpApiSettings.api_root}bswp/v1/bswp_email_template?${queryStr}`;
    fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json;charset=utf-8",
        "X-WP-Nonce": `${bswpApiSettings.nonce}`,
      },
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const { email_subject, email_body, has_template_vars } =
          JSON.parse(data);
        const previews = document.querySelectorAll(
          ".bswp-template-ui-email-preview"
        );
        const customMessageInputWrappers = document.querySelectorAll(
          ".bswp-email-custom-message"
        );
        const messageContainer = document.getElementById(
          "bswp-email-message-fallback"
        );
        previews.forEach((element) => {
          const subject = element.shadowRoot.querySelector(
            ".bswp-email-preview-subject"
          );

          subject.innerHTML = email_subject;
          const body = element.shadowRoot.querySelector(
            ".bswp-email-preview-body"
          );
          body.innerHTML = email_body;

          if (messageContainer) {
            const emailMessageWrappers = element.shadowRoot.querySelectorAll(
              ".bswp-email-message-wrapper"
            );
            emailMessageWrappers.forEach(
              (element) => (element.innerText = messageContainer.value)
            );
          }
        });

        if (has_template_vars.includes("sender_custom_message")) {
          customMessageInputWrappers.forEach((element) => {
            if (
              element.classList.contains("bswp-hidden-custom-message-container")
            ) {
              element.classList.remove("bswp-hidden-custom-message-container");
            }
          });
        } else {
          customMessageInputWrappers.forEach((element) => {
            if (
              !element.classList.contains(
                "bswp-hidden-custom-message-container"
              )
            ) {
              element.classList.add("bswp-hidden-custom-message-container");
            }
          });
        }
      })
      .catch(function (err) {
        console.log("Fetch Error :-S", err);
      });
  };

  /**
   * Updates the referral link's
   * (url to share)
   * displayed in the UI template
   * email section, email preview
   * @param {*} e
   */
  updateEmailPreviewReferralLink = (e, shadow) => {
    const previewRefLinkContainers = shadow.querySelectorAll(
      ".bswp-email-preview-ref-link"
    );
    if (previewRefLinkContainers.length) {
      let url = "";
      if ("page_url" === e.target.value) {
        url = bswpApiSettings.page_url;
      } else if ("custom_url" === e.target.value) {
        url = document.getElementById("bswp-custom-url-content")
          ? document.getElementById("bswp-custom-url-content").value
          : "";
      } else if ("bswp-custom-url-content" === e.target.id) {
        url = e.target.value;
      }
      previewRefLinkContainers.forEach((element) => (element.innerText = url));
    }

    if ("page_url" === e.target.value) {
      url = bswpApiSettings.page_url;
    } else if ("custom_url" === e.target.value) {
      url = $("#bswp-custom-url-content").val();
    } else if ("bswp-custom-url-content" === e.target.id) {
      url = e.target.value;
    }
  };

  emailMessageFallbackHandler = (e, shadow) => {
    const emailMessageWrappers = shadow.querySelectorAll(
      ".bswp-email-message-wrapper"
    );
    emailMessageWrappers.forEach(
      (element) => (element.innerText = e.target.value)
    );
  };

  toggleHelperBlock = (e) => {
    if (e.target.classList.contains("active-content")) {
      e.target.classList.remove("active-content");
      e.target.classList.add("show-content");
      document.getElementById("helper-content").classList.add("hidden-helper");
      document
        .getElementById("helper-heading")
        .classList.remove("hidden-helper");
    } else if (e.target.classList.contains("show-content")) {
      e.target.classList.remove("show-content");
      e.target.classList.add("active-content");

      document.getElementById("helper-heading").classList.add("hidden-helper");
      document
        .getElementById("helper-content")
        .classList.remove("hidden-helper");
    }
  };

  handleTextInputInCheckboxGroup = (e) => {
    const replyToOption = $(e.target)
      .parents(".checkbox-group")
      .find("input[type=radio]")
      .val();
    const customAddressInput = $(e.target)
      .parents(".bswp-emails-replyto")
      .find("#bswp-emails-custom-replyto-address");
    if ("0" === replyToOption || "1" === replyToOption) {
      customAddressInput.prop("readonly", true);
    } else if ("2" === replyToOption) {
      customAddressInput.prop("readonly", false);
      customAddressInput.trigger("focus");
    }
  };
}

const BSWPAdmin = new BSWPAdminJS();
$(document).ready(function () {
  BSWPAdmin.init();
});
