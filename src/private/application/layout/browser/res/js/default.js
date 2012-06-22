if (typeof BD ==="undefined") { 		BD = function() {}; }

BD.UI = function() {
	return {
		
		init: function() {
			BD.UI.ContentMenu.init();
			this.fancybox();
			this.tip();
		},
		
		fancybox: function() {
			$('.fancybox').fancybox({
				'width'				: '90%',
				'height'			: '100%',
				'autoScale'			: false,
				'transitionIn'		: 'elastic',
				'transitionOut'		: 'elastic',
				'type'				: 'iframe',
				'titlePosition'		: 'over'
			});
		},
		
		tip: function() {
			$('strong[title]').tipsy({
				delayIn: 0,
				delayOut: 0,
				fade: true,
				gravity: 'n',
				html: true,
				offset: 0,
				opacity: 1,
				title: function() {
					return $(this).attr('original-title').replace(/\*\*/gi, '</strong>').replace(/\*/gi, '<strong>');
				},
				trigger: 'hover'
			}).each(function() {
				$(this).css({cursor: 'pointer'});
			});
		}
		
	};
}();

BD.UI.ContentMenu = function() {
	
	var menu			= $('div.options');
	var content			= $('div.right-column');
	var arrow 			= $('div.divider-arrow');
	var rightColumn 	= $('.right-column');
	
	var current;
	var grayed_out 		= 0.4;
	
	var clickHandler;
	
	return {
		
		init: function() {			
			var self = this;
						
			// Event handlers
			menu.find('.item').mouseover(function() {
				var key = $(this).attr('data-key');
				BD.UI.ContentMenu.slideTo(key);
			});
			menu.find('.item').mouseout(function() {
				BD.UI.ContentMenu.slideTo(current);
			});
			menu.find('.item').click(function() {
				var key = $(this).attr('data-key');
				BD.UI.ContentMenu.setMenu(key);
				var triggerDefault = true; 
				if (clickHandler) {
					triggerDefault = clickHandler(this);
				}
				if (triggerDefault) {
					BD.UI.ContentMenu.setContent(key);
				}
			});
		},
		
		click: function(callback) {
			clickHandler = callback;
		},
		
		setMenu: function(className, speed) {
			current = className;
			this.slideTo(className, speed);
		},
		
		slideTo: function(className, speed) {
			var speed = speed || 300;
			
			// Image opacity
			menu.find('.item:not(.' + className + ') img')
				.stop().animate({opacity: grayed_out}, speed);
			menu.find('.item.' + className + ' img')
				.stop().animate({opacity: 1}, speed);
				
			// Arrow positioning
			var pos = menu.find('.item.' + className).position();
			var newArrowTop = pos.top + 20;
			arrow.stop().animate({ top: newArrowTop + 'px' }, speed);
		},
		
		setContent: function(className, speed) {
			var speed = speed || 300;
			
			// Show content
			content
				.find('> div:not(.' + className + ')')
				.stop()
				.animate({opacity: 0}, speed, function() {
					$(this).removeAttr('filter').hide();
					content
						.find('> div.' + className)
						.stop()
						.animate({opacity: 1}, speed, function() {
							$(this).removeAttr('style').show();
						})
						.show();
				});	
		}
		
	};
}();


$(document).ready(function() {
	BD.UI.init();
});