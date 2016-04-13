<?php

//░░░░░░░░░░░░░░░░░░░░░░░░
//
//     DIRECTORY
//
//     _Javascript
//
//░░░░░░░░░░░░░░░░░░░░░░░░


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Javascript
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

function template_admin_js(){
	wp_enqueue_script( 'admin-js-min', get_asset('admin.min.js', 'js'), array('jquery'), '', true );
}
add_action('admin_enqueue_scripts', 'template_admin_js');