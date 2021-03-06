// @codekit-prepend 'plugins/owl2-smashingboxes/owl.carousel.min.js'
// @codekit-prepend 'plugins/jquery.magnific-popup.min.js'
// @codekit-prepend 'plugins/mediaelement-and-player.min.js'
// @codekit-prepend 'plugins/plugins.js'
// @codekit-prepend 'plugins/prettify.js'
// @codekit-prepend 'plugins/simple-media.js'

(function($) {

	window.shortcodes = {};

	var SC = window.shortcodes;

	var $window = $(window);

	SC.init = function(){
		SC.setElements();
		SC.basics();
		SC.fullWidthSections();
		SC.prettyPrint();
		SC.alerts();
		SC.tooltips();
		SC.toggles();
		SC.accordions();
		SC.tabs();
		SC.modal();
		SC.mediaElements();
		SC.magnificPopup();
		SC.gallery();
		SC.carousel();
		SC.stats();
	};

	SC.setElements = function(){
		SC.elems = {};
		SC.elems.fullWidthSections  =   $('.full-width-section');
		SC.elems.tooltip            =   $('[data-toggle=tooltip]');
		SC.elems.modalLink          =   $('[data-modal]');
		SC.elems.tab                =   $('[data-tab]');
		SC.elems.mediaElements      =   $('[data-media-src]');
		SC.elems.toggles            =   $('.simple-toggle');
		SC.elems.accordions         =   $('.simple-accordion');
		SC.elems.mfpImage           =   $('.mfp-image');
		SC.elems.mfpInline          =   $('.mfp-inline');
		SC.elems.mfpIframe          =   $('.mfp-iframe');
		SC.elems.slider             =   $('[data-slider]');
		SC.elems.carousel           =   $('.post-type-shortcode.owl-carousel');
		SC.elems.stats              =   $('.stats');
	};

	SC.basics = function(){

		// quick fix for icons
		// (needs work - current this finds anything that contains these letters within classes)
		jQuery('body').find('[class*=ion-]').filter(function() {
			return this.className.match(/\bion-/);
		}).addClass('ion');

		jQuery('body').find('[class*=fa-]').filter(function() {
			return this.className.match(/\bfa-/);
		}).addClass('fa');

		jQuery('body').find('[class*=fi-]').filter(function() {
			return this.className.match(/\bfi-/);
		}).addClass('fi');

		SC.elems.mediaElements.simpleMedia();
	};


	SC.fullWidthSections = function(){

		function fullWidthSections(){

			var $justOutOfSight = '';

			if( $('#main.boxed').length ) {
				$justOutOfSight = ( ( ( parseInt($('[data-layout="grid"]').css('max-width') ) + 102 ) - parseInt( $('[data-layout="grid"]').css('max-width') ) ) / 2 );
			} else {
				$justOutOfSight = ( ( $(window).width() - parseInt( $('[data-layout="grid"]').css('max-width') ) ) / 2 );
			}

			SC.elems.fullWidthSections.each(function() {

				if ( !$(this).parents().hasClass('has-sidebar') ) {
					$(this).css({
						'margin-left': ($justOutOfSight < 0) ? $justOutOfSight : -$justOutOfSight,
						'padding-left': ($justOutOfSight < 0) ? -$justOutOfSight : $justOutOfSight,
						'padding-right': ($justOutOfSight < 0) ? -$justOutOfSight : $justOutOfSight,
					});
				} else {
					$(this).css({
						'margin-left': 0,
						'padding-left': 0,
						'padding-right': 0,
					});
				}

			});

		}

		fullWidthSections();
		$(window).resize(fullWidthSections);

	};

	SC.prettyPrint = function(){

		prettyPrint();

	};

	SC.alerts = function(){

		$(".alert .close").click( function() {
			$(this).parent('.alert').fadeTo(300, 0.001).slideUp();
		});

	};

	SC.tooltips = function() {
		SC.elems.tooltip.hover(
			function() {
				var $this = $(this),
					title = $this.attr('title'),
					position = $this.data('placement'),
					tooltip = $( '<div class="tooltip '+position+'"><div class="tooltip-arrow"></div><div class="tooltip-inner">'+title+'</div></div>' );

				$this.append( $( tooltip ) );

				if ( position === 'top' ) {
					tooltip.css({
						'top': - tooltip.height() - 10,
						'margin-left': - tooltip.width()/2
					});
				}

				if ( position === 'bottom' ) {
					tooltip.css({
						'margin-left': - tooltip.width()/2
					});
				}

				if ( position === 'left' ) {
					tooltip.css({
						'top': - tooltip.height()/4,
						'left': - tooltip.width() - 20
					});
				}

				if ( position === 'right' ) {
					tooltip.css({
						'top': - tooltip.height()/4,
						'right': - tooltip.width() - 20
					});
				}

				tooltip.addClass('in');

			}, function() {
				$( this ).find( ".tooltip" ).removeClass('in').remove();
			}
		);
	};

	SC.toggles = function(){

		SC.elems.toggles.each( function () {

			if( $(this).attr('data-id') === 'closed' ) {
				$(this).accordion({
					header: '.simple-toggle-title',
					collapsible: true,
					active: false
				});
			} else {
				$(this).accordion({
					header: '.simple-toggle-title',
					collapsible: true
				});
			}

		});

		var icon = SC.elems.toggles.find('.ui-icon');
		icon.addClass('fa fa-angle-down');

		if ( SC.elems.toggles.find('.simple-toggle-title').is('.ui-state-active') ) {
			$('.ui-state-active').find(icon).addClass('fa-angle-up');
		}

		SC.elems.toggles.on('click',function(){
			if ( $(this).find('.simple-toggle-title').is('.ui-state-active') ) {
				$(this).find(icon).removeClass('fa-angle-down').addClass('fa-angle-up');
			} else {
				$(this).find(icon).removeClass('fa-angle-up').addClass('fa-angle-down');
			}
		});

	};

	SC.accordions = function(){

		// SC.elems.accordions.siblings('.simple-accordion').andSelf().wrapAll('<div class="accordion"></div>');
		// SC.elems.accordions.nextUntil('*:not(.simple-accordion)').addBack().wrapAll( '<div class="accordion"></div>' );

		$('.accordion').accordion({
			header: '.simple-accordion-title',
			// collapsible: true,
			heightStyle: "content"
		});

		var icon = SC.elems.accordions.find('.ui-icon');
		icon.addClass('ion-plus');

		if ( SC.elems.accordions.find('.simple-accordion-title').is('.ui-state-active') ) {
			$('.ui-state-active').find(icon).addClass('ion-minus');
		}

		SC.elems.accordions.on('click', function() {
			icon.removeClass('ion-minus').addClass('ion-plus');
			if ( $(this).find('.simple-accordion-title').is('.ui-state-active') ) {
				$(this).find(icon).removeClass('ion-plus').addClass('ion-minus');
			}
		});

	};

	SC.tabs = function(){

		//  Add active states to first tab and link
		var tabs = $('[data-type="tabs"]');
		tabs.find('[data-tab-content]:first-of-type').addClass('active');
		tabs.find('[data-tab]:first-of-type').addClass('active');

		// var tabHeight = [];

		// $('[data-tab-content]').each(function(){
		//     tabHeight.push($(this).height());
		// });

		SC.elems.tab.on('click', function(e) {

			// console.log('click');

			e.preventDefault();

			if ( $(this).is('.active') ) return;

			$(this)
				.addClass('active')
				.siblings('[data-tab]')
				.removeClass('active')

				// .siblings('[data-tab-content="' + $(this).data('tab') + '"]')

				.parents('[data-type="tabs"]').find('[data-tab-content="' + $(this).data('tab') + '"]')

				.addClass('active')
				.siblings('[data-tab-content]')
				.removeClass('active');

		});
	};

	SC.modal = function(){

		if ( !SC.elems.modalLink.length ) return;

		SC.elems.modalLink.on('click', function(){

			var $this           = $(this),
				modalOpen       = $this.data('modal'),
				modalTarget     = $('[data-modal-id=' + modalOpen + ']'),
				modalClose      = modalTarget.find('[data-modal-trigger="close"]');

			// Show Modal
			modalTarget.addClass('visible');
			modalTarget.attr('data-modal-status','visible');

			$(modalTarget).on('click', function(e){
				// Check if whats being clicked on is the overlay, not the modal itself
				if ( e.target === modalTarget.get(0) ) {
					// Hide Modal
					$(this).removeClass('visible');
					$(this).attr('data-modal-status','');
				}
			});

			modalClose.on('click',function(){
				// Hide Modal
				modalTarget.removeClass('visible');
				modalTarget.attr('data-modal-status','');
			});

		});
	};

	SC.mediaElements = function(){

		if( !SC.elems.mediaElements.length ) return;

		SC.elems.mediaElements.each(function(){
			$(this).mediaelementplayer({
				// if the <video width> is not specified, this is the default
				defaultVideoWidth: 480,
				// if the <video height> is not specified, this is the default
				defaultVideoHeight: 270,
				// if set, overrides <video width>
				videoWidth: -1,
				// if set, overrides <video height>
				videoHeight: -1,
				// width of audio player
				audioWidth: 400,
				// height of audio player
				audioHeight: 50,
				// initial volume when the player starts
				startVolume: 0.8,
				// // path to Flash and Silverlight plugins
				// pluginPath: theme_objects.base + '/_include/js/mediaelement/',
				// // name of flash file
				// flashName: 'flashmediaelement.swf',
				// // name of silverlight file
				// silverlightName: 'silverlightmediaelement.xap',
				// useful for <audio> player loops
				loop: false,
				// enables Flash and Silverlight to resize to content size
				enableAutosize: true,
				// the order of controls you want on the control bar (and other plugins below)
				// Hide controls when playing and mouse is not over the video
				alwaysShowControls: false,
				// force iPad's native controls
				iPadUseNativeControls: false,
				// force iPhone's native controls
				iPhoneUseNativeControls: false,
				// force Android's native controls
				AndroidUseNativeControls: false,
				// forces the hour marker (##:00:00)
				alwaysShowHours: false,
				// show framecount in timecode (##:00:00:00)
				showTimecodeFrameCount: false,
				// used when showTimecodeFrameCount is set to true
				framesPerSecond: 25,
				// turns keyboard support on and off for this instance
				enableKeyboard: true,
				// when this player starts, it will pause other players
				pauseOtherPlayers: true,
				// array of keyboard commands
				keyActions: [],
				// autosizeProgress: false
			});
		});
	};

	SC.magnificPopup = function(){

		if( !SC.elems.mfpInline.length && !SC.elems.mfpImage.length && !SC.elems.mfpIframe.length ) return;

		// Inline popups
		SC.elems.mfpInline.magnificPopup({
			delegate: 'a', //if elem is nested
			removalDelay: 500, //delay removal by X to allow out-animation
			callbacks: {
				beforeOpen: function() {
					this.st.mainClass = this.st.el.attr('data-effect');
				}
			},
			midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
		});

		// Image popups
		SC.elems.mfpImage.magnificPopup({
			delegate: 'a', //if elem is nested
			type: 'image',
			removalDelay: 500, //delay removal by X to allow out-animation
			preloader: true,
			callbacks: {
				beforeOpen: function() {
					// just a hack that adds mfp-anim class to markup
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					// this.st.mainClass = this.st.el.attr('data-effect');
					this.st.mainClass = this.st.el.attr('data-effect').length ? this.st.el.attr('data-effect') : 'mfp-fade-in-up';
				}
			},
			// closeOnContentClick: true,
			midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
		});

		// Image popups
		SC.elems.mfpIframe.magnificPopup({
			delegate: 'a', //if elem is nested
			type: 'iframe',
			removalDelay: 500,
			preloader: true,
			callbacks: {
				beforeOpen: function() {
					// just a hack that adds mfp-anim class to markup
					this.st.iframe.markup = this.st.iframe.markup.replace('mfp-iframe-scaler', 'mfp-iframe-scaler mfp-with-anim');
					this.st.mainClass = this.st.el.attr('data-effect').length ? this.st.el.attr('data-effect') : 'mfp-fade-in-up';
				}
			},
			midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
		});


	};

	SC.gallery = function(){

		if ( !SC.elems.slider.length ) return;

		$.each(SC.elems.slider, function(index, item) {

			var data = $(item).data('slider');

			var effect = data && (data !== 'slide') ? data : false;

			$(item).owlCarousel({
				items: 1,
				slideBy: 1,
				loop: true,
				navText: ['<i class="ion ion-ios-arrow-left"></i>','<i class="ion ion-ios-arrow-right"></i>'],
				// margin: '',
				// center: '',
				// mouseDrag: '',
				// touchDrag: '',
				// pullDrag: '',
				// freeDrag: '',
				// stagePadding: '',
				// merge: '',
				// mergeFit: '',
				// autoWidth: '',
				// startPosition: '',
				// URLhashListener: '',
				// nav: '',
				// rewind: '',
				// dots: '',
				// dotsEach: '',
				// dotData: '',
				// lazyLoad: '',
				// lazyContent: '',
				// autoplay: '',
				// autoplayTimeout: '',
				// autoplayHoverPause: '',
				// smartSpeed: '',
				// fluidSpeed: '',
				// autoplaySpeed: '',
				// navSpeed: '',
				// dotsSpeed: '',
				// dragEndSpeed: '',
				// callbacks: '',
				// responsive: '',
				// responsiveRefreshRate: '',
				// responsiveBaseElement: '',
				// video: '',
				// videoHeight: '',
				// videoWidth: '',
				// animateOut: '',
				// animateInClass: '',
				// fallbackEasing: '',
				// info: '',
				// nestedItemSelector: '',
				// itemElement: '',
				// stageElement: '',
				// navContainer: '',
				// dotsContainer: ''
			});

		});

	};

	SC.carousel = function() {

		if ( !SC.elems.carousel.length ) return;

		var numberOfItems = SC.elems.carousel.data('items') ? SC.elems.carousel.data('items') : '4';

		SC.elems.carousel.owlCarousel({
			loop: true,
			items: 1,
			slideBy: 1,
			lazyLoad: true,
			autoplay: true,
			autoplayHoverPause: true,
			stagePadding: 10,
			margin: 20,
			dots: true,
			navText: ['<i class="ion ion-ios-arrow-left"></i>','<i class="ion ion-ios-arrow-right"></i>'],
			responsive:{
				480:{
					items: numberOfItems,
					slideBy: numberOfItems,
				},
				920:{
					items: numberOfItems,
					slideBy: numberOfItems,
				},
				1140:{
					items: numberOfItems,
					slideBy: numberOfItems,
				}
			}
		});

	};

	SC.stats = function(){

		if( !SC.elems.stats.length ) return;

		var fired = 0;

		$(window).scroll(function() {
			if ( SC.elems.stats.isOnScreen() ) {
				if ( fired === 0 ) {
					scrollStats();
					fired = 1;
				}
			}
		});

		var scrollStats = function() {

			// cycle through every scrollable stat and set the scrollCounter
			SC.elems.stats.find('.scrollstat').each( function() {

				scrollCounter($(this), 0);

			});

		};

		var scrollCounter = function($stat, current) {

			// return if total reached
			if ( current > $stat.data('total') ) return;

			// set element html to new value
			$stat.html(current);

			// set timeout to increment value
			setTimeout(function(){ scrollCounter($stat, current + 1); }, 40);

		};

	};

	$window.load(function(){

	});

	$(document).ready(function(){

		SC.init();

	});

})(jQuery);