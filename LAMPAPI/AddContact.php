<?php
	$indata = getRequestInfo();

	// TODO: update according to client-side JSON names
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];

	$connection = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$sql = "INSERT into Contacts (ID,FirstName,LastName,PhoneNumber,Email) VALUES (" . $userId . ", '" . $firstName . "', '" . $lastName . "', '" . $phoneNumber . "', '" . $email . "')";
		$result = $connection->query($sql);

		if ( $result != TRUE )
		{
			returnWithError( $connection->error );
		}
		else
		{
			returnWithError("");
		}

		$connection->close();
	}


	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}

	function returnWithError( $error )
	{
		// TODO: update according to client-side JSON names
		$retValue = '{"error":"' . $error . '"}';
		sendResultInfoAsJson( $retValue );
	}
?>