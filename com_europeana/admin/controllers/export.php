<?php

defined('_JEXEC') or die('You do not have permission');
jimport('joomla.application.component.controller');

class EuropeanaControllerExport extends JController {
    
    public function cancel() {
        $this->setRedirect('index.php?option=com_europeana');
    }
    
    public function export() {
        echo JText::_('Exporting....');
    }
}
