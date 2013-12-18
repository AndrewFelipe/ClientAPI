<?php

class PagueVeloz_Boleto_Model_BoletoMethod extends Mage_Payment_Model_Method_Banktransfer {
    
    const PAGUEVELOZ_URL_STAGING = 'http://pagueveloz.homolog.bludata.net/api';
    const PAGUEVELOZ_URL_PRODUCTION = 'https://api.pagueveloz.com.br/';
    
    const PAYMENT_METHOD_BANKTRANSFER_CODE = 'pagueveloz_boleto';
    protected $_code = self::PAYMENT_METHOD_BANKTRANSFER_CODE;

    protected $_formBlockType = 'pagueveloz/form_boleto';
    protected $_infoBlockType = 'pagueveloz/info_boleto';

    public function getInstructions() {
        return $this->getConfig('instruction1') . "<br>" . $this->getConfig('instruction2');
    }

    public function getIsProduction() {
    	return Mage::getStoreConfig('payment/pagueveloz_boleto/production');
    }

    public function getOrderStatus() {
    	return Mage::getStoreConfig('payment/pagueveloz_boleto/order_status');
    }

    public function log($msg) {
    	Mage::log($msg, null, $this->_code . '.log');
    }

    public function getPaguevelozUrl() {
    	if($this->getIsProduction())
    		return self::PAGUEVELOZ_URL_PRODUCTION;

   		return self::PAGUEVELOZ_URL_STAGING;
    }

    public function getEmail() {
    	return Mage::getStoreConfig('payment/pagueveloz_boleto/email');
    }

    public function getToken() {
    	return Mage::getStoreConfig('payment/pagueveloz_boleto/token');
    }

    public function getConfig($key) {
    	return Mage::getStoreConfig("payment/{$this->_code}/{$key}");
    }

    public function generateBoletoUrl($valor, $seuNumero, $nome, $cpf) {
		$boleto = new PagueVeloz_Boleto_Model_Api_Boleto(Mage::getModel('pagueveloz/dto_emailDTO', $this->getEmail()), $this->getToken());
		$dto = Mage::getModel('pagueveloz/dto_boletoDTO');

        $vencimento = (int) $this->getConfig('vencimento');
        $date = date("Y-m-d"); // Data de hoje
        $mod_date = strtotime($date."+ {$vencimento} days"); // Soma dias na data
        $dataVencimento = date("Y-m-d",$mod_date);

        $dto->setVencimento($dataVencimento);
        $dto->setValor($valor); 
        $dto->setSeuNumero($seuNumero);
        $dto->setSacado($nome);
        $dto->setCpfCnpjSacado($cpf);
        $dto->setParcela(1);
        $dto->setLinha1($this->getConfig('instruction1'));
        $dto->setLinha2($this->getConfig('instruction1'));
        $dto->setCpfCnpjCedente($this->getConfig('taxvat'));
        $dto->setCedente($this->getConfig('cedente_name'));
		$resposta_final = $boleto->Post($dto);

        $url = $resposta_final->getBody();
        if(stripos($url, 'Erro') !== false)
            $url = "";

		return $url;
	} 

	public function getBoletoPago($date) {
		$boleto = new PagueVeloz_Boleto_Model_Api_Boleto(Mage::getModel('pagueveloz/dto_emailDTO', $this->getEmail()), $this->getToken());
		
		$dto = Mage::getModel('pagueveloz/dto_boletoDTO');
		$dto->setData($date);
		$resposta_final = $boleto->Get($dto);

		return json_decode($resposta_final->getBody());
	}
}
?>