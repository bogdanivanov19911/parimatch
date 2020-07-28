		<div class="title-top">
			<h1>Список заявок</h1>
		</div>
		
		<div class="filter-buttons">
			<a href="/master.php?do=cashout&option=all" class="buttonsNew">Все</a>
			<a href="/master.php?do=cashout&option=close" class="buttonsNew">Оплаченные</a>
			<a href="/master.php?do=cashout&option=reject" class="buttonsNew">Отмененные</a>
			<a href="/master.php?do=cashout&option=wait" class="buttonsNew">Ожидают</a>
		</div>

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="newTable">
				{body}
				{body2}
			</table>
		</form>