/*!
 * updatemybrowser.org JavaScript Library v1
 * http://updatemybrowser.org/
 *
 * Copyright 2015, Joram van den Boezem
 * Licensed under the GPL Version 3 license.
 * http://www.gnu.org/licenses/gpl.html
 * 
 */
;

UMB = function () {

    var hasInit = false;
    var hasLoaded = false;
    var config = {};

    /*
     * Recursively merge properties of two objects
     */
    function mergeRecursive(obj1, obj2, lvl) {
        var lvl = lvl || 0;
        for (var p in obj1) {
            try {
                if (obj2[p].constructor == Object) {
                    obj1[p] = mergeRecursive(obj1[p], obj2[p], lvl + 1);
                } else {
                    obj1[p] = obj2[p];
                }
            } catch (e) {
            }
        }
        return obj1;
    }

    var init = function () {
        if (hasInit) {
            return;
        }
        hasInit = true;

        UMB.Detect.init();

        var _umb = window._umb || {};
        config = {
            require: {
                chrome: UMB.Browsers['chrome'].minimum,
                firefox: UMB.Browsers['firefox'].minimum,
                ie: UMB.Browsers['ie'].minimum,
                opera: UMB.Browsers['opera'].minimum,
                safari: UMB.Browsers['safari'].minimum,
                edge: UMB.Browsers['edge'].minimum
            },
            display: true,
            nonCritical: false
        };
        config = mergeRecursive(config, _umb);
    };

    return {

        load: function () {
            if (hasLoaded) {
                return;
            }
            hasLoaded = true;

            UMB.attach(window, 'load', function () {
                init();
                // Display at all?
                if (config.display) {
                    UMB.autoDisplayWidget();
                }
            });
        },

        // http://stackoverflow.com/questions/9434/how-do-i-add-an-additional-window-onload-event-in-javascript
        attach: function (elm, event, callback) {
            if (elm.addEventListener) { // W3C standard
                window.addEventListener(event, callback, false);
            } else if (elm.attachEvent) { // Microsoft
                elm.attachEvent('on' + event, callback);
            }
        },

        getConfig: function () {
            init();
            return config;
        },

        getCurrentBrowser: function () {
            init();
            return UMB.Detect.browser;
        },

        getCurrentVersion: function () {
            init();
            return UMB.Detect.version;
        },

        getBrowserInfo: function (browser) {
            init();
            return UMB.Browsers[browser];
        },

        getStatus: function () {
            init();
            return UMB.Status.getStatus();
        },

        displayWidget: function () {
            init();
            UMB.Widget.display();
        },

        hideWidget: function () {
            init();
            UMB.Widget.hide();
        },

        autoDisplayWidget: function () {
            init();

            // Cookie set to hide bar?
            if (document.cookie.indexOf('_umb=hide') == -1) {
                var status = UMB.getStatus();

                if (status == 'update' && config.nonCritical) {
                    // Display on recommended update
                    UMB.displayWidget();
                } else if (status == 'warning') {
                    // Display on critical update
                    UMB.displayWidget();
                }
            }
        },

        scrollToTop: function () {
            // http://stackoverflow.com/questions/871399/cross-browser-method-for-detecting-the-scrolltop-of-the-browser-window
            var B = document.body; //IE 'quirks'
            var D = document.documentElement; //IE with doctype
            D = (B.clientHeight) ? B : D;
            D.scrollTop = 0;
        }
    };
}();
UMB.load();