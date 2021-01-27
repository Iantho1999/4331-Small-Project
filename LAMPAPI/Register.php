<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$login = $indata["login"];
	$password = $indata["password"];

	// API Response Variables
	$id = 0;

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Check that username is not already taken
		$sql = "SELECT ID FROM Users where Login='{$login}'";
		$result = $db->query($sql);

			if ($result->num_rows > 0)
				throw new Exception( "User \"{$login}\" Already Exists" );

		// Add user to database
		$sql = "INSERT into Users (FirstName,LastName,Login,Password) VALUES ('{$firstName}', '{$lastName}', '{$login}', '{$password}')";
		$result = $db->query($sql);

			if (!$result)
				throw new Exception( $db->error );

		// Search ID of new user
		$sql = "SELECT ID from Users where Login='{$login}' and Password='{$password}'";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "No Records Found" );

		// Return ID
		$row = $result->fetch_assoc();
		$id = $row["ID"];

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