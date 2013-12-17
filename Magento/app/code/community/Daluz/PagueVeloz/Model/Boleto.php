<?php

class Daluz_PagueVeloz_Model_Boleto extends Mage_Core_Model_Abstract {
	
	public function _construct()
    {
        parent::_construct();
        $this->_init('pagueveloz/boleto'); // this is location of the resource file.
    }

    public function saveWithConfigData() 
    {
    	$vencimento = (int) Mage::getModel('pagueveloz/boletoMethod')->getConfig('vencimento');
        $date = date("Y-m-d"); // Data de hoje
        $mod_date = strtotime($date."+ {$vencimento} days"); // Soma dias na data
        $dataVencimento = date("Y-m-d",$mod_date);

        $this->setDataVencimento($dataVencimento);
        $this->setStatus(0);

        return $this->save(); 
    }

    public function loadByOrderId($orderId) 
    {
    	$item = $this->getCollection()->addFieldToFilter('order_id', array('in' => $orderId))->getFirstItem();

    	if($item)
    		return $item;

    	return $this;
    }
}