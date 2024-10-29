(function($) {
	var MediaField = function($initial_field) {
		var $field = $initial_field;
		var valueField = $field.find('[data-name="media-data"]');
		var fieldWrapper = $field.find('.acf-media-uploader');
		var currentValue = {};
		var alignmentButtons = null;
		var altField = null;
		var titleField = null;

		if (valueField.val())
			currentValue = JSON.parse(valueField.val());


		initialize_responsiveness();
		initialize_alignment();
		initialize_alt_and_title();
		initialize_hover_buttons();

		$field.find('[data-name="add"]').on('click', function(event) {
			pick_file(false);
			event.preventDefault();
		});

		$field.find('[data-name="add_fallback"]').on('click', function(event) {
			pick_file(true);
			event.preventDefault();
		});

		function update_hidden_field(key, val) {
			if (valueField.val())
				currentValue = JSON.parse(valueField.val());

			currentValue[key] = val;
			valueField.val(JSON.stringify(currentValue));
			valueField.trigger('change');
		}

		function update_display_fields() {
			altField.val(currentValue.alt);
			titleField.val(currentValue.title);

			alignmentButtons.removeClass('active');
			alignmentButtons.filter('.horizontal[data-alignment="' + currentValue.alignment_h + '"]').addClass('active');
			alignmentButtons.filter('.vertical[data-alignment="' + currentValue.alignment_v + '"]').addClass('active');
		}

		function initialize_alignment() {
			alignmentButtons = $field.find('.alignment_icon');

			alignmentButtons.click(function() {
				var alignment = $(this).attr('data-alignment');
				if ($(this).hasClass('horizontal')) {
					update_hidden_field('alignment_h', alignment);
				} else {
					update_hidden_field('alignment_v', alignment);
				}

				update_display_fields();
				return false;
			});

		}

		function initialize_alt_and_title() {
			altField = $field.find('[data-name="alt"]');
			titleField = $field.find('[data-name="title"]');

			altField.keyup(function() {
				update_hidden_field('alt', $(this).val());
			});

			titleField.keyup(function() {
				update_hidden_field('title', $(this).val());
			});
		}

		function initialize_hover_buttons() {
			$field.find('[data-name="edit"]').click(function() {
				pick_file(false);
				return false;
			});

			$field.find('[data-name="remove"]').click(function() {
				currentValue = {};
				valueField.val('');
				fieldWrapper.removeClass('has-value');
				update_display_fields();
				return false;
			});

			$field.find('[data-name="edit_fallback"]').click(function() {
				pick_file(true);
				return false;
			});

			$field.find('[data-name="remove_fallback"]').click(function() {
				update_hidden_field('fallback_id', '');
				fieldWrapper.find('.fallback_image').removeClass('has_fallback_image');
				update_display_fields();
				return false;
			});
		}

		function pick_file(fallback) {
			var file_frame = null;
			var wp_media_post_id = wp.media.model.settings.post.id;
			var set_to_post_id = null;

			// If the media frame already exists, reopen it.
			if (file_frame) {
				// Set the post ID to what we want
				file_frame.uploader.uploader.param('post_id', set_to_post_id);
				// Open frame
				file_frame.open();
				return;
			} else {
				// Set the wp.media post id so the uploader grabs the ID we want when initialised
				wp.media.model.settings.post.id = set_to_post_id;
			}

			var fileFrameConfig = {
				title: 'Select a media file to upload',
				button: {
					text: 'Use this image',
				},
				multiple: false	// Set to true to allow multiple files to be selected
			}

			if (fallback)
				fileFrameConfig.library = {type: 'image/*'}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media(fileFrameConfig);

			// When an image is selected, run a callback.
			file_frame.on('select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();

				fieldWrapper.addClass('has-value');

				var titleDisplay = $field.find('[data-name="title"]');
				var fileNameDisplay = $field.find('[data-name="filename"]');
				var fileSizeDisplay = $field.find('[data-name="filesize"]');
				var fallbackWrapper = $field.find('.fallback_image');
				var fallbackDisplay = $field.find('[data-name="fallback_image"]');

				if (fallback) {
					fallbackWrapper.addClass('has_fallback_image');
					fallbackDisplay.attr('src', attachment.url);
					update_hidden_field('fallback_id', attachment.id);
				} else {
					if (attachment.type == 'video') {
						var fileDisplay = $field.find('[data-name="video"]');
						fieldWrapper.removeClass('is-image').addClass('is-video');
					} else if (attachment.type == 'image') {
						var fileDisplay = $field.find('[data-name="image"]');
						fieldWrapper.removeClass('is-video').addClass('is-image');
					}
					titleDisplay.text(attachment.title);
					fileNameDisplay.text(attachment.filename);
					fileSizeDisplay.text(attachment.filesizeHumanReadable);
					fileDisplay.attr('src', attachment.url);
					update_hidden_field('id', attachment.id);
				}

				wp.media.model.settings.post.id = wp_media_post_id;
			});

			// Finally, open the modal
			file_frame.open();

		}

		function initialize_responsiveness() {
			$(window).on('resize', function() {
				$field.removeClass('large').removeClass('small');
				if ($field.width() > 500) {
					$field.addClass('large');
				} else {
					$field.addClass('small');
				}
			}).trigger('resize');
		}
	}

	function initialize_field($field) {
		new MediaField($field);
	}

	if (typeof acf.add_action !== 'undefined') {

		/*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*/

		acf.add_action('ready_field/type=media', initialize_field);
		acf.add_action('append_field/type=media', initialize_field);

	}

})(jQuery);