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
        JToolBarHelper::title('COM_EUROPEANA','download-icon-48x48.png');
        //JToolBarHelper::title(JText::_('COM_EUROPEANA_FILE_LIST'));
        JToolBarHelper::custom('europeana.export','export','export','COM_EUROPEANA_TASK_EXPORT',false);
    }
}