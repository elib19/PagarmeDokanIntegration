<?php
/**
 * Install script for the plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function my_plugin_install() {
    global $wpdb;

    // Define o nome da tabela com prefixo do WordPress
    $table_name = $wpdb->prefix . 'my_plugin_table';

    // Verifica se a tabela já existe
    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

        // SQL para criar a tabela
        $sql = "CREATE TABLE {$table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            seller_id mediumint(9) NOT NULL,
            data text NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) {$wpdb->get_charset_collate()};";

        // Inclui a função dbDelta que lida com a criação de tabelas
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    // Adiciona uma opção inicial
    add_option( 'my_plugin_option', 'default_value' );

    // Configura roles e capabilities
    $role = get_role( 'administrator' );
    if ( $role ) {
        $role->add_cap( 'manage_my_plugin' );
    }
}

// Registra a função de instalação para rodar quando o plugin for ativado
register_activation_hook( __FILE__, 'my_plugin_install' );
