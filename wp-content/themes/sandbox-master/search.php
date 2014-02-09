<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
get_header(); ?>

<h1>Search Results for: <?php get_search_query(); ?></h1>

<?php include(get_template_directory().'/loops/loop.php'); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>