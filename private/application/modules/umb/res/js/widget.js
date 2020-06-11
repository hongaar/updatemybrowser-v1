/*!
 * updatemybrowser.org JavaScript Library v1
 * http://updatemybrowser.org/
 *
 * Copyright 2012, Joram van den Boezem
 * Licensed under the GPL Version 3 license.
 * http://www.gnu.org/licenses/gpl.html
 *
 */
/*!
 * Require UMB.Status
 */
;if (typeof UMB === "undefined") {UMB = function(){}};

UMB.Widget = function () {

    var hasInit = false;
    var isFixed = false;

    var oldBodyMarginTop;

    var applyStyle = function (style, elm) {
        for (var x in style) {
            elm.style[x] = style[x];
        }
        ;
    };

    var setCookie = function (key, value, days) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + days);
        var content = encodeURIComponent(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString()) + '; path=/';
        document.cookie = key + '=' + content;
    };

    var insertHtml = function () {

        // CLEAN UP OLD WRAPPER
        isFixed = false;
        var oldWrapper = document.getElementById('BrowserBar');
        if (oldWrapper) {
            document.getElementsByTagName('body')[0].removeChild(oldWrapper);
        }

        // WRAPPER
        var wrapper = document.createElement('div');
        var wrapperStyle = {
            display: 'none',
            position: 'absolute',
            height: '19px',
            fontSize: '14px',
            lineHeight: '1em',
            fontFamily: 'Arial, sans-serif',
            color: 'black',
            padding: '10px 0',
            top: '-40px',
            left: '0px',
            backgroundColor: '#FDF2AB',
            backgroundImage: 'url(//updatemybrowser.org/warning.png)',
            backgroundPosition: '10px center',
            backgroundRepeat: 'no-repeat',
            borderBottom: '1px solid #A29330',
            width: '100%',
            textAlign: 'left',
            cursor: 'pointer',
            zoom: '1',
            zIndex: 9999,
            '-webkit-box-sizing': 'content-box',
            '-moz-box-sizing': 'content-box',
            'box-sizing': 'content-box',
            overflow: 'hidden'
        };
        applyStyle(wrapperStyle, wrapper);
        wrapper.setAttribute('id', 'BrowserBar');

        // PARAGRAPH
        var p = document.createElement('p');
        var pStyle = {
            margin: '0px 0px 0px 40px',
            padding: '0px',
            lineHeight: '1.5em'
        };
        applyStyle(pStyle, p);

        // CLOSE BUTTON
        var a = document.createElement('a');
        a.href = 'javascript:void(0);';
        a.title = 'Don\'t show me this notification bar for the next 24 hours';
        a.onclick = function (e) {
            if (!e) {
                var e = window.event;
            }
            e.cancelBubble = true;
            if (e.stopPropagation) {
                e.stopPropagation();
            }

            UMB.Widget.hidePersistent(1);
            return false;
        };
        var aStyle = {
            display: 'block',
            width: '20px',
            height: '20px',
            margin: '0px 0px 0px 40px',
            padding: '0px',
            lineHeight: '1.5em',
            position: 'absolute',
            top: '10px',
            right: '10px',
            backgroundImage: 'url(//updatemybrowser.org/close.png)',
            backgroundPosition: '0 0',
            backgroundRepeat: 'no-repeat'
        };
        applyStyle(aStyle, a);

        wrapper.appendChild(p);
        wrapper.appendChild(a);
        document.getElementsByTagName('body')[0].appendChild(wrapper);
    };

    var prepareHtml = function () {
        // Get current browser info and status
        var status = UMB.getStatus();
        var browser = UMB.getBrowserInfo(UMB.getCurrentBrowser());
        var version = UMB.getCurrentVersion();

        if (!status || !browser || !version) return;

        var wrapper = document.getElementById('BrowserBar');
        var link = document.createElement('a');
        link.href = 'https://www.updatemybrowser.org';
        link.onclick = function () {
            return false;
        };
        link.style.color = '#2183d0';
        link.style.fontWeight = 'bold';
        link.target = '_blank';

        var message = '';
        var post = '';
        if (status == 'latest') {
            message = 'You have the latest version of your browser installed (' + browser.name + ' ' + version + '). ';
            link.style.color = '#00A651';
            link.appendChild(document.createTextNode('Learn more'));
        } else if (status == 'update') {
            message = 'An update (' + browser.name + ' ' + browser.current + ') is available for your browser. Please ';
            link.appendChild(document.createTextNode('install this browser update'));
            post = '.';
        } else if (status == 'warning') {
            message = 'An important update (' + browser.name + ' ' + browser.current + ') is available for your browser. Please ';
            link.style.color = '#ED1C24';
            link.appendChild(document.createTextNode('install this critical browser update'));
            post = '.';
            isFixed = true;	// make position fixed
        }
        wrapper.getElementsByTagName('p')[0].appendChild(document.createTextNode(message));
        wrapper.getElementsByTagName('p')[0].appendChild(link);
        wrapper.getElementsByTagName('p')[0].appendChild(document.createTextNode(post));

        // Make click event on BrowserBar go to link
        document.getElementById('BrowserBar').onclick = function () {
            window.open(link.href);
        };
    };

    var getComputedVal = function (elm, property) {
        var r;
        if (window.getComputedStyle) {
            r = window.getComputedStyle(elm)[property];
        } else if (elm.currentStyle) {
            r = elm.currentStyle[property];
        }
        if (!r) {
            r = elm.style[property];
        }
        return r;
    };

    var animate = function (elm, property, end, length, callback, pre, post) {
        // Animate opacity for IE
        if (property == 'opacity') {
            animate(elm, 'filter', end * 100, length, callback, 'alpha(opacity=', ')');
        }

        // Set property syntax
        var pxProps = '|top|marginTop|';
        pre = pre || '';
        post = post || '';
        if (pxProps.indexOf(property) > -1) {
            post = post || 'px';
        }

        // Begin value
        var begin = parseFloat(getComputedVal(elm, property).replace(pre, '').replace(post, '')) || 0;

        // Relative value?
        if (end.toString().indexOf('+') == 0 || end.toString().indexOf('-') == 0) {
            end = begin + parseFloat(end);
        }

        // Setup variables
        var interval = 10;
        var percstep = 1 / (length / interval);
        var perc = 0;

        // Setup helpers
        var prop = function (p) {
            var easedP = 0.5 - Math.cos(p * Math.PI) / 2;
            var propStep = (end - begin) * easedP;
            var newProp = begin + propStep;
            return Math.round(newProp * 100) / 100;
        };
        var apply = function (v) {
            elm.style[property] = pre + v + post;
        };

        // Make an interval
        var timer = setInterval(function () {
            perc = perc + percstep;
            apply(prop(perc));

            if (perc >= 1) {
                clearInterval(timer);
                apply(prop(1));
                if (callback) {
                    callback();
                }
            }
        }, interval);
    };

    var showBar = function () {
        var body = document.getElementsByTagName('body')[0];
        var BrowserBar = document.getElementById('BrowserBar');

        // Hide bar body only when BrowserBar is invisible
        if (getComputedVal(BrowserBar, 'display') !== 'none') {
            return;
        }

        // Add body class
        body.className += ' umb-active';

        // BrowserBar
        BrowserBar.style.opacity = '0';
        BrowserBar.style.filter = 'alpha(opacity=0)';
        BrowserBar.style.display = 'block';
        animate(BrowserBar, 'opacity', 0.95, 600);

        if ((UMB.getCurrentBrowser() == 'ie' && document.compatMode == 'BackCompat')) {
            // Reposition BrowserBar for IE quirks workaround
            BrowserBar.style.top = '0px';
            BrowserBar.style.width = (document.documentElement.clientWidth || document.body.clientWidth) + 'px';
        } else {
            // Reposition body element
            body.style.position = 'relative';
            body.style.overflow = 'visible';
            animate(body, 'top', "+40", 300);

            if (!isFixed) {
                // Body margin fix
                UMB.attach(window, 'resize', function () {
                    BrowserBar.style.width = (document.documentElement.clientWidth || document.body.clientWidth) + 'px';
                });
                BrowserBar.style.width = (document.documentElement.clientWidth || document.body.clientWidth) + 'px';
                BrowserBar.style.top = '-' + (parseFloat(getComputedVal(body, 'marginTop')) + 40) + 'px';
                BrowserBar.style.left = '-' + parseFloat(getComputedVal(body, 'marginLeft')) + 'px';
            }
        }
        if (isFixed) {
            if ((UMB.getCurrentBrowser() == 'ie' && document.compatMode == 'BackCompat')) {
                // Fixed position for Quirks mode
                UMB.attach(window, 'scroll', function () {
                    BrowserBar.style.top = ((document.documentElement.scrollTop || document.body.scrollTop) + (!BrowserBar.offsetHeight && 0)) + 'px';
                });
                BrowserBar.style.top = ((document.documentElement.scrollTop || document.body.scrollTop) + (!BrowserBar.offsetHeight && 0)) + 'px';
            } else if (UMB.getCurrentBrowser() == 'ie' && UMB.getCurrentVersion() <= 6) {
                // Fixed position IE6
                UMB.attach(window, 'resize', function () {
                    BrowserBar.style.width = (document.documentElement.clientWidth || document.body.clientWidth) + 'px';
                });
                BrowserBar.style.width = (document.documentElement.clientWidth || document.body.clientWidth) + 'px';
                var bbTop = parseFloat(getComputedVal(body, 'marginTop')) + 40;
                BrowserBar.style.top = '-' + bbTop + 'px';
                BrowserBar.style.left = '-' + parseFloat(getComputedVal(body, 'marginLeft')) + 'px';
                UMB.attach(window, 'scroll', function () {
                    BrowserBar.style.top = ((document.documentElement.scrollTop || document.body.scrollTop) - bbTop) + 'px';
                });
                BrowserBar.style.top = ((document.documentElement.scrollTop || document.body.scrollTop) - bbTop) + 'px';
            } else {
                // Fixed position
                BrowserBar.style.top = '0px';
                BrowserBar.style.position = 'fixed';
            }
        }
    };

    var hideBar = function () {
        var body = document.getElementsByTagName('body')[0];
        var BrowserBar = document.getElementById('BrowserBar');

        // Hide bar body only when BrowserBar is visible
        if (getComputedVal(BrowserBar, 'display') !== 'block') {
            return;
        }

        // Remove body class
        body.className = body.className.replace(' umb-active', '');

        // BrowserBar
        animate(BrowserBar, 'opacity', 0, 600, function () {
            BrowserBar.style.display = 'none';
        });

        // IE Quirks workaround
        if (UMB.getCurrentBrowser() == 'ie' && document.compatMode == 'BackCompat') {
        } else {
            animate(body, 'top', "-40", 300);
        }
    };

    return {

        init: function () {
            if (hasInit) {
                return;
            }
            hasInit = true;

            UMB.Widget.redraw();
        },

        redraw: function () {
            insertHtml();
            prepareHtml();
        },

        display: function () {
            UMB.Widget.init();
            showBar();
        },

        hide: function () {
            UMB.Widget.init();
            hideBar();
        },

        hidePersistent: function (days) {
            days = days || 1;
            setCookie('_umb', 'hide', days);
            UMB.hideWidget();
        }

    };
}();
