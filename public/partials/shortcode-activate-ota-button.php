<input id="<?php echo $fid . '_message'; ?>" value='<?php echo $message; ?>' type="hidden">
<input id="<?php echo $fid . '_success'; ?>" value="<?php echo $success_msg; ?>" type="hidden">
<input id="<?php echo $fid . '_error'; ?>" value="<?php echo $error_msg; ?>" type="hidden">
<div class="elementor-button-wrapper">
			<a href="#" data-fid="<?php echo $fid; ?>" class="elementor-button-link elementor-button elementor-size-sm activate-ota-btn" role="button">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-text"><?php echo $btn_text; ?></span>
		</span>
					</a>
		</div>