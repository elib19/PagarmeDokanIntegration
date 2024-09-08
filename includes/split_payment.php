<?php

/**
 * Calcula o valor a ser pago a cada seller
 */
function pegarValorDeCadaSellerPorVenda($order_id)
{
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    $valor_total = 0;
    $split = array();

    foreach ($items as $item_id => $item) {
        $product_id = $item->get_product_id();
        $vendor_id = get_post_field('post_author', $product_id);
        $valor_item = $item->get_total();
        $valor_total += $valor_item;

        if (!isset($split[$vendor_id])) {
            $split[$vendor_id] = 0;
        }
        $split[$vendor_id] += $valor_item;
    }

    return array(
        'valor_total' => $valor_total,
        'split' => $split,
    );
}

/**
 * Prepara o array de split payment para o Pagar.me
 */
function retornaPagarMeSplitArray($order_id)
{
    $valor = pegarValorDeCadaSellerPorVenda($order_id);
    $split = array();

    foreach ($valor['split'] as $seller_id => $valor) {
        $split[] = array(
            'recipient' => get_user_meta($seller_id, 'id_recipiente_pagarme', true),
            'amount' => $valor * 100, // Converte para centavos
            'charge_processing_fee' => true,
            'liable' => true,
        );
    }

    return $split;
}

/**
 * Adiciona as regras de split payment às transações do Pagar.me
 */
function wc_pagarme_slip_rules($order_id)
{
    $order = wc_get_order($order_id);
    $split_array = retornaPagarMeSplitArray($order_id);

    require_once('vendor/autoload.php');
    $pagarme_options = get_option('woocommerce_pagarme-credit-card_settings');
    $apiKey = isset($pagarme_options['api_key']) ? $pagarme_options['api_key'] : '';
    
    $pagarMe = new \PagarMe\Sdk\PagarMe($apiKey);

    $transaction = $pagarMe->transaction()->create(array(
        'amount' => $order->get_total() * 100, // Converte para centavos
        'payment_method' => 'boleto',
        'split_rules' => $split_array,
        'capture' => true,
        'postback_url' => home_url('/pagarme_postback')
    ));

    if ($transaction) {
        update_post_meta($order_id, '_pagarme_transaction_id', $transaction->getId());
    }
}
add_action('woocommerce_order_status_processing', 'wc_pagarme_slip_rules');
?>
