<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = wc_get_account_payment_methods_types();

do_action( 'woocommerce_before_account_payment_methods', $has_methods ); ?>

<?php if ( $has_methods ) : ?>

    <table class="woocommerce-MyAccount-paymentMethods shop_table shop_table_responsive account-payment-methods-table">
        <thead>
        <tr>
            <?php foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name ) : ?>
                <th class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $column_id ); ?> payment-method-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <?php foreach ( $saved_methods as $type => $methods ) : ?>
            <?php foreach ( $methods as $method ) : ?>
                <tr class="payment-method<?php echo ! empty( $method['is_default'] ) ? ' default-payment-method' : '' ?>">
                    <?php foreach ( wc_get_account_payment_methods_columns() as $column_id => $column_name ) : ?>
                        <td class="woocommerce-PaymentMethod woocommerce-PaymentMethod--<?php echo esc_attr( $column_id ); ?> payment-method-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                            <?php
                            if ( has_action( 'woocommerce_account_payment_methods_column_' . $column_id ) ) {
                                do_action( 'woocommerce_account_payment_methods_column_' . $column_id, $method );
                            } else if ( 'method' === $column_id ) {
                                if ( ! empty ( $method['method']['last4'] ) ) {
                                    echo sprintf( __( '%s ending in %s', 'woocommerce' ), esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) ), esc_html( $method['method']['last4'] ) );
                                } else {
                                    echo esc_html( wc_get_credit_card_type_label( $method['method']['brand'] ) );
                                }
                            } else if ( 'expires' === $column_id ) {
                                echo esc_html( $method['expires'] );
                            } else if ( 'actions' === $column_id ) {
                                foreach ( $method['actions'] as $key => $action ) {
                                    echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>&nbsp;';
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>

<?php else : ?>

    <p class="woocommerce-Message woocommerce-Message--info"><?php esc_html_e( 'No saved methods found.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>

<a class="button btn-lg m-b" href="<?php echo esc_url( wc_get_endpoint_url( 'add-payment-method' ) ); ?>"><?php esc_html_e( 'Add Payment Method', 'woocommerce' ); ?></a>