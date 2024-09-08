<?php

/**
 * Exibe uma mensagem de erro se o administrador responsável por receber comissões tiver dados inválidos
 */
function verifica_dados_pagarme_usuario_pagarmedokan()
{
    $user_id = get_current_user_id();
    $dados_bancarios = get_user_meta($user_id, 'dados_bancarios', true);

    if (empty($dados_bancarios)) {
        echo '<div class="notice notice-error"><p>Os dados bancários do administrador não estão completos. Por favor, atualize as informações.</p></div>';
    }
}
add_action('admin_notices', 'verifica_dados_pagarme_usuario_pagarmedokan');
?>
