<?php
/* Template Name: Add Service*/
acf_form_head();
get_header();?>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
			</div>
			<div class="col-md-6 col-xs-12">
			<h3>Add Service</h3>
<?php	
$url = home_url('/profile/');
 $args = array(
       'field_groups' => array(78), // Field Group name
       'post_id' => 'new_post',
'return' => $url,
'post_title'	=> true,
       'new_post' => array(
           'post_type' => 'service',
           'post_status' => 'publish'
        ),

       'submit_value' => 'Add Service',


   );
//print_r($args);
   acf_form($args); 
?>
		</div>
	</div>
</div>
<br>
<?php
get_footer();