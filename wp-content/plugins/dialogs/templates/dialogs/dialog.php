<?php dialog_get_header() ?>
<div id="content">
    <div class="padder">

	<div class="page" id="tk-dialog-single">

	<?php if ( have_posts ( ) ) : ?>
	    <?php while ( have_posts ( ) ) : ?>
		<?php the_post(); ?>

	    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="post-content">
		    <div class="entry"><?php the_content( __( 'Read the rest of this entry &rarr;', '' ) ); ?></div>
		</div>
	    </div>

	    <?php endwhile; ?>
	<?php else: ?>
	    <p><?php _e( 'Sorry, no posts matched your criteria.', '' ) ?></p>
	<?php endif; ?>
	</div>
    </div><!-- .padder -->
</div><!-- #content -->
<?php dialog_get_footer() ?>