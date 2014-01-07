<?php

defined('_JEXEC') or die('You do not have permission');
jimport('joomla.application.component.view');

class EuropeanaViewExport extends JView
{
    public function display($tpl = null)
    {
        JToolBarHelper::title('COM_EUROPEANA_VIEW_EXPORT_ADMINISTRATOR_PAGE');
        JToolBarHelper::cancel('export.cancel');
        JToolBarHelper::custom('export.export','export','export','Export',false,false);
        parent::display($tpl);
    }
}
?>