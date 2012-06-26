/*!
 * BrowserBar JavaScript Library v1
 * http://browserbar.org/
 *
 * Copyright 2012, Joram van den Boezem
 * Licensed under the GPL Version 3 license.
 * http://www.gnu.org/licenses/gpl.html
 * 
 */
;

BBJS = function() {
	
	var hasInit = false;
	var hasLoaded = false;
	var config = {};
	
	/*
	* Recursively merge properties of two objects 
	*/
	function mergeRecursive(obj1, obj2) {
		for (var p in obj2) {
			try {
				// Property in destination object set; update its value.
				if ( obj2[p].constructor==Object ) {
					obj1[p] = MergeRecursive(obj1[p], obj2[p]);
				} else {
					obj1[p] = obj2[p];
				}
			} catch(e) {
				// Property in destination object not set; create it and set its value.
				obj1[p] = obj2[p];
			}
		}
		return obj1;
	}
	
	var init = function() {
		if (hasInit) {return;}
		hasInit = true;
				
		BBJS.Status.init();
		
		var _bbjs = window._bbjs || {};
		config = {
			require: {
				chrome:		BBJS.Browsers['chrome'].minimum,
				firefox:	BBJS.Browsers['firefox'].minimum,
				ie:			BBJS.Browsers['ie'].minimum,
				opera:		BBJS.Browsers['opera'].minimum,
				safari:		BBJS.Browsers['safari'].minimum
			},
			display: true,
			nonCritical: true
		};
		config = mergeRecursive(config, _bbjs);
	};
	
	// http://stackoverflow.com/questions/9434/how-do-i-add-an-additional-window-onload-event-in-javascript
	var addOnload = function(callback) {
		if (window.addEventListener) { // W3C standard
			window.addEventListener('load', callback, false);
		} else if (window.attachEvent) { // Microsoft
			window.attachEvent('onload', callback);
		}
	};
	
	return {
		
		load: function() {
			if (hasLoaded) {return;}
			hasLoaded = true;
			
			addOnload(function() {
				init();
				
				// Display at all?
				if (config.display) {
					BBJS.autoDisplayWidget();
				}
			});
		},
		
		getConfig: function() {
			init();
			return config;
		},
		
		getCurrentBrowser: function() {
			init();
			return BBJS.Detect.browser;
		},
		
		getCurrentVersion: function() {
			init();
			return BBJS.Detect.version;
		},
		
		getBrowserInfo: function(browser) {
			init();
			return BBJS.Status.getBrowserInfo(browser);
		},

		getStatus: function() {
			init();
			return BBJS.Status.getStatus();
		},
		
		displayWidget: function() {
			init();
			BBJS.Widget.display();
		},
		
		hideWidget: function() {
			init();
			BBJS.Widget.hide();
		},
		
		autoDisplayWidget: function() {
			init();
			var status = BBJS.getStatus();
			
			// Display on recommended update?
			if (status == 'update' && config.nonCritical) { 
				BBJS.displayWidget();
			// Display on critical update
			} else if (status == 'warning') {
				BBJS.displayWidget();
			}
		},
		
		scrollToTop: function() {
			// http://stackoverflow.com/questions/871399/cross-browser-method-for-detecting-the-scrolltop-of-the-browser-window			
			var B = document.body; //IE 'quirks'
			var D = document.documentElement; //IE with doctype
			D = (B.clientHeight) ? B : D;
			D.scrollTop = 0;
		}
	};
}();
BBJS.load();