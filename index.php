<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Тестовое задание</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="script.js"></script>
  </head>
  <body>

	<div class="container">

    <h1>Тестовое задание</h1>

	<form name="" action="" method="post" onSubmit="return ajax();">

		<div class="row" id="train_row">
        	<label for="train">Номер поезда:</label>
        	<input id="train" type="text" value="016А">
        	<div class="err"></div>
      	</div>

		<div class="row" id="from_row">
        	<label for="from">Станция отправления:</label>
        	<input id="from" type="text" value="Санкт-Петербург">
        	<div class="err"></div>
      	</div>

		<div class="row" id="to_row">
        	<label for="to">Станция прибытия:</label>
        	<input id="to" type="text" value="Москва">
        	<div class="err"></div>
      	</div>

		<div class="row" id="day_row">
        	<label for="day">Дата отправления:</label>
        	<input id="day" type="text" value="10">
        	<div class="err"></div>
      	</div>

		<div class="row" id="month_row">
        	<label for="month">Месяц отправления:</label>
        	<input id="month" type="text" value="12">
        	<div class="err"></div>
      	</div>

		<div class="row submit">
            <input type="submit" id="submit" value="Отправить">
			<img src="loading.gif" id="img" border="0" style="display: none;" />
      	</div>

	</form>

    <div style="clear: both;"></div>

    <div id="ajax">

    	<div id="error"></div>

    	<div class="info">
			Номер поезда: <span id="number"></span>
			<br />
			Станция отправления: <span id="train_from"></span>
			<br />
			Станция назначения: <span id="train_to"></span>
		</div>

		<table id="stop_list">
			<tr>
    			<th>Станция</th>
            	<th>Время прибыти</th>
            	<th>Время отправления</th>
            	<th>Время стоянки (минут)</th>
    		</tr>
    	</table>

    </div>

	</div>


  </body>
</html>