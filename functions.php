<?php

include_once( __DIR__ . '/includes/wsu-calendar-roles-and-capabilities.php' );

// If a user authenticates with WSU AD, and they don't exist as a user, add them as a user.
add_filter( 'wsuwp_sso_create_new_user', '__return_true' );

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