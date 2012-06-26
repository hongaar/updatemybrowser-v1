/*!
 * BrowserBar JavaScript Library v1
 * http://browserbar.org/
 *
 * Copyright 2012, Joram van den Boezem
 * Licensed under the GPL Version 3 license.
 * http://www.gnu.org/licenses/gpl.html
 * 
 */
/*!
 * Require BBJS.Detect
 * Require BBJS.Browsers
 */
;if (typeof BBJS === "undefined") {BBJS = function() {}};

BBJS.Status = function() {
	
	var b;
	var v;
	
	var STATUS_LATEST 		= 'latest';
	var STATUS_UPDATE 		= 'update';
	var STATUS_WARNING 		= 'warning';
	
	return {
		
		init: function() {
			BBJS.Detect.init();
			
			b = BBJS.Detect.browser;
			v = BBJS.Detect.version;
		},
		
		getBrowserInfo: function(browser) {
			return BBJS.Browsers[browser];
		},

		getStatus: function() {
			var browser = BBJS.Status.getBrowserInfo(b);
			var latestVersion = parseFloat(browser.current);
			var minimumVersion = parseFloat(BBJS.getConfig().require[b]);
			if (v >= latestVersion) {
				return STATUS_LATEST;
			} else if (v >= minimumVersion) {
				return STATUS_UPDATE;
			} else {
				return STATUS_WARNING;
			}
		}		
	};
}();