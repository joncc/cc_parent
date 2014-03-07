;(function ( $, window, document, undefined ) {

	
	// $.fn.childrenHeight = function(){
	// 	$parent = $(this)
	// 	var totalHeight = 0;

	// 	$parent.children().each(function( i, e){
	// 		totalHeight += $(e).outerHeight(true)
	// 	})
	// 	return totalHeight
	// }


	$.fn.responsiveNav = function( breakpoint ){
		if( typeof breakpoint == 'undefined' ){
			breakpoint = 650;
		}
		var $nav = $(this)
		$nav.addClass('responsive')

		$(window).resize(function(){
			if( $(window).width() < breakpoint ){
				$nav.addClass('mobile')
			}else{
				$nav.removeClass('mobile')
			}
		})
		$(window).resize()

		// create main menu toggle elements
		var $main_toggle = {
			label :
				$('<label for="toggle-main-nav">')
				.addClass('navtoggle'),
			checkbox : 
				$('<input type="checkbox" id="toggle-main-nav"">')
				.addClass('navtoggle')
		}
		// add main menu toggle to main nav
		$nav.prepend(
			$main_toggle.checkbox,
			$main_toggle.label
		)


		$placeholder = $('<div>').css('display', 'none')
		$placeholder.insertAfter( $nav )

		$main_toggle.label.click(
			function(){
				if( $main_toggle.checkbox.is(':checked')){
					$('body').css('overflow', '')
					$nav.insertAfter( $placeholder )
				}else{
					// prevent body scrolling when nav is open
					$('body').css('overflow', 'hidden')
					// make nav the last element in DOM
					// so stacking context is on top of everything
					$nav.appendTo('body')
				}
			}
		)

		/* =Submenu items
		--------------------------------------------------------------- */
		$nav.find('.menu-item-has-children').each(
			function( i, e){
				var $li = $(e)
				var id = $li.attr('id')
				var $a = $li.children('a')
				var $ul = $li.children('ul')

				// move parent nav link to subnav
				if( $a.attr('href') == '#' ){
					$a.remove()
				}else{
					$('<li>').prepend( $a ).prependTo($ul)
				}

				// add submenu toggle checkboxes
				$li.prepend(
					$('<label>')
						.attr('for', id + '-subtoggle')
						.addClass('navtoggle')
						.html( $a.html() )
					,
					$('<input type="checkbox">')
						.attr(
							'id', id + '-subtoggle'
						)
						.addClass('navtoggle')
				)
			}
		)
	}

})( jQuery, window, document );