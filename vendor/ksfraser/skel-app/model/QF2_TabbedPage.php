<?php

require_once 'HTML/QuickForm2.php';
require_once 'HTML/QuickForm2/Controller.php';
require_once 'HTML/QuickForm2/Renderer.php';

// Load some default action handlers
require_once 'HTML/QuickForm2/Controller/Action/Next.php';      //wizard
require_once 'HTML/QuickForm2/Controller/Action/Back.php';      //wizard
require_once 'HTML/QuickForm2/Controller/Action/Submit.php';    //tabbed
require_once 'HTML/QuickForm2/Controller/Action/Jump.php';      //both
require_once 'HTML/QuickForm2/Controller/Action/Direct.php';    //tabbed
require_once 'HTML/QuickForm2/Controller/Action/Display.php';   //both


abstract class TabbedPage extends HTML_QuickForm2_Controller_Page
{
    protected function addTabs()
    {
        $tabGroup = $this->form->addElement('group')->setSeparator('&nbsp;')
                               ->setId('tabs');
        foreach ($this->getController() as $pageId => $page) {
            $tabGroup->addElement('submit', $this->getButtonName($pageId),
                                  array('class' => 'flat', 'value' => ucfirst($pageId)) +
                                  ($page === $this? array('disabled' => 'disabled'): array()));
        }
    }

    protected function addGlobalSubmit()
    {
        $this->form->addElement('submit', $this->getButtonName('submit'),
                                array('value' => 'Submit Data', 'class' => 'bigred'));
        $this->setDefaultAction('submit', 'empty.gif');
    }
}
?>
