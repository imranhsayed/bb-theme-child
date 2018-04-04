<?php
/* Template Name: Enquiry Form */
acf_form_head();
get_header();?>
<style>
.acf-field.acf-field-user.acf-field-5a6f526ef873d {
    display: none;
}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
		<h3 style="text-align:center;color: #ef4d3b;">Enquiry Form</h3>
	<?php
	$url=home_url("/thank-you/");
	 $args = array(
       'field_groups' => array(99), // Field Group name
       'post_id' => 'new_post',
       	'return' => $url,
	'post_title'	=> false,
	       'new_post' => array(
	           'post_type' => 'enquiry',
	           'post_status' => 'publish',
	        ),
	       'submit_value' => 'Submit',
	   );
	//print_r($args);
	   acf_form($args); 
	
	?>
	 </div>
	</div>
</div>
<br>
<?php get_footer(); ?>