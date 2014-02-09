<?php
/**
 * The template for displaying search forms
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
?>

<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="assistive-text">Search</label>
	<input type="text" class="field" name="s" id="s" placeholder="Search" />
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="Search" />
</form>