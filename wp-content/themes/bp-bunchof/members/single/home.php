<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */


get_header( 'buddypress' ); ?>

	<div id="content"> 
		<div class="padder"> 
                    
                        <?php
                        
                        if (is_user_logged_in()) {
                            
                        ?>

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->
                        
                        

			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->
                        
                        <?php
                        // SYP: remove member details if not a friend 
                        if (bp_is_friend() == 'is_friend' || bp_is_my_profile()) {
                        ?>

			<div id="item-body">

				<?php do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );
                                
                                // SYP: fix issue with notifications causing double window
				elseif ( function_exists('bp_is_user_notifications') && bp_is_user_notifications() ) : 
                                        bp_get_template_part( 'members/single/notifications' );
                                
                                elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;

				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->
                        
                        <?php
                        }
                        else {
                        ?>
                        <div id="item-body">
                            <?php
                            if ( bp_is_user_profile() ) {
                                locate_template( array( 'members/single/profile.php'   ), true );
                            }
                            else {
                            ?>
                            You must be a friend to view this member's details.
                            <?php
                            }
                            ?>
                        </div>
                        
                        <?php
                            
                        }
                        ?>

			<?php do_action( 'bp_after_member_home_content' ); ?>
                        
                    <?php
                    
                    }
                    
                    else {
                        ?>
                        <div class="message">You must be logged into view member profiles</div>
                        <?php
                    }
                        
                    ?>
		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_sidebar( 'buddypress' ); ?>
<?php get_footer( 'buddypress' ); ?>
