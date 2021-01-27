<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];

	$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($db->connect_error)
	{
		returnWithError( $db->connect_error );
	}
	else
	{
		$sql = "INSERT into Contacts (ID,FirstName,LastName,PhoneNumber,Email) VALUES ({$userId}, '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$email}')";
		$result = $db->query($sql);

		if (!$result)
			returnWithError( $db->error );
		else
			returnWithError("");

		$db->close();
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
		$retValue = json_encode( ['error' => $error] );
		sendResultInfoAsJson( $retValue );
	}
?>