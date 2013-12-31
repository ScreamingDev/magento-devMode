# Core

Information about internals


## E-Mail

- Easily design an E-Mail
- Redirect mails


### Easily design an E-Mail

- Open E-Mail
- Set "Send mails" to "No"

Note: Now the mails will stuck in your browser instead of sending them.


### Redirect mails

- Write down mails separated by comma.
- All mails will go there instead.

Note: Mails no longer will reach it's destination.
They will be redirected to the configured mails.


## PHP

- `phpinfo()` in the backend


### phpinfo() in the backend

Look up a PHP-Info in `Development > Core > PHP` if you want to know what the environment says.

![Lookup phpinfo](https://f.cloud.github.com/assets/2559177/1098276/2ac26af2-171e-11e3-8b17-a3c71a55bd15.png)


## Config

In `Development > Core > Config` you can:

- Take a look at the rewrites
- Crontabs that will be done
- All active observer


### Rewrites

Find a list of rewrites for every module.

![Rewrites in menu "Development" > "Core" > "Config"](https://f.cloud.github.com/assets/2559177/1061743/b6feb66c-11fe-11e3-9f5f-7e92a6df97be.png)


### Cron Jobs

Crontabs that will be done by each module.

![See Crontabs in "Development" > "Core" > "Config"](https://f.cloud.github.com/assets/2559177/1148064/f9a92bf8-1eb7-11e3-9007-b2524062b08f.png)


### See all observer

Observer that will handle events across every extension.

![Development > Core > Config](https://f.cloud.github.com/assets/2559177/1148893/31bed89a-1ed0-11e3-9410-6656b828d850.png)


## Extensions

- Rerun setup for a module


### Rerun setup for a module

Rerun setup scripts with a single click.

![2013-09-07-tooltip_003](https://f.cloud.github.com/assets/2559177/1101309/7c7a9bb2-17be-11e3-882c-c4ef8a3d37d6.png)

Note:
You see the name, the cached version, the installed as in the database and the available filesystem version.
With an click on "Run setup again" the version will be set back to "0.0.0" and the config cache will be cleaned up.
Reinstalling something with Mage_Admin* is forbidden because this would harm the instance.
