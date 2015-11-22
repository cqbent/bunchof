		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo">
				<?php do_action( 'bp_dtheme_credits' ); ?>
				<p><?php printf( __( 'Proudly powered by <a href="%1$s">WordPress</a> and <a href="%2$s">BuddyPress</a>.', 'buddypress' ), 'http://wordpress.org', 'http://buddypress.org' ); ?></p>
			</div>

			<?php do_action( 'bp_footer' ); ?>

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>
    <script src="<?php print get_stylesheet_directory_uri(); ?>/isotope.js" ></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $container = $('#storecontainer');
        $container.isotope({
            itemSelector: '.storeitem',
            resizesContainer: true,
            masonry: {
                columnWidth: 100
            }
        });
        $('#filters').on('click', 'a', function () {
            var selector = $(this).data('filter');
            if (selector !== '*') {
                // include corner-stamp in filter
                // so it never gets filtered out
                selector = selector + ', .corner-stamp'
            }
            $container.isotope({
                filter: selector
            });
        });
        $('#filters li a').click(function () {
            $('#filters li a').removeClass('selected');
            $(this).addClass('selected');
        });
    });
    </script>
</html>