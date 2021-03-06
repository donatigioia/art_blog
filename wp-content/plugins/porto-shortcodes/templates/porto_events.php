<?php 
$output = $event_type = $event_numbers = $event_column = $event_countdown = $el_class = '';

$event_layout = 'list';

extract(shortcode_atts(array(
    'event_type' 		=> '',
	'event_numbers' 	=> '1',
	'event_column' 		=> '',
	'event_countdown' 	=> '',
	'el_class'			=> '',
), $atts));
 
 
switch($event_type)
{
	case "next":
		$event_layout = 'grid';
		$args = 
			array(
				'post_type' 	 	=> 'event',
				'posts_per_page' 	=> '1',
				'post_status'	 	=> 'publish',
				'meta_key'      	=> 'event_start_date',
				'orderby'       	=> 'meta_value',
				'order'				=>  'ASC',
				'meta_query'   	 	=> array(
										'relation'      => 'OR',  	
										array(
										  'key'         => 'event_end_date',
										  'value'       => date_i18n('Y/m/d'),
										  'compare'     => '>=',
										  'type'        => 'date'
										 ),    
				),
		);
		
		break;
		
	case "upcoming":
		$args = 
		array(
			'post_type' 	 	=> 'event',
			'posts_per_page' 	=> $event_numbers,
			'post_status'	 	=> 'publish',
			'meta_key'      	=> 'event_start_date',
  			'orderby'       	=> 'meta_value',
			'order'				=>  'ASC',
			'offset'			=> '1',
			'meta_query'   	 	=> array(
									'relation'      => 'OR',  	
								   array(
									 'key'         => 'event_end_date',
									 'value'       => date_i18n('Y/m/d'),
									 'compare'     => '>=',
									 'type'        => 'date'
									),    
			),
	  	);
		
		
		break;
	
	default :
		$args = 
			array(
				'post_type' 	=> 'event',
				'posts_per_page'=> $event_numbers,
				'post_status'	=> 'publish',
				'meta_key'      => 	'event_start_date',
				'orderby'       => 	'meta_value',
				'order'			=>  'ASC',
				'meta_query'   	=> array(
									'relation'       => 'OR',  	
									array(
									  'key'         => 'event_start_date',
									  'value'       => date_i18n('Y/m/d'),
									  'compare'     => '<',
									  'type'        => 'date'
									 ),    
								),
				);	
}

global $event_countdown_vc;

$event_countdown_vc = $event_countdown;

$event_query = new WP_Query( $args );
$event_count = 0;

if( isset($event_column) && $event_column == '2')
 echo '<div class="row event-row vc-event-row">';

while ( $event_query->have_posts() ) : $event_query->the_post();
	$event_count++;
	if( isset($event_column) && $event_column == '2'):
?>
	<div class="col-md-6 <?php if($event_layout == 'grid') echo 'col-sm-8 col-md-offset-0 col-sm-offset-2 custom-sm-margin-bottom-1 p-b-lg'; ?>">
<?php
	endif;
	get_template_part('content','archive-event-'.$event_layout);
	if( isset($event_column) && $event_column == '2'):
?>
	</div>
<?php
	if($event_count % 2 == 0 && ($event_query->current_post +1) != ($event_query->post_count))
		echo '</div><div class="row event-row vc-event-row">';
	endif;
endwhile;
if( isset($event_column) && $event_column == '2')
	echo '</div>';
$event_countdown_vc = '';

wp_reset_postdata();