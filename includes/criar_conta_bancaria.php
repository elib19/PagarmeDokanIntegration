<?php

/**
 * Pré-processa os dados bancários do vendedor e cria uma conta no Pagar.me.
 */
function PreCriarContaBancoSellerPagarme($seller)
{
    // Dados bancários padrão
    $bank_code = '';
    $agencia = '';
    $agencia_dv = '';
    $conta = '';
    $conta_dv = '';
    $type = '';
    $document_number = '';
    $legal_name = '';

    // Obtém os dados do vendedor
    $data = get_userdata($seller);
    $dados_bancarios = get_user_meta($seller, 'dados_bancarios', true);

    // Verifica se os dados bancários foram fornecidos
    if (empty($dados_bancarios)) {
        return false;
    }

    foreach ($dados_bancarios as $key => $value) {
        switch ($key) {
            case 'bank_code':
                $bank_code = $value;
                break;
            case 'agencia':
                $agencia = $value;
                break;
            case 'agencia_dv':
                $agencia_dv = $value;
                break;
            case 'conta':
                $conta = $value;
                break;
            case 'conta_dv':
                $conta_dv = $value;
                break;
            case 'type':
                $type = $value;
                break;
            case 'document_number':
                $document_number = $value;
                break;
            case 'iban':
                $legal_name = substr($value, 0, 30);
                break;
            case 'swift':
                $value = ''; // Campo não utilizado
                break;
            default:
                return false;
        }
    }

    // Cria a conta bancária no Pagar.me
    return criarContaBancoSellerPagarMe($seller, $bank_code, $agencia, $agencia_dv, $conta, $conta_dv, $type, $document_number, $legal_name);
}

/**
 * Cria uma conta bancária para um seller no Pagar.me
 */
function criarContaBancoSellerPagarMe($seller, $bank_code, $agencia, $agencia_dv, $conta, $conta_dv, $type, $document_number, $legal_name)
{
    require_once('vendor/autoload.php');
    $pagarme_options = get_option('woocommerce_pagarme-credit-card_settings');
    
    // Obtém a API Key
    $apiKey = isset($pagarme_options['api_key']) ? $pagarme_options['api_key'] : 'ak_test_flvf2vgyplmI0oEhA9tHPuODUKhcTF';
    
    $pagarMe = new \PagarMe\Sdk\PagarMe($apiKey);
    
    $bankAccount = $pagarMe->bankAccount()->create(
        $bank_code,
        $agencia,
        $conta,
        $conta_dv,
        $document_number,
        $legal_name,
        $agencia_dv,
        $type
    );
    
    $idBankAccount = $bankAccount->getId();
    if (is_numeric($idBankAccount)) {
        if (empty(get_user_meta($seller, 'conta_bancaria_pagarme'))) {
            add_user_meta($seller, 'conta_bancaria_pagarme', $idBankAccount);
        } else {
            update_user_meta($seller, 'conta_bancaria_pagarme', $idBankAccount);
        }
    } else {
        update_user_meta($seller, 'conta_bancaria_pagarme', null);
    }
}
?>
