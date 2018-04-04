<?php
/* Template Name: User Profile */
get_header();
global $wpdb;
$user_id = get_current_user_id();
$user = wp_get_current_user();
if ( !in_array( 'artist', (array) $user->roles ) ) {
    echo "<h3 style='text-align:center;'>You are not authentic user</h3>";
    exit;
}
$user_name = get_user_meta($user_id,'first_name',true).' '.get_user_meta($user_id,'last_name',true);

$email = $user->user_email;
$phone = get_user_meta($user_id,'phone',true);
$address = get_user_meta($user_id,'address',true);
$state = get_user_meta($user_id,'state',true);
$city = get_user_meta($user_id,'city',true);
$zip_code = get_user_meta($user_id,'zip',true);
?>
<div class="container">
	<div class="row">
		<div class="col-md-3 col-xs-12">
			  <img src="<?php the_field('profile_pic',"user_$user_id"); ?>" class="rounded img-responsive" width="300px" height="300px" alt="...">
		</div>
		<div class="col-md-4 col-xs-12">
			<h3 class="user_name"><?php echo $user_name;?></h3>
			<p class="occasion">Photographer</p>
			<a href="<?php echo home_url("/edit-profile/");?>"><button type="button" class="btn btn-danger">Edit Profile</button></a>
		</div>
		<div class="col-md-5 col-xs-12">
			<div class="row" style="margin-top: 32px;">
				<div class="col-md-6 service">
					<a href="<?php echo home_url("/add-service/");?>"><h4 style="color: #fff;">Total Services</h4></a>
					<?php $args = array(
									    'post_type'  => 'service',
									    'author' =>$user_id
									);
									$all_service= get_posts( $args );
									$total_service = count($all_service);
									?>
					<h2 style="color: #fff;"><?php echo $total_service;?></h2>
				</div>
				<div class="col-md-6 total_orders">
					<h4 style="color: #fff;">Total Order</h4>
					<h2 style="color: #fff;"><?php echo $total_service;?></h2>
				</div>
			</div>	
		</div>	
	</div>
</div>	

<div class="container tab_container_profile">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs">
			    <li class="active"><a data-toggle="tab" href="#manage_profile">Manage Your Profile</a></li>
			    <li><a data-toggle="tab" href="#service">Your Services</a></li>
			    <li><a data-toggle="tab" href="#total_order">Total Orders</a></li>
			    <li><a data-toggle="tab" href="#purchase_history">Your Purchase History</a></li>
			</ul>

		  <div class="tab-content">
		    <div id="manage_profile" class="tab-pane fade in active">
		      <h5 class="heading_tab_profile"><strong>Manage Your Profile</strong></h5>
		      		<table class="table table-striped">
					    <thead>
					      <tr>
					        <th>Your name</th>
					        <th><?php echo $user_name;?></th>
					      </tr>
					    </thead>
					    <tbody>
					      <tr>
					        <td>Email Address</td>
					        <td><?php echo $email;?></td>
					      </tr>
					      <tr>
					        <td>Phone</td>
					        <td><?php echo $phone;?></td>
					      </tr>
					      <tr>
					        <td>Address</td>
					        <td><?php echo $address;?></td>
					      </tr>
					      <tr>
					      	<td>State</td>
					      	<td><?php echo $state;?></td>
					      </tr>
					      <tr>
					      	<td>City</td>
					      	<td><?php echo $city;?></td>
					      </tr>
					      <tr>
					      	<td>Zip code</td>
					      	<td><?php echo $zip_code;?></td>
					      </tr>
					    </tbody>
					</table>
		    </div>
		    <div id="service" class="tab-pane fade">
		      <h5 class="heading_tab_profile"><strong>Your Services</strong></h5>
		      <a href="<?php echo home_url("/add-service/");?>" role="button" class="btn btn-danger">Add Service</a>
		      <?php 
		      $args = array(
			    'post_type'  => 'service',
			    'author' =>$user_id
			);
			$all_service= get_posts( $args );
			if(!empty($all_service)){
		      ?>

		      <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Service For </th>
                <th>Your team name  </th>
                <th>Budget </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Service For </th>
                <th>Your team name  </th>
                <th>Budget </th>
            </tr>
        </tfoot>
        <tbody>
        <?php 
      
        foreach($all_service as $service){
        $service_id = $service->ID;
        $service_type = get_post_meta($service_id,'service_type',true);
        $your_team_name = get_post_meta($service_id,'your_team_name',true);
        $budget = get_post_meta($service_id,'budget',true);
        ?>
            <tr>
                <td><?php echo $service->post_title;?></td>
                <td><?php echo $service_type;?></td>
                <td><?php echo $your_team_name ;?></td>
                <td><?php echo $budget ;?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else{
    		echo "<h3 style='text-align:center;'>No service created by you..</h3>";
		}?>
		    </div>
		    <div id="total_order" class="tab-pane fade">
		      <h5><strong>Total Orders</strong></h5>
		      <?php
		      $args = array(
				'posts_per_page'   => -1,
				'orderby'          => 'date',
				'order'            => 'DESC',
				'meta_key'         => 'task_assign_to',
				'meta_value'       => $user_id,
				'post_type'        => 'enquiry',
				'post_status'      => 'publish'
			);
			$all_services_get= get_posts( $args );
			
			if(!empty($all_services_get)){?>
			  <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>Name</th>
			                <th>Service For </th>
			                <th>City </th>
			                
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
			                <th>Name</th>
			                <th>Service For </th>
			                <th>City</th>
			                
			            </tr>
			        </tfoot>
			        <tbody>
			        <?php 
			      
			        foreach($all_services_get as $service){
			        $service_id = $service->ID;
			        $city = get_post_meta($service_id,'city',true);
			        ?>
			            <tr>
			                <td><?php echo $service->post_title;?></td>
			                <td><?php echo $service_type;?></td>
			                <td><?php echo $city;?></td>
			               
			            </tr>
			            <?php } ?>
			        </tbody>
			    </table>
			    <?php
			}
			else{
			echo "<h3 style='text-align:center;'>No orders found..</h3>";
			}?>
		    </div>
		    <div id="purchase_history" class="tab-pane fade">
		      <h5>Your Purchase history</h5>
		     <h3 style="text-align:center;">No purchase availble..</h3>
		    </div>
		  </div>
		</div>
	</div>
	</div>
	
<?php get_footer();?>