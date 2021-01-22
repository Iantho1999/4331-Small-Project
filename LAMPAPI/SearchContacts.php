<?php
	$indata = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;

	// TODO: fill in database name, username, and password 
	$connection = new mysqli("localhost", "db_username", "db_password", "db");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		// TODO: update according to database column names (ID, FirstName, LastName, PhoneNumber, Email) and client-side JSON names (userId, search)
		$sql = "SELECT * from Contacts where ID=" . $inData["userId"] . " and (FirstName like '%" . $inData["search"] . "%' or LastName like '%" . $inData["search"] . "%' or PhoneNumber like '%" . $inData["search"] . "%' or Email like '%" . $inData["search"] . "%')";
		$result = $connection->query($sql);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				if ( $searchCount > 0 )
				{
					$searchResults .= ",";
				}

				// TODO: update according to database column names (FirstName, LastName, PhoneNumber, Email)
				$searchResults .= '{"firstName":"' . $row["FirstName"] . '", "lastName":"' . $row["LastName"] . '", "phoneNumber":"' . $row["PhoneNumber"] . '", "email":"' . $row["email"] . '"}';
				$searchCount++;
			}

			returnWithInfo( $searchResults );
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
		$retValue = '{"results":[], "error":"' . $error . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		// TODO: update according to client-side JSON names
		$retValue = '{"results":[' . $searchResults . '], "error":""}';
		sendResultInfoAsJson( $retValue );
	}
?>