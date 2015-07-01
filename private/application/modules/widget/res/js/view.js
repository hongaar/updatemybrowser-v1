if (typeof BD ==="undefined") {BD = function() {}};

BD.Widget = function() {
	
	var speed = 0;
	var clip;
	
	var makeCode = function() {
		// The code
		var code = '<script type="text/javascript">\n';
		if ($('#custom').is(':checked') || $('#adv_display').is(':not(:checked)') || $('#adv_noncritical').is(':not(:checked)')) {
			code = code + 'var _umb = {\n';
			if ($('#custom').is(':checked')) {
				code = code + '	require: {\n\		chrome: '+$('select[name=chrome]').val()+',\n\		firefox: '+$('select[name=firefox]').val()+',\n\		ie: '+$('select[name=ie]').val()+',\n\		opera: '+$('select[name=opera]').val()+',\n\		safari: '+$('select[name=safari]').val()+'\n\	}';
			}
			if ( $('#adv_display').is(':not(:checked)') ) {
				if ( $('#custom').is(':checked') ) {
					code = code + ',\n';
				}
				code = code + '	display: false';
			}
			if ( $('#adv_noncritical').is(':not(:checked)') ) {
				if ( $('#custom').is(':checked') || $('#adv_display').is(':not(:checked)') ) {
					code = code + ',\n';
				}
				code = code + '	nonCritical: false';
			}
			var code = code + '\n};\n';			
		}
		code = code + '(function() {\n\	var s = document.createElement(\'script\'); s.type = \'text/javascript\';\n\	s.async = true; s.src = \'//updatemybrowser.org/umb.js\';\n\	var b = document.getElementsByTagName(\'script\')[0]; b.parentNode.insertBefore(s, b);\n\})();\n\</script>';
		
		// Clean up old code
		$('div.syntaxhighlighter').parent().remove();
		
		// Insert new
		var $before = $('div.codewrapper h3');		
		var $pre = $('<pre></pre>');
		$pre.addClass('brush: js');
		$pre.text(code);
		$pre.insertAfter($before);
		
		// Highlight
		SyntaxHighlighter.highlight();
		
		// Set code to ZeroClipboard
		clip.setText(code);
	};
	
	return {
		
		init: function() {
			BD.UI.ContentMenu.setMenu('html', speed);
			BD.UI.ContentMenu.click(function(elm, key) {
				if (key == 'html') {
					$('.codewrapper').slideDown();
				} else {
					$('.codewrapper').slideUp();
				}
				BD.UI.ContentMenu.setContent(key);
			});
			BD.UI.ContentMenu.setContent('html');
			
			// SyntaxHighlighter init
			SyntaxHighlighter.defaults['toolbar'] = false;
			SyntaxHighlighter.defaults['gutter'] = false;
			SyntaxHighlighter.defaults['class-name'] = 'gray-box';
			SyntaxHighlighter.all();
			
			// ZeroClipboard init
			ZeroClipboard.setMoviePath('public/js/lib/ZeroClipboard/ZeroClipboard.swf' );
			clip = new ZeroClipboard.Client();
			clip.glue( 'clipButton', 'clipContainer' );
			clip.addEventListener( 'onComplete', function(client, text) {
				alert("Code is copied to the clipboard");
			});
			
			// Init code 
			makeCode();
			
			// Advanced
			$('#advanced').change(function(e) {
				$('.advancedWrapper').slideToggle();
			});
			
			// Demo
			$('a.demo').click(function(e) {
				$('#BrowserBar').hide();
				$('body').css({top: 0});
				UMB.scrollToTop();
				setTimeout(function() {
					UMB.displayWidget();
				}, 500);
			});
			
			// Options for code
			$(':input').bind('click, change', makeCode);	
		}
		
	};
}();

$(document).ready(function() {
	BD.Widget.init();
});