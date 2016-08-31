<?php
include 'helpers.php';
require_once 'vendor/autoload.php';
require_once 'mysql_connect.php';
require_once 'db_queries.php';
require_once 'EmailTemplate.php';
require_once 'EmailSender.php';

use mikehaertl\wkhtmlto\Pdf;

startSession();

$formFields = $_POST;
$_SESSION['reg-form-data'] = $_POST;

if (empty($formFields)) {
	flash('empty_form', 'Empty form.', 'error');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace-skolka.php');
}

// check if input fields are filled and uses htmlspecialchars to prevent XSS
$cleanedFields = checkInput($formFields);

try {
	$connection = getDbConnection();

	$connection->beginTransaction();

	// mother
	$peopleColumns = [
		'ssn', 'name', 'surname', 'title', 'address', 'city', 'zip', 'nationality_id',
		'language_id', 'gender_id', 'created_at', 'updated_at', 'deleted_at'
	];
	$peopleNamedParameters = [];
	for ($i = 0; $i < count($peopleColumns); $i++) {
		$peopleNamedParameters[] = ':' . $peopleColumns[$i];
	}

	$parent = findParentByIdCard($cleanedFields['motherIDCard']);
	$peopleSql = 'INSERT INTO people ('. implode(', ', $peopleColumns) .') VALUES ('. implode(', ', $peopleNamedParameters) .')';

	if (empty($parent)) {

		$statement = $connection->prepare($peopleSql);

		$statement->bindValue(':ssn', $cleanedFields['motherID']);
		$statement->bindValue(':name', $cleanedFields['motherName']);
		$statement->bindValue(':surname', $cleanedFields['motherSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId1 = $connection->lastInsertId();

		$addressTableColumns = [
			'street_and_house_number', 'city', 'zip', 'created_at', 'updated_at', 'deleted_at',
		];
		$addressNamedParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressTableColumns);
		$addressSql = 'INSERT INTO addresses ('. implode(', ', $addressTableColumns) .') VALUES ('. implode(', ', $addressNamedParameters) .')';

		$statement = $connection->prepare($addressSql);
		$statement->bindValue(':street_and_house_number', $cleanedFields['motherAddress']);
		$statement->bindValue(':city', $cleanedFields['motherCity']);
		$statement->bindValue(':zip', $cleanedFields['motherZIP']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$addressId = $connection->lastInsertId();

		$addressPersonTableColumns = [
			'address_id', 'person_id', 'is_transition_address',
		];
		$addressPersonNameParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressPersonTableColumns);
		$addressPersonSql = 'INSERT INTO address_person ('. implode(', ', $addressPersonTableColumns) .') VALUES ('. implode(', ', $addressPersonNameParameters) .')';

		$statement = $connection->prepare($addressPersonSql);
		$statement->bindValue('address_id', $addressId);
		$statement->bindValue('person_id', $personId1);
		$statement->bindValue('is_transition_address', 0);
		$statement->execute();

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
		$statement->bindValue(':person_id', $personId1);
		$statement->bindValue(':email', $cleanedFields['motherEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['motherPhone']);
		$statement->bindValue(':id_card', $cleanedFields['motherIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$parentId1 = $connection->lastInsertId();
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
		$statement->bindValue(':ssn', $cleanedFields['motherID']);
		$statement->bindValue(':name', $cleanedFields['motherName']);
		$statement->bindValue(':surname', $cleanedFields['motherSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId1 = $person['id'];
		$address = findOneAddressByPersonId($personId1)[0];

		$addressTableColumns = [
			'street_and_house_number', 'city', 'zip', 'created_at', 'updated_at', 'deleted_at',
		];
		$addressNamedParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressTableColumns);

		$addressUpdateSql = array_map(function ($column, $namedParameter) {
			return $column . ' = ' .$namedParameter;
		}, $addressTableColumns, $addressNamedParameters);

		$addressSql = 'UPDATE addresses SET '. implode(', ', $addressUpdateSql) .' WHERE id = :id';

		$statement = $connection->prepare($addressSql);
		$statement->bindValue(':id', $address['id']);
		$statement->bindValue(':street_and_house_number', $cleanedFields['motherAddress']);
		$statement->bindValue(':city', $cleanedFields['motherCity']);
		$statement->bindValue(':zip', $cleanedFields['motherZIP']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

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
		$statement->bindValue(':person_id', $personId1);
		$statement->bindValue(':email', $cleanedFields['motherEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['motherPhone']);
		$statement->bindValue(':id_card', $cleanedFields['motherIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$parentId1 = $parent['id'];
	}

	// father
	$peopleColumns = [
		'ssn', 'name', 'surname', 'title', 'address', 'city', 'zip', 'nationality_id',
		'language_id', 'gender_id', 'created_at', 'updated_at', 'deleted_at'
	];
	$peopleNamedParameters = [];
	for ($i = 0; $i < count($peopleColumns); $i++) {
		$peopleNamedParameters[] = ':' . $peopleColumns[$i];
	}

	$parent = findParentByIdCard($cleanedFields['fatherIDCard']);
	$peopleSql = 'INSERT INTO people ('. implode(', ', $peopleColumns) .') VALUES ('. implode(', ', $peopleNamedParameters) .')';

	if (empty($parent)) {

		$statement = $connection->prepare($peopleSql);

		$statement->bindValue(':ssn', $cleanedFields['fatherID']);
		$statement->bindValue(':name', $cleanedFields['fatherName']);
		$statement->bindValue(':surname', $cleanedFields['fatherSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId2 = $connection->lastInsertId();

		$addressTableColumns = [
			'street_and_house_number', 'city', 'zip', 'created_at', 'updated_at', 'deleted_at',
		];
		$addressNamedParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressTableColumns);
		$addressSql = 'INSERT INTO addresses ('. implode(', ', $addressTableColumns) .') VALUES ('. implode(', ', $addressNamedParameters) .')';

		$statement = $connection->prepare($addressSql);
		$statement->bindValue(':street_and_house_number', $cleanedFields['fatherAddress']);
		$statement->bindValue(':city', $cleanedFields['fatherCity']);
		$statement->bindValue(':zip', $cleanedFields['fatherZIP']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$addressId = $connection->lastInsertId();

		$addressPersonTableColumns = [
			'address_id', 'person_id', 'is_transition_address',
		];
		$addressPersonNameParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressPersonTableColumns);
		$addressPersonSql = 'INSERT INTO address_person ('. implode(', ', $addressPersonTableColumns) .') VALUES ('. implode(', ', $addressPersonNameParameters) .')';

		$statement = $connection->prepare($addressPersonSql);
		$statement->bindValue('address_id', $addressId);
		$statement->bindValue('person_id', $personId2);
		$statement->bindValue('is_transition_address', 0);
		$statement->execute();

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
		$statement->bindValue(':person_id', $personId2);
		$statement->bindValue(':email', $cleanedFields['fatherEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['fatherPhone']);
		$statement->bindValue(':id_card', $cleanedFields['fatherIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$parentId2 = $connection->lastInsertId();
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
		$statement->bindValue(':ssn', $cleanedFields['fatherID']);
		$statement->bindValue(':name', $cleanedFields['fatherName']);
		$statement->bindValue(':surname', $cleanedFields['fatherSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$personId2 = $person['id'];

		$address = findOneAddressByPersonId($personId2)[0];

		$addressTableColumns = [
			'street_and_house_number', 'city', 'zip', 'created_at', 'updated_at', 'deleted_at',
		];
		$addressNamedParameters = array_map(function ($item) {
			return ':' . $item;
		}, $addressTableColumns);

		$addressUpdateSql = array_map(function ($column, $namedParameter) {
			return $column . ' = ' . $namedParameter;
		}, $addressTableColumns, $addressNamedParameters);

		$addressSql = 'UPDATE addresses SET '. implode(', ', $addressUpdateSql) .' WHERE id = :id';

		$statement = $connection->prepare($addressSql);
		$statement->bindValue(':id', $personId2);
		$statement->bindValue(':street_and_house_number', $cleanedFields['fatherAddress']);
		$statement->bindValue(':city', $cleanedFields['fatherCity']);
		$statement->bindValue(':zip', $cleanedFields['fatherZIP']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

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
		$statement->bindValue(':person_id', $personId2);
		$statement->bindValue(':email', $cleanedFields['fatherEmail']);
		$statement->bindValue(':phone_number', $cleanedFields['fatherPhone']);
		$statement->bindValue(':id_card', $cleanedFields['fatherIDCard']);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
		$statement->bindValue(':deleted_at', null);
		$statement->execute();

		$parentId2 = $parent['id'];
	}

	// student
	$person = findPersonBySsn($cleanedFields['childID']);
	if (empty($person)) {

		$statement = $connection->prepare($peopleSql);
		$statement->bindValue(':ssn', $cleanedFields['childID']);
		$statement->bindValue(':name', $cleanedFields['childName']);
		$statement->bindValue(':surname', $cleanedFields['childSurname']);
		$statement->bindValue(':title', null);
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
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
		if (array_key_exists('vacciantionStatement', $cleanedFields) && $cleanedFields['vaccinationStatement'] == 'on') {
			$statement->bindValue(':is_vaccinated', 1);
		} else {
			$statement->bindValue(':is_vaccinated', null);
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
		$statement->bindValue(':address', null);
		$statement->bindValue(':city', null);
		$statement->bindValue(':zip', null);
		$statement->bindValue(':nationality_id', null);
		$statement->bindValue(':language_id', null);
		$statement->bindValue(':gender_id', null);
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
		if (array_key_exists('vaccinationStatement', $cleanedFields) && $cleanedFields['vaccinationStatement'] == 'on') {
			$statement->bindValue(':is_vaccinated', 1);
		} else {
			$statement->bindValue(':is_vaccinated', null);
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

	// reservation
	$reservationsTableColumns = [
		'student_id', 'parent_id', 'parent2_id', 'time_start', 'time_end', 'day_attendance',
		'morning_attendance', 'afternoon_attendance', 'is_kindergarten_application',
		'created_at', 'updated_at', 'deleted_at'
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
	$statement->bindValue(':parent_id', $parentId1);
	$statement->bindValue(':parent2_id', $parentId2);
	$statement->bindValue(':time_start', null);
	$statement->bindValue(':time_end', null);

	if (empty($cleanedFields['wholeDayAttendance'])) {
		$statement->bindValue(':day_attendance', null);
	} else {
		$statement->bindValue(':day_attendance', implode(',', $cleanedFields['wholeDayAttendance']));
	}

	if (empty($cleanedFields['morningAttendance'])) {
		$statement->bindValue(':morning_attendance', null);
	} else {
		$statement->bindValue(':morning_attendance', implode(',', $cleanedFields['morningAttendance']));
	}

	if (empty($cleanedFields['afternoon_attendance'])) {
		$statement->bindValue(':afternoon_attendance', null);
	} else {
		$statement->bindValue(':afternoon_attendance', implode(',', $cleanedFields['afternoonAttendance']));
	}

	$statement->bindValue(':is_kindergarten_application', 1);
	$statement->bindValue(':created_at', date('Y-m-d H:i:s'));
	$statement->bindValue(':updated_at', date('Y-m-d H:i:s'));
	$statement->bindValue(':deleted_at', null);
	$statement->execute();

	$parentStudentTableColumns = ['parent_id', 'student_id'];
	$parentStudentNamedParameters = array_map(
		function ($columns) {
			return ':'. $columns;
		},
		$parentStudentTableColumns
	);
	$parent1StudentInsertSql = 'INSERT INTO parent_student ('. implode(', ', $parentStudentTableColumns) .') VALUES ('. implode(', ', $parentStudentNamedParameters) .')';
	$statement = $connection->prepare($parent1StudentInsertSql);
	$statement->bindValue(':parent_id', $parentId1);
	$statement->bindValue(':student_id', $studentId);
	$statement->execute();

	$parent2StudentInsertSql = 'INSERT INTO parent_student ('. implode(', ', $parentStudentTableColumns) .') VALUES ('. implode(', ', $parentStudentNamedParameters) .')';
	$statement = $connection->prepare($parent2StudentInsertSql);
	$statement->bindValue(':parent_id', $parentId2);
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

$template = new EmailTemplate('application-email.html');
$template->data = $cleanedFields;

$pdf = new Pdf($template->parse());
if (getenv('WKHTMLTOPDF_BIN')) {
	$pdf->binary = getenv('WKHTMLTOPDF_BIN');
} else {
	file_put_contents('log.txt', 'Add path to wkhtmltopdf binary to .env file', FILE_APPEND);
}

$message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam dictum sem nec quam pulvinar,
congue rhoncus tortor placerat. Pellentesque quis enim in lectus volutpat tempor et et odio. Praesent quis
porta ligula. Sed sed urna a sem efficitur sollicitudin nec gravida quam. Vivamus bibendum massa imperdiet,
malesuada ligula quis, venenatis est. Vestibulum sagittis augue in mi feugiat, in aliquam ex ullamcorper.
Etiam placerat magna sit amet commodo aliquam. Donec sed turpis semper, sodales ex in, condimentum mi.

Aenean vitae volutpat urna. Nunc ut diam ut mi consectetur maximus sed nec neque. Etiam arcu nisl, sagittis
sed tempor ac, facilisis ac mauris. Sed id sagittis dolor. Donec venenatis tempus nibh a molestie. Sed volutpat
 nulla a risus congue, nec tempor felis hendrerit. Sed tincidunt ultrices enim at scelerisque. Maecenas sagittis
 vehicula dolor, sed elementum neque commodo id. Maecenas scelerisque elit at est dapibus, vitae fermentum purus
 vehicula. Curabitur auctor leo vel erat porta finibus at ut purus. Proin vitae elementum ex, nec ultricies sem.
 Fusce sed velit nec massa interdum elementum in vel metus. Fusce eu finibus ipsum, vel fringilla felis.';

if (getenv('MAIL_TEST')) {
	$result = EmailSender::send(getenv('MAIL_TEST'), 'test', $message, 'rezervace@skolkahafik.cz', $pdf->toString());
} else {
	EmailSender::send('info@skolkahafik.cz', 'Přihláška do školky', $message, 'rezervace@skolkahafik.cz', $pdf->toString());
	EmailSender::send($cleanedFields['fatherEmail'], 'Přihláška do školky', $message, 'rezervace@skolkahafik.cz', $pdf->toString());
	EmailSender::send($cleanedFields['motherEmail'], 'Přihláška do školky', $message, 'rezervace@skolkahafik.cz', $pdf->toString());
}

if ($result) {
	unset($_SESSION['reg-form-data']);
	flash('registration', 'Přihláška byla úspěšně uložena a na Vaši e-mailovou adresu jsme zaslali její potvrzení', 'alert alert-success');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace-skolka.php');
	exit;
} else {
	flash('registration', 'Chyba při zpracování přihlášky. Zkuste registraci prosím vyplnit ještě jednou a pokud se Vám tato zpráva objeví podruhé, tak nám prosím zavolejte na tel. číslo: 604787347.', 'alert alert-danger');
	header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace-skolka.php');
	exit;
}

function clean($value)
{
	return trim(htmlspecialchars($value));
}

function checkInput($formFields) {
	$cleanedFields = [];
	$emptyFields = [];

	if (!empty($formFields['motherName'])) {
		$cleanedFields['motherName'] = clean($formFields['motherName']);
	} else {
		$emptyFields[] = 'jméno zákonného zástupce';
	}
	if (!empty($formFields['motherSurname'])) {
		$cleanedFields['motherSurname'] = clean($formFields['motherSurname']);
	} else {
		$emptyFields[] = 'příjmení zákonného zástupce';
	}
	if (!empty($formFields['motherIDCard'])) {
		$cleanedFields['motherIDCard'] = clean($formFields['motherIDCard']);
	} else {
		$emptyFields[] = 'číslo občanského průkazu zákonného zástupce';
	}
	if (!empty($formFields['motherID'])) {
		$id = clean($formFields['motherID']);
		$cleanedFields['motherID'] = str_replace('/', '', $id);
	} else {
		$emptyFields[] = 'rodné číslo zákonného zástupce';
	}
	if (!empty($formFields['motherPhone'])) {
		$cleanedFields['motherPhone'] = clean($formFields['motherPhone']);
	} else {
		$emptyFields[] = 'telefon zákonného zástupce';
	}
	if (!empty($formFields['motherEmail'])) {
		$cleanedFields['motherEmail'] = clean($formFields['motherEmail']);
	} else {
		$emptyFields[] = 'email zákonného zástupce';
	}
	if (!empty($formFields['motherAddress'])) {
		$cleanedFields['motherAddress'] = clean($formFields['motherAddress']);
	} else {
		$emptyFields[] = 'ulice a číslo popisné zákonného zástupce';
	}
	if (!empty($formFields['motherCity'])) {
		$cleanedFields['motherCity'] = clean($formFields['motherCity']);
	} else {
		$emptyFields[] = 'město zákonného zástupce';
	}
	if (!empty($formFields['motherZIP'])) {
		$cleanedFields['motherZIP'] = clean($formFields['motherZIP']);
	} else {
		$emptyFields[] = 'PSČ zákonného zástupce';
	}

	if (!empty($formFields['fatherName'])) {
		$cleanedFields['fatherName'] = clean($formFields['fatherName']);
	} else {
		$emptyFields[] = 'jméno zákonného zástupce';
	}
	if (!empty($formFields['fatherSurname'])) {
		$cleanedFields['fatherSurname'] = clean($formFields['fatherSurname']);
	} else {
		$emptyFields[] = 'příjmení zákonného zástupce';
	}
	if (!empty($formFields['fatherIDCard'])) {
		$cleanedFields['fatherIDCard'] = clean($formFields['fatherIDCard']);
	} else {
		$emptyFields[] = 'číslo občanského průkazu zákonného zástupce';
	}
	if (!empty($formFields['fatherID'])) {
		$id = clean($formFields['fatherID']);
		$cleanedFields['fatherID'] = str_replace('/', '', $id);
	} else {
		$emptyFields[] = 'rodné číslo zákonného zástupce';
	}
	if (!empty($formFields['fatherPhone'])) {
		$cleanedFields['fatherPhone'] = clean($formFields['fatherPhone']);
	} else {
		$emptyFields[] = 'telefon zákonného zástupce';
	}
	if (!empty($formFields['fatherEmail'])) {
		$cleanedFields['fatherEmail'] = clean($formFields['fatherEmail']);
	} else {
		$emptyFields[] = 'email zákonného zástupce';
	}
	if (!empty($formFields['fatherAddress'])) {
		$cleanedFields['fatherAddress'] = clean($formFields['fatherAddress']);
	} else {
		$emptyFields[] = 'ulice a číslo popisné zákonného zástupce';
	}
	if (!empty($formFields['fatherCity'])) {
		$cleanedFields['fatherCity'] = clean($formFields['fatherCity']);
	} else {
		$emptyFields[] = 'město zákonného zástupce';
	}
	if (!empty($formFields['fatherZIP'])) {
		$cleanedFields['fatherZIP'] = clean($formFields['fatherZIP']);
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
	// if ($formFields['rulesApproval'] !== 'on') {
	// 	$emptyFields[] = 'potvrzení podmínek';
	// }

	$wholeDayAttendance = [];
	if (array_key_exists('wholeDay', $formFields)) {
		$wholeDayAttendance = $formFields['wholeDay'];
	}

	$morningAttendance = [];
	if (array_key_exists('morning', $formFields)) {
		$morningAttendance = $formFields['morning'];
	}

	$afternoonAttendance = [];
	if (array_key_exists('afternoon', $formFields)) {
		$afternoonAttendance = $formFields['afternoon'];
	}

	if (empty($wholeDayAttendance) && empty($morningAttendance) && empty($afternoonAttendance)) {
		$emptyFields[] = 'docházka dítěte';
	}

	$cleanedFields['wholeDayAttendance'] = array_keys($wholeDayAttendance);
	$cleanedFields['morningAttendance'] = array_keys($morningAttendance);
	$cleanedFields['afternoonAttendance'] = array_keys($afternoonAttendance);

	$cleanedFields['vaccinationStatement'] = $formFields['vaccinationStatement'];

	if (!empty($emptyFields)) {
		$messages = [];
		foreach ($emptyFields as $emptyField) {
			$messages[] = '<p>'. $emptyField .'</p>';
		}
		flash('registration-form-error', implode('', $messages), 'alert alert-danger');
		header('Location: http://' .$_SERVER['HTTP_HOST'].'/rezervace-skolka.php');
		exit;
	}

	return $cleanedFields;
}
