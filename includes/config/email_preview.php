<?php
//array of default string values for BSWP Email Template Preview
return [
    'buttons' => [
        'view_source'    => 'View Source',
        'preview'        => 'Preview',
        'send_test_modal'      => 'Send Test Email',
        'send_email'    => 'Send'
    ],
    'template_variables' => [
        'greeting'          => 'there',
        'sender_first_name' => 'your friend', 
        'default_contact_name'  => '<span class="bswp-contact-name">there</span>',
        'referral_link'         => '<a href="https://referral.link.com">https://referral.link.com</a>',
        'sender_custom_message' => 'Sender\'s custom message',
        'email_message'         => 'Check out this link!',
    ],
];