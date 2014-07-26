<?php
/**
 * @param string $str
 * @return string
 */
function cleanURI($str)
{
    return preg_replace('#/+#', '/', $str);
}