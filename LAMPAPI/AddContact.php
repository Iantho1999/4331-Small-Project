<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];

	// API Response Variables
	$id = 0;

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Check that user with given user ID exists
		$sql = "SELECT * from Users where ID={$userId}";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "Invalid User ID" );

		// Add contact to database
		$sql = "INSERT into Contacts (userID,FirstName,LastName,PhoneNumber,Email) VALUES ({$userId}, '{$firstName}', '{$lastName}', '{$phoneNumber}', '{$email}')";
		$result = $db->query($sql);

			if (!$result)
				throw new Exception( $db->error );

		// Search ID of new contact
		$sql = "SELECT ID from Contacts where userID={$userId} and FirstName='{$firstName}' and LastName='{$lastName}' and PhoneNumber='{$phoneNumber}' and Email='{$email}'";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "No Records Found" );

		$rows = $result->fetch_all(MYSQLI_ASSOC);
		$id = $rows[array_key_last($rows)]['ID'];

		// Return ID
		returnWithInfo($id);

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
		$retValue = json_encode( ['id' => 0, 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $id )
	{
		$retValue = json_encode( ['id' => $id, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>