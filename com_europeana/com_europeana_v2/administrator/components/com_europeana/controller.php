<?php
defined('_JEXEC') or die('Access denied');

jimport('joomla.application.component.controller');

class EuropeanaController extends JController {
    
    function display() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root() . 'media/com_europeana/css/backend-com_europeana.css');
        $doc->addScript(JURI::root() . 'media/com_europeana/js/backend-scripts-com_europeana.js');
        
        JToolBarHelper::title('COM_EUROPEANA','download-icon-48x48.png');
        
        echo JText::_('COM_EUROPEANA_WELCOME');
    }
    
    function export() {
        echo "Exporting..:";
    }
}