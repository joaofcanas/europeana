<?php

defined('_JEXEC') or die('You do not have permission');

?>
<form name="adminForm" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <h3>Export page</h3>
    
    <input type="hidden" name="component" value="com_europeana"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="export"/>
</form>