<?php
defined('_JEXEC') or die("Access Denied");
jimport('joomla.application.component.controller');

$controller = JController::getInstance('Europeana');

$controller->execute(JRequest::getCmd('task'));

$controller->redirect();
?>