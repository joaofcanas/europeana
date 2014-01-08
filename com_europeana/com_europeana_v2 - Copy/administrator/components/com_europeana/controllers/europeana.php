<?php
defined('_JEXEC') or die('Access denied');

jimport('joomla.application.component.controller');

class EuropeanaControllerEuropeana extends JController {
    
    /**
    * Setting the toolbar
    */
    protected function addToolBar() 
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root() . 'media/com_europeana/css/backend-com_europeana.css');
        $doc->addScript(JURI::root() . 'media/com_europeana/js/backend-scripts-com_europeana.js');
        
        JToolBarHelper::title('COM_EUROPEANA_FILE_LIST','download-icon-48x48.png');
//      JToolBarHelper::title(JText::_('COM_EUROPEANA_FILE_LIST'));
        JToolBarHelper::back();
    }
    
    function export(){
        $this->addToolBar();
        echo '<h3>';
        echo JText::_('COM_EUROPEANA_TASK_EXPORT');
        echo '</h3>';
        
        $date =& JFactory::getDate();
        $fileName = $date->toFormat('%Y%m%d_%H%M%S');
        
        $this->storeIntoDatabase($fileName);
    }
    
    private function storeIntoDatabase($filename) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $filename .= '_' . $user->username;
        
        $query = "INSERT INTO #__europeana_files (`user_id`,`filename`) VALUES ('".$user->id."','". $filename ."')";
        
        $db->setQuery($query);
        $db->query();
        
    }
}