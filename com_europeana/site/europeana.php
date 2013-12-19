<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import joomla controller library
jimport('joomla.application.component.controller');

$doc = JFactory::getDocument();
$doc->addScript('/components/com_europeana/js/jquery.js');
$doc->addScript('/components/com_europeana/js/com_europeana_scripts.js');
$doc->addStyleSheet('/components/com_europeana/css/com_europeana_layout.css');

// Get an instance of the controller prefixed by Europeana
$controller = JController::getInstance('Europeana');

// Perform the Request task
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>