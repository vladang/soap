function ajax() {

    //Данные формы
    var train = $('#train').val();
    var from  = $('#from').val();
    var to    = $('#to').val();
    var day   = parseInt($('#day').val());
    var month = parseInt($('#month').val());

    //Очистим форму от ошибок
    $('input').removeClass('error_frm');
    $('.err').empty();

    //Проверка формы на ошибки
    if (train == '') {
    	$('#train').addClass('error_frm');
        $('#train_row .err').text('Укажите номер поезда!');
    }

    if (from == '') {
    	$('#from').addClass('error_frm');
        $('#from_row .err').text('Укажите станцию отправления!');
    }

    if (to == '') {
    	$('#to').addClass('error_frm');
        $('#to_row .err').text('Укажите станцию прибытия!');
    }

    if (day < 1 || day > 31 || !$.isNumeric(day)) {
    	$('#day').addClass('error_frm');
        $('#day_row .err').text('Не верно указана дата!');
    }

    if (month < 1 || month > 12 || !$.isNumeric(month)) {
    	$('#month').addClass('error_frm');
        $('#month_row .err').text('Не верно указан месяц!');
    }

    //Если в форме есть ошибки, останавливаем работу сценария
    if ($('input').hasClass('error_frm')) return false;

    //Очистим блоки от предыдущего запроса и покажем индикатор загрузки
    $('#submit, .info, #stop_list').hide();
    $('#img').show();
    $('#stop_list tr').remove('.app');
    $('#error').empty();

    //Отправляем AJAX запрос
	$.post('soap.php', {train: train, from: from, to: to, day: day, month: month},

		function(data, status) {

			var jdata = JSON.parse(data);
            //Если запрос прошел успешно
			if (jdata.status == 'success') {

                //Выведем номер поезда, станцию отправления и прибытия
                $('#number').text(jdata.number);
                $('#train_from').text(jdata.from);
                $('#train_to').text(jdata.to);

                //Вывод маршрута следования
    			$.each(jdata.stop_list, function (index, value) {
      				$('#stop_list').append('<tr class="app"><td>' + value.stop + '</td><td>' + value.arrival_time + '</td><td>' + value.departure_time + '</td><td>' + value.stop_time + '</td></tr>');
    			});

                $('#stop_list, .info').show();

			} else {
                //Иначе вывод ошибки
				$('#error').text(jdata.message);
			}

            //Убираем индикатор загрузки и отобразим кнопку
    		$('#submit').show();
    		$('#img').hide();
		}
	);

	return false;
}
