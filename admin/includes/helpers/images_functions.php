<?php
function getImageExtension($name)
{
  $exe = explode('.', $name);
  return end($exe);
}

function uniqueImageName($name)
{
  return time().md5($name).$name;
}
