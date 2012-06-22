if (typeof AC ==="undefined") { 		AC = function() {}; }
if (typeof AC.Crud ==="undefined") { 	AC.Crud = function() {}; }

AC.Crud.List = function() {
	return {
		
		init: function() {
			$('form.ACCrudList a.new').click(AC.Crud.List.newHandler);
			$('form.ACCrudList a.edit').click(AC.Crud.List.editHandler);
			$('form.ACCrudList a.delete').click(AC.Crud.List.deleteHandler);
			
			$('form.ACCrudList').bind('result', function(events, data) {
				console.log(data);
			});			
		},
		
		newHandler: function() {		
			window.location.href = window.location.pathname + '?new';
		},
		
		editHandler: function() {
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			window.location.href = window.location.pathname + '?edit=' + id;
		},
		
		deleteHandler: function() {
			var row = $(this).parents('tr');
			var id = row.find('input[type=checkbox]').attr('value');			
			var form = $(this).parents('form');
			
			if (confirm('Are you sure you want to delete the record with ID = ' + id + '?')) {
				var options = {
					operation	: 'delete',
					crudId		: form.attr('id'), 
					id			: id					
				};
				$.getJSON(form.attr('action'), options, function(data) {
					if (data.operation === 'delete' && data.success === true) {
						row.css({backgroundColor:'red'}).fadeOut();
					}
				});
			}
		}
	};
}();

$(document).ready(function() {
	AC.Crud.List.init();
})
