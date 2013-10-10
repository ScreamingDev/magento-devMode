## v0.3.1

- Used latest development version of EcomDev_PHPUnit
- 7885a5b Swap template definition
- 608aac8 Additional tests on blocks
- 1bf1cd9 Enhanced testing a lot
- c11ba95 Multiple tests added
- 4693f60 obsolete method Helper::getStoreConfig
- f75e2fe #37 Test on config to redirect all mails to another recipient
- 495dd6f #35 swapped logic of LeMike_DevMode_Model_Core_Email_Template::send to
LeMike_DevMode_Helper_Core::handleMail
- df84b20 #34 correct tests


## v0.3.0

### Major changes

- #21 list rewrites via shell script and in backend
- phpinfo - tab, block, template and documentation
- #9 variables can be changed for one time via url
- #26 added scripts to delete categories and products
- #25 and some additional information in the about page
- #27 ip can now be restricted
- #28 show modules and reinstall them (in core > resources)
- Removed aliases from layout because they can not be tested / asserted

### Minor changes

- Changed introduction in documentation
- Documentation for security
- Added dependency to Mage_Core
- Create composer.json and now available on packagist
- #29 Code Quality with UnitTests, Documentation, lesser code duplicates etc.
- #30 Write correct unit test for LeMike_DevMode_Model_Core_Email_Template::send
  - Made a fixture with the option lemike_devmode_core/email/active disabled (0)
  - Mock the Mail-Template so that it only outputs a unique string (in the usual way with $foo->getMock() etc.)
  - Dispatch newsletter/subscriber/new with the post ['email' => 'lemike_devmode']
  - Test against / Assert the previous defined unique string
  - check if magento output is suppressed by testing helper and current response body


### Bug fix

- fix in makePresentation
- bug fix due to resource changes in setup script
- Hot fix for missing Setup Model - Model not needed.
- Magento Config Model has not always data, used Mage::app()->getConfig() for collecting rewrites
- Removed dump and exits
- Test on Adminhtml controller with certain bug-fixes (catalog, core, customer and sales)
