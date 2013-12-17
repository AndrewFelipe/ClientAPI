<?php
/**
 * @category    PagueVeloz
 * @package     Daluz_PagueVeloz
 * @copyright   André Felipe (andrew.daluz@gmail.com)
 */

class Daluz_PagueVeloz_Block_Form_Boleto extends Mage_Payment_Block_Form
{

    protected $_instructions;

    /**
     * Block construction. Set block template.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/pagueveloz_boleto.phtml');
    }

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        if (is_null($this->_instructions)) {
            $this->_instructions = $this->getMethod()->getInstructions();
        }
        return $this->_instructions;
    }

}
