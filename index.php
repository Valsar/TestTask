<?php

    $url = "http://sugarsrm.gnome.esy.es/service/v4_1/rest.php";
    $username = "admin";
    $password = "qwerty";

	require_once("function.php");
	
	$session_id = login($username, $password, $url);

	$entities['Leads'] = get_entity($session_id, 'Leads', $url);
	$entities['Contacts'] = get_entity($session_id, 'Contacts', $url);
	$entities['Accounts'] = get_entity($session_id, 'Accounts', $url);
	$entities['Tasks'] = get_entity($session_id, 'Tasks', $url);
	$entities['Opportunities'] = get_entity($session_id, 'Opportunities', $url);
	$entities['Users'] = get_entity($session_id, 'Users', $url);
	
	echo '<pre>'; print_r($entities); echo '</pre>';
