<?php 

function call($method, $parameters, $url)
    {
        ob_start();
        $curl_request = curl_init();

        curl_setopt($curl_request, CURLOPT_URL, $url);
        curl_setopt($curl_request, CURLOPT_POST, 1);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, 1);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

        $jsonEncodedData = json_encode($parameters);

        $post = array(
             "method" => $method,
             "input_type" => "JSON",
             "response_type" => "JSON",
             "rest_data" => $jsonEncodedData
        );

        curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl_request);
        curl_close($curl_request);

        $result = explode("\r\n\r\n", $result, 2);
        $response = json_decode($result[1], true);
        ob_end_flush();

        return $response;
    }

function login($username, $password, $url)
{
	$login_parameters = array(
         "user_auth" => array(
              "user_name" => $username,
              "password" => md5($password),
              "version" => "1"
         ),
         "application_name" => "RestTest",
         "name_value_list" => array(),
    );

    $login_result = call("login", $login_parameters, $url);
	return $login_result['id']; //session id
}

function get_entity($session_id, $module_name, $url)
{
	$get_entry_list_parameters = array(

         //session id
         'session' => $session_id,

         //The name of the module from which to retrieve records
         'module_name' => $module_name,

         //The SQL WHERE clause without the word "where".
         'query' => "",

         //The SQL ORDER BY clause without the phrase "order by".
         'order_by' => "",

         //The record offset from which to start.
         'offset' => '0',

         //Optional. A list of fields to include in the results.
         'select_fields' => array(
              'id',
              'name',
              'title',
         ),
		 'link_name_to_fields_array' => array(
         ),

         //The maximum number of results to return.
         'max_results' => '10',

         //To exclude deleted records
         'deleted' => '0',

         //If only records marked as favorites should be returned.
         'Favorites' => false,
    );
	$get_entry_list_result = call('get_entry_list', $get_entry_list_parameters, $url);

	$result = array();

	foreach($get_entry_list_result['entry_list'] as $key => $field)
	{
		foreach($field['name_value_list'] as $k=>$v)
		{
			$result[$key][$k] = $v['value'];
		}
	}
	
	return $result;
}