<?php 

// Used to set a limit for emails sent per form submission

return [    
    'default_emails_limit' => 10,
    'default_spam_regex'    => "[.?](?=[^\s])",
    'errors'                =>  [ 
                                    ['code' => 'E-1', 'message' => 'Spam detected. Display error messages'], 
                                    ['code' => 'E-2', 'message' => 'Spam detected. Hide error messages'], 
                                    ['code' => 'E-3', 'message' => 'Detected url in the custom message.'], 
                                ]
    ];