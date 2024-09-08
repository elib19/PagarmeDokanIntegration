<?php

/**
 * Configura um hook para enviar e-mails diários para sellers com dados bancários incompletos
 */
function cadastra_hook_enviar_email_dados_incompletos_sellers()
{
    if (!wp_next_scheduled('enviar_email_dados_incompletos_sellers')) {
        wp_schedule_event(time(), 'daily', 'enviar_email_dados_incompletos_sellers');
    }
}
add_action('wp', 'cadastra_hook_enviar_email_dados_incompletos_sellers');

/**
 * Envia e-mails diários para sellers com dados bancários incompletos
 */
function enviar_email_dados_incompletos_sellers()
{
    $args = array(
        'meta_key' => 'dados_bancarios',
        'meta_value' => '',
        'meta_compare' => 'NOT EXISTS',
        'fields' => 'ID',
        'number' => -1,
    );

    $sellers = get_users($args);
    foreach ($sellers as $seller_id) {
        $user = get_userdata($seller_id);
        $email = $user->user_email;

        if ($settingsDokanPagarMe['email_admin_quando_seller_dados_incompletos']) {
            wp_mail($email, 'Dados bancários incompletos', 'Olá, os dados bancários do vendedor '.$user->display_name.' estão incompletos. Por favor, atualize as informações.');
        }
    }
}
add_action('enviar_email_dados_incompletos_sellers', 'enviar_email_dados_incompletos_sellers');
?>
