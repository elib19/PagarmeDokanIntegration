<?php

/**
 * Exibe o Wizard se for Seller e estiver sem dados bancários ou endereço cadastrados
 */
function wizard_corrigir_dados_bancarios()
{
    if (usuario_is_seller_dokan(get_current_user_id()) && !wp_doing_ajax() && !isset($_GET['wc-ajax'])) {
        if ($_GET['page'] != 'dokan-seller-setup' && !is_admin() && !is_login_page()) {
            if (!validarEnderecoSeller(get_current_user_id())) {
                wp_redirect(home_url().'?page=dokan-seller-setup&step=store', 302);
                exit;
            }
        }

        if ($_GET['page'] != 'dokan-seller-setup' && !is_admin() && !is_login_page()) {
            if (!validarDadosPagamentoSeller(get_current_user_id())) {
                wp_redirect(home_url().'?page=dokan-seller-setup&step=payment', 302);
                exit;
            }
        }
    }
}
add_action('init', 'wizard_corrigir_dados_bancarios', -1);

/**
 * Roda quando salva o endereço em ?page=dokan-seller-setup&step=store
 */
function salvar_endereco()
{
    if (!validarEnderecoSeller(get_current_user_id())) {
        wp_redirect(home_url().'?page=dokan-seller-setup&step=store', 302);
        exit;
    }
}
add_action('dokan_seller_wizard_store_field_save', 'salvar_endereco');

/**
 * Roda quando salva os dados bancários em ?page=dokan-seller-setup&step=payment
 */
function salvar_dados_bancarios()
{
    PreCriarContaBancoSellerPagarme(get_current_user_id());

    if (!validarDadosPagamentoSeller(get_current_user_id())) {
        wp_redirect(home_url().'?page=dokan-seller-setup&step=payment', 302);
        exit;
    }

    require_once('vendor/autoload.php');
    $pagarme_options = get_option('woocommerce_pagarme-credit-card_settings');
    $apiKey = isset($pagarme_options['api_key']) ? $pagarme_options['api_key'] : '';
    
    $pagarMe = new \PagarMe\Sdk\PagarMe($apiKey);

    $bankAccount = get_user_meta(get_current_user_id(), 'conta_bancaria_pagarme');
    $bankAccount = $pagarMe->bankAccount()->get($bankAccount[0]);
    
    $recipient = $pagarMe->recipient()->create(
        $bankAccount,
        'daily',
        0,
        true
    );
    
    $idRecipiente = $recipient->getId();
    if (isset($idRecipiente)) {
        if (empty(get_user_meta(get_current_user_id(), 'id_recipiente_pagarme'))) {
            add_user_meta(get_current_user_id(), 'id_recipiente_pagarme', $idRecipiente);
        } else {
            update_user_meta(get_current_user_id(), 'id_recipiente_pagarme', $idRecipiente);
        }
    } else {
        update_user_meta(get_current_user_id(), 'id_recipiente_pagarme', null);
    }
}
add_action('dokan_seller_wizard_payment_field_save', 'salvar_dados_bancarios');

/**
 * Função auxiliar para informar se um UserID é um seller de dokan
 */
function usuario_is_seller_dokan($user_id)
{
    return user_can($user_id, 'dokandar');
}
?>
