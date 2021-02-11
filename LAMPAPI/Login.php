<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$login = $indata["login"];
	$password = $indata["password"];

	// API Response Variables
	$id = 0;
	$firstName = "";
	$lastName = "";

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "group13sql", "COP4331");
	
			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Search user ID & information that matches given login and password
		$sql = "SELECT ID,FirstName,LastName FROM Users where Login='{$login}' and Password='{$password}'";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "No Records Found" );

		// Return user ID & information
		$row = $result->fetch_assoc();
		$id = $row["ID"];
		$firstName = $row["FirstName"];
		$lastName = $row["LastName"];

		returnWithInfo($id, $firstName, $lastName);

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
		$retValue = json_encode( ['id' => 0, 'firstName' => "", 'lastName' => "", 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $id, $firstName, $lastName )
	{
		$retValue = json_encode( ['id' => $id, 'firstName' => $firstName, 'lastName' => $lastName, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>