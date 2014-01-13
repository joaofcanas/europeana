jq(document).ready(function(){
    Joomla.submitbutton = function(task)
    {
        if (task == '')
        {
            return false;
        }
        else
        {
            var isValid = true;
            var action = task.split('.');
            
            if (action[1] == 'delete')
            {
                var result = confirm(Joomla.JText._('COM_EUROPEANA_THIS_CAN_NOT_BE_UNDONE',
                                         'This can not be undone!!!'));
                if (result == false){
                    isValid = false;
                }
            }

            if (isValid)
            {
                Joomla.submitform(task);
                return true;
            }
            else
            {
                return false;
            }
        }
    }
});
