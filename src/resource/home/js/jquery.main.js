$(window).scroll(function(){
	var t_ = $(this).scrollTop();
	if(t_ > 0){
		$('#nav li.hidden-btn').addClass('animate');
		$('.h-area').addClass('fixed');
	}
	else{
		$('#nav li.hidden-btn').removeClass('animate');
		$('.h-area').addClass('fixed');
	}
});

$(function(){
	$('.m-nav-link').click(function(){
		$(this).toggleClass('active');
		$('#nav').stop().slideToggle(400);
		return false;
	});

	var isMobile = {
	    hasTouch: function() {
	        return 'ontouchstart' in document.documentElement;
	    },
	    Android: function() {
	        return navigator.userAgent.match(/Android/i);
	    },
	    BlackBerry: function() {
	        return navigator.userAgent.match(/BlackBerry/i);
	    },
	    iOS: function() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	    },
	    Opera: function() {
	        return navigator.userAgent.match(/Opera Mini/i);
	    },
	    Windows: function() {
	        return navigator.userAgent.match(/IEMobile/i);
	    },
	    any: function() {
	        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	    },
	    ismobi: function() {
	        return navigator.userAgent.match(/Mobi/i);
	    }
	};
	
	if(!isMobile){
		var s = skrollr.init({
			render: function(data) {}
		});
	}
});

$(window).load(function(){
	if($('.q-slides .slides').length){
		$('.q-slides .slides').bxSlider({
			mode: 'fade',
			controls: false
		});
	}
});