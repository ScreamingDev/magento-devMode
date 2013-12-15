common = require('common.coffee').common

casper.test.begin common.home, 0, (test) ->

  # Store itself:
  casper.start common.home, ->
    test.assertElementCount '#ld_toolbox a.store', 1, 'Topic of store exists'
    test.assertSelectorHasText '#ld_toolbox a.store', 'default', 'Shows correct store'
    this.click('#ld_toolbox a.store')

  # Check for backend:
  casper.then ->
    test.info "Link to edit the current store:"
    test.assertExists 'body.adminhtml-system-store-editstore',
      'Link ends in adminhtml-system-store-editstore'
    test.assertExists 'input#store_code',
      'Store code input-field exists'
    test.assertField 'store[code]', 'default',
      'Has correct value'
    test.pass "Link to edit the store works!"

  casper.run ->
    test.done()

