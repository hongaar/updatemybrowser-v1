;

var _umb = {
	display: false
};

if (typeof BD ==="undefined") {BD = function() {}};

BD.UI = function() {
	
	// http://www.abeautifulsite.net/blog/2011/11/detecting-mobile-devices-with-javascript/
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i) ? true : false;
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i) ? true : false;
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i) ? true : false;
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
		}
	};
	
	var addMobileClass = function() {
		if (isMobile.any()) {
			$('body').addClass('mobile');
		}
	};
	
	return {
		
		init: function() {
			BD.UI.ContentMenu.init();
			addMobileClass();
			this.fancybox();
			this.blank();
			this.tip();
			
			// Last tweet
			(function() {
				var ga = document.createElement('script');ga.type = 'text/javascript';ga.async = true;
				ga.src = 'https://twitter.com/statuses/user_timeline/updatemybrowser.json?include_rts=1&callback=twitterCallback2&count=1';
				var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ga, s);
			})();
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
		
		blank: function() {
			$('a.blank').live('click', function(e) {
				e.preventDefault();
				window.open($(this).attr('href'));
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
	var grayed_item		= 0.5;
	
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
				// Item opacity
				menu.find('.item div.box')
					.stop().animate({opacity: 1}, 300);
			});
			menu.find('.item').click(function() {
				var key = $(this).attr('data-key');
				BD.UI.ContentMenu.setMenu(key);
				var triggerDefault = true; 
				if (clickHandler) {
					triggerDefault = clickHandler(this, key);
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
				
			// Item opacity
			menu.find('.item:not(.' + className + ') div.box')
				.stop().animate({opacity: grayed_item}, speed);
			menu.find('.item.' + className + ' div.box')
				.stop().animate({opacity: 1}, speed);
				
			// Arrow positioning
			var pos = menu.find('.item.' + className).position();
			var newArrowTop = pos.top + 20;
			arrow.stop().animate({top: newArrowTop + 'px'}, speed);
		},
		
		setContent: function(className, speed) {
			var speed = speed || 300;
			
			// Item opacity
			menu.find('.item div.box')
				.stop().animate({opacity: 1}, speed);
			
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

// http://twitter.com/javascripts/blogger.js
function twitterCallback2(twitters) {
  var statusHTML = [];
  for (var i=0; i<twitters.length; i++){
    var username = twitters[i].user.screen_name;
    var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, function(url) {
      return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });
    statusHTML.push('<li><span>'+status+'</span> <a style="font-size:85%" href="http://twitter.com/'+username+'/statuses/'+twitters[i].id_str+'">'+relative_time(twitters[i].created_at)+'</a></li>');
  }
  document.getElementById('twitter_update_list').innerHTML = statusHTML.join('');
}

function relative_time(time_value) {
  var values = time_value.split(" ");
  time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
  var parsed_date = Date.parse(time_value);
  var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
  var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
  delta = delta + (relative_to.getTimezoneOffset() * 60);

  if (delta < 60) {
    return 'less than a minute ago';
  } else if(delta < 120) {
    return 'about a minute ago';
  } else if(delta < (60*60)) {
    return (parseInt(delta / 60)).toString() + ' minutes ago';
  } else if(delta < (120*60)) {
    return 'about an hour ago';
  } else if(delta < (24*60*60)) {
    return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
  } else if(delta < (48*60*60)) {
    return '1 day ago';
  } else {
    return (parseInt(delta / 86400)).toString() + ' days ago';
  }
};