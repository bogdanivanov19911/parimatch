		<div class="title-top">
			<h1>ניהול סוכנים</h1>
		</div>

		<form enctype="multipart/form-data" action="" method="post">
			<table width="100%" border="0" class="table">
				
				<tr>
					<td>
						הוסף נקודות*:
						<span></span>
					</td>
					<td>
						<input type="text" name="balance" value="">
					</td>
				</tr>
				
				<tr>
					<td>
						סיסמה*:
					</td>
					<td>
						<input type="text" name="pass" value="******">
					</td>
				</tr>
				
				<tr>
					<td>
						הערות:
					</td>
					<td>
						<input type="text" name="document" value="{document}">
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						סכום כללי: <b>{balance}</b><br>
						מצב חשבון עכשיו : <b>{balance_now}</b>
					</td>
				</tr>
				
				<tr>
					<td colspan="2" style="border-right: 0;">
						<button type="sumbit" class="btn btn-primary btn-primaryNew right" name="button">שמור</button>
					</td>
				</tr>
				
				{history}
			
			</table>
		</form>