# Additional tricks

via URL or Shell


## Mighty URL

- See what events and observer has been used.
- Change config on the fly


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


### Change the config on the fly

Imagine the config `dev/translate_inline/active`.
Enable it with `foo.html?__dev__translate_inline__active=1`

![Enable translation on the fly](https://f.cloud.github.com/assets/2559177/1100839/8f28f710-178f-11e3-9066-e12f0c587e63.png)

Note: A prefix is added and the slashes became the same.
Think about a JavaScript Bookmark to enable this even faster.


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


### Manipulate modules

Take a look at the current modules is easy with `php core_modules.php`.
You got the options:

```
  --codePool local          Get only the modules in the "local" code pool.
                            Also works with "core" and "community".
  --name LeMike             Get all modules beginning with "LeMike".
```

Note: Filter like `codePool` or `name` can be combined.


Here is an example output:

```
    Module name      | Cached  | Installed | Filesystem | Code Pool |
---------------------+---------+-----------+------------+-----------+-
EcomDev_PHPUnit      | 0.1.0   |           | 0.1.0      | community |
LeMike_DevMode       | 0.2.0   | 0.2.0     | 0.2.0      | community |
Phoenix_Moneybookers | 1.6.0.0 | 1.6.0.0   | 1.6.0.0    | community |

Module name: The name of the module
     Cached: What is stored in the cache
  Installed: What is stored in the db
 Filesystem: The version in the according config.xml
  Code Pool: Where the extensions resides
```


