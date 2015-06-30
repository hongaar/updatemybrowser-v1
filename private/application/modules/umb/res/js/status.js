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
 * Require UMB.Detect
 * Require UMB.Browsers
 */
;if (typeof UMB === "undefined") {UMB = function() {}};

UMB.Status = function() {
	
	var b;
	var v;
	
	var STATUS_LATEST 		= 'latest';
	var STATUS_UPDATE 		= 'update';
	var STATUS_WARNING 		= 'warning';
	
	return {
		
		init: function() {
			UMB.Detect.init();
			
			b = UMB.Detect.browser;
			v = UMB.Detect.version;
		},
		
		getBrowserInfo: function(browser) {
			return UMB.Browsers[browser];
		},

		getStatus: function() {
			var browser = UMB.Status.getBrowserInfo(b);
			var latestVersion = parseFloat(browser.current);
			var minimumVersion = parseFloat(UMB.getConfig().require[b]);
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