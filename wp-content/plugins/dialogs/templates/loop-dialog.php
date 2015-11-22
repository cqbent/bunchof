<?php if ( have_posts() ) : ?>
	<?php /* Start the Loop */ ?>
	
	<ul class="dialogs">
	<?php while ( have_posts() ) : the_post(); ?>
		
		<?php if( has_post_thumbnail() ): ?>
			
			<!-- With post thumbnail -->
			<?php $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) ) ;?>
			<li id="dialog-<?php the_ID(); ?>" <?php post_class( 'dialog featured' ); ?> style="background-image: url(<?php echo $src[0]; ?>);">
				<a class="thickbox" onclick="return false" href="<?php the_splashlink( 'width=800&height=500' ); ?>" title="<?php the_title(); ?>"><h3 class="entry-title"><?php the_title(); ?></h3></a>
			</li>
		
		<?php else: ?>
			
			<!-- Simple text -->
			<li id="dialog-<?php the_ID(); ?>" <?php post_class( 'dialog' ); ?>>
				<a class="thickbox" onclick="return false" href="<?php the_splashlink( 'width=800&height=500' ); ?>" title="<?php the_title(); ?>"><h3 class="entry-title"><?php the_title(); ?></h3></a>
			</li>
		
		<?php endif; ?>
		
	<?php endwhile; ?>
	</ul>
<?php endif; ?>