<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?><meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php do_action( 'bp_head' ); ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?> id="bp-default"> 
		<?php do_action( 'bp_before_header' ); ?>

		<div id="header">
			<div id="search-bar" role="search">
				<div class="padder">
					<a href="<?php echo home_url(); ?>"><div id="head-logo" class="shake"></div></a>

						<form action="<?php echo bp_search_form_action(); ?>" method="post" id="search-form">
							<label for="search-terms" class="accessibly-hidden"></label>
							<input type="text" id="search-terms" name="search-terms" placeholder="Search by keyword, town/zip code, interest, etc."/>

							<?php echo bp_search_form_type_select(); ?>

							<input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ); ?>" />

							<?php wp_nonce_field( 'bp_search_form' ); ?>

						</form><!-- #search-form -->

				<?php do_action( 'bp_search_login_bar' ); ?>

				</div><!-- .padder -->
			</div><!-- #search-bar -->

			<div id="navigation" role="navigation">
				<?php wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => 'bp_dtheme_main_nav' ) ); ?>
			</div>

			<?php do_action( 'bp_header' ); ?>

		</div><!-- #header -->

		<?php do_action( 'bp_after_header'     ); ?>
		<?php do_action( 'bp_before_container' ); ?>

		<div id="container">
