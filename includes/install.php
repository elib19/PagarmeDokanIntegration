<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Função para executar durante a ativação do plugin
 */
function plugin_ativar() {
    // Criação das opções de configuração do plugin
    add_option('pagarme_api_key', '');
    add_option('pagarme_token', '');

    // Outros procedimentos de instalação, como a criação de tabelas no banco de dados, se necessário
    // Exemplo:
    // global $wpdb;
    // $table_name = $wpdb->prefix . 'example_table';
    // $charset_collate = $wpdb->get_charset_collate();
    // $sql = "CREATE TABLE $table_name (
    //     id mediumint(9) NOT NULL AUTO_INCREMENT,
    //     name varchar(255) DEFAULT '' NOT NULL,
    //     PRIMARY KEY  (id)
    // ) $charset_collate;";
    // require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    // dbDelta( $sql );
}
register_activation_hook(__FILE__, 'plugin_ativar');

/**
 * Função para executar durante a desativação do plugin
 */
function plugin_desativar() {
    // Você pode adicionar funções que devem ser executadas na desativação do plugin aqui
    // Exemplo: Remover tabelas de banco de dados, se necessário
}
register_deactivation_hook(__FILE__, 'plugin_desativar');
