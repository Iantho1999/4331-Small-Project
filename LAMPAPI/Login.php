<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$login = $indata["login"];
	$password = $indata["password"];

	// API Response Variables
	$id = 0;
	$firstName = "";
	$lastName = "";

	// Connect to database
	$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($db->connect_error)
	{
		returnWithError( $db->connect_error );
	}
	else
	{
		// Search for user that matches given login and password
		$sql = "SELECT ID,FirstName,LastName FROM Users where Login='{$login}' and Password='{$password}'";
		$result = $db->query($sql);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();

			$id = $row["ID"];
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];

			returnWithInfo($id, $firstName, $lastName);
		}
		else
		{
			returnWithError( "No Records Found" );
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
		$retValue = json_encode( ['id' => 0, 'firstName' => "", 'lastName' => "", 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $id, $firstName, $lastName )
	{
		$retValue = json_encode( ['id' => $id, 'firstName' => $firstName, 'lastName' => $lastName, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>