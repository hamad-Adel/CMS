<?php
// sub string function
function miniString($string, $limit = 50)
{
  $str = trim(substr($string, 0, $limit));
  return $str.' .....';
}
