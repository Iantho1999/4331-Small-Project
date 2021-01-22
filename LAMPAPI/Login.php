<?php
	$indata = getRequestInfo();

	$id = 0;
	$firstName = "";
	$lastName = "";

	$connection = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$sql = "SELECT ID,FirstName,LastName FROM Users where Login='" . $indata["login"] . "' and Password='" . $indata["password"] . "'";
		$result = $connection->query($sql);

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