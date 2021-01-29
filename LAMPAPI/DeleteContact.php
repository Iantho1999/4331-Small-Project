<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$id = $indata["id"];
	$userId = $indata["userId"];

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Check that contact exists for given user
		$sql = "SELECT ID from Contacts where ID={$id} and userID={$userId}";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "No Records Found" );

		// Delete contact from database
		$sql = "DELETE from Contacts where ID={$id} LIMIT 1";
		$result = $db->query($sql);

			if (!$result)
				throw new Exception( $db->error );

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