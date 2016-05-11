<?php

require_once('Mage/Adminhtml/controllers/Cms/BlockController.php');

/**
 * Cms manage blocks controller
 *
 * @category   DR
 * @package    DR_DuplicateCmsBlock
 * @author     Daniel Rose <daniel-rose@gmx.de>
 * @copyright  Copyright (c) 2016 Daniel Rose
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DR_DuplicateCmsBlock_Adminhtml_Cms_BlockController extends Mage_Adminhtml_Cms_BlockController
{
    /**
     * Duplicate action
     */
    public function duplicateAction()
    {
        $id = $this->getRequest()->getParam('block_id');

        if (!$id) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dr_duplicatecmsblock')->__('The param "block_id" is not defined.'));
            $this->_redirect('*/*/');
            return;
        }

        $block = Mage::getModel('cms/block')->load($id);

        if (!$block || !$block->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dr_duplicatecmsblock')->__('This block no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $blockData = $block->getData();

        unset($blockData['block_id']);

        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $date = Mage::app()->getLocale()->date()->toString($dateFormat);

        if (array_key_exists('title', $blockData)) {
            $blockData['title'] = $blockData['title'] . ' ' . $date;
        }

        if (array_key_exists('identifier', $blockData)) {
            $blockData['identifier'] = $blockData['identifier'] . ' ' . $date;
        }

        try {
            $duplicateBlock = Mage::getModel('cms/block')
                ->setData($blockData)
                ->save();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('dr_duplicatecmsblock')->__('Unable to duplicate given block.'));
            $this->_redirect('*/*/');
            return;
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('dr_duplicatecmsblock')->__('The block has been duplicated.'));
        $this->_redirect('*/*/edit', array(
            'block_id' => $duplicateBlock->getId(),
            '_current' => true
        ));
        return;
    }
}