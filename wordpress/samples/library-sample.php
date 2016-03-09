<?php

//░░░░░░░░░░░░░░░░░░░░░░░░
//
//	 DIRECTORY
//
//	 _Debugging
//	 	∟_log
//	 _SVGs
//	 	∟cc_mime_types
//	 	∟get_svg
//	 	∟the_svg
//	 _Strings
//	 	∟get_accent_words
//	 	∟the_accent_words
//	 	∟get_phone
//	 	∟the_phone
//	 	∟get_currency
//	 	∟the_currency
//	 	∟get_parse_currency
//	 	∟the_parse_currency
//	 _Path
//	 	∟get_static
//	 	∟the_static
//	 _Iframe
//	 	∟get_iframe_id
//	 	∟the_iframe_id
//	 _Youtube
//	 	∟get_video_thumbnail
//	 	∟the_video_thumbnail
//	 _Templates
//	 	∟get_page_by_template
//	 	∟the_page_by_template
//	 	∟get_page_link_by_template
//	 	∟the_page_link_by_template
//	 _Taxonomies
//	 	∟get_page_by_template
//	 	∟the_page_by_template
//
//░░░░░░░░░░░░░░░░░░░░░░░░

//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Debugging
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * output any type of variable or value to the debug log
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('_log')){
	function _log( $message ) {
		if( WP_DEBUG === true ){
			if( is_array( $message ) || is_object( $message ) ){
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _SVGs
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * allow svg file types
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('cc_mime_types')){
	function cc_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	add_filter('upload_mimes', 'cc_mime_types');
}

/**
 * return svg file contents
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_svg')){
	function get_svg($svg) {
		if ( $svg && strpos($svg, '.svg')) {
			$svg = str_replace( site_url(), ABSPATH, $svg);
			return file_get_contents($svg);
		}
	}
}

/**
 * echo svg file contents
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_svg')){
	function the_svg($svg) {
		echo get_svg($svg);
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Strings
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * replace and return all quotes in string with specified html tags
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_accent_words')){
	function get_accent_words( $content, $tag="em" ) {
		if (preg_match_all('/\"|\'/', $content, $matches)) {
			$opening = '<' .$tag. '>';
			$closing = '</' .$tag. '>';
			foreach ($matches[0] as $index => $match) {
				$regex_match = '/'.preg_quote($match).'/';
				if (($index % 2) == 0) {
					$content = preg_replace('/\"|\'/', $opening, $content, 1);
				} else {
					$content = preg_replace('/\"|\'/', $closing, $content, 1);
				}
			}
			if ((count($matches[0]) % 2) != 0) {
				$content .= $closing;
			}
		}

		return $content;
	}
}

/**
 * replace and echo all quotes in string with specified html tags
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_accent_words')){
	function the_accent_words( $content, $tag="em" ) {
		echo get_accent_words( $content, $tag );
	}
}

/**
 * return clean phone number text and prepare for link tag
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_phone')){
	function get_phone($number, $prefix="+1") {
		$url_phone = str_replace(array("+1", "+44", "+", "(", ")", "-", ".", " " ), "", $number);
		$url_phone = 'tel:'.$prefix.$url_phone;
		return $url_phone;
	}
}

/**
 * echo clean phone number text and prepare for link tag
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_phone')){
	function the_phone($number, $prefix="+1") {
		echo get_phone($number, $prefix);
	}
}

/**
 * return number formatted as currency
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_currency')){
	function get_currency($number, $trim_length=0) {
		setlocale(LC_MONETARY, 'en_US');
		$format = '%.'.$trim_length.'n';
		return money_format($format, floatval($number));
	}
}

/**
* echo number formatted as currency
*
* @since version 1.1.0
* @author PeterJohn Hunt <email@peterjohnhunt.com>
*/
if(!function_exists('the_currency')){
	function the_currency($number, $trim_length=0) {
		echo get_currency($number, $trim_length);
	}
}

/**
* return currency formatted as number
*
* @since version 1.1.0
* @author PeterJohn Hunt <email@peterjohnhunt.com>
*/
if(!function_exists('get_parse_currency')){
	function get_parse_currency($amount){
		$amount = str_replace(array('$',','),'',$amount);
		return floatVal($amount);
	}
}

/**
* echo currency formatted as number
*
* @since version 1.1.0
* @author PeterJohn Hunt <email@peterjohnhunt.com>
*/
if(!function_exists('the_currency')){
	function the_parse_currency($number) {
		echo get_parse_currency($number);
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Paths
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * return path to static files location
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_static')){
	function get_static($filename='') {
		return get_template_directory_uri().'/'.$filename;
	}
}

/**
 * echo path to static files location
 *
 * @since version 1.0.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_static')){
	function the_static($filename='') {
		echo get_static($filename);
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Iframe
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * return stripped iframe src
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_iframe_id')){
	function get_iframe_id( $iframe, $fieldName='video' ){

		if ( is_object( $iframe ) ) {
			$iframe = get_field( $fieldName, $iframe );
		}

		preg_match('/embed\/(.+?)\?/', $iframe, $matches);
		$src = $matches[1];

		return $src;
	}
}

/**
 * echo stripped iframe src
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_iframe_id')){
	function the_iframe_id( $iframe ){
		echo get_iframe_id( $iframe );
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Youtube
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * return video youtube thumbnail url
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_video_thumbnail')){
	function get_video_thumbnail( $video_post=false, $fieldName='video' ){
		if (!$video_post) {
			$video_post = get_the_ID();
		}
		$video = get_field( $fieldName, $video_post );
		$video_id = get_iframe_id($video);

		return 'http://img.youtube.com/vi/' .$video_id. '/0.jpg';
	}
}

/**
 * echo video youtube thumbnail url
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_video_thumbnail')){
	function the_video_thumbnail( $video_post=false, $fieldName='video' ){
		echo get_video_thumbnail($video_post, $fieldName);
	}
}


//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Templates
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * return page id linked to template
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_page_by_template')){
	function get_page_by_template($template){
		$template .= '.php';
		$pages = get_posts(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template,
			'posts_per_page' => 1,
			'post_type' => 'page',
		));

		if (!empty($pages)) {
			return $pages[0]->ID;
		}
	}
}

/**
 * echo page id linked to template
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_page_by_template')){
	function the_page_by_template($template){
		echo get_page_by_template($template);
	}
}

/**
 * return page url linked to template
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_page_link_by_template')){
	function get_page_link_by_template($template){
		return get_the_permalink(get_page_by_template($template));
	}
}

/**
 * echo page url linked to template
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_page_link_by_template')){
	function the_page_link_by_template($template){
		echo get_page_link_by_template($template);
	}
}

//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡
//  _Taxonomies
//≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡≡

/**
 * return taxonomy term title
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('get_term_title')){
	function get_term_title($term_id, $taxonomy){
		if (!$term_id)
			return;

		$term = get_term( $term_id, $taxonomy ) ;

		return $term->name;

	}
}

/**
 * echo taxonomy term title
 *
 * @since version 1.1.0
 * @author PeterJohn Hunt <email@peterjohnhunt.com>
 */
if(!function_exists('the_term_title')){
	function the_term_title($term_id, $taxonomy){
		echo get_term_title($term_id, $taxonomy);
	}
}

?>
