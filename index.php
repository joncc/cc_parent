<?php get_header(); ?>
<main>
<?php if ( have_posts() ) : ?>
	<article>
		<?php while ( have_posts() ) : the_post(); ?>
			<a href='<?php the_permalink() ?>'>
				<h2><?php the_title() ?></h2>
			</a>
			<?php the_excerpt() ?>
		<?php endwhile; ?>
	</article>
<?php else: ?>
	<p>No posts match your search</p>
<?php endif ?>
</main>
<?php get_footer(); ?>