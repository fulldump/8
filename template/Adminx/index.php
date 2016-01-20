<?php if(Session::isLoggedIn()) { ?>
[[COMPONENT name=TrunkToolbar]]
<div component="TrunkToolbarWorkspace">[[BODY]]</div>
<?php } else { ?>
[[COMPONENT name=TrunkLogin]]
<?php } ?>