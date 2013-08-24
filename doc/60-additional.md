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


### Change admin password

- Run `shell/adminPassword.php`
- You can promt the username of the admin
- and a new password

Note: The admin have to exist.
