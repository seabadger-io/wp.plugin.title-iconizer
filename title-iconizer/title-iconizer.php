<?php
/*
Plugin Name:  Title Iconizer
Plugin URI:   https://seabadger.io/
Description:  Add Font Awesome icons to post titles
Version:      1.0.0
Author:       SeaBadger.io
Author URI:   https://seabadger.io/about
License:      GNU GPLv3 or later
License URI:  https://www.gnu.org/licenses/gpl.txt
Text Domain:  sbti
Domain Path:  /languages

Title Iconizer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.
 
Title Iconizer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Title Iconizer. If not, see https://www.gnu.org/licenses/gpl.txt.
*/

register_activation_hook( __FILE__, 'sbti_activate' );

register_deactivation_hook( __FILE__, 'sbti_deactivate' );

function sbti_activate() {
}

function sbti_deactivate() {
	remove_filter('the_title', 'sbti_iconize_title', 10);
}

function sbti_iconize_title($title, $postid = null) {
	if ($postid !== null) {
		$value = get_post_meta($postid, '_sbti_custom_icon', true);
		if ($value) {
			return $title . sprintf(' <i class="fa fa-%s"></i>', $value);
		}
	}
	return $title;
}
add_filter('the_title', 'sbti_iconize_title', 10, 2);

// function sbti_admin_init() {
// }
// add_action('admin_init', 'sbti_admin_init');

function sbti_add_metabox() {
	add_meta_box(
		'sbti_custom_icon_box',
		'Custom icon',
		'sbti_custom_icon_html',
		'post');
}
add_action('add_meta_boxes', 'sbti_add_metabox');

function sbti_load_scripts() {
	$screen = get_current_screen();
	if (is_object($screen) && $screen->post_type == 'post') {
		wp_enqueue_script('sbti_admin_script', plugin_dir_url(__FILE__) . 'admin.js', ['jquery']);
	}
}
add_action('admin_enqueue_scripts', 'sbti_load_scripts');

function sbti_custom_icon_html($post) {
	$value = get_post_meta($post->ID, '_sbti_custom_icon', true);
?>
	<label for="sbti_custom_icon"><?php echo __('Title icon', 'sbti'); ?></label>
	<input type="text" name="sbti_custom_icon" id="sbti_custom_icon" class="form-input-tip" value="<?php echo $value; ?>">
	<span id="sbti_custom_icon_preview"></span>
<?php
}

function sbti_save_postdata($post_id) {
	$fa_classes = sbti_read_fa_classes();
	if (array_key_exists('sbti_custom_icon', $_POST) &&
		in_array($_POST['sbti_custom_icon'], $fa_classes)) {
		update_post_meta(
			$post_id,
			'_sbti_custom_icon',
			$_POST['sbti_custom_icon']
			);
	}
}
add_action('save_post', 'sbti_save_postdata');

function sbti_read_fa_classes() {
	$handle = fopen(plugin_dir_path(__FILE__) . 'fa-classes.txt','r');
	$fa_classes = array();
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			$line = trim($line);
			array_push($fa_classes, $line);
		}
		fclose($handle);
	} else {
		array_push($fa_classes, 'Error loading FontAwesome class names');
	}
	return $fa_classes;
}

?>
