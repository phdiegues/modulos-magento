<?php
// +----------------------------------------------------------------------+
// | BoletoPhp - Vers�o Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo est� dispon�vel sob a Licen�a GPL dispon�vel pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Voc� deve ter recebido uma c�pia da GNU Public License junto com     |
// | esse pacote; se n�o, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colabora��es de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do	  |
// | PHPBoleto de Jo�o Prado Maia e Pablo Martins F. Costa			       	  |
// | 																	                                    |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordena��o Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto Bradesco: Ramon Soares						            |
// +----------------------------------------------------------------------+

//converte string UTF8 para ISO-8859-1
foreach ($_POST as $key => $value) {
    $_POST[$key] = utf8_decode($value);
}

//  pegando os dados via post
$base_url = $_POST['base_url'];
if (strrpos($base_url, '/') != strlen($base_url) - 1) {
	$base_url .= '/'; 
}

//  pegando os dados via post
$dadosboleto["logo_url"] = $_POST["logo_url"]; 
$dadosboleto["store_url"] = $_POST["store_url"]; 

$dadosboleto["sacado"] = $_POST["cliente_nome"]; 
$dadosboleto["endereco1"] = $_POST["cliente_end"];
$dadosboleto["endereco2"] = $_POST["cliente_cep"]." ".$_POST["cliente_cidade"]." ".$_POST["cliente_uf"];
$dadosboleto["nosso_numero"] = $_POST["ref_transacao"]; 
$valor_cobrado = $_POST["total_pedido"];

// ------------------------- DADOS DIN�MICOS DO SEU CLIENTE PARA A GERA��O DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formul�rio c/ POST, GET ou de BD (MySql,Postgre,etc)	//

$dias_de_prazo_para_pagamento = $_POST["prazo_pagamento"];
$taxa_boleto = $_POST["taxa_boleto"];
$taxa_boleto = str_replace(",", ".",$taxa_boleto);
//$valor_cobrado = "2950,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

// DADOS DO BOLETO PARA O SEU CLIENTE
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 

//$dadosboleto["nosso_numero"] = "5000001";                  // MAGENTO  the order number  (maximum 11)
$dadosboleto["nosso_numero"] = $_POST["ref_transacao"]; 
$dadosboleto["inicio_nosso_numero"] = $_POST["inicio_nosso_numero"];  // Carteira SR: 80, 81 ou 82  -  Carteira CR: 90 (Confirmar com gerente qual usar)
$dadosboleto["numero_documento"] = $dadosboleto["nosso_numero"];
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = $_POST["demonstrativo1"]; // "Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"] = $_POST["demonstrativo2"]; //"Mensalidade referente a nonon nonooon nononon<br>Taxa banc�ria - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"] = $_POST["demonstrativo3"];
if ($dadosboleto["demonstrativo2"] == '') {
	$dadosboleto["demonstrativo2"] = "Taxa banc�ria - R$ " . number_format($taxa_boleto, 2, ',', '');
}
if ($dadosboleto["demonstrativo3"] == '') {
	$dadosboleto["demonstrativo3"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";
}
$dadosboleto["demonstrativo2"] = str_replace('$taxa_boleto', number_format($taxa_boleto, 2, ',', ''), $dadosboleto["demonstrativo2"]);

// INSTRU��ES PARA O CAIXA
$dadosboleto["instrucoes1"] = $_POST["instrucoes1"]; // "- Sr. Caixa, cobrar multa de 2% ap�s o vencimento";
$dadosboleto["instrucoes2"] = $_POST["instrucoes2"]; //"- Receber at� 10 dias ap�s o vencimento";
$dadosboleto["instrucoes3"] = $_POST["instrucoes3"]; //"- Em caso de d�vidas entre em contato conosco: xxxx@xxxx.com.br";
$dadosboleto["instrucoes4"] = $_POST["instrucoes4"]; //"&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

if ($dadosboleto["instrucoes4"] == '') {
	$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";
}

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "001";
$dadosboleto["valor_unitario"] = $valor_boleto;
$dadosboleto["aceite"] = "";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = $_POST["especie"];
$dadosboleto["uso_banco"] = ""; 	

// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //
// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = $_POST["agencia"]; //"1565"; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = $_POST["agencia_dv"];;   
$dadosboleto["conta"] = $_POST["conta"]; //"13877"; 	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $_POST["conta_dv"]; //"4"; 	// Digito do Num da conta

$dadosboleto["conta_cedente"] = $_POST["conta_cedente"]; //"87000000414"; // ContaCedente do Cliente, sem digito (Somente N�meros)
$dadosboleto["conta_cedente_dv"] = $_POST["conta_cedente_dv"]; //"3"; // Digito da ContaCedente do Cliente
$dadosboleto["carteira"] = $_POST["carteira"]; //"SR"; // C�digo da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

// SEUS DADOS
$dadosboleto["identificacao"] = $_POST["identificacao"]; //"BoletoPhp - C�digo Aberto de Sistema de Boletos";
$dadosboleto["cpf_cnpj"] = $_POST["cpf_cnpj"];
$dadosboleto["endereco"] = $_POST["endereco"]; //"Coloque o endere�o da sua empresa aqui";
$dadosboleto["cidade_uf"] = $_POST["cidade_uf"]; //"Cidade / Estado";
$dadosboleto["cedente"] = $_POST["cedente"]; //"Coloque a Raz�o Social da sua empresa aqui";

// N�O ALTERAR!
include("include/funcoes_bradesco.php"); 
include("include/layout_bradesco.php");
?>
