<?php
/**
 * Funções para instalação e ativação do plugin
 */

// Função de ativação do plugin
function plugin_ativacao() {
    global $wpdb;

    // Nome das tabelas
    $tabela_sellers = $wpdb->prefix . 'sellers';
    $tabela_produtos = $wpdb->prefix . 'produtos';

    // SQL para criar a tabela de sellers
    $sql_sellers = "CREATE TABLE $tabela_sellers (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        dados_bancarios TEXT,
        endereco TEXT,
        PRIMARY KEY (id),
        UNIQUE KEY user_id (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // SQL para criar a tabela de produtos
    $sql_produtos = "CREATE TABLE $tabela_produtos (
        id INT(11) NOT NULL AUTO_INCREMENT,
        seller_id INT(11) NOT NULL,
        nome VARCHAR(255) NOT NULL,
        preco DECIMAL(10, 2) NOT NULL,
        PRIMARY KEY (id),
        KEY seller_id (seller_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    // Executa as queries
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_sellers);
    dbDelta($sql_produtos);

    // Adiciona opções padrão para a chave API e o token
    add_option('pagarme_api_key', '');
    add_option('pagarme_token', '');
}

// Registra a função de ativação
register_activation_hook(__FILE__, 'plugin_ativacao');

?>
