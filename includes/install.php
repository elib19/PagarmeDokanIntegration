<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Função para executar durante a ativação do plugin
 */
function plugin_ativar() {
    global $wpdb;

    // Prefixo da tabela
    $prefixo = $wpdb->prefix;

    // Nome da tabela
    $tabela = $prefixo . 'pagarme_configuracao';

    // SQL para criar a tabela
    $sql = "CREATE TABLE $tabela (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        chave_api varchar(255) DEFAULT '' NOT NULL,
        token varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY (id)
    );";

    // Carrega a função dbDelta
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    // Executa a atualização da tabela
    dbDelta( $sql );

    // Adiciona opções padrão
    add_option('pagarme_api_key', '');
    add_option('pagarme_token', '');
}

register_activation_hook(__FILE__, 'plugin_ativar');
