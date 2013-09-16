# The Magento_Core


## E-Mail

- See an E-Mail in the Browser instead of sending them


### Easily design an E-Mail

- In the Backend go to: `System > Configuration > Developer Mode - Core`
- Open E-Mail
- Set "Send mails" to "No"

Note: Now the mails will stuck in your browser instead of sending them.


## PHP

- `phpinfo()` in the backend


### PHP-Info

Look up a PHP-Info in `Development > Core > PHP` if you want to know what the environment says.

![Lookup phpinfo](https://f.cloud.github.com/assets/2559177/1098276/2ac26af2-171e-11e3-8b17-a3c71a55bd15.png)


## Config

- Take a look at the rewrites


### Rewrites

Go to `Development > Core > Config`

![Rewrites in menu "Development" > "Core" > "Config"](https://f.cloud.github.com/assets/2559177/1061743/b6feb66c-11fe-11e3-9f5f-7e92a6df97be.png)


### Cron Jobs

![See Crontabs in "Development" > "Core" > "Config"](https://f.cloud.github.com/assets/2559177/1148064/f9a92bf8-1eb7-11e3-9007-b2524062b08f.png)


## Extensions

- Rerun setup for a module


### Rerun setup for a module

Rerun setup scripts with a single click.

![2013-09-07-tooltip_003](https://f.cloud.github.com/assets/2559177/1101309/7c7a9bb2-17be-11e3-882c-c4ef8a3d37d6.png)

Note:
You see the name, the cached version, the installed as in the database and the available filesystem version.
With an click on "Run setup again" the version will be set back to "0.0.0" and the config cache will be cleaned up.
Reinstalling something with Mage_Admin* is forbidden because this would harm the instance.
