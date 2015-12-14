if (typeof BD ==="undefined") {BD = function() {}};

BD.Check = function() {

    var statusDiv 			= $('.right-column div.status');
    var statusElements 		= $('.right-column div.status > *');
    var arrowDiv			= $('#arrow');

    var speed				= 0;

    var showOverlay = function() {
        $('.teaserwrapper').addClass('fill');
        setTimeout(function() {
            arrowDiv.css({top: $('a.bigbutton.update').offset().top + 60 + 'px'});
            arrowDiv.fadeIn(1000);
        }, 1000);
    };

    var hideOverlay = function() {
        arrowDiv.fadeOut();
        $('.teaserwrapper').removeClass('fill');
    };

    return {

        init: function() {
            b = UMB.getCurrentBrowser();
            v = UMB.getCurrentVersion();
            s = UMB.getStatus();

            if (s === 'unsupported') {
                BD.UI.ContentMenu.setContent('unknown');
                return;
            }

            BD.UI.ContentMenu.setMenu(b, speed);
            BD.UI.ContentMenu.setContent('status');

            BD.Check.setStatus(s);

            arrowDiv.click(function() {
                hideOverlay();
            });

            // Event handlers
            BD.UI.ContentMenu.click(function(elm) {
                hideOverlay();
                var key = $(elm).attr('data-key');
                if (key == b) {
                    BD.UI.ContentMenu.setContent('status');
                    return false;
                }
                return true;
            });
        },

        setStatus: function(status) {
            // Prepare right column
            statusElements.hide();
            statusDiv.show();

            // Show arrow?
            if (status !== 'latest' && !((UMB.getCurrentBrowser() == 'ie' && UMB.getCurrentVersion() <= 6))) {
                //showOverlay();
            }

            // Get current browser info and fill text
            var browser = UMB.getBrowserInfo(b);
            statusDiv.addClass(b);
            statusDiv.find('.browser').text(browser.name);
            statusDiv.find('.version').text(' ' + v);
            statusDiv.find('.bigbutton').removeClass('fancybox, blank').attr('href', browser.update_url).addClass(browser.iframe_allowed == 1 ? 'fancybox' : 'blank');
            statusDiv.find('.readmore').removeClass('fancybox, blank').attr('href', browser.info_url).addClass(browser.iframe_allowed == 1 ? 'fancybox' : 'blank');

            statusElements.filter('.' + status).fadeIn(speed);
        }

    };
}();

$(document).ready(function() {
    BD.Check.init();
});