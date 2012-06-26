if (typeof BD ==="undefined") {BD = function() {}};

BD.Check = function() {
	
	var statusDiv 			= $('.right-column div.status');
	var statusElements 		= $('.right-column div.status > *');
		
	var speed				= 0;
	
	return {
		
		init: function() {			
			b = UMB.getCurrentBrowser();
			v = UMB.getCurrentVersion();
			
			BD.UI.ContentMenu.setMenu(b, speed);
			BD.UI.ContentMenu.setContent('status');
			
			BD.Check.setStatus(UMB.getStatus());
			
			// Event handlers
			BD.UI.ContentMenu.click(function(elm) {
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