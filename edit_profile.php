<?php
/* Template Name: Edit Profile*/
acf_form_head();
get_header();
$user_id = get_current_user_id();
?>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-xs-12">
			<h3>Edit Additional Profile</h3>
<?php	
$url = home_url('/profile/');
 $args = array(
       'field_groups' => array(121), // Field Group name
       'post_id' =>'user_'.$user_id,
'return' => $url,
       'submit_value' => 'Update Profile',


   );
//print_r($args);
   acf_form($args); 
?>
		</div>
	<div class="col-md-6 col-xs-12">
		<h3>Edit Profile</h3>
		<?php echo do_shortcode( '[wppb-edit-profile]' ); ?>
	</div>
</div>
</div>
<br>
<?php
get_footer();