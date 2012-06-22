if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Form ==="undefined") { 	AC.Form = function() {}; }

AC.Form.Ajax = function() {
	return {
		
		init: function() {
			$('form.ACAjaxForm').submit(AC.Form.Ajax.submitHandler);
		},
		
		submitHandler: function() {
			var url = $(this).attr('action');
			var data = $(this).serialize();
			var form = this;
			var success = function(data) {
				$('body').removeClass('loading');
				$(form).trigger('result', [data]);
			};
			var dataType = 'json';
			$('body').addClass('loading');
			$.post(url, data, success, dataType);
			return false;	
		},
		
		linkHandler: function() {
			var href = $(this).attr('href');
			var link = this;
			var success = function(data) {
				$('body').removeClass('loading');
				$(link).trigger('result', [data]);
			};
			var dataType = 'json';
			$('body').addClass('loading');
			$.post(href, {}, success, dataType);
			return false;	
		}
	};
}();

$(document).ready(function() {
	AC.Form.Ajax.init();
})
