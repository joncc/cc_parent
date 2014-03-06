;(function ( $, window, document, undefined ) {

	
	// $.fn.childrenHeight = function(){
	// 	$parent = $(this)
	// 	var totalHeight = 0;

	// 	$parent.children().each(function( i, e){
	// 		totalHeight += $(e).outerHeight(true)
	// 	})
	// 	return totalHeight
	// }

	$.fn.responsiveNav = function(){
		var $nav = $(this)
		$nav.addClass('responsive')

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