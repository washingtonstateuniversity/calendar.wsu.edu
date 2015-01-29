<?php

class WSU_Calendar_Roles_And_Capabilities {

	/**
	 * Add the filters and actions used
	 */
	public function __construct() {
		add_action( 'init',          array( $this, 'modify_subscriber_capabilities' ), 10 );
		add_action( 'do_meta_boxes', array( $this, 'remove_meta_boxes'              ), 10 );
	}

	public function modify_subscriber_capabilities() {
		$subscriber = get_role( 'subscriber' );
		$subscriber->add_cap( 'edit_tribe_events' );
	}

	public function remove_meta_boxes() {
		if ( current_user_can( 'read' ) && current_user_can( 'edit_tribe_events') && ! current_user_can( 'edit_posts' ) ) {
			remove_meta_box( 'commentstatusdiv', 'tribe_events', 'normal' );
			remove_meta_box( 'postimagediv',     'tribe_events', 'side'   );
			remove_meta_box( 'commentsdiv',      'tribe_events', 'normal' );
			remove_meta_box( 'postexcerpt',      'tribe_events', 'normal' );
			remove_meta_box( 'postcustom',       'tribe_events', 'normal' );
			remove_meta_box( 'slugdiv',          'tribe_events', 'normal' );
		}
	}
}
if ( defined( 'WP_INSTALLING' ) && false == WP_INSTALLING ) {
	new WSU_Calendar_Roles_And_Capabilities();
}