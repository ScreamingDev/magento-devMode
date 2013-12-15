common = require('common.coffee').common
utils = require('utils');

casper.test.begin 'Toolbox in "Home Page"', 0, (test) ->

  # Toolbox itself:
  casper.start common.home, ->
    test.comment common.home
    test.info 'Toolbox there?'

    test.assertExists "#ld_toolbox", 'toolbox found'
    test.assertVisible "#ld_toolbox", 'toolbox can be seen'

  # Store:
  casper.then ->
    test.info 'Store section'
    section = '#ld_toolbox a.store '
    test.assertElementCount section, 1, 'Topic of store exists'
    test.assertSelectorHasText section, 'default', 'Shows correct store'

  # Module:
  casper.then ->
    test.info 'Module section'
    test.assertElementCount '#ld_toolbox span.module', 1, 'Topic of module exists'
    test.assertSelectorHasText '#ld_toolbox span.module', 'cms', 'Shows correct module'

  # Controller:
  casper.then ->
    test.info 'Controller section'
    test.assertElementCount '#ld_toolbox span.controller', 1, 'Topic of controller exists'
    test.assertSelectorHasText '#ld_toolbox span.controller', 'index', 'Shows correct controller'

  # Action:
  casper.then ->
    test.info 'Action section'
    test.assertElementCount '#ld_toolbox span.action', 1, 'Topic of action exists'
    test.assertSelectorHasText '#ld_toolbox span.action', 'index', 'Shows correct action'

  # OnLoad:
  casper.then ->

    this.captureSelector 'default_cms_index_index-ld_toolbox.png', '#ld_toolbox'

  casper.run ->
    test.done()
