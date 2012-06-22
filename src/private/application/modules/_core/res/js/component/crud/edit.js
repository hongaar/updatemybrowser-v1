if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Crud ==="undefined") { 	AC.Crud = function() {}; }

AC.Crud.Edit = function() {
	return {
		
		init: function() {
			$('form.ACCrudEdit a.cancel').click(AC.Crud.Edit.cancelHandler);
			$('form.ACCrudEdit button.save').click(AC.Crud.Edit.saveHandler);
			
			AC.Shortcut.add('Ctrl+S', AC.Crud.Edit.saveHandler);
		},
		
		cancelHandler: function() {			
			window.location.href = window.location.pathname;
		},
		
		saveHandler: function() {			
			var form = $(this).parents('form');
			if (!form.length) {
				form = $('form:eq(0)');
			}
			
			if (form[0].checkValidity) {
				if (form[0].checkValidity() === false) {
					alert('Error in form');
					return false;
				};
			}
			
			var options = {
				operation	: 'save',
				crudId		: form.attr('id')					
			};
			
			var url = $(form).attr('action') + "?" + $.param(options);
			var data = $(form).serialize();
			
			$.post(url, data, function(data) {
				if (data.success === false) {
					alert('Something went wrong...');
				} else {
					if (data.operation === 'update') {
						$('dl.crudEdit > *').css({backgroundColor:'orange'}).fadeOut(function() {
							window.location.href = window.location.pathname + '?list';
						});
					} else {
						$('dl.crudEdit > *').css({backgroundColor:'green'}).fadeOut(function() {
							window.location.href = window.location.pathname + '?list';
						});
					}
				}
			}, 'json');
			
			return false;
		}
	};
}();

$(document).ready(function() {
	AC.Crud.Edit.init();
})
