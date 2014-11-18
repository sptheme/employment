<?php

add_action('wp_ajax_sp_branch_shortcode', 'sp_branch_shortcode_ajax' );

function sp_branch_shortcode_ajax(){
	$defaults = array(
		'branch' => null
	);
	$args = array_merge( $defaults, $_GET );
	?>

	<div id="sc-branch-form">
			<table id="sc-branch-table" class="form-table">
				<tr>
					<?php $field = 'category_id'; ?>
					<th><label for="<?php echo $field; ?>"><?php _e( 'Select branch location', 'sptheme_admin' ); ?></label></th>
					<td>
						<select name="<?php echo $field; ?>" id="<?php echo $field; ?>">
							<option value="0" selected><?php _e( 'Select all location', 'sptheme_admin' ); ?></option>
						<?php
						$args = (array( 'hide_empty' => 0 ));
						$terms = get_terms( 'branch-location', $args );
						foreach ( $terms as $term ) {
							echo '<option class="level-0" value="' . $term->term_id . '">' . $term->name . '</option>';
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<?php $field = 'numberposts'; ?>
					<th><label for="<?php echo $field; ?>"><?php _e( 'Number of marker', 'sptheme_admin' ); ?></label></th>
					<td>
						<input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="-1" /> <smal>(-1 for show all)</small>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="button" id="option-submit" class="button-primary" value="<?php _e( 'Add Branch', 'sptheme_admin' ) ; ?>" name="submit" />
			</p>
	</div>			

	<?php
	exit();	
}
?>