<?php
/**
 * Custom functions for bb-theme-child
 *
 * @package bb-theme
 */

add_action( 'wp_ajax_mti_send_email', 'mti_send_email' );

function mti_send_email() {

	$to = $_POST['to'];
	$name = $_POST['from'];
	$cc = $_POST['cc'];
	$cc_array = explode( ',', $cc );
	$subject = $_POST['sub'];
	$msg = $_POST['msg_body'];
	$reply_body = $_POST['reply_body'];
	$remove_splash = stripslashes( $msg  );
	$msg_send = str_replace("{{full_name}}",$name , $remove_splash );

	$body    = nl2br($msg_send);
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	$headers[] = "'From: " . $name . "'";
	foreach ( $cc_array as $cc ) {
		$headers[] = "'Cc: " . trim( $cc ) . "'";
	}
	echo '<pre>';
	echo $to;
	echo $subject;
	echo $body;
	print_r( $headers );
	$myvar = wp_mail( $to, $subject, $body, $headers );

	echo 'Mail Send Successfully ...';
	// if nonce verification fails die.
	if ( ! wp_verify_nonce( $_POST['security'], 'ihs_nonce_action_name' ) ) {
		wp_die();
	}

	wp_send_json_success( array(
		'my_data' => 'Pass all your data here',  // Always pass your data here that you want to access in js file.
		'data_recieved_from_js' => $myvar,  // $_POST will contain the array of data object passed in js file second parameter. like action,name etc
	) );
}

function mti_compose_email_form() {
	echo '<div class="wrap mti-reply-email-container mti-reply-hidden"><div id="mti-icon-tools" class="mti-icon32"></div>';

//	$content = get_option( 'custom_msg', true );
	$content = '';
	$editor_id = 'mti-email-text-editor';
	echo '<form method="post" id="mti-compose-email-form">';
	echo '<label for="">To: <input type="text" name="email_to" size="60" value="" id = "mti-email_to" class="mti_input_text" placeholder=""></label>';
	echo '<label for="">CC: <input type="text" name="email_from" size="55" value="" id="mti-email_cc" class="mti_input_cc" placeholder=""></label>';
	echo '<label for="">Sub: <input type="text" name="email_sub" size="55" value="" id="mti-email_sub" class="mti_input_sub" placeholder=""></label>';
	wp_editor( $content, $editor_id );
	echo '</br>';
	echo '<div class="mti-reply-editor-area" id="mti-reply-textarea"></div>';
	echo '<input type="submit" value="Send" id="mti-email-submit-btn" class="" name="send_msg">';
	echo '</form>';
	echo '</div>';

}

function mti_email_enqueue_scripts() {
	wp_localize_script( 'mti_admin_custom_script', 'emaildata', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ), // admin_url( 'admin-ajax.php' ) returns the url till admin-ajax.php file of wordpress.
		'ajax_nonce' => wp_create_nonce('ihs_nonce_action_name'),  // Create nonce and send it to js file in postdata.ajax_nonce.
	) );
}
add_action( 'admin_enqueue_scripts', 'mti_email_enqueue_scripts' );