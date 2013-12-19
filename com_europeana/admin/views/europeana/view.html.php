<?php

defined('_JEXEC') or die('You do not have permission');
jimport('joomla.application.component.view');

class EuropeanaViewEuropeana extends JView
{
    public function display($tpl = null)
    {
       JToolBarHelper::title('COM_EUROPEANA_VIEW_EUROPEANA_ADMINISTRATOR_PAGE');
       JToolBarHelper::cancel('cancel');
       parent::display($tpl);
    }
}
?>