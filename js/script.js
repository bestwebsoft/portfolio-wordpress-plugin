function setMessage( msg ) {
	(function($){		
		$( ".error" ).hide();
		$( ".updated" ).html( msg ).show();
		$( '#bws_save_settings_notice' ).hide();	
	})(jQuery);
}

function setError( msg ) {
	(function($){
		$( ".updated" ).hide();
		$( ".error" ).html( msg );
		$( ".error" ).show();
	})(jQuery);
}

(function($) {
	$(document).ready( function() {	
		$( '#prtfl_ajax_update_images' ).click( function() {
			setMessage( "<p>" + prtfl_var.update_img_message + "</p>" );
			var curr = 0;
			$.ajax({
				url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
				type: "POST",
				data: "action1=get_all_attachment" + '&prtfl_ajax_nonce_field=' + prtfl_var.prtfl_nonce,
				success: function( result ) {
					var list = eval( '(' + result + ')' );				
					if ( ! list ) {
						setError( "<p>" + prtfl_var.not_found_img_info + "</p>" );
						$( "#ajax_update_images" ).removeAttr( "disabled" );
						return;
					}							
					$( '#prtfl_img_loader' ).css( 'display', 'inline-block' );

					function updatenImageItem() {
						if ( curr >= list.length ) {
							$( "#ajax_update_images" ).removeAttr( "disabled" );
							setMessage("<p>" + prtfl_var.img_success + "</p>");
							$( '#prtfl_img_loader' ).hide();
							return;
						}
						$.ajax({
							url: '../wp-admin/admin-ajax.php?action=prtfl_update_image',
							type: "POST",
							data: "action1=update_image&id=" + list[ curr ] + '&prtfl_ajax_nonce_field=' + prtfl_var.prtfl_nonce,
							success: function( result ) {
								curr = curr + 1;
								updatenImageItem();
							}
						});
					}
					updatenImageItem();
				},
				error: function( request, status, error ) {
					setError( "<p>" + prtfl_var.img_error + request.status + "</p>" );
				}
			});
		});
		
		var is_rtl = ( $( 'body' ).hasClass( 'rtl' ) );

		$( '#_prtfl_date_compl' ).datepicker({
			dateFormat : 'dd.mm.yy',
			isRTL : is_rtl
		});

		/* Portfolio images */
		var images_frame;

		$( '.prtfl_add_portfolio_images' ).on( 'click', 'a', function( event ) {
			event.preventDefault();
			var $element = $( this );

			/* If the media frame already exists, reopen it */
			if ( images_frame ) {
				images_frame.open();
				return;
			}

			/* Create the media frame */
			images_frame = wp.media.frames.product_gallery = wp.media({
				title: $element.data( 'choose' ),
				button: {
					text: $element.data( 'update' )
				},
				states: [
					new wp.media.controller.Library({
						title: $element.data( 'choose' ),
						filterable: 'all',
						multiple: true
					})
				]
			});

			/* run a callback when an image is selected */
			images_frame.on( 'select', function() {
				var selection = images_frame.state().get( 'selection' );
				var attachment_ids = $( '#prtfl_images' ).val();

				selection.map( function( attachment ) {
					attachment = attachment.toJSON();

					if ( attachment.id ) {
						attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
						var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

						$( '#prtfl_images_container ul' ).append( '<li class="prtfl_single_image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><span class="prtfl_delete_image"><a href="#" title="' + $element.data( 'delete' ) + '">' + $element.data( 'text' ) + '</a></span></li>' );
					}
				});

				$( '#prtfl_images' ).val( attachment_ids );
			});

			/* Open the modal */
			images_frame.open();
		});

		/* Add image ordering */
		if ( $( '#prtfl_images_container ul' ).length > 0 ) {
			$( '#prtfl_images_container ul' ).sortable({
				items: 'li.prtfl_single_image',
				cursor: 'move',
				scrollSensitivity: 40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'prtfl-sortable-placeholder',
				start: function( event, ui ) {
					ui.item.css( 'background-color', '#f6f6f6' );
				},
				stop: function( event, ui ) {
					ui.item.removeAttr( 'style' );
				},
				update: function() {
					var attachment_ids = '';
					$( '#prtfl_images_container' ).find( 'ul li.prtfl_single_image' ).css( 'cursor', 'default' ).each( function() {
						var attachment_id = $( this ).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});
					$( '#prtfl_images' ).val( attachment_ids );
				}
			});
		}

		/* Remove image */
		$( '#prtfl_images_container' ).on( 'click', '.prtfl_delete_image a', function() {
			$( this ).closest( 'li.prtfl_single_image' ).remove();
			var attachment_ids = '';

			$( '#prtfl_images_container' ).find( 'ul li.prtfl_single_image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = $( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$( '#prtfl_images' ).val( attachment_ids );
			return false;
		});
	});
})(jQuery);