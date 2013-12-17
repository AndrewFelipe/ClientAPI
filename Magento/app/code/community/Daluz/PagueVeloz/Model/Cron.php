<?php

class Daluz_PagueVeloz_Model_Cron
{
	public static function verificaBoletoPago() 
	{
		$boletosPago = array();
        $boletoMethod = Mage::getModel('pagueveloz/boletoMethod');
        
        // @TODO Usar enum/constante para status de "pago"
        $_boletos = Mage::getModel('pagueveloz/boleto')->getCollection()
                    ->addFieldToFilter('status', array('neq' => 'pago'));

        foreach ($_boletos as $_boletoData) 
        {

            $data = new DateTime($_boletoData->getDataVencimento());
            $data = $data->format('Y-m-d'); // FORMAT LIKE > '2013-11-23'

            if(!isset($boletosPago[$data]))
                $boletosPago[$data] = $boletoMethod->getBoletoPago($data);

            if (isset($boletosPago[$data]->Message)) 
            {
                $boletoMethod->log($boletosPago[$data]->Message);
                continue;
            }
            
            try 
            {
                foreach($boletosPago[$data] as $_boleto) 
                {
                    $_order = Mage::getModel('sales/order')->loadByIncrementId($_boleto->SeuNumero);
                    
                    foreach($_boleto->Pagamentos as $_pagamento) 
                    {
                        $_order->setStatus($boletoMethod->getOrderStatus())
                            ->setState($boletoMethod->getOrderStatus())
                            ->addStatusHistoryComment("BOLETO PAGO EM: {$_pagamento->DataProcessamento} | {$_pagamento->Valor} R$")
                            ->save();

                        $_boletoData->setStatus('pago')
                                    ->setValorPago($_pagamento->Valor)
                                    ->setUpdatedTime(Mage::getSingleton('core/date')->gmtDate())
                                    ->save();
                    }

                    $boletoMethod->log("[{$order->getIncrementId()}] Boleto Pago | ID: " . $_boleto['Id'] . " | URL: " . $_boleto['Url']);
                }
            }
            catch (Exception $e)
            {
                $boletoMethod->log($e->getMessage());
            }
        }
	} 
}
?>