<?php
defined('_JEXEC') or die('Access denied');

jimport('joomla.application.component.controller');

class EuropeanaController extends JController {
    
    private $perPage;
    private $limitstart;
    private $pagination;
    
    function __construct() {
        parent::__construct();
        $this->perPage = 5;
        $this->limitstart = JRequest::getInt('limitstart',0);
    }
    
    function display() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::root() . 'media/com_europeana/css/backend-com_europeana.css');
        $doc->addScript(JURI::root() . 'media/com_europeana/js/backend-scripts-com_europeana.js');
        
        JToolBarHelper::title('COM_EUROPEANA','download-icon-48x48.png');
        
        $path = JPATH_ROOT . DS . 'media/com_europeana/xml-files';
        $f = JFolder::files($path, '.xml', false, false);        
        echo JText::_('COM_EUROPEANA_WELCOME');
        JHTML::_('behavior.formvalidation');
        echo '<form action="index.php?option=com_europeana" method="post" name="adminForm" id="adminForm">';
        echo '<table class="adminlist">';
            echo '<thead>';
                echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Nome</th>';
                    echo '<th>Utilizador</th>';
                    echo '<th>Ficheiro</th>';
                    echo '<th>Data</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
        $files = $this->getFilesHistory();
        //id,Full Name, username, filenaem, datetime
        foreach($files as $file) {
                echo '<tr>';
                    echo '<td>' . $file->id . '</td>';
                    echo '<td>' . $file->name . '</td>';
                    echo '<td>' . $file->username . '</td>';
                    echo '<td>' . $file->filename . '</td>';
                    echo '<td>' . $file->datetime . '</td>';
                echo '</tr>'; 
        }
            echo '</tbody>';
            echo '<tfoot>';
                echo '<tr>';
                    echo '<td colspan="5">' . $this->pagination->getListFooter() . '</td>';
                echo '</tr>';
            echo '</tfoot>';
        echo '</table>';
        
        echo '</form>';
    }
    
    function export() {
        echo "Exporting..:";
        $date = new DateTime();
        $this->storeIntoDatabase($date->getTimestamp());
    }
    
    private function storeIntoDatabase($filename) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        
        $query = "INSERT INTO #__europeana_files (`user_id`,`filename`) VALUES ('".$user->id."','". $filename ."')";
        
        $db->setQuery($query);
        $db->query();
        
    }
    
    private function getTotalFiles(){
        $db = JFactory::getDBO();
        $query = 'SELECT f.*, u.username, u.name FROM #__europeana_files AS f LEFT JOIN #__users AS u ON f.user_id = u.id;';
        $db->setQuery($query);
        $db->query();
        $total = $db->getNumRows();
        return $total;
    }
    
    private function getFilesHistory(){
        $db = JFactory::getDBO();
        $query = 'SELECT f.*, u.username, u.name FROM #__europeana_files AS f LEFT JOIN #__users AS u ON f.user_id = u.id LIMIT '.$this->limitstart . ',' . $this->perPage .';';
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        
        $total = $this->getTotalFiles();
        
        jimport('joomla.html.pagination');
        $this->pagination = new JPagination($total,$this->limitstart,$this->perPage);
        
        return $rows;
    }
}