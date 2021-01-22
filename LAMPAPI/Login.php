<?php
	$indata = getRequestInfo();

	$id = 0;
	$firstName = "";
	$lastName = "";

	// TODO: fill in database name, username, and password 
	$connection = new mysqli("localhost", "db_username", "db_password", "db");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		// TODO: update according to database column names (Login/Username, Password) and client-side JSON names (Login/Username, Password)
		$sql = "SELECT ID,firstName,lastName FROM Users where Login='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";
		$result = $connection->query($sql);

		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();

			// TODO: update according to database column names (ID, firstName, lastName)
			$id = $row["ID"];
			$firstName = $row["firstName"];
			$lastName = $row["lastName"];

			returnWithInfo($id, $firstName, $lastName);
		}
		else
		{
			returnWithError( "No Records Found" );
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
		$retValue = '{"id":0, "firstname":"", "lastName":"", "error":"' . $error . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $id, $firstName, $lastName )
	{
		// TODO: update according to client-side JSON names
		$retValue = '{"id":' . $id . ', "firstName":"' . $firstName . '", "lastName":"' . $lastName . '", "error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>