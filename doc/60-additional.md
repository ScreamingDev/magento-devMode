# Additional tricks

via URL or Shell


## Mighty URL

- See what events and observer has been used.


### Events and Observers

- Add `?__events` to the URL (or Query)
- The original output will be replaced with something like this:

```
Array (
    [global] => Array (
            [controller_front_init_before] =>
            [controller_front_init_routers] => Array (
                    [observers] => Array (
                            [cms] => Array (
                                    [type] =>
                                    [model] => Mage_Cms_Controller_Router
                                    [method] => initControllerRouters
                                    [args] => Array ()
    ...
```

Note:
This will show all used events and observer only almost in the called order.
Global means that they are used in frontend and backend.
So the other keys are `frontend` and `backend`.


## Shell Tools

- Change the admin password without nagging mail
- List the current rewrites

### Change admin password

- Run `shell/adminPassword.php`
- You can promt the username of the admin
- and a new password

Note: The admin have to exist.

### List current rewrites

```
magento/shell$ php coreConfig_listRewrites.php

 Config path                                       | New class
---------------------------------------------------+-----------------------------------------------------
 global/models/core/rewrite/email                  | LeMike_DevMode_Model_Core_Email
 global/models/core/rewrite/email_template         | LeMike_DevMode_Model_Core_Email_Template
 global/models/core/rewrite/email_transport        | LeMike_DevMode_Model_Core_Email_Transport
 global/models/tax/rewrite/config                  | FireGento_GermanSetup_Model_Tax_Config
 global/blocks/customer/rewrite/account_navigation | Webguys_CustomerNavigation_Block_Account_Navigation
```

Note: Duplicates / Conflicts will be marked or highlighted.
