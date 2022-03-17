<?php


$email = $_POST['email'];
$name = $_POST['name'];
$message = 'Вы получили письмо отправки';
$phone = $_POST['phone'];
$phone_rule = '^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	
		mail($email, 'Отправка', $message);
    	echo "Данные отправлены на вашу почту";
} else {
	echo "Произошла ошибка";
}

$b24Url = "https://b24-z2mudj.bitrix24.ru";	// укажите URL своего Битрикс24
	$b24UserID = "1";						// ID пользователя, от имени которого будем добавлять лид
	$b24WebHook = "mdo4xv73b6gmdpw8";		// код вебхука, который мы только что получили
	
	// формируем URL, на который будем отправлять запрос
	$queryURL = "$b24Url/rest/$b24UserID/$b24WebHook/crm.lead.add.json";	
	
	// формируем параметры для создания лида	
	$queryData = http_build_query(array(
		"fields" => array(
			"TITLE" => "Лид с нашего сайта",	// название лида
			"NAME" => "$name",				// имя ;)
			"PHONE" => array(	// телефон в Битрикс24 = массив, поэтому даже если передаем 1 номер, то передаем его в таком формате
				"n0" => array(
					"VALUE" =>  "$phone",	// ненастоящий номер Меган Фокс
					"VALUE_TYPE" => "MOBILE",			// тип номера = мобильный
				),
			),
		),
		'params' => array("REGISTER_SONET_EVENT" => "Y")	// Y = произвести регистрацию события добавления лида в живой ленте. Дополнительно будет отправлено уведомление ответственному за лид.	
	));

	// отправляем запрос в Б24 и обрабатываем ответ
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $queryURL,
		CURLOPT_POSTFIELDS => $queryData,
	));
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result,1); 
 
	// если произошла какая-то ошибка - выведем её
	if(array_key_exists('error', $result))
	{      
		die("Ошибка при сохранении лида: ".$result['error_description']);
	}
	
	echo "Лид добавлен, отличная работа :)";

?>
