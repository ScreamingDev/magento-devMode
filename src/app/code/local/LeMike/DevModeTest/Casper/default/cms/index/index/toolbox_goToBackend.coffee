common = require('common.coffee').common

casper.test.begin common.home, 0, (test) ->

  # Backend link itself:
  casper.start common.home, ->
    test.assertElementCount '#ld_toolbox a[href*="admin/index/index/key/"]', 1, 'Link to backend given'
    this.click('#ld_toolbox a[href*="admin/index/index/key/"]')

  # Check for backend:
  casper.then ->
    test.assertExists 'body.adminhtml-dashboard-index',
      'Link ends in adminhtml-dashboard-index'

  casper.run ->
    test.done()

