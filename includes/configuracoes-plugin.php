/**
 * Adiciona a página de configurações do plugin ao menu do WordPress
 */
function plugin_adicionar_menu() {
    add_options_page(
        'Configurações do Plugin',
        'Configurações do Plugin',
        'manage_options',
        'configuracoes-plugin',
        'plugin_pagina_configuracoes'
    );
}
add_action('admin_menu', 'plugin_adicionar_menu');

/**
 * Exibe o formulário de configurações do plugin
 */
function plugin_pagina_configuracoes() {
    ?>
    <div class="wrap">
        <h1>Configurações do Plugin</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('plugin_opcoes');
            do_settings_sections('configuracoes-plugin');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Registra as configurações do plugin
 */
function plugin_registrar_configuracoes() {
    register_setting('plugin_opcoes', 'pagarme_api_key');
    register_setting('plugin_opcoes', 'pagarme_token');

    add_settings_section(
        'plugin_secao_configuracao',
        'Configurações da API Pagar.me',
        null,
        'configuracoes-plugin'
    );

    add_settings_field(
        'pagarme_api_key',
        'Chave API',
        'plugin_campo_api_key',
        'configuracoes-plugin',
        'plugin_secao_configuracao'
    );

    add_settings_field(
        'pagarme_token',
        'Token',
        'plugin_campo_token',
        'configuracoes-plugin',
        'plugin_secao_configuracao'
    );
}
add_action('admin_init', 'plugin_registrar_configuracoes');

/**
 * Exibe o campo para a chave API
 */
function plugin_campo_api_key() {
    $api_key = get_option('pagarme_api_key');
    echo '<input type="text" name="pagarme_api_key" value="' . esc_attr($api_key) . '" />';
}

/**
 * Exibe o campo para o token
 */
function plugin_campo_token() {
    $token = get_option('pagarme_token');
    echo '<input type="text" name="pagarme_token" value="' . esc_attr($token) . '" />';
}
