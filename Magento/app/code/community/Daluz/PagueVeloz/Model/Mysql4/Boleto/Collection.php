<?php

class Daluz_PagueVeloz_Model_Mysql4_Boleto_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
    protected function _construct()
    {
        $this->_init('pagueveloz/boleto');
    }
}