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
 * Require BBJS.Status
 */
;if (typeof BBJS === "undefined") {BBJS = function() {}};

BBJS.Widget = function() {
	
	var hasInit				= false;
	var isFixed				= false;
	
	var applyStyle = function(style, elm) {
		for(var x in style) {
			elm.style[x] = style[x];
		};
	};
	
	var insertHtml = function() {
		
		// CLEAN UP OLD WRAPPER
		var oldWrapper = document.getElementById('BrowserBar');
		if (oldWrapper) {
			document.getElementsByTagName('body')[0].removeChild(oldWrapper);
		}
		
		// WRAPPER
		var wrapper = document.createElement('div');
		var wrapperStyle = {
			display: 'none',
			position: 'absolute',
			height: '20px',
			fontSize: '14px',
			lineHeight: '1em',
			fontFamily: 'Arial, sans-serif',
			color: 'black',
			padding: '10px 0',
			top: '-40px',
			backgroundColor: '#FDF2AB',
			backgroundImage: 'url(http://the.nabble.nl/browser/src/warning.png)',
			backgroundPosition: '10px center',
			backgroundRepeat: 'no-repeat',
			borderBottom: '1px solid #A29330',
			width: '100%',
			textAlign: 'left',
			cursor: 'pointer'
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
		
		wrapper.appendChild(p);
		document.getElementsByTagName('body')[0].appendChild(wrapper);
	};
		
	var prepareHtml = function() {
		// Get current browser info and status
		var status = BBJS.getStatus();
		var browser = BBJS.getBrowserInfo(BBJS.getCurrentBrowser());
		var version = BBJS.getCurrentVersion();
		
		var wrapper = document.getElementById('BrowserBar');
		var link = document.createElement('a');
		link.href = 'http://www.browserbar.org';
		link.onclick = function(){return false;};
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
			link.appendChild(document.createTextNode('install this update'));
			post = '.';
		} else if (status == 'warning') {
			message = 'An important update (' + browser.name + ' ' + browser.current + ') is available for your browser. Please ';
			link.style.color = '#ED1C24';
			link.appendChild(document.createTextNode('install this critical update'));
			post = '.';
			isFixed = true;	// make position fixed			
		}		
		wrapper.getElementsByTagName('p')[0].appendChild(document.createTextNode(message));
		wrapper.getElementsByTagName('p')[0].appendChild(link);
		wrapper.getElementsByTagName('p')[0].appendChild(document.createTextNode(post));
		
		// Make click event on BrowserBar go to link
		document.getElementById('BrowserBar').onclick = function(){window.open(link.href);};
	};
	
	var animate = function(elm, property, begin, end, length, pre, post, callback) {
		var curstep = begin;
		var ease = function(p) { return 0.5 - Math.cos( p * Math.PI ) / 2; };
		var perc = function(c) { return 1 - ((end - c) / (end - begin)); };
		var prop = function(c) { return ease(rvrs ? (1 - perc(c)) : perc(c)) * Math.abs(end - begin); };
		var rvrs  = (begin > end) ? true : false;
		var i = 0;
		var timer = setInterval(function() {
			curstep = curstep + ((end - begin) / length);
			elm.style[property] = pre + prop(curstep) + post;
			i++;
			if (i >= length) {
				clearInterval(timer);
				if (callback) {
					callback();
				}
			}
		}, 10);
	};
	
	var showBar = function() {
		// BODY FIX
		//document.getElementsByTagName('body')[0].style.top = '40px';
		animate(
			document.getElementsByTagName('body')[0],
			'top', 0, 40, 25, '', 'px'
		);
		document.getElementsByTagName('body')[0].style.position = 'relative';		
		// BROWSERBAR
		document.getElementById('BrowserBar').style.opacity = '0';
		document.getElementById('BrowserBar').style.filter = 'alpha(opacity=0)';
		document.getElementById('BrowserBar').style.display = 'block';
		
		//document.getElementById('BrowserBar').style.opacity = '1';
		animate(
			document.getElementById('BrowserBar'),
			'opacity', 0, 0.95, 75, '', ''
		);
		//document.getElementById('BrowserBar').style.filter = 'alpha(opacity=100)';
		animate(
			document.getElementById('BrowserBar'),
			'filter', 0, 95, 75, 'alpha(opacity=', ')'
		);

		if (isFixed) {
			document.getElementById('BrowserBar').style.top = '0px';
			document.getElementById('BrowserBar').style.position = 'fixed';			
		} else {
			
		}
	};
	
	var hideBar = function() {
		// RESET BODY
		//document.getElementsByTagName('body')[0].style.top = '0px';
		animate(
			document.getElementsByTagName('body')[0],
			'top', parseInt(document.getElementsByTagName('body')[0].style.top), 0, 25, '', 'px'
		);
		// BROWSERBAR
		//document.getElementById('BrowserBar').style.opacity = '1';
		animate(
			document.getElementById('BrowserBar'),
			'opacity', parseFloat(document.getElementById('BrowserBar').style.opacity), 0, 75, '', ''
		);
		//document.getElementById('BrowserBar').style.filter = 'alpha(opacity=100)';
		animate(
			document.getElementById('BrowserBar'),
			'filter', parseFloat(document.getElementById('BrowserBar').style.opacity)*100, 0, 75, 'alpha(opacity=', ')',
			function() {
				document.getElementById('BrowserBar').style.display = 'none';
			}
		);
	};
	
	return {
		
		init: function() {
			if (hasInit) {return;}
			hasInit = true;
			
			insertHtml();
			prepareHtml();
		},
		
		display: function() {
			BBJS.Widget.init();
			showBar();
		},
		
		hide: function() {
			BBJS.Widget.init();
			hideBar();
		}
		
	};
}();