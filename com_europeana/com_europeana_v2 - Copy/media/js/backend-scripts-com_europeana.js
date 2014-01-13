var jq = jQuery.noConflict();
jq(document).ready(function(){
    if (jq('#adminform :input')){
    	var $inputs = jq('#adminform :input')
    	var values = {};
    	$inputs.each(function(){
            values[this.name] = this.value;
            if (this.name == 'task')
            {
                jq('input[name="'+this.name+'"]').val('');
            }
            //jq('input[name="'+this.name+'"]').val('');
    	});
    }
});