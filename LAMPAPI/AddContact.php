<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Check that user with given user ID exists
		$sql = "SELECT * from Contacts where ID={$userId}";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "Invalid User ID" );

		// Add contact to database
		$sql = "INSERT into Contacts (ID,FirstName,LastName,PhoneNumber,Email) VALUES ({$userId}, '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$email}')";
		$result = $db->query($sql);

			if (!$result)
				throw newException( $db->error );

		// Return no error
		returnWithError("");

		// Close database connection
		$db->close();
	}
	catch (Exception $exception)
	{
		returnWithError( $exception->getMessage() );
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