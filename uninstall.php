<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Função para executar durante a desinstalação do plugin
 */
function plugin_desinstalar() {
    global $wpdb;

    // Prefixo da tabela
    $prefixo = $wpdb->prefix;

    // Nome da tabela
    $tabela = $prefixo . 'pagarme_configuracao';

    // Remove a tabela
    $wpdb->query( "DROP TABLE IF EXISTS $tabela" );

    // Remove as opções
    delete_option('pagarme_api_key');
    delete_option('pagarme_token');
}

plugin_desinstalar();
