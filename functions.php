<?php

include_once( __DIR__ . '/includes/wsu-calendar-roles-and-capabilities.php' );

// If a user authenticates with WSU AD, and they don't exist as a user, add them as a user.
add_filter( 'wsuwp_sso_create_new_user', '__return_true' );

add_action( 'wsuwp_sso_user_created', 'wsu_calendar_new_user_member', 10, 1 );
/**
 * Add new users created through the SSO plugin as subscribers to the site.
 * @param $user_id
 */
function wsu_calendar_new_user_member( $user_id ) {
	add_user_to_blog( get_current_blog_id(), $user_id, 'subscriber' );
}

add_action( 'admin_menu', 'wsu_calendar_user_auto_role' );
/**
 * Add all logged in users in the admin screen to the Calendar.
 */
function wsu_calendar_user_auto_role() {
	if ( is_user_logged_in() && ! is_user_member_of_blog() ) {
		add_user_to_blog( get_current_blog_id(), get_current_user_id(), 'subscriber' );
		wp_safe_redirect( admin_url( '/post-new.php?post_type=tribe_events' ) );
		exit;

	}
}

add_action( 'rss2_item', 'wsu_calendar_rss_item' );
/**
 * Add calendar specific items to the events feed. The WSU mobile team looks
 * for this information in the calendar feed.
 */
function wsu_calendar_rss_item() {
	global $post;

	if ( 'tribe_events' !== $post->post_type ) {
		return;
	}

	$start_date = get_post_meta( $post->ID, '_EventStartDate', true );
	$end_date   = get_post_meta( $post->ID, '_EventEndDate',   true );

	if ( $start_date ) {
		$start_date = date( 'r', strtotime( $start_date ) );
		echo '<ev:startdate>' . esc_html( $start_date ) . '</ev:startdate>';
	}

	if ( $end_date ) {
		$end_date = date( 'r', strtotime( $end_date ) );
		echo '<ev:enddate>' . esc_html( $end_date ) . '</ev:enddate>';
	}

}

add_action( 'rss2_ns', 'wsu_calendar_rss_namespace' );
/**
 * Ensure the events namespace is attached to the RSS feed.
 */
function wsu_calendar_rss_namespace() {
	?>
	xmlns:ev="http://purl.org/rss/1.0/modules/event/"
<?php
}