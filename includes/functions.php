// functions.php

// Recupera a chave API e o token
function get_pagarme_credentials() {
    return [
        'api_key' => get_option('pagarme_api_key'),
        'token' => get_option('pagarme_token')
    ];
}

// Usa as credenciais para se comunicar com a API
function conectar_com_pagarme() {
    $creds = get_pagarme_credentials();
    $api_key = $creds['api_key'];
    $token = $creds['token'];
    
    // Cria instância da API com as credenciais
    $pagarme = new \PagarMe\Sdk\PagarMe($api_key);
    return $pagarme;
}
