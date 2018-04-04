<?php
/* Template Name: User Registration */
acf_form_head();
get_header();

 $args = array(
       'field_groups' => array(4), // Field Group name
       'post_id' => 'new_post',
'return' => 'https://pickneycare.testplanets.com/manager-login/',
'post_title'	=> true,
       'new_post' => array(
           'post_type' => 'post',
           'post_status' => 'publish'
           'post_'
        ),

       'submit_value' => 'Submit',


   );
//print_r($args);
   acf_form($args); 


get_footer();