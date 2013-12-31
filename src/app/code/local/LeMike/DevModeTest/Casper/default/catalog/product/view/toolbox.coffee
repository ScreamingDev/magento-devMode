common = require('common.coffee').common
utils = require('utils');

casper.test.begin 'Toolbox in product view', 0, (test) ->

  # Toolbox itself:
  casper.start common.productUrl, ->
    test.comment common.productUrl
    test.assertHttpStatus(200)

    test.info 'Toolbox there?'

    test.assertExists "#ld_toolbox", 'toolbox found'
    test.assertVisible "#ld_toolbox", 'toolbox can be seen'

    test.assertResourceExists 'toolbox.js', 'JavaScript loaded'
    test.assertResourceExists 'toolbox.css', 'Stylesheet loaded'

  # Store:
  casper.then ->
    test.info 'Store section'
    section = '#ld_toolbox a.store '
    test.assertElementCount section, 1, 'Topic of store exists'
    test.assertSelectorHasText section, 'Store: default', 'Shows correct store'

  casper.back()

  # Module:
  casper.then ->
    test.info 'Module section'
    test.assertElementCount '#ld_toolbox span.module', 1, 'Topic of module exists'
    test.assertSelectorHasText '#ld_toolbox span.module', 'Module: catalog', 'Shows correct module'

  # Controller:
  casper.then ->
    test.info 'Controller section'
    test.assertElementCount '#ld_toolbox span.controller', 1, 'Topic of controller exists'
    test.assertSelectorHasText '#ld_toolbox span.controller', 'Controller: product', 'Shows correct controller'

  # Action:
  casper.then ->
    test.info 'Action section'
    test.assertElementCount '#ld_toolbox span.action', 1, 'Topic of action exists'
    test.assertSelectorHasText '#ld_toolbox span.action', 'Action: view', 'Shows correct action'

  casper.then ->
    this.captureSelector 'default_catalog_product_view-ld_toolbox.png', '#ld_toolbox'

  casper.run ->
    test.done()
