<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Observer::observe( 'page_layout_bottom', function($plugin) {
	echo View::factory( 'reformal/footer', array(
		'plugin' => $plugin
	) );
}, $plugin);