<?php

/**
 * Observer model
 *
 * @category   DR
 * @package    DR_DuplicateCmsBlock
 * @author     Daniel Rose <daniel-rose@gmx.de>
 * @copyright  Copyright (c) 2016 Daniel Rose
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DR_DuplicateCmsBlock_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function addDuplicateButton(Varien_Event_Observer $observer)
    {
        if ($observer === null || !($observer instanceof Varien_Event_Observer)) {
            return $this;
        }

        $block = $observer->getBlock();

        if ($block === null || !($block instanceof Mage_Adminhtml_Block_Cms_Block_Edit)) {
            return $this;
        }

        $block->addButton('duplicate', array(
            'label'     => Mage::helper('dr_duplicatecmsblock')->__('Duplicate'),
            'onclick'   => 'setLocation(\'' . $block->getUrl('*/*/duplicate', array('_current' => true)) . '\')',
            'class'     => 'add'
        ));

        return $this;
    }
}