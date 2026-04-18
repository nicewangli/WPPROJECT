<?php
get_header();
?>
<main>
	<h1>search.php 命中</h1>

	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/content', 'excerpt' ); ?>
		<?php endwhile; ?>

		<nav class="posts-pagination" aria-label="<?php esc_attr_e( 'Search pagination', 'my-first-theme' ); ?>">
        <?php my_first_theme_posts_pagination(); ?>
		</nav>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>
<?php
get_footer();