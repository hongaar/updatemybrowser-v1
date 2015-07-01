if (typeof BD ==="undefined") {BD = function() {}};

BD.Widget = function() {
	
	var speed = 0;
	var clip;
	
	var makeCode = function() {
		// The code
		var code = '<script>\n';
		if ($('#custom').is(':checked') || $('#adv_display').is(':not(:checked)') || $('#adv_noncritical').is(':not(:checked)')) {
			code += 'var _umb = {\n';
			if ($('#custom').is(':checked')) {
				code += '\trequire: {\n\t\t';
                var require = [];
                if ($('select[name=chrome]').val() != 'auto') require.push('chrome: ' + $('select[name=chrome]').val());
                if ($('select[name=ie]').val() != 'auto') require.push('ie: ' + $('select[name=ie]').val());
                if ($('select[name=firefox]').val() != 'auto') require.push('firefox: ' + $('select[name=firefox]').val());
                if ($('select[name=safari]').val() != 'auto') require.push('safari: ' + $('select[name=safari]').val());
                if ($('select[name=opera]').val() != 'auto') require.push('opera: ' + $('select[name=opera]').val());
                code += require.join(',\n\t\t');
                code += '\n\t}';
			}
			if ( $('#adv_display').is(':not(:checked)') ) {
				if ( $('#custom').is(':checked') ) {
					code += ',\n';
				}
				code += '\tdisplay: false';
			}
			if ( $('#adv_noncritical').is(':not(:checked)') ) {
				if ( $('#custom').is(':checked') || $('#adv_display').is(':not(:checked)') ) {
					code += ',\n';
				}
				code += '\tnonCritical: false';
			}
			code += '\n};\n';
        }
		code += '(function(u) {\n\tvar s = document.createElement(\'script\'); s.async = true; s.src = u;\n\tvar b = document.getElementsByTagName(\'script\')[0]; b.parentNode.insertBefore(s, b);\n})(\'//updatemybrowser.org/umb.js\');\n<'+'/script>';
		
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