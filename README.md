# MAGENTO2 CONSOLE LOG HANDLER 

Writes logs to the console output depending on its verbosity setting.

Inspired by Symonfy Console Log Handler.

### Install via composer

```
composer require mage4/magento2-console-log-handler

php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

### Enable from system configration

To Enabled this handle, either manually turn on the `Is Enabled` flag from system configuration area in Magento admin panel at MAGE4 EXTENSIONS > Console Log Handler > Settings > Is Enabled = Yes.

**OR**

Update it via console command by: `bin/magento config:set console_log_handler/settings/is_enabled 1`

### Usage

Can be used with any Magento2 custom or inbuild console command by adding verbosity level to the command. e.g.
```
bin/magento setup:upgrade -vvv
### OR ###
setup:di:compile -v
```

The minimum logging level at which this handler will be triggered depends on the verbosity setting of the console output. The default mapping is:
`OutputInterface::VERBOSITY_VERBOSE` (-v) will show all NOTICE and higher logs
`OutputInterface::VERBOSITY_VERY_VERBOSE` (-vv) will show all INFO and higher logs
`OutputInterface::VERBOSITY_DEBUG` (-vvv) will show all DEBUG and higher logs, i.e. all logs

## About us
We’re an innovative development agency building awesome websites, webshops and web applications with Latest Technologies. Check out our website [mage4.com](http://mage4.com/) or email us at [contact@mage4.com](mailto:contact@mage4.com) | [rehan@mage4.com](mailto:rehan@mage4.com).

