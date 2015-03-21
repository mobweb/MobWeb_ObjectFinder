<?php


class MobWeb_ObjectFinder_Block_Page_Header extends Mage_Adminhtml_Block_Page_Header
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mobweb_objectfinder/page/header.phtml');
    }
}