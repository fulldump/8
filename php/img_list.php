<style type="text/css">
body {
  font-family: sans-serif;
}

.box {
  display: block;
  overflow: hidden;
  border: solid silver 1px;
  width: 128px;
  height: 0px;
  padding-top:128px;
  margin: 8px;
  float: left;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: contain;
  background-color: #F8F8F8;
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
  color: black;
  text-decoration: none;
  text-align: center;
}

.box:hover {
  box-shadow: 0 0 10px rgba(0,0,0,0.6);
  overflow: visible;
  text-shadow: 1px 1px 2px white;
}
</style>

<?php

	foreach (Image::SELECT() as $im) {
		echo '<a href="/img/'.$im->getId().'" class="box" style="background-image:url(\'/img/'.$im->getId().'/w:128;q:50\')">'.$im->getId().': '.$im->getWidth().'x'.$im->getHeight().'</a>';
	}
