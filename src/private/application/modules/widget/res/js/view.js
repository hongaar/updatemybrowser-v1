if (typeof BD ==="undefined") { 		BD = function() {}; }

BD.Widget = function() {
	
	var speed				= 0;
	
	return {
		
		init: function() {
			BD.UI.ContentMenu.setMenu('html5', speed);
			BD.UI.ContentMenu.setContent('html5');
			
			// SyntaxHighlighter init
			SyntaxHighlighter.defaults['toolbar'] = false;
			SyntaxHighlighter.defaults['gutter'] = false;
			SyntaxHighlighter.defaults['class-name'] = 'gray-box';
			SyntaxHighlighter.all();
		}
		
	};
}();

$(document).ready(function() {
	BD.Widget.init();
});