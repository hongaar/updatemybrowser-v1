if (typeof BD ==="undefined") { 		BD = function() {}; }

BD.Check = function() {
	
	var b;
	var v;
	var statusDiv 			= $('.right-column div.status');
	var statusElements 		= $('.right-column div.status > *');
	
	var STATUS_LATEST 		= 'latest';
	var STATUS_UPDATE 		= 'update';
	var STATUS_WARNING 		= 'warning';
	
	var speed				= 0;
	
	var getBrowser = function(browser) {
		return BD.Browsers[browser];
	};
	
	var getStatus = function() {
		var browser = getBrowser(b);
		var latestVersion = parseFloat(browser.current);
		var minimumVersion = parseFloat(browser.minimum);
		if (v >= latestVersion) {
			return STATUS_LATEST;
		} else if (v >= minimumVersion) {
			return STATUS_UPDATE;
		} else {
			return STATUS_WARNING;
		}
	};
	
	return {
		
		init: function() {
			b = BrowserDetect.browser;
			v = BrowserDetect.version;
			
			BD.UI.ContentMenu.setMenu(b, speed);
			BD.Check.setStatus(getStatus());
			
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
			var browser = getBrowser(b);
			statusDiv.addClass(b);
			statusDiv.find('.browser').text(browser.name);
			statusDiv.find('.version').text(v);
			statusDiv.find('.bigbutton').attr('href', browser.update_url);
			statusDiv.find('.readmore').attr('href', browser.info_url);
			
			statusElements.filter('.' + status).fadeIn(speed);
		}
		
	};
}();

$(document).ready(function() {
	BD.Check.init();
});