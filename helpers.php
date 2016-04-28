<?php
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

function displayFlash($name)
{
	if (!empty($_SESSION[$name])) {
		$class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
		echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name . '_message'].'</div>';
		unset($_SESSION[$name]);
		unset($_SESSION[$name.'_class']);
	}
}
function startSession()
{
	if(!session_id()) {
		session_start();
	}
}