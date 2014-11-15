<div class="frame">

	<h1>Change password</h1>

	<form method="POST" action="">

		<?php

if (isset($_POST['action']) && 'change_password' === $_POST['action']) {

	if ($_POST['new_password'] !== $_POST['repeat_password']) {
		echo '<div class="msg msg-error">Password confirmation does not match.</div>';
	} else if (strlen($_POST['new_password'])<10) {
		echo '<div class="msg msg-error">New password must be at least 10 chars length.</div>';
	} else if (SystemUser::validate(Session::getUser()->getLogin(), $_POST['old_password'])) {
		Session::getUser()->setPassword($_POST['new_password']);
		Session::closeAll();
		Session::logout();
		echo '<div class="msg msg-info">Password has been changed. Please, log in again.</div>';
	} else {
		echo '<div class="msg msg-error">Wrong old password.</div>';
	}
}

		?>



		<label>Old password<input type="password" name="old_password"></label>
		<label>New password<input type="password" name="new_password"></label>
		<label>Repeat password<input type="password" name="repeat_password"></label>


		<input type="hidden" name="action" value="change_password">
		<br>
		<button type="submit">Change my password</button>
	</form>


	<h1>Sessions</h1>

	<form method="POST" action="">

		<?php

if (isset($_POST['action']) && 'close_all_sessions' === $_POST['action']) {
	Session::closeAll();
	echo '<div class="msg msg-info">Sesiones borradas</div>';
} else {

		?>
		<input type="hidden" name="action" value="close_all_sessions">
		<br>
		<button type="submit">Close other sessions</button>

		<?php } ?>
	</form>

	<table class="table">
		<tr>
			<th>SessionID</th>
			<th>UserName</th>
			<th>Created</th>
			<th>IP</th>
		</tr>
		<?php

foreach(Session::getAll() as $session) {
	echo "<tr>";
	echo "<td>{$session->getSessionId()}</td>";
	echo "<td>{$session->getUser()->getName()}</td>";
	echo "<td>".date('r', $session->getCreated())."</td>";
	echo "<td>{$session->getIp()}</td>";
	echo "</tr>";
}

		?>
	</table>

</div>