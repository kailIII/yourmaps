<?php

/**
 * Monitoring service for a addmap-batch-backend service.
 * 
 * It prints out a json representation of a message string with the progress
 * of the process
 */

$processId = $_POST['processid'];
session_start();
$message = $_SESSION[$processId];

$message = array(
		'message' => $message
);

print json_encode($message);