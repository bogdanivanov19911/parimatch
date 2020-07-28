<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="ru">
	<title>Менеджер панель</title>
	<link rel="stylesheet" href="/admin/template/css/login.css">
</head>
<body style="direction: ltr;">

<form id="loginForm" action="" method="post">

    <div class="field">
        <label>Логин:</label>
        <div class="input"><input type="text" name="login" value="" id="login" /></div>
    </div>

    <div class="field">
        <label>Пароль:</label>
        <div class="input"><input type="password" name="password" value="" id="pass" /></div>
    </div>

    <div class="submit">
        <button type="submit" name="submit">Вход</button>
        <label id="remember"><input name="save" type="checkbox" value="" />  Запомнить</label>
		
		{error}
    </div>

</form>

</body>
</html>