<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];

	// Connect to database
	$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($db->connect_error)
	{
		returnWithError( $db->connect_error );
	}
	else
	{
		// Check that user with given user ID exists
		$sql = "SELECT * from Contacts where ID={$userId}";
		$result = $db->query($sql);

		if ($result->num_rows > 0)
		{
			// Add contact to database
			$sql = "INSERT into Contacts (ID,FirstName,LastName,PhoneNumber,Email) VALUES ({$userId}, '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$email}')";
			$result = $db->query($sql);

			if ($result)
				returnWithError("");
			else
				returnWithError( $db->error );
		}
		else
		{
			returnWithError( "Invalid User ID" );
		}

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