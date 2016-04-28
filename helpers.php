<?php
// file for helper functions

// creates flash message in session
// $name name of flash message. it used to find the message
// $message flash message
// $class name of class for styling the <div>
function flash($name, $message, $class)
{
	if (!empty($name)) {
		if (!empty($message) && empty($_SESSION[$name])) {
			if (!empty($_SESSION[$name])) {
				unset($_SESSION[$name]);
			}
			if (!empty($_SESSION[$name . '_class'])) {
				unset($_SESSION[$name . '_class']);
			}
			$_SESSION[$name] = $name;
			$_SESSION[$name . '_class'] = $class;
			$_SESSION[$name . '_message'] = $message;
		}
	}
}

// displays flash message with name $name
function displayFlash($name)
{
	if (!empty($_SESSION[$name])) {
		$class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
		echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name . '_message'].'</div>';
		unset($_SESSION[$name]);
		unset($_SESSION[$name.'_class']);
	}
}

// starts session in case it is stopped
function startSession()
{
	if(!session_id()) {
		session_start();
	}
}