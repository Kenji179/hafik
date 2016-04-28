<?php
include 'helpers.php';

startSession();

$formFields = $_POST;
if (empty($formFields)) {
	flash('empty_form', 'Empty form.', 'error');
	header('Location: http://localhost/hafik/rezervace.php');
}

// check if input fields are filled and uses htmlspecialchars to prevent XSS
$cleanedFields = checkInput($formFields);

// email for employees of preschool is in file preschool-email.php
// that file works with variable $cleanedFields
$preschoolEmail = include 'preschool-email.php';
sendMail('info@skolkahafik.cz', 'Rezervace hlídání', $preschoolEmail);

// email for customers is in file customer-registration-email.php which
// uses variable $cleanedFields
$customerEmail = include 'customer-registration-email.php';
sendMail(
	$cleanedFields['guardianEmail'],
	'Dětské centrum Hafík - potvrzení registrace',
	$customerEmail
);

function sendMail($to, $subject, $message, $from = 'rezervace@skolkahafik.cz')
{
	$headers = "From: " . strip_tags($from) . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$result = mail($to, $subject, $message, $headers);
	if ($result) {
		flash('registration', 'Registrace byla úšpěšně dokončena', 'alert alert-success');
		header('Location: http://localhost/hafik/rezervace.php');
	} else {
		flash('registration', 'Chyba při zpracování registrace. Pracujeme na nápravě.', 'alert alert-danger');
		header('Location: http://localhost/hafik/rezervace.php');
	}

}

function clean($value)
{
	return trim(htmlspecialchars($value));
}

function checkInput($formFields) {
	$cleanedFields = [];
	$emptyFields = [];

	if (!empty($formFields['careStart'])) {
		$cleanedFields['careStart'] = clean($formFields['careStart']);
	} else {
		$emptyFields[] = 'začátek hlídání';
	}
	if (!empty($formFields['careEnd'])) {
		$cleanedFields['careEnd'] = clean($formFields['careEnd']);
	}  else {
		$emptyFields[] = 'konec hlídání';
	}

	$careDateTimes = $formFields['care'];
	$cleanedFields['care'] = [];
	foreach ($careDateTimes as $key => $careDateTime) {
		if (!empty($careDateTime['start']) && !empty($careDateTime['end'])) {
			$cleanedFields['care'][] = [
				'start' => clean($careDateTime['start']),
				'end' => clean($careDateTime['end']),
			];
		}
	}

	if (!empty($formFields['guardianName'])) {
		$cleanedFields['guardianName'] = clean($formFields['guardianName']);
	} else {
		$emptyFields[] = 'jméno zákonného zástupce';
	}
	if (!empty($formFields['guardianSurname'])) {
		$cleanedFields['guardianSurname'] = clean($formFields['guardianSurname']);
	} else {
		$emptyFields[] = 'příjmení zákonného zástupce';
	}
	if (!empty($formFields['guardianSex'])) {
		$cleanedFields['guardianSex'] = clean($formFields['guardianSex']);
	} else {
		$emptyFields[] = 'pohlaví zákonného zástupce';
	}
	if (!empty($formFields['guardianIDCard'])) {
		$cleanedFields['guardianIDCard'] = clean($formFields['guardianIDCard']);
	} else {
		$emptyFields[] = 'číslo občanského průkazu zákonného zástupce';
	}
	if (!empty($formFields['guardianID'])) {
		$cleanedFields['guardianID'] = clean($formFields['guardianID']);
	} else {
		$emptyFields[] = 'rodné číslo zákonného zástupce';
	}
	if (!empty($formFields['guardianPhone'])) {
		$cleanedFields['guardianPhone'] = clean($formFields['guardianPhone']);
	} else {
		$emptyFields[] = 'telefon zákonného zástupce';
	}
	if (!empty($formFields['guardianEmail'])) {
		$cleanedFields['guardianEmail'] = clean($formFields['guardianEmail']);
	} else {
		$emptyFields[] = 'email zákonného zástupce';
	}
	if (!empty($formFields['guardianAddress'])) {
		$cleanedFields['guardianAddress'] = clean($formFields['guardianAddress']);
	} else {
		$emptyFields[] = 'ulice a číslo popisné zákonného zástupce';
	}
	if (!empty($formFields['guardianCity'])) {
		$cleanedFields['guardianCity'] = clean($formFields['guardianCity']);
	} else {
		$emptyFields[] = 'město zákonného zástupce';
	}
	if (!empty($formFields['guardianZIP'])) {
		$cleanedFields['guardianZIP'] = clean($formFields['guardianZIP']);
	} else {
		$emptyFields[] = 'PSČ zákonného zástupce';
	}

	$otherGuardians = $formFields['otherGuardians'];
	$cleanedFields['otherGuardians'] = [];
	foreach ($otherGuardians as $key => $otherGuardian) {
		if (!empty($otherGuardian['name']) &&
			!empty($otherGuardian['surname']) &&
			!empty($otherGuardian['phone'])
		) {
			$cleanedFields['otherGuardians'][] = [
				'name' => clean($otherGuardian['name']),
				'surname' => clean($otherGuardian['surname']),
				'phone' => clean($otherGuardian['phone']),
			];
		}
	}

	if (!empty($formFields['childName'])) {
		$cleanedFields['childName'] = clean($formFields['childName']);
	} else {
		$emptyFields[] = 'jméno dítěte';
	}
	if (!empty($formFields['childSurname'])) {
		$cleanedFields['childSurname'] = clean($formFields['childSurname']);
	} else {
		$emptyFields[] = 'příjmení dítěte';
	}
	if (!empty($formFields['childID'])) {
		$cleanedFields['childID'] = clean($formFields['childID']);
	} else {
		$emptyFields[] = 'rodné číslo dítěte';
	}
	if (!empty($formFields['childSex'])) {
		$cleanedFields['childSex'] = clean($formFields['childSex']);
	} else {
		$emptyFields[] = 'pohlaví dítěte';
	}
	if (!empty($formFields['childBirth'])) {
		$cleanedFields['childBirth'] = clean($formFields['childBirth']);
	} else {
		$emptyFields[] = 'pohlaví dítěte';
	}
	if (!empty($formFields['childAddress'])) {
		$cleanedFields['childAddress'] = clean($formFields['childAddress']);
	} else {
		$emptyFields[] = 'adresa dítěte';
	}
	if (!empty($formFields['childCity'])) {
		$cleanedFields['childCity'] = clean($formFields['childCity']);
	} else {
		$emptyFields[] = 'město dítěte';
	}
	if (!empty($formFields['childZIP'])) {
		$cleanedFields['childZIP'] = clean($formFields['childZIP']);
	} else {
		$emptyFields[] = 'PSČ dítěte';
	}
	if (!empty($formFields['childHealthInsurance'])) {
		$cleanedFields['childHealthInsurance'] = clean($formFields['childHealthInsurance']);
	} else {
		$emptyFields[] = 'zdravotní pojišťovna dítěte';
	}
	if (!empty($formFields['childImportantInfo'])) {
		$cleanedFields['childImportantInfo'] = clean($formFields['childImportantInfo']);
	}
	if ($formFields['rulesApproval'] !== 'on') {
		$emptyFields[] = 'potvrzení podmínek';
	}
	$cleanedFields['vaccinationStatement'] = $formFields['vaccinationStatement'];

	return $cleanedFields;
}
