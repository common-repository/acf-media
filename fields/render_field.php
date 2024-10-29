<?php
	// TODO Support for mime types.

	if ($field['value']) {
		if (is_int($field['value'])) {
			$field['value'] = array(
				'id' => $field['value']
			);
		}

		if (is_string($field['value'])) {
			$field['value'] = @json_decode($field['value'], TRUE);
		}

		$value = $field['value'];
		$o = array();
		$attachment = acf_get_attachment($value['id']);
		$fallback_attachment = acf_get_attachment(@$value['fallback_id']);

		if ($attachment) {
			// has value
			$div['class'] .= ' has-value';
			$div['class'] .= ' is-' . $attachment['type'];

			// update
			$o['title']	= $attachment['title'];
			$o['title_text'] = @$value['title'];
			$o['title_alt']	= @$value['alt'];
			$o['url'] = $attachment['url'];
			$o['filename'] = $attachment['filename'];
			$o['fallback_url'] = $fallback_attachment['url'];
			$o['fallback_filename'] = $fallback_attachment['filename'];

			if ($attachment['filesize']) {
				$o['filesize'] = size_format($attachment['filesize']);
			}
		}
	}

	if (!function_exists('check_active')) {
		function check_active($current_alignment, $v_val, $h_val) {
			if ($v_val && $v_val == $current_alignment) {
				echo 'active';
			} else if ($h_val && $h_val == $current_alignment) {
				echo 'active';
			}
		}
	}

?>

<div <?php acf_esc_attr_e($div); ?>>

	<?php acf_hidden_input(array('name' => $field['name'], 'value' => $field['value'], 'data-name' => 'media-data')); ?>

	<div class="show-if-value file-wrap">
		<div class="file-preview">
			<div class="acf-actions -hover">
				<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a><a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
			</div>
			<div class="fallback_image show-if-video <?php if (@$o['fallback_url']): ?>has_fallback_image<?php endif; ?>">
				<div class="fallback_actions">
					<a class="acf-icon -plus dark" data-name="add_fallback" href="#" title="Add fallback image"></a>
					<a class="acf-icon -pencil dark" data-name="edit_fallback" href="#" title="Edit fallback image"></a>
					<a class="acf-icon -cancel dark" data-name="remove_fallback" href="#" title="Remove fallback image"></a>
				</div>
				<img data-name="fallback_image" src="<?php echo esc_url($o['fallback_url']); ?>" title="Fallback image" />
			</div>
			<img class="show-if-image" data-name="image" src="<?php echo esc_url($o['url']); ?>" alt=""/>
			<video class="show-if-video" data-name="video" controls src="<?php echo esc_url($o['url']); ?>"></video>
		</div>
		<div class="file-info">
			<p>
				<strong data-name="title"><?php echo esc_html($o['title']); ?></strong>
			</p>
			<p>
				<strong><?php _e('File name', 'acf'); ?>:</strong>
				<a data-name="filename" href="<?php echo esc_url($o['url']); ?>" target="_blank"><?php echo esc_html($o['filename']); ?></a>
			</p>
			<p>
				<strong><?php _e('File size', 'acf'); ?>:</strong>
				<span data-name="filesize"><?php echo esc_html(@$o['filesize']); ?></span>
			</p>
		</div>
		<div class="alignment">
			<p><strong>Horizontal Alignment:</strong></p>
			<button data-alignment="left" class="alignment_icon horizontal left <?php check_active(@$value['alignment_h'], null, 'left'); ?>"></button>
			<button data-alignment="center" class="alignment_icon horizontal hcenter <?php check_active(@$value['alignment_h'], null, 'center'); ?>"></button>
			<button data-alignment="right" class="alignment_icon horizontal right <?php check_active(@$value['alignment_h'], null, 'right'); ?>"></button>
		</div>
		<div class="alignment">
			<p><strong>Vertical Alignment:</strong></p>
			<button data-alignment="top" class="alignment_icon vertical top <?php check_active(@$value['alignment_v'], 'top', null); ?>"></button>
			<button data-alignment="center" class="alignment_icon vertical vcenter <?php check_active(@$value['alignment_v'], 'center', null); ?>"></button>
			<button data-alignment="bottom" class="alignment_icon vertical bottom <?php check_active(@$value['alignment_v'], 'bottom', null); ?>"></button>
		</div>
		<div class="alt_and_title">
			<p><strong>Alt:</strong> <input type="text" data-name="alt" value="<?php echo @$o['title_alt']; ?>"></p>
			<p><strong>Title:</strong> <input type="text" data-name="title" value="<?php echo @$o['title_text']; ?>"></p>
		</div>
	</div>
	<div class="hide-if-value">
		<?php if ($uploader == 'basic'): ?>

			<?php
			if ($field['value'] && !is_numeric($field['value']) ): ?>
				<div class="acf-error-message"><p><?php echo acf_esc_html($field['value']); ?></p></div>
			<?php
			endif; ?>

			<label class="acf-basic-uploader">
				<?php acf_file_input(array('name' => $field['name'], 'id' => $field['id'])); ?>
			</label>

		<?php else: ?>

			<p><?php _e('No file selected','acf'); ?> <a data-name="add" class="acf-button button" href="#"><?php _e('Add File','acf'); ?></a></p>

		<?php endif; ?>

	</div>

</div>