<?php if(Session::isLoggedIn()) { ?>
	[[BODY]]
<?php } else { ?>
	[[COMPONENT name=TrunkLogin]]
<?php } ?>