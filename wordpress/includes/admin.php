<?php

//░░░░░░░░░░░░░░░░░░░░░░░░
//
//     DIRECTORY
//
//     _Javascript
//     _FADashicons
//
//░░░░░░░░░░░░░░░░░░░░░░░░

//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Javascript
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

function template_admin_js(){
	wp_enqueue_script( 'admin-js-min', get_asset('admin.min.js', 'js'), array('jquery'), '', true );
}
add_action('admin_enqueue_scripts', 'template_admin_js');


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _FADashicons
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

function fontawesome_dashicons() {
   wp_enqueue_style('fontawesome-dashicons', get_asset('font-awesome.dashicons.min.css','fonts/font-awesome/css'));
}
add_action('admin_init', 'fontawesome_dashicons');