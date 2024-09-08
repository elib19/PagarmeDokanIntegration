<?php
/**
 * Funções para desinstalação do plugin
 */

// Função de desinstalação do plugin
function plugin_desinstalacao() {
    global $wpdb;

    // Nome das tabelas
    $tabela_sellers = $wpdb->prefix . 'sellers';
    $tabela_produtos = $wpdb->prefix . 'produtos';

    // Remove as tabelas
    $wpdb->query("DROP TABLE IF EXISTS $tabela_sellers");
    $wpdb->query("DROP TABLE IF EXISTS $tabela_produtos");

    // Remove as opções
    delete_option('pagarme_api_key');
    delete_option('pagarme_token');
}

// Registra a função de desinstalação
register_uninstall_hook(__FILE__, 'plugin_desinstalacao');

?>
