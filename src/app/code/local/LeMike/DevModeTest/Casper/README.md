# Testing with Casper

... is easy.
This is the `skeleton.coffee`:

```
common = require('common.coffee').common

casper.test.begin 'Suite title', 0, (test) ->

  casper.start common.home, ->
    test.assertExists "#ld_toolbox", 'toolbox found'

  casper.then ->
    test.assertVisible "#ld_toolbox", 'toolbox can be seen'

  casper.run ->
    test.done()

```

And here are the possible assertions: http://docs.casperjs.org/en/latest/modules/tester.html
The selector almost works like in jQuery as you can read here: http://docs.casperjs.org/en/latest/selectors.html
Lookup common.coffee where information like URLs are stored in.
