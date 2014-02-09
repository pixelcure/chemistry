<?php
/**
 * The template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Sandbox
 * @since Sandbox 2.0
 */
get_header(); ?>

<?php the_post(); ?>

<h1><?php the_title(); ?></h1>

<?php the_content(); ?>

<?php comments_template( '', true ); ?>

<hr />

<?php get_sidebar(); ?>
<?php get_footer(); ?>