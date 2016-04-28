<?php

$email = '';

$email .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.
	'<html xmlns="http://www.w3.org/1999/xhtml">'.
	'<head>'.
	'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.
	'<meta name="viewport" content="width=device-width, initial-scale=1.0"/>'.
	'<title>Notifications from Arctrack</title>'.
	'<style type="text/css">'.
	'#outlook a {padding:0;}'.
	'body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}'.
	'.ExternalClass {width:100%;}'.
	'.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}'.
	'#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}'.

	'@media only screen and (max-device-width: 480px) {'.
		'a[href^="tel"], a[href^="sms"] {'.
			'text-decoration: none;'.
			'color: #ba68c8;'.
			'pointer-events: none;'.
			'cursor: default;'.
		'}'.

		'.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {'.
			'text-decoration: default;'.
			'color: #ba68c8 !important;'.
			'pointer-events: auto;'.
			'cursor: default;'.
		'}'.
	'}'.

	'@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {'.
		'a[href^="tel"], a[href^="sms"] {'.
			'text-decoration: none;'.
			'color: #ba68c8; /* or whatever your want */'.
			'pointer-events: none;'.
			'cursor: default;'.
		'}'.

		'.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {'.
			'text-decoration: default;'.
			'color: #ba68c8 !important;'.
			'pointer-events: auto;'.
			'cursor: default;'.
		'}'.
	'}'.
	'</style>'.
	'</head>'.
	'<body>';
$email .= '<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" style="width: 800px">';
$email .=	'<tr>';
$email .=		'<p>';
$email .=			'Tento e-mail shrnuje informace zadané při registraci hlídání v děckém centru Hafík.';
$email .=	    '</p>';
$email .=       '<td>';
$email .=           '<table>';
$email .=               '<tr><td>Jméno dítěte: '. $cleanedFields['childName'] .' '. $cleanedFields['childSurname'] .'</td></tr>';
$email .=               '<tr><td>Zákonný zástupce: '. $cleanedFields['guardianName'] .' '. $cleanedFields['guardianSurname'] .'</td>';
					if (count($cleanedFields['otherGuardians']) > 0) {
$email .=               '<tr><td>Osoby oprávněné k vyzvednutí dítěte:</td></tr>';
$email .=				'<tr><td><ul>';
						foreach ($cleanedFields['otherGuardians'] as $otherGuardian) {
$email .=                   '<li>' . $otherGuardian['name'] . ' ' . $otherGuardian['surname'] . '</li>';
						}
$email .=				'</ul></td></tr>';
					}
$email .=               '<tr><td>Dítě je registrované na termíny:</td></tr>';
$email .=               '<tr><td><ul><li>Od '. $cleanedFields['careStart'] .' do '. $cleanedFields['careEnd'] .'</li>';
					foreach ($cleanedFields['care'] as $careDates) {
$email .=               '<li>Od '. $careDates['start'] .' do '. $careDates['end'] .'</li>';
					}
$email .=               '</ul></td></tr>';
$email .=           '</table>';
$email .=       '</td>';
$email .=	'</tr>';
$email .= '</table>';
$email .= '</body>';
$email .= '</html>';

return $email;