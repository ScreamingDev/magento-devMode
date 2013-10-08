/**
 * Scripts for the toolbox.
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     0.4.0
 */

var ld_toolboxId = "ld_toolbox";

function lemikeDevmode_urlSetParameter(url, param, value) {
    // Using a positive look-ahead (?=\=) to find the
    // given parameter, preceded by a ? or &, and followed
    // by a = with a value after than (using a non-greedy selector)
    // and then followed by a & or the end of the string
    var val = new RegExp('(\\?|\\&)' + param + '=.*?(?=(&|$))'),
        parts = url.toString().split('#'),
        url = parts[0],
        hash = parts[1]
    qstring = /\?.+$/,
        newURL = url;

    // Check if the parameter exists
    if (val.test(url)) {
        // if it does, replace it, using the captured group
        // to determine & or ? at the beginning
        newURL = url.replace(val, '$1' + param + '=' + value);
    }
    else if (qstring.test(url)) {
        // otherwise, if there is a query string at all
        // add the param to the end of it
        newURL = url + '&' + param + '=' + value;
    }
    else {
        // if there's no query string, add one
        newURL = url + '?' + param + '=' + value;
    }

    if (hash) {
        newURL += '#' + hash;
    }

    return newURL;
}

var ld_toolboxDrag;

document.observe("dom:loaded", function () {
    var toolbox = $(ld_toolboxId);

    ld_toolboxDrag = new Draggable(ld_toolboxId);
});

function lemikeDevmode_alert(target, title) {
    showDialog($(target).innerHTML, title);
}

function showDialog(content, title) {
    Dialog.alert(
        content,
        {
            width: document.viewport.getDimensions().width * 0.66,
            height: document.viewport.getDimensions().height * 0.66,
            okLabel: "close",
            ok: function (win) {
                return true;
            }
        }
    );
}

function lemikeDevmode_makeWindow(url) {
    win = new Window({
            className: 'magento',
            title: 'Events and observer',
            url: url,
            width: document.viewport.getDimensions().width * 0.66,
            height: document.viewport.getDimensions().height * 0.66,
            minimizable: true,
            maximizable: true,
            showEffectOptions: {duration: 0.4},
            hideEffectOptions: {duration: 0.4}}
    );

    win.setZIndex(9999);
    win.showCenter(true);
}
function lemikeDevmode_showEvents() {
    lemikeDevmode_makeWindow(
        lemikeDevmode_urlSetParameter(document.location.href, '__events', 1)
    );
}
