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
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_NAME'); ?>
    </th>                
    <th width="20%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_FILENAME'); ?>
    </th>
    <th width="10%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_USERNAME'); ?>
    </th>
    <th width="15%">
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_DATETIME'); ?>
    </th>
    <th>
        <?php echo JText::_('COM_EUROPEANA_EUROPEANA_LOGS'); ?>
    </th>
</tr>