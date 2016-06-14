<?php
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Import epanel settings</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;

	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results(
		$wpdb->prepare( "SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP %s", 'http://et_sample_images.com' )
	);
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();

				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];

			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );

			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );

			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => esc_url_raw( $local_image ) ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;

	$importOptions = 'YTo4MDp7czowOiIiO047czoxMzoidGhlc3R5bGVfbG9nbyI7czowOiIiO3M6MTY6InRoZXN0eWxlX2Zhdmljb24iO3M6MDoiIjtzOjIxOiJ0aGVzdHlsZV9jb2xvcl9zY2hlbWUiO3M6NzoiRGVmYXVsdCI7czoxOToidGhlc3R5bGVfYmxvZ19zdHlsZSI7TjtzOjE5OiJ0aGVzdHlsZV9ncmFiX2ltYWdlIjtOO3M6MjE6InRoZXN0eWxlX3NpZGViYXJfaG9tZSI7TjtzOjE2OiJ0aGVzdHlsZV9zaWRlYmFyIjtzOjI6Im9uIjtzOjIxOiJ0aGVzdHlsZV9jYXRudW1fcG9zdHMiO3M6MToiNiI7czoyNToidGhlc3R5bGVfYXJjaGl2ZW51bV9wb3N0cyI7czoxOiI1IjtzOjI0OiJ0aGVzdHlsZV9zZWFyY2hudW1fcG9zdHMiO3M6MToiNSI7czoyMToidGhlc3R5bGVfdGFnbnVtX3Bvc3RzIjtzOjE6IjUiO3M6MjA6InRoZXN0eWxlX2RhdGVfZm9ybWF0IjtzOjY6Ik0gaiwgWSI7czoyMDoidGhlc3R5bGVfdXNlX2V4Y2VycHQiO047czoxNDoidGhlc3R5bGVfY3Vmb24iO3M6Mjoib24iO3M6MjM6InRoZXN0eWxlX2hvbWVwYWdlX3Bvc3RzIjtzOjE6IjciO3M6MjM6InRoZXN0eWxlX2V4bGNhdHNfcmVjZW50IjtOO3M6MTg6InRoZXN0eWxlX21lbnVwYWdlcyI7YToyOntpOjA7czozOiI3MjQiO2k6MTtzOjM6IjY2OCI7fXM6MjU6InRoZXN0eWxlX2VuYWJsZV9kcm9wZG93bnMiO3M6Mjoib24iO3M6MTg6InRoZXN0eWxlX2hvbWVfbGluayI7czoyOiJvbiI7czoxOToidGhlc3R5bGVfc29ydF9wYWdlcyI7czoxMDoicG9zdF90aXRsZSI7czoxOToidGhlc3R5bGVfb3JkZXJfcGFnZSI7czozOiJhc2MiO3M6MjY6InRoZXN0eWxlX3RpZXJzX3Nob3duX3BhZ2VzIjtzOjE6IjMiO3M6MTc6InRoZXN0eWxlX21lbnVjYXRzIjthOjI6e2k6MDtzOjE6IjMiO2k6MTtzOjE6IjEiO31zOjM2OiJ0aGVzdHlsZV9lbmFibGVfZHJvcGRvd25zX2NhdGVnb3JpZXMiO3M6Mjoib24iO3M6MjU6InRoZXN0eWxlX2NhdGVnb3JpZXNfZW1wdHkiO3M6Mjoib24iO3M6MzE6InRoZXN0eWxlX3RpZXJzX3Nob3duX2NhdGVnb3JpZXMiO3M6MToiMyI7czoxNzoidGhlc3R5bGVfc29ydF9jYXQiO3M6NDoibmFtZSI7czoxODoidGhlc3R5bGVfb3JkZXJfY2F0IjtzOjM6ImFzYyI7czoyNDoidGhlc3R5bGVfZGlzYWJsZV90b3B0aWVyIjtOO3M6MTg6InRoZXN0eWxlX3Bvc3RpbmZvMiI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6MjQ6InRoZXN0eWxlX2Jsb2dfdGh1bWJuYWlscyI7czoyOiJvbiI7czoyNjoidGhlc3R5bGVfc2hvd19wb3N0Y29tbWVudHMiO3M6Mjoib24iO3M6MjQ6InRoZXN0eWxlX3BhZ2VfdGh1bWJuYWlscyI7TjtzOjI3OiJ0aGVzdHlsZV9zaG93X3BhZ2VzY29tbWVudHMiO047czoxODoidGhlc3R5bGVfcG9zdGluZm8xIjthOjQ6e2k6MDtzOjY6ImF1dGhvciI7aToxO3M6NDoiZGF0ZSI7aToyO3M6MTA6ImNhdGVnb3JpZXMiO2k6MztzOjg6ImNvbW1lbnRzIjt9czoyMjoidGhlc3R5bGVfY3VzdG9tX2NvbG9ycyI7TjtzOjE4OiJ0aGVzdHlsZV9jaGlsZF9jc3MiO047czoyMToidGhlc3R5bGVfY2hpbGRfY3NzdXJsIjtzOjA6IiI7czoyMjoidGhlc3R5bGVfY29sb3JfYmdjb2xvciI7czowOiIiO3M6MjM6InRoZXN0eWxlX2NvbG9yX21haW5mb250IjtzOjA6IiI7czoyMzoidGhlc3R5bGVfY29sb3JfbWFpbmxpbmsiO3M6MDoiIjtzOjIzOiJ0aGVzdHlsZV9jb2xvcl9wYWdlbGluayI7czowOiIiO3M6Mjk6InRoZXN0eWxlX2NvbG9yX3NpZGViYXJfdGl0bGVzIjtzOjA6IiI7czoyODoidGhlc3R5bGVfY29sb3JfZm9vdGVyX3RpdGxlcyI7czowOiIiO3M6Mjc6InRoZXN0eWxlX2NvbG9yX2Zvb3Rlcl9saW5rcyI7czowOiIiO3M6MjM6InRoZXN0eWxlX3Nlb19ob21lX3RpdGxlIjtOO3M6Mjk6InRoZXN0eWxlX3Nlb19ob21lX2Rlc2NyaXB0aW9uIjtOO3M6MjY6InRoZXN0eWxlX3Nlb19ob21lX2tleXdvcmRzIjtOO3M6Mjc6InRoZXN0eWxlX3Nlb19ob21lX2Nhbm9uaWNhbCI7TjtzOjI3OiJ0aGVzdHlsZV9zZW9faG9tZV90aXRsZXRleHQiO3M6MDoiIjtzOjMzOiJ0aGVzdHlsZV9zZW9faG9tZV9kZXNjcmlwdGlvbnRleHQiO3M6MDoiIjtzOjMwOiJ0aGVzdHlsZV9zZW9faG9tZV9rZXl3b3Jkc3RleHQiO3M6MDoiIjtzOjIyOiJ0aGVzdHlsZV9zZW9faG9tZV90eXBlIjtzOjI3OiJCbG9nTmFtZSB8IEJsb2cgZGVzY3JpcHRpb24iO3M6MjY6InRoZXN0eWxlX3Nlb19ob21lX3NlcGFyYXRlIjtzOjM6IiB8ICI7czoyNToidGhlc3R5bGVfc2VvX3NpbmdsZV90aXRsZSI7TjtzOjMxOiJ0aGVzdHlsZV9zZW9fc2luZ2xlX2Rlc2NyaXB0aW9uIjtOO3M6Mjg6InRoZXN0eWxlX3Nlb19zaW5nbGVfa2V5d29yZHMiO047czoyOToidGhlc3R5bGVfc2VvX3NpbmdsZV9jYW5vbmljYWwiO047czozMToidGhlc3R5bGVfc2VvX3NpbmdsZV9maWVsZF90aXRsZSI7czo5OiJzZW9fdGl0bGUiO3M6Mzc6InRoZXN0eWxlX3Nlb19zaW5nbGVfZmllbGRfZGVzY3JpcHRpb24iO3M6MTU6InNlb19kZXNjcmlwdGlvbiI7czozNDoidGhlc3R5bGVfc2VvX3NpbmdsZV9maWVsZF9rZXl3b3JkcyI7czoxMjoic2VvX2tleXdvcmRzIjtzOjI0OiJ0aGVzdHlsZV9zZW9fc2luZ2xlX3R5cGUiO3M6MjE6IlBvc3QgdGl0bGUgfCBCbG9nTmFtZSI7czoyODoidGhlc3R5bGVfc2VvX3NpbmdsZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6Mjg6InRoZXN0eWxlX3Nlb19pbmRleF9jYW5vbmljYWwiO047czozMDoidGhlc3R5bGVfc2VvX2luZGV4X2Rlc2NyaXB0aW9uIjtOO3M6MjM6InRoZXN0eWxlX3Nlb19pbmRleF90eXBlIjtzOjI0OiJDYXRlZ29yeSBuYW1lIHwgQmxvZ05hbWUiO3M6Mjc6InRoZXN0eWxlX3Nlb19pbmRleF9zZXBhcmF0ZSI7czozOiIgfCAiO3M6MzI6InRoZXN0eWxlX2ludGVncmF0ZV9oZWFkZXJfZW5hYmxlIjtzOjI6Im9uIjtzOjMwOiJ0aGVzdHlsZV9pbnRlZ3JhdGVfYm9keV9lbmFibGUiO3M6Mjoib24iO3M6MzU6InRoZXN0eWxlX2ludGVncmF0ZV9zaW5nbGV0b3BfZW5hYmxlIjtzOjI6Im9uIjtzOjM4OiJ0aGVzdHlsZV9pbnRlZ3JhdGVfc2luZ2xlYm90dG9tX2VuYWJsZSI7czoyOiJvbiI7czoyNToidGhlc3R5bGVfaW50ZWdyYXRpb25faGVhZCI7czowOiIiO3M6MjU6InRoZXN0eWxlX2ludGVncmF0aW9uX2JvZHkiO3M6MDoiIjtzOjMxOiJ0aGVzdHlsZV9pbnRlZ3JhdGlvbl9zaW5nbGVfdG9wIjtzOjA6IiI7czozNDoidGhlc3R5bGVfaW50ZWdyYXRpb25fc2luZ2xlX2JvdHRvbSI7czowOiIiO3M6MTk6InRoZXN0eWxlXzQ2OF9lbmFibGUiO047czoxODoidGhlc3R5bGVfNDY4X2ltYWdlIjtzOjA6IiI7czoxNjoidGhlc3R5bGVfNDY4X3VybCI7czowOiIiO3M6MjA6InRoZXN0eWxlXzQ2OF9hZHNlbnNlIjtzOjA6IiI7fQ==';

	/*global $options;

	foreach ($options as $value) {
		if( isset( $value['id'] ) ) {
			update_option( $value['id'], $value['std'] );
		}
	}*/

	$importedOptions = unserialize(base64_decode($importOptions));

	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}
	update_option( $shortname . '_use_pages', 'false' );
} ?>