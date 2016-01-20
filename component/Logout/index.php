<?php

$callback = urlencode( Lib::getCurrentUrl() );

$href = "/__ajax__/Logout/logout?callback={$callback}";

?>
<a  href="<?=$href?>">[[COMPONENT name=Label text=Logout id=logout]]</a>