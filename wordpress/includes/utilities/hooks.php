<?php

//░░░░░░░░░░░░░░░░░░░░░░░░
//
//	 DIRECTORY
//
//   _ExclusiveTemplate
//      ∟Template_In_Use
//      ∟Template_Reset
//
//░░░░░░░░░░░░░░░░░░░░░░░░


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _ExclusiveTemplate
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

//∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴
// ∟Template_In_Use
//∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴

function template_in_use($page_templates, $this, $post){
	$default = get_post_meta($post->ID, '_wp_page_template', true);
	foreach ($page_templates as $template => $name) {
		if ($template != $default) {
			if (strpos($template, '.exc.php') !== false) {
				$pages = get_posts(array(
					'meta_key' => '_wp_page_template',
					'meta_value' => $template,
					'posts_per_page' => 1,
					'post_type' => 'page',
				));
				if (!empty($pages)) {
					$page_templates[$template] = $name . ' (In Use)';
				}
			}
		}
	}
	return $page_templates;
}
add_filter('theme_page_templates', 'template_in_use', 10, 3);

//∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴
// ∟Template_Reset
//∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴∵∴

function template_reset($post_id){
	$template = get_post_meta($post_id, '_wp_page_template', true);
	if (strpos($template, '.exc.php') !== false) {
		$pages = get_posts(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template,
			'posts_per_page' => -1,
			'post_type' => 'page',
		));
		foreach ($pages as $page){
			if ($page->ID != $post_id){
				update_post_meta($post_id, '_wp_page_template', 'default');
			}
		}
	}
}
add_action( 'save_post_page', 'template_reset', 10, 1 );


?>