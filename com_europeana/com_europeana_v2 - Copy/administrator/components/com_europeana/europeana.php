<?php
// No direct access to this file
defined('_JEXEC') or die("Access Denied");

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Europeana
$controller = JController::getInstance('Europeana');

// Get the task
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', "", 'STR' );

// Perform the Request task
$controller->execute($task);

// Redirect if set by the controller
$controller->redirect();
?>