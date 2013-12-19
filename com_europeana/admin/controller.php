<?php

defined('_JEXEC') or die('You do not have permission');
jimport('joomla.application.component.controller');

class EuropeanaController extends JController {
    
    public function display($cachable = false, $urlparams = false)
    {
        $view = JRequest::getVar('view');
        
        JToolBarHelper::title('COM_EUROPEANA', 'generic');
        JToolBarHelper::custom('export', 'export', 'export', 'COM_EUROPEANA_EXPORT');
        
        if ($view == 'export')
        {
            JSubMenuHelper::addEntry('Europeana', 'index.php?option=com_europeana');
            JSubMenuHelper::addEntry('Export', 'index.php?option=com_europeana&view=export',true);
        }
        else
        {
            JSubMenuHelper::addEntry('Europeana', 'index.php?option=com_europeana',true);
            JSubMenuHelper::addEntry('Export', 'index.php?option=com_europeana&view=export');
        }
        
        parent::display();
    }
    
    public function export()
    {
        //$this->setRedirect('index.php?option=com_europeana&view=export');
        echo JText::_('COM_EUROPEANA_TASK_EXPORT');
    }
    
    public function cancel() {
        //$this->setRedirect('index.php');
    }
}
