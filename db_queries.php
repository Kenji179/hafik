<?php
require_once 'mysql_connect.php';

function findAllGenders()
{
	$connection = getDbConnection();
	$statement = $connection->query('SELECT * FROM genders');
	$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function findGenderById($id)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT * FROM genders WHERE id = :id');
	$statement->bindValue(':id', $id);
	$statement->execute();
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function findPersonById($id)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT id FROM people WHERE id = :id');
	$statement->bindValue(':id', $id);
	$statement->execute();
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function findPersonBySsn($ssn)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT id FROM people WHERE ssn = :ssn');
	$statement->bindValue(':ssn', $ssn);
	$statement->execute();
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function findParentByIdCard($idCard)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT id, person_id FROM parents WHERE id_card = :id_card');
	$statement->bindValue(':id_card', $idCard);
	$statement->execute();
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function findStudentByPersonId($personId)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT id FROM students WHERE person_id = :person_id');
	$statement->bindValue(':person_id', $personId);
	$statement->execute();
	return $statement->fetch(PDO::FETCH_ASSOC);
}

function findOneAddressByPersonId($personId)
{
	$connection = getDbConnection();
	$statement = $connection->prepare('SELECT * FROM addresses WHERE id IN (SELECT address_id FROM address_person WHERE person_id = :person_id)');
	$statement->bindValue(':person_id', $personId);
	$statement->execute();
	return $statement->fetchAll(PDO::FETCH_ASSOC);
}
