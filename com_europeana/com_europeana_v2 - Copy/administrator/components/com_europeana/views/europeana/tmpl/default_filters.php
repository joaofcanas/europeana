<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$filter = JRequest::getVar('filter','0');

$estados = array(
    '0' => 'Current',
    '1' => 'Trashed'
);

$options = array();

foreach($estados as $key => $value) {
    $options[] = JHTML::_('select.option', $key, $value);
}

$selectElem = JHTML::_('select.genericlist', $options, 'filter', 'class="inputbox" onchange="Joomla.submitform();"', 'value', 'text', $filter);

echo $selectElem;
?>