<?php
defined('APP_NAME') or die('Ampun boss');
function pingIP($ip_address) {
    $start = microtime(true);
    $fp = @fsockopen($ip_address, 80, $errno, $errstr, 10);
    if (!$fp) {
      $latency = false;
    }
    else {
      $latency = microtime(true) - $start;
      $latency = round($latency * 1000, 4);
    }
    return $latency;
}