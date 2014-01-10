<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Europeana View
 */
class EuropeanaViewEuropeana extends JView
{
    /**
     * Europeana view display method
     * @return void
     */
    function display($tpl = null) 
    {
        // Get data from the model
        $files = $this->get('Items');
        $pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign data to the view
        $this->files = $files;
        $this->pagination = $pagination;
        
        // Set the toolbar
        $this->addToolBar();
                
        // Display the template
        parent::display($tpl);
    }
    
    /**
    * Setting the toolbar
    */
    protected function addToolBar() 
    { 
        $filter = JRequest::getVar('filter','0');
        $user = JFactory::getUser();
        

        
        
        JToolBarHelper::title(JText::_('COM_EUROPEANA'),'download-icon-48x48.png');
        //JToolBarHelper::title(JText::_('COM_EUROPEANA_FILE_LIST'));
        if ($filter == '0')
            JToolBarHelper::custom('europeana.remove','delete','delete','JTOOLBAR_DELETE');
        else
            JToolBarHelper::custom('europeana.restore','restore','restore','COM_EUROPEANA_RESTORE');
        JToolBarHelper::custom('europeana.export','export','export','COM_EUROPEANA_TASK_EXPORT',false);
    }
}