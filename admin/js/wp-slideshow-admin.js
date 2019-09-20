(function( $ ) {
	$( document ).ready(function() {

		$( ".sortable" ).sortable();

		var mediaUploader;
		$(document).on('click','.wp-slider-add',function(e) {
			e.preventDefault();
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				}, multiple: false }
			);

			mediaUploader.on('select', function() {

				var attachment = mediaUploader.state().get('selection').first().toJSON();

				var data = {
					'action': 'wp_add_new_slide',
					'attachment_url': attachment.url,
				};

				$.post(ajaxurl, data, function(response) {
					response = $.parseJSON(response);
					$('.wp-slider-lists').html('').html(response.html);
					$('.slide-counts p.slide-count span').html('').html(response.count);
					$('.notification-wrap p.notification').html('').html(response.message).show();
					$( ".sortable" ).sortable();
				});
			});

			mediaUploader.open();
		});

		$(document).on('click','.wp-slider-order-save',function () {

			var slides = new Array();

			$( "#wp_slider li" ).each(function( index ,html) {
				var get_slide_ul = $(this).find('img').attr("src");
				slides.push(get_slide_ul);
			});

			var data = {
				'action': 'wp_change_slide_order',
				'slides': slides,
			};

			$.post(ajaxurl, data, function(response) {
				response = $.parseJSON(response);
				$('.wp-slider-lists').html('').html(response.html);
				$('.slide-counts p.slide-count span').html('').html(response.count);
				$('.notification-wrap p.notification').html('').html(response.message).show();
				$( ".sortable" ).sortable();
			});
		});

		$(document).on('click','a.remove-slide',function () {
			var slide_id = $(this).attr('data-id');

			var data = {
				'action': 'wp_remove_slide',
				'slide_id': slide_id,
			};

			$.post(ajaxurl, data, function(response) {
				response = $.parseJSON(response);
				$('.wp-slider-lists').html('').html(response.html);
				$('.slide-counts p.slide-count span').html('').html(response.count);
				$('.notification-wrap p.notification').html('').html(response.message).show();
				$( ".sortable" ).sortable();
			});

		});
	});
})( jQuery );
