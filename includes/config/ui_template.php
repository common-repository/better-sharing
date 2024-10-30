<?php
//array of default values for BSWP UI Template settings form
return [
    'view_style' => 'compact',
    'url_to_share' => [
        'link_type' => 'page_url',
        'custom_link' => '',
        'default_page_endpoint' => '/',
    ],
    'social_share' => [
        'order' => '1',
        'enabled' => '1',
        'title'         => 'Share on Social',
        'subtitle'      => '',
        'fb_enabled'    => '1',
        'fb_intentUrl'  => 'https://www.facebook.com/sharer/sharer.php?&u={{permalink}}',
        'fb_icon'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
								<path fill="none" d="M0 0h24v24H0z"/>
								<path d="M15.402 21v-6.966h2.333l.349-2.708h-2.682V9.598c0-.784.218-1.319 1.342-1.319h1.434V5.857a19.19 19.19 0 0 0-2.09-.107c-2.067 0-3.482 1.262-3.482 3.58v1.996h-2.338v2.708h2.338V21H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-4.598z"/>
							</svg>',
        'twitter_enabled'    => '1',
        'twitter_intentUrl' => 'https://www.twitter.com/intent/tweet?url={{permalink}}&text=',
        'twitter_icon'  => '<svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M11.6467787,8.46863508 L18.932133,0 L17.2057373,0 L10.879865,7.35319683 L5.82740935,0 L0,0 L7.64030431,11.1193359 L0,20 L1.72649364,20 L8.40677773,12.2347742 L13.742541,20 L19.5699503,20 L11.6463547,8.46863508 L11.6467787,8.46863508 Z M9.28210899,11.2172998 L8.50798699,10.1100647 L2.34857343,1.29967954 L5.00036694,1.29967954 L9.9710854,8.40994153 L10.7452074,9.51717671 L17.2065527,18.7594282 L14.5547592,18.7594282 L9.28210899,11.2177239 L9.28210899,11.2172998 Z" id="Shape"></path></svg>',
        'twitter_msg'   => "Check out this link!",
    ],
    'referral_link' => [
        'order' => '2',
        'enabled' => '1',
        'title'         => 'Share your Link',
        'subtitle'      => '',
    ],
    'email' => [
        'order' => '3',
        'enabled' => '1',
        'title'     => 'Share via Email',
        'subtitle'  => 'We\'ll email a link to this page to your friends for you.',
        'emails_input_placeholder'  => 'To: Enter emails separated with commas', 
        'message_placeholder'       => 'Write a message to your friends',
        'send_btn_text'             => 'Send',
        'email_template'    =>'',
        'email_preview'     => 'on',
        "email_message_fallback" => 'Check out this page!',
        'contact_picker_config'  => '',
        'success_screen_msg' => '🎉 Congratulations! You just successfully sent {{emails_count}} email(s).',
        'success_screen_cta_label' => 'Continue',

    ],
    'compact_view_icons' => [
        'email' => '<svg width="20" height="13" viewBox="0 0 20 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.0711 0.0279541H0.928906C0.902773 0.0279541 0.87707 0.0297331 0.851484 0.0318215L1.72246 0.612938C1.77367 0.632507 1.82359 0.657335 1.87098 0.688971L10 6.11273L18.129 0.688971C18.1291 0.688893 18.1291 0.688893 18.1292 0.688855L19.1163 0.0302359C19.1012 0.0295011 19.0864 0.0279541 19.0711 0.0279541Z" fill="white"/>
                        <path d="M15.8129 3.00871L10.4152 6.61011C10.2898 6.69388 10.1448 6.73572 9.99996 6.73572C9.85508 6.73572 9.71016 6.69384 9.58469 6.61011L7.84465 5.44915C7.79262 5.42947 7.74203 5.40371 7.69391 5.37161L0 0.23806V12.0522C0 12.5601 0.415898 12.9719 0.928906 12.9719H19.0711C19.5841 12.9719 20 12.5601 20 12.0522V0.215088L15.8129 3.00871Z" fill="white"/>
                    </svg>',
        'share' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.8032 1.4428L16.1772 0.889045C14.7192 -0.403786 12.4867 -0.269737 11.1943 1.18858L9.40278 3.20978C8.99841 3.66605 9.04043 4.36653 9.49652 4.77128L9.66467 4.92048C10.1223 5.32624 10.8207 5.28434 11.2267 4.82661L13.0182 2.80547C13.4178 2.35503 14.11 2.31358 14.5607 2.71256L15.186 3.26726C15.6368 3.66675 15.6793 4.35886 15.2791 4.80956L11.3617 9.22881C11.0301 9.60345 10.4876 9.70448 10.0423 9.47441C9.55546 9.22298 8.96165 9.33345 8.5981 9.74346L8.56755 9.77794C8.32157 10.0554 8.21903 10.4237 8.28621 10.7884C8.35346 11.153 8.58042 11.4605 8.90892 11.6322C9.42845 11.9037 9.98949 12.0352 10.545 12.0352C11.5294 12.0352 12.4968 11.6224 13.1861 10.8452L17.1032 6.42645C18.3942 4.9701 18.2598 2.73474 16.8032 1.4428Z" fill="white"/>
                        <path d="M8.49283 13.2199L8.3243 13.0706C7.86771 12.6661 7.16704 12.7086 6.76261 13.165L4.97187 15.1859C4.57206 15.6362 3.88033 15.678 3.43001 15.2793L2.80388 14.7238C2.35293 14.3245 2.31059 13.6329 2.7109 13.1821L6.62818 8.76316C6.95294 8.39638 7.4873 8.29149 7.92773 8.50787C8.43389 8.75657 9.04849 8.63564 9.42319 8.21277L9.43726 8.19692C9.67798 7.92515 9.77926 7.56445 9.71525 7.2073C9.65123 6.85009 9.43105 6.54707 9.11104 6.37582C7.6642 5.60131 5.89329 5.91821 4.80454 7.14684L0.887008 11.5649V11.5649C-0.404112 13.0213 -0.269809 15.2566 1.18654 16.548L1.81255 17.1025C2.4833 17.6972 3.3191 17.9894 4.15211 17.9894C5.12778 17.9894 6.0994 17.5884 6.79621 16.8026L8.5872 14.7817C8.9924 14.3241 8.95082 13.626 8.49283 13.2199Z" fill="white"/>
                    </svg>',
    ]
];