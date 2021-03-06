<?php
$output = $title = $category_layout = $info_view = $thumb_image = $portfolios_counter = $cat_in = $number = $el_class = $hover_image_class = '';
$classes_arr = $cat_ids_arr = array();

extract( shortcode_atts( array (
    'title' 				=> '',
    'category_layout' 		=> 'stripes',
    'info_view' 			=> '',
	'thumb_image' 			=> '',
	'portfolios_counter' 	=> 'show',
    'cat_in' 				=> '',
    'number' 				=> 5,
    'el_class' 				=> ''
), $atts ) );

if( $title ) {
	$output .= porto_shortcode_widget_title( array( 'title' => $title, 'extraclass' => '' ) );
}

$cat_args = array();
if( $cat_in ){
	$cat_args[ 'orderby' ] = 'include';
	$cat_ids_arr = explode( ',', $cat_in );
	$cat_args[ 'include' ] = $cat_ids_arr;
}
if( $number ){
	$cat_args[ 'number' ] = esc_attr( $number );
}

$cats = get_terms( 'portfolio_cat', $cat_args );

if( $category_layout == 'stripes' ){
	$classes_arr[] = $thumb_image == 'zoom' ? '' : 'thumb-info-'.$thumb_image;
}

switch( $info_view ){
	case 'bottom-info': $classes_arr[] = 'thumb-info-bottom-info'; break;
	case 'bottom-info-dark': $classes_arr[] = 'thumb-info-bottom-info thumb-info-bottom-info-dark'; break;
	default: $classes_arr[] = 'thumb-info-basic-info';	
}

$classes = implode( ' ', $classes_arr );

$el_class = porto_shortcode_extract_class( $el_class );

switch ( $category_layout ) {
	
	case 'stripes':
	
		$items_arr = array( 'items' => 4, 'lg' => 3, 'md' => 2, 'sm' => 1, 'xs' => 1 );
		
		if( $number == 1 || count( $cat_ids_arr ) == 1){
			$items_arr = array( 'items' => 1, 'lg' => 1, 'md' => 1, 'sm' => 1, 'xs' => 1 );
		}
		if( $number == 2 || count( $cat_ids_arr ) == 2){
			$items_arr = array( 'items' => 2, 'lg' => 2, 'md' => 2, 'sm' => 1, 'xs' => 1 );
		}
		if( $number == 3 || count( $cat_ids_arr ) == 3){
			$items_arr = array( 'items' => 3, 'lg' => 3, 'md' => 2, 'sm' => 1, 'xs' => 1 );
		}
		
		$carousel_options = array_merge( $items_arr, array( 'loop' => false, 'dots' => false, 'nav' => true ) );
		
		$output .= '<div class="portfolio-'.$category_layout.' ' . $el_class . '">';
			$output .= '<div class="porto-carousel owl-carousel owl-theme nav-center custom-carousel-arrows-style m-none" data-plugin-options='.json_encode( $carousel_options ) . '>';
				
				foreach( $cats as $cat ){
					
					$cat_id = $cat->term_id; 
					$cat_title = $cat->name;
					$cat_img_id = porto_get_image_id ( esc_url( get_metadata( 'portfolio_cat', $cat_id, 'category_image', true ) ) );
					$cat_img_arr = wp_get_attachment_image_src( $cat_img_id, 'portfolio-cat-stripes' );
					$cat_img_url = $cat_img_arr[0];
					$term = get_term( $cat_id, 'portfolio_cat' );
					$term_count = $term->count;
					
					$output .= '<div>';
						$output .= '<div class="portfolio-item">';
							$output .= '<a href="' . get_term_link( $cat_id ) . '" class="text-decoration-none">';
								$output .= '<span class="thumb-info ' . $classes . '"><span class="thumb-info-wrapper m-none">';
								
									if( $cat_img_url ){
										$output .= '<div class="background-image" style="background-image: url(' . $cat_img_url . ')"></div>';
									}
									
									if( ! $info_view ){ // Basic
									
										$output .= '<span class="thumb-info-title text-capitalize alternative-font font-weight-light">';
											$output .= $cat_title;
										$output .= '</span>';
										
										if( $portfolios_counter == 'show' ){
											$output .= '<span class="thumb-info-icons position-style-1 text-color-light">';
												$output .= '<span class="thumb-info-icon pictures background-color-primary">';
													$output .= $term_count;
													$output .= '<i class="fa fa-picture-o"></i>';
												$output .= '</span>';
											$output .= '</span>';
										}
										
										$output .= '<span class="thumb-info-plus"></span>';
										
									}else{
										
										$output .= '<span class="thumb-info-title">';
											$output .= '<span class="thumb-info-inner">' . $cat_title . '</span>';
											if( $portfolios_counter == 'show' ){
												$output .= '<span class="thumb-info-type">' . sprintf( _n( '%d Portfolio', '%d Portfolios', $term_count, 'porto-shortcodes' ), number_format_i18n( $term_count )).'</span>';
											}
										$output .= '</span>';
									}
									
									
									
								$output .= '</span></span>';
							$output .= '</a>';
						$output .= '</div>';
					$output .= '</div>';
				}
			$output .= '</div>';
		$output .= '</div>'; 
	
		break;
		
	case 'parallax': 
			
			$parallax_options = array( 'speed' => 1.5 );
			
			$output .= '<div class="' . $el_class . '">';
			
			foreach( $cats as $cat ){
				
				$cat_id = $cat->term_id; 
				$cat_title = $cat->name;
				$cat_img_id = porto_get_image_id ( esc_url( get_metadata( 'portfolio_cat', $cat_id, 'category_image', true ) ) );
				$cat_img_arr = wp_get_attachment_image_src( $cat_img_id, 'portfolio-cat-parallax' );
				$cat_img_url = $cat_img_arr[0];
				$term = get_term( $cat_id, 'portfolio_cat' );
				$term_count = $term->count;
				
				
				$output .= '<a href="' . get_term_link( $cat_id ) . '" class="text-decoration-none">';
				
					$output .= '<section class="portfolio-parallax parallax thumb-info section section-text-light section-parallax m-none '.$classes.'" data-plugin-parallax data-plugin-options=' . json_encode( $parallax_options ) . ' data-image-src="' . $cat_img_url . '">';
						$output .= '<div class="container-fluid">';
							
							if( ! $info_view ){ // Basic
							
								$output .= '<h2>' . $cat_title . '</h2>';
								
								if( $portfolios_counter == 'show' ){
									$output .= '<span class="thumb-info-icons position-style-3 text-color-light">';
										$output .= '<span class="thumb-info-icon pictures background-color-primary">';
											$output .= $term_count;
											$output .= '<i class="fa fa-picture-o"></i>';
										$output .= '</span>';
									$output .= '</span>';
								}
								
								$output .= '<span class="thumb-info-plus"></span>';
							
							} else {
								
								$output .= '<span class="thumb-info-title">';
									$output .= '<span class="thumb-info-inner">' . $cat_title . '</span>';
									if( $portfolios_counter == 'show' ){
										$output .= '<span class="thumb-info-type">' . sprintf( _n( '%d Portfolio', '%d Portfolios', $term_count, 'porto-shortcodes' ), number_format_i18n( $term_count ) ). '</span>';
									}
								$output .= '</span>';
								
							}
							
						$output .= '</div>';
					$output .= '</section>';
				
				$output .= '</a>';
				
			}
			
			$output .= '</div>';
			
		break;

}

echo $output;