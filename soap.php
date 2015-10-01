<?php

error_reporting(E_ALL);

ini_set('log_errors', 'On');
ini_set('error_log', 'error.log');

header('Content-Type: text/html; charset=utf-8');

class Engine {    //Путь к WSDL схеме
    private $wsdl = 'http://api.starliner.ru/Api/connect/TrainAPI?wsdl';
    //Учетные данные
	private $auth = array('login'        => 'test',
						  'psw'          => 'test',
						  'terminal'     => 'test',
						  'represent_id' => 'test');

    //Функция для обработки POST данных переданных пользователем
	private function str($str) {
		$str = trim($str);
		$str = stripslashes($str);
		$str = htmlspecialchars($str);
		return $str;
	}

	private function soap($train, $array) {
		try {
			//Создание SOAP-клиента по WSDL-документу
			$client = new SoapClient($this->wsdl);
			//Поcылка SOAP-запроса и получение результата
			$result = $client->trainRoute($this->auth, $train, $array);

			return $result;

		} catch(Exception $e) {
            //Исключение, если произошла ошибка (не верный пароль, логин или номер поезда)
            //вывод этой ошибки и завершение работы сценария
            $error = array('status' => 'error', 'message' => $e->getMessage());
    		exit(json_encode($error));
		}
	}

    //Функция для проверки данных переданных пользователем
	private function checkError() {
		$error = array();

		if (empty($_POST['train'])) $error[] = 'Не указан номер поезда!';
		if (empty($_POST['from'])) $error[] = 'Не указана станция отправления!';
		if (empty($_POST['to'])) $error[] = 'Не указана станция прибытия!';
		if (empty($_POST['day']) || $this->str($_POST['day']) < 1 || $this->str($_POST['day']) > 31) $error[] = 'Не верно указана дата прибытия!';
		if (empty($_POST['month']) || $this->str($_POST['month']) < 1 || $this->str($_POST['month']) > 12) $error[] = 'Не верно указан месяц прибытия!';

	    if (count($error) > 0) {	    	//Если ошибки есть, выведем их в формате JSON и остановим работу сценария
	    	$error = array('status' => 'error', 'message' => implode(',', $error));
	        exit(json_encode($error));
	    } else {	    	return true;
	    }
	}

	public function index() {        //Перед отправкой SOAP запроса проверим данные отправленные пользователем
        //Если имеются ошибки, сценарий остановится и отобразит их в формате JSON
		$this->checkError();
		$array = array('from'  => $this->str($_POST['from']),
					   'to'    => $this->str($_POST['to']),
					   'day'   => $this->str($_POST['day']),
					   'month' => $this->str($_POST['month']));

        //Отправим запрос на создание SOAP клиента и посылки
		$result = $this->soap($this->str($_POST['train']), $array);
        $count = count($result->route_list->stop_list)-1;

        $stop_list = array();

		for ($i = 0; $i <= $count; $i++) {            //Формируем массив следования поезда (остановки)
			$stop_list[] = array('stop'           => $result->route_list->stop_list[$i]->stop,
								 'arrival_time'   => $result->route_list->stop_list[$i]->arrival_time,
								 'departure_time' => $result->route_list->stop_list[$i]->departure_time,
								 'stop_time'      => $result->route_list->stop_list[$i]->stop_time);
		}

		$json = array('status'    => 'success',
					  'number'    => $result->train_description->number, //Номер поезда
					  'from'      => $result->train_description->from, //Откуда
					  'to'        => $result->train_description->to,  //Куда
					  'stop_list' => $stop_list); //Остановки

        //Возвращаем результат в формате JSON
        return json_encode($json);
	}
}

$Engine = new Engine();

echo $Engine->index();