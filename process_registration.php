<?php
include 'helpers.php';
require_once 'vendor/autoload.php';
require_once 'mysql_connect.php';
require_once 'db_queries.php';

startSession();

$formFields = $_POST;
$_SESSION['reg-form-data'] = $_POST;

if (empty($formFields)) {
	flash('empty_form', 'Empty form.', 'error');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace.php');
}

// check if input fields are filled and uses htmlspecialchars to prevent XSS
$cleanedFields = checkInput($formFields);

try {
	$connection = getDbConnection();

	$connection->beginTransaction();

	$peopleColumns = [
		'ssn', 'name', 'surname', 'title', 'address', 'city', 'zip', 'nationality',
		'language', 'gender_id', 'created_at', 'updated_at', 'deleted_at'
	];
	$peopleNamedParameters = [];
	for ($i = 0; $i < count($peopleColumns); $i++) {
		$peopleNamedParameters[] = ':' . $peopleColumns[$i];
	}

	$parent = findParentByIdCard($cleanedFields['guardianIDCard']);
	$peopleSql = 'INSERT INTO people ('. implode(', ', $peopleColumns) .') VALUES ('. implode(', ', $peopleNamedParameters) .')';

	if (empty($parent)) {

		$statement = $connection->prepare($peopleSql);

		$statement->bindValue(':ssn', $cleanedFields['guardianID']);
		$statement->bindValue(':name', $cleanedFields['guardianName']);
		$statement->bindValue(':surname', $cleanedFields['guardianSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', $cleanedFields['guardianAddress']);
		$statement->bindValue(':city', $cleanedFields['guardianCity']);
		$statement->bindValue(':zip', $cleanedFields['guardianZIP']);
		$statement->bindValue(':nationality', null);
		$statement->bindValue(':language', null);

		$gender = findGenderById($cleanedFields['guardianSex']);
		$statement->bindValue(':gender_id', $gender['id']);

		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId = $connection->lastInsertId();

		$parentsTableColumns = [
			'user_id', 'person_id', 'email', 'phone_number', 'id_card', 'created_at',
			'updated_at', 'deleted_at'
		];
		$parentsNamedParameters = array_map(
			function ($item) {
				return ':' . $item;
			},
			$parentsTableColumns
		);
		$parentsSql = 'INSERT INTO parents ('. implode(', ', $parentsTableColumns) .') VALUES ('. implode(', ', $parentsNamedParameters) .')';

		$statement = $connection->prepare($parentsSql);
		$statement->bindValue(':user_id', null);
		$statement->bindValue(':person_id', $personId);
		$statement->bindValue(':email', $cleanedFields['guardianEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['guardianPhone']);
		$statement->bindValue(':id_card', $cleanedFields['guardianIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();
		
		$parentId = $connection->lastInsertId();
	} else {

		$person = findPersonById($parent['person_id']);

		$updateSql = array_map(
			function ($column, $namedParameter) {
				return $column .' = '. $namedParameter;
			},
			$peopleColumns,
			$peopleNamedParameters
		);
		$peopleUpdateSql = 'UPDATE people SET '. implode(', ', $updateSql) .' WHERE id = :id';
		$statement = $connection->prepare($peopleUpdateSql);

		$statement->bindValue(':id', $person['id']);
		$statement->bindValue(':ssn', $cleanedFields['guardianID']);
		$statement->bindValue(':name', $cleanedFields['guardianName']);
		$statement->bindValue(':surname', $cleanedFields['guardianSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', $cleanedFields['guardianAddress']);
		$statement->bindValue(':city', $cleanedFields['guardianCity']);
		$statement->bindValue(':zip', $cleanedFields['guardianZIP']);
		$statement->bindValue(':nationality', null);
		$statement->bindValue(':language', null);

		$gender = findGenderById($cleanedFields['guardianSex']);
		$statement->bindValue(':gender_id', $gender['id']);

		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId = $person['id'];

		$parentsTableColumns = [
			'user_id', 'person_id', 'email', 'phone_number', 'id_card',
			'created_at', 'updated_at', 'deleted_at'
		];
		$parentsNamedParameters = array_map(
			function ($item) {
				return ':' . $item;
			},
			$parentsTableColumns
		);
		$updateSql = array_map(
			function ($column, $namedParameter) {
				return $column .' = '. $namedParameter;
			},
			$parentsTableColumns,
			$parentsNamedParameters
		);
		$parentsSql = 'UPDATE parents SET '. implode(', ', $updateSql) .' WHERE id = :id';

		$statement = $connection->prepare($parentsSql);
		$statement->bindValue(':id', $parent['id']);
		$statement->bindValue(':user_id', null);
		$statement->bindValue(':person_id', $personId);
		$statement->bindValue(':email', $cleanedFields['guardianEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['guardianPhone']);
		$statement->bindValue(':id_card', $cleanedFields['guardianIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();
		
		$parentId = $parent['id'];
	}

	$person = findPersonBySsn($cleanedFields['childID']);
	if (empty($person)) {

		$statement = $connection->prepare($peopleSql);
		$statement->bindValue(':ssn', $cleanedFields['childID']);
		$statement->bindValue(':name', $cleanedFields['childName']);
		$statement->bindValue(':surname', $cleanedFields['childSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', $cleanedFields['childAddress']);
		$statement->bindValue(':city', $cleanedFields['childCity']);
		$statement->bindValue(':zip', $cleanedFields['childZIP']);
		$statement->bindValue(':nationality', null);
		$statement->bindValue(':language', null);

		$gender = findGenderById($cleanedFields['childSex']);
		$statement->bindValue(':gender_id', $gender['id']);

		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId = $connection->lastInsertId();

		$studentsTableColumns = [
			'person_id', 'birthday', 'birthplace', 'health_insurance', 'is_vaccinated',
			'other_info', 'created_at', 'updated_at', 'deleted_at'
		];
		$studentsNamedParameters = array_map(function ($item) {
			return ':' . $item;
		},
			$studentsTableColumns
		);
		$studentsSql = 'INSERT INTO students ('. implode(', ', $studentsTableColumns) .') VALUES ('. implode(', ', $studentsNamedParameters) .')';

		$statement = $connection->prepare($studentsSql);
		$statement->bindValue(':person_id', $personId);

		$birthday = \DateTime::createFromFormat('d.m.Y', $cleanedFields['childBirth']);
		$statement->bindValue(':birthday', $birthday->format('Y-m-d'));

		$statement->bindValue(':birthplace', null);
		$statement->bindValue(':health_insurance', $cleanedFields['childHealthInsurance']);
		if ($cleanedFields['vaccinationStatement'] == 'on') {
			$statement->bindValue(':is_vaccinated', 1);
		}
		if (array_key_exists('childImportantInfo', $cleanedFields)) {
			$statement->bindValue(':other_info', $cleanedFields['childImportantInfo']);
		}
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$studentId = $connection->lastInsertId();
	} else {

		$statement = $connection->prepare($peopleUpdateSql);
		$statement->bindValue(':id', $person['id']);
		$statement->bindValue(':ssn', $cleanedFields['childID']);
		$statement->bindValue(':name', $cleanedFields['childName']);
		$statement->bindValue(':surname', $cleanedFields['childSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', $cleanedFields['childAddress']);
		$statement->bindValue(':city', $cleanedFields['childCity']);
		$statement->bindValue(':zip', $cleanedFields['childZIP']);
		$statement->bindValue(':nationality', null);
		$statement->bindValue(':language', null);

		$gender = findGenderById($cleanedFields['childSex']);
		$statement->bindValue(':gender_id', $gender['id']);

		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId = $person['id'];

		$studentsTableColumns = [
			'person_id', 'birthday', 'birthplace', 'health_insurance', 'is_vaccinated',
			'other_info', 'created_at', 'updated_at', 'deleted_at'
		];
		$studentsNamedParameters = array_map(function ($item) {
			return ':' . $item;
		},
			$studentsTableColumns
		);

		$updateSql = array_map(
			function ($column, $namedParameter) {
				return $column .' = '. $namedParameter;
			},
			$studentsTableColumns,
			$studentsNamedParameters
		);
		$studentsSql = 'UPDATE students SET '. implode(', ', $updateSql) .' WHERE id = :id';

		$statement = $connection->prepare($studentsSql);
		$statement->bindValue(':id', $personId);
		$statement->bindValue(':person_id', $personId);

		$birthday = \DateTime::createFromFormat('d.m.Y', $cleanedFields['childBirth']);
		$statement->bindValue(':birthday', $birthday->format('Y-m-d'));

		$statement->bindValue(':birthplace', null);
		$statement->bindValue(':health_insurance', $cleanedFields['childHealthInsurance']);
		if ($cleanedFields['vaccinationStatement'] == 'on') {
			$statement->bindValue(':is_vaccinated', 1);
		}
		if (array_key_exists('childImportantInfo', $cleanedFields)) {
			$statement->bindValue(':other_info', $cleanedFields['childImportantInfo']);
		} else {
			$statement->bindValue(':other_info', null);
		}
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$student = findStudentByPersonId($personId);
		$studentId = $student['id'];
	}

	$reservationsTableColumns = [
		'student_id', 'time_start', 'time_end', 'status', 'created_at',
		'updated_at', 'deleted_at'
	];
	$reservationsNamedParameters = array_map(
		function ($column) {
			return ':'. $column;
		},
		$reservationsTableColumns
	);
	$reservationInsertSql = 'INSERT INTO reservations ('. implode(', ', $reservationsTableColumns) .') VALUES (' . implode(', ', $reservationsNamedParameters) .')';
	$statement = $connection->prepare($reservationInsertSql);
	$statement->bindValue(':student_id', $studentId);
	$start = \DateTime::createFromFormat('d.m.Y H:i', $cleanedFields['careStart']);
	$statement->bindValue(':time_start', $start->format('Y-m-d H:i:s'));
	$end = \DateTime::createFromFormat('d.m.Y H:i', $cleanedFields['careEnd']);
	$statement->bindValue(':time_end', $end->format('Y-m-d H:i:s'));
	$statement->bindValue(':status', null);
	$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
	$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
	$statement->bindValue(':deleted_at', null);
	$statement->execute();

	if (!empty($cleanedFields['care'])) {
		foreach ($cleanedFields['care'] as $care) {
			$reservationInsertSql = 'INSERT INTO reservations ('. implode(', ', $reservationsTableColumns) .') VALUES (' . implode(', ', $reservationsNamedParameters) .')';
			$statement = $connection->prepare($reservationInsertSql);
			$statement->bindValue(':student_id', $studentId);
			$start = \DateTime::createFromFormat('d.m.Y H:i', $care['start']);
			$statement->bindValue(':time_start', $start->format('Y-m-d H:i:s'));
			$end = \DateTime::createFromFormat('d.m.Y H:i', $care['end']);
			$statement->bindValue(':time_end', $end->format('Y-m-d H:i:s'));
			$statement->bindValue(':status', null);
			$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
			$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
			$statement->bindValue(':deleted_at', null);
			$statement->execute();
		}
	}

	$parentStudentTableColumns = ['parent_id', 'student_id'];
	$parentStudentNamedParameters = array_map(
		function ($columns) {
			return ':'. $columns;
		},
		$parentStudentTableColumns
	);
	$parentStudentInsertSql = 'INSERT INTO parent_student ('. implode(', ', $parentStudentTableColumns) .') VALUES ('. implode(', ', $parentStudentNamedParameters) .')';
	$statement = $connection->prepare($parentStudentInsertSql);
	$statement->bindValue(':parent_id', $parentId);
	$statement->bindValue(':student_id', $studentId);
	$statement->execute();

	if (!empty($cleanedFields['otherGuardians'])) {
		$guardianTableColumns = [
			'user_id', 'name', 'surname', 'phone_number', 'created_at',
			'updated_at', 'deleted_at'
		];
		$guardianNamedParameters = array_map(
			function ($column) {
				return ':'. $column;
			},
			$guardianTableColumns
		);
		$guardianInsertSql = 'INSERT INTO guardians ('. implode(', ', $guardianTableColumns) .') VALUES ('. implode(', ', $guardianNamedParameters) .')';

		$guardianStudentTableColumns = [
			'student_id', 'guardian_id', 'relationship'
		];
		$guardianStudentNamedParameters = array_map(
			function ($column) {
				return ':'. $column;
			},
			$guardianStudentTableColumns
		);
		$guardianStudentInsertSql = 'INSERT INTO guardian_student ('. implode(', ', $guardianStudentTableColumns) .') VALUES ('. implode(', ', $guardianStudentNamedParameters) .')';
		
		foreach ($cleanedFields['otherGuardians'] as $guardian) {
			$statement = $connection->prepare($guardianInsertSql);
			$statement->bindValue(':user_id', null);
			$statement->bindValue(':name', $guardian['name']);
			$statement->bindValue(':surname', $guardian['surname']);
			$statement->bindValue(':phone_number', $guardian['phone']);
			$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
			$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
			$statement->bindValue(':deleted_at', null);
			$statement->execute();
			$guardianId = $connection->lastInsertId();
			
			$statement = $connection->prepare($guardianStudentInsertSql);
			$statement->bindValue(':student_id', $studentId);
			$statement->bindValue(':guardian_id', $guardianId);
			$statement->bindValue(':relationship', $guardian['relationship']);
			$statement->execute();
		}
	}

	$connection->commit();
} catch (\Exception $e) {
	file_put_contents('log.txt', $e->getMessage(), FILE_APPEND);
	file_put_contents('log.txt', $e->getFile() . ': ' . $e->getLine(), FILE_APPEND);
	file_put_contents('log.txt', $e->getTraceAsString(), FILE_APPEND);
	$connection->rollback();
}

// email for employees of preschool is in file preschool-email.php
// that file works with variable $cleanedFields
$preschoolEmail = include 'preschool-email.php';

//$preschoolEmailResult = sendMail('info@skolkahafik.cz', 'Rezervace hlídání', $preschoolEmail);
$preschoolEmailResult = sendMail(
	'info@skolkahafik.cz',
	'Rezervace hlídání',
	$preschoolEmail,
	$cleanedFields['guardianEmail']
);

// email for customers is in file customer-registration-email.php which
// uses variable $cleanedFields
$customerEmail = include 'customer-registration-email.php';
$customerEmailResult = sendMail(
	$cleanedFields['guardianEmail'],
	'Dětské centrum Hafík - potvrzení registrace',
	$customerEmail
);

if ($preschoolEmailResult) {
	unset($_SESSION['reg-form-data']);
	flash('registration', 'Registrace byla úšpěšně dokončena a na Vaši e-mailovou adresu jsme zaslali její potvrzení. Děkujeme a těšíme se na Vás v Hafíkovi.', 'alert alert-success');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace.php');
	exit;
} else {
	flash('registration', 'Chyba při zpracování registrace. Zkuste registraci prosím vyplnit ještě jednou a pokud se Vám tato zpráva objeví podruhé, tak nám prosím zavolejte na tel. číslo: 604787347.', 'alert alert-danger');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace.php');
	exit;
}



function sendMail($to, $subject, $message, $from = 'rezervace@skolkahafik.cz')
{
//	$headers = "From: " . strip_tags($from) . "\r\n";
//	$headers .= "MIME-Version: 1.0\r\n";
//	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
//	$headers .= "Content-Transfer-Encoding: base64" . PHP_EOL;
//
//	$result = mail($to, $subject, $message, $headers);

	$mail = new PHPMailer();
	$mail->CharSet = 'utf-8';
	$mail->isHTML(true);

	$mail->setFrom($from);
	$mail->addAddress($to);
	$mail->Subject = $subject;
	$mail->Body = $message;

	return $mail->send();
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

	$start = DateTime::createFromFormat('d.m.Y H:i', $cleanedFields['careStart']);
	$end = DateTime::createFromFormat('d.m.Y H:i', $cleanedFields['careEnd']);

	$datesError = false; // indicates that end date is set before start
	$pastError = false; // indicates that start or end date is set to past

	if ($end <= $start) $datesError = true;

	$now = new DateTime();
	if ($end <= $now || $start <= $now) $pastError = true;

	$careDateTimes = $formFields['care'];
	$cleanedFields['care'] = [];
	foreach ($careDateTimes as $key => $careDateTime) {
		if (!empty($careDateTime['start']) && !empty($careDateTime['end'])) {
			$start = DateTime::createFromFormat('d.m.Y H:i', $careDateTime['start']);
			$end = DateTime::createFromFormat('d.m.Y H:i', $careDateTime['end']);

			if ($end <= $start) $datesError = true;
			if ($end <= new DateTime('now') || $start <= new DateTime('now')) $pastError = true;

			$cleanedFields['care'][] = [
				'start' => clean($careDateTime['start']),
				'end' => clean($careDateTime['end']),
			];
		}
	}

	if ($datesError) $emptyFields[] = 'Termín vyzvednutí bylo zadáno před začátkem hlídání';
	if ($pastError) $emptyFields[] = 'Termín začátku hlídání nesmí být zadán před dnešním dnem';

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
		$id = clean($formFields['guardianID']);
		$cleanedFields['guardianID'] = str_replace('/', '', $id);
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
			!empty($otherGuardian['relationship']) &&
			!empty($otherGuardian['phone'])
		) {
			$cleanedFields['otherGuardians'][] = [
				'name' => clean($otherGuardian['name']),
				'surname' => clean($otherGuardian['surname']),
				'relationship' => clean($otherGuardian['relationship']),
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
		$id = clean($formFields['childID']);
		$cleanedFields['childID'] = str_replace('/', '', $id);
	} else {
		$emptyFields[] = 'rodné číslo dítěte';
	}
	if (!empty($formFields['childSex'])) {
		$cleanedFields['childSex'] = clean($formFields['childSex']);
	} else {
		$emptyFields[] = 'pohlaví dítěte';
	}
	if (!empty($formFields['childBirth'])) {
		$birthday = DateTime::createFromFormat('d.m.Y', clean($formFields['childBirth']));
		$now = new DateTime();
		if ($birthday >= $now) {
			$emptyFields[] = 'Datum narození dítěte bylo zadáno před dnešním dnem';
		}
		$cleanedFields['childBirth'] = clean($formFields['childBirth']);
	} else {
		$emptyFields[] = 'narození dítěte';
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

	if (!empty($emptyFields)) {
		$messages = [];
		foreach ($emptyFields as $emptyField) {
			$messages[] = '<p>'. $emptyField .'</p>';
		}
		flash('registration-form-error', implode('', $messages), 'alert alert-danger');
		header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace.php');
		exit;
	}

	return $cleanedFields;
}
