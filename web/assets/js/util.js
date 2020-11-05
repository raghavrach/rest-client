$(document).on({
    ajaxStart: function(){
        $("body").addClass("loading"); 
    },
    ajaxStop: function(){ 
        $("body").removeClass("loading"); 
    }    
});

/*
 * This function returns Bootstrap alert message box
 * 
 * @param type - Alert type (info, danger, warning)
 * @param message - Message to display
 * @return - HTML alert message
 */
function get_alert_message(type, message)
{
	return '<div class="col-lg-12 text-center"><div class="alert alert-'+type+'">'+message+'</div></div>';
}

/*
 * This function converts form data to Json object
 * 
 * @param $form - Form Object
 * @return indexed_array - Json form data
 */
function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};
    var query_array = {};

    counter = 0;
    $.map(unindexed_array, function(n, i){

    	if(indexed_array[n['name']] === undefined){
    		indexed_array[n['name']] = 0;
    	}

    	key_name = n['name'].replace("[]", "");
    	key_name += "["+indexed_array[n['name']]+"]" 
    	indexed_array[n['name']] += 1;

        query_array[key_name] = n['value'];
        counter++;
    });

    return query_array;
}

/*
 * This function HTML for server data.
 * 
 * @param data - Json server data
 * @return server_data - HTML server data
 */
function createServerTableData(data)
{
	$server_data = "<table class='table table-striped'>\
                    <thead>\
                        <tr>\
                            <th>#</th>\
                            <th>Model</th>\
                            <th>RAM</th>\
                            <th>HDD</th>\
                            <th>Price</th>\
                            <th>Location</th>\
                        </tr>\
                    </thead>\
                    <tbody>";
    $tr_html = '';
    $.each(data, function( key, server_json ) {
        $tr_html = '<tr><td>' + ++key + '</td><td>' + server_json.model + '</td><td>' + server_json.ram + 
        	'</td><td>' + server_json.hdd + '</td><td>' + server_json.price + '</td><td>' + server_json.location + '</td></tr>';
        $server_data += $tr_html;
    });
    $server_data += '</tbody>\
                </table>';
    return $server_data;
}