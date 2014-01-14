<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<tr>
    <th width="1%">
        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->files); ?>);" />
    </th>
    <th width="1%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_FILE_ID'); ?>
    </th>                
    <th style="text-align:left;">
        <?php //echo JText::_('COM_EUROPEANA_EUROPEANA_NAME'); ?>
        <?php echo JHTML::_( 'grid.sort', 'COM_EUROPEANA_EUROPEANA_NAME', 'u.name', $this->sortDirection, $this->sortColumn); ?>
    </th>                
    <th width="20%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_FILENAME'); ?>
    </th>
    <th width="10%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_USERNAME'); ?>
    </th>
    <th width="15%">
        <?php //echo JText::_('COM_EUROPEANA_EUROPEANA_DATETIME'); ?>
        <?php echo JHTML::_( 'grid.sort', 'COM_EUROPEANA_EUROPEANA_DATETIME', 'f.datetime', $this->sortDirection, $this->sortColumn); ?>
    </th>
    <th>
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_LOGS'); ?>
    </th>
</tr>