<?php
/**
 * The template to display the reviewers star rating in reviews
 *
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $comment;
$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

if ( $rating && get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) { ?>

	<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo esc_attr( $rating ) ?>">
		<span style="width:<?php echo ( esc_attr( $rating ) / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo esc_attr( $rating ); ?></strong> <?php esc_attr_e( 'out of 5', 'woocommerce' ); ?></span>
	</div>

<?php }
