<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * EuropeanaList Model
 */
class EuropeanaModelEuropeana extends JModelList
{
    public function __construct($config = array()) {
        $config['filter_fields'] = array(
            'f.filename',
            'u.name'
            // ...
        );
        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        $filter = JRequest::getVar('filter','0');
        // Create a new query object.           
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields
        $query->select('f.*, u.username, u.name');
        $query->from('#__europeana_files AS f');
        $query->leftJoin('#__users AS u ON f.user_id = u.id');
        $query->where('f.deleted = ' . $db->quote($filter));
        //$query->order('f.datetime DESC');
        $query->order($db->escape($this->getState('list.ordering', 'f.datetime')).' '.
                $db->escape($this->getState('list.direction', 'DESC')));
        return $query;
    }
    
    /**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
    protected function populateState($ordering = null, $direction = null) {
        parent::populateState('f.datetime','DESC');
    }
}