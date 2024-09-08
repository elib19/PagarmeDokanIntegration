<?php
/**
 * Funções para desinstalação e remoção do plugin
 */

// Função de desinstalação do plugin
function plugin_desativacao() {
    global $wpdb;

    // Nome das tabelas
    $tabela_sellers = $wpdb->prefix . 'sellers';
    $tabela_produtos = $wpdb->prefix . 'produtos';

    // SQL para remover as tabelas
    $sql_remove_sellers = "DROP TABLE IF EXISTS $tabela_sellers;";
    $sql_remove_produtos = "DROP TABLE IF EXISTS $tabela_produtos;";

    // Executa as queries
    $wpdb->query($sql_remove_sellers);
    $wpdb->query($sql_remove_produtos);
}

// Registra a função de desinstalação
register_uninstall_hook(__FILE__, 'plugin_desativacao');

?>
