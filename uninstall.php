<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Função para executar durante a desinstalação do plugin
 */
function plugin_desinstalar() {
    // Remover opções de configuração do plugin
    delete_option('pagarme_api_key');
    delete_option('pagarme_token');

    // Outros procedimentos de desinstalação, como a remoção de tabelas de banco de dados, se necessário
    // Exemplo:
    // global $wpdb;
    // $table_name = $wpdb->prefix . 'example_table';
    // $sql = "DROP TABLE IF EXISTS $table_name;";
    // $wpdb->query($sql);
}
plugin_desinstalar();
