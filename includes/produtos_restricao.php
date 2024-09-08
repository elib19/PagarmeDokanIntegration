<?php
/**
 * Funções para lidar com a separação do frete por vendedor e restrição de produtos
 */

/**
 * Exibe produtos apenas de sellers com dados bancários válidos
 *
 * @param bool $visible Status de visibilidade do produto.
 * @param int $this_get_id ID do produto.
 * @return bool
 */
function listagem_de_produtos_woocommerce($visible, $this_get_id)
{
    $produto = wc_get_product($this_get_id);
    return validarSeller($produto->post->post_author);
}

/**
 * Redireciona o usuário se tentar visualizar um produto de um seller com dados incompletos
 */
function single_page_produto_woocommerce()
{
    $produto = wc_get_product(get_the_ID());
    if (!validarSeller($produto->post->post_author)) {
        echo '<script>window.location = "'.home_url().'?problemasVendedor='.$produto->post->post_author.'"</script>';
    }
}

// Adiciona filtros para a listagem e a página única dos produtos
add_filter('woocommerce_product_is_visible', 'listagem_de_produtos_woocommerce', 10, 2);
add_filter('woocommerce_single_product_summary', 'single_page_produto_woocommerce', 12, 1);

/**
 * Impede que um comprador adicione no carrinho produtos de um seller que está com dados incompletos
 *
 * @param bool $passed Status de validação.
 * @param int $product_id ID do produto.
 * @param int $quantity Quantidade.
 * @param int $variation_id ID da variação.
 * @param array $variations Variações.
 * @return bool
 */
function proibe_add_carrinho_produto_seller_incompleto($passed, $product_id, $quantity, $variation_id = '', $variations = '')
{
    $produto = wc_get_product($product_id);
    return validarSeller($produto->post->post_author);
}

// Adiciona a função de validação ao adicionar ao carrinho
if ($settingsDokanPagarMe['proibe_add_carrinho_produto_seller_incompleto']) {
    add_action('woocommerce_add_to_cart_validation', 'proibe_add_carrinho_produto_seller_incompleto', 10, 5);
}

/**
 * Valida se o seller tem dados bancários e endereço completos
 *
 * @param int $seller_id ID do vendedor.
 * @return bool
 */
function validarSeller($seller_id)
{
    return validarEnderecoSeller($seller_id) && validarDadosPagamentoSeller($seller_id);
}

/**
 * Função auxiliar para validar endereço do seller
 *
 * @param int $user_id ID do usuário.
 * @return bool
 */
function validarEnderecoSeller($user_id)
{
    // Aqui você deve implementar a lógica para validar o endereço do vendedor
    return true;
}

/**
 * Função auxiliar para validar dados bancários do seller
 *
 * @param int $user_id ID do usuário.
 * @return bool
 */
function validarDadosPagamentoSeller($user_id)
{
    // Aqui você deve implementar a lógica para validar os dados bancários do vendedor
    return true;
}

/**
 * Função auxiliar para notificar sobre problemas com o vendedor
 */
function aviso_problema_vendedor()
{
    if (isset($_GET['problemasVendedor']) && !wp_doing_ajax() && !isset($_GET['wc-ajax'])) {

        $vendedor = get_userdata($_GET['problemasVendedor']);
        $email_pagarmedokan = get_bloginfo('admin_email');

        // Envia um email para o dono do site avisando
        if ($settingsDokanPagarMe['email_admin_quando_visualizar_produto_invalido']) {
            wp_mail($email_pagarmedokan, 'Um comprador tentou visualizar o produto de um vendedor com dados incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Alguém tentou visualizar um produto no '.$settingsDokanPagarMe['nome_site'].', porém não conseguiu concretizar a compra pois os dados de endereço e/ou bancários do vendedor estão incompletos em nosso site.<br><br>ID do Vendedor: '.$_GET['problemasVendedor'].'<br>Nome do Vendedor: '.$vendedor->first_name.' '.$vendedor->last_name.'<br>E-mail do Vendedor: '.$vendedor->user_email.'<br><br>O vendedor acaba de ser notificado por email.');
        }

        // Envia um email para o vendedor
        if ($settingsDokanPagarMe['email_seller_quando_visualizar_produto']) {
            wp_mail($vendedor->user_email, 'Dados de pagamento e/ou endereço incompletos no '.$settingsDokanPagarMe['nome_site'], 'Olá. Alguém tentou visualizar um dos seus produtos no '.$settingsDokanPagarMe['nome_site'].', porém não conseguiu concretizar a compra pois seus dados de endereço e/ou bancários estão incompletos em nosso site. Para poder vender, por favor, complete seu cadastro clicando neste link: <a href="'.home_url().'?page=dokan-seller-setup&step=store">'.home_url().'?page=dokan-seller-setup&step=store</a>');
        }

        echo '<script>
                alert("Desculpe, você tentou visualizar um produto que está temporariamente desabilitado.");
                window.location = "'.home_url().'"
              </script>';
    }
}
add_filter('init', 'aviso_problema_vendedor', 10, 1);
?>
