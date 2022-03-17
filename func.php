<?php


$email = $_POST['email'];
$message = 'Вы получили письмо отправки';
$phone = $_POST['phone'];
$phone_rule = '/^\+7\d{3}\d{2}\d{2}\d{2}$/';

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	if (preg_match($phone_rule, $phone)) {
		mail($email, 'Отправка', $message);
    	echo "Данные отправлены на вашу почту";
	}
		else {
			echo "Введите номер телефона правильно";
		};
} else {
	echo "Произошла ошибка";
};

?>