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
//        $doc = JFactory::getDocument();
//        $doc->addScript('components/com_europeana/views/europeana/submitbutton.js');
        
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
        // Title
        JToolBarHelper::title(JText::_('COM_EUROPEANA'),'download-icon-48x48.png');
        //JToolBarHelper::title(JText::_('COM_EUROPEANA_FILE_LIST'));
        JToolBarHelper::custom('europeana.export','export','export','COM_EUROPEANA_TASK_EXPORT',false);
        JToolBarHelper::divider();
        
        if ($filter == '0')
        {
            JToolBarHelper::custom('europeana.remove','trash','trash','JTOOLBAR_TRASH');
        }
        else
        {
            JToolBarHelper::custom('europeana.restore','restore','restore','COM_EUROPEANA_RESTORE');
            if (JFactory::getUser()->authorise('core.admin', 'com_europeana')) 
            {
                JToolBarHelper::custom('europeana.delete','delete','delete','JTOOLBAR_DELETE');
            }
        }
        
        // Options button.
        if (JFactory::getUser()->authorise('core.admin', 'com_europeana')) 
        {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_europeana');
        }
    }
}