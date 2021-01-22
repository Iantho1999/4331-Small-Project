<?php
	$indata = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;

	$connection = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		// TODO: update according to client-side JSON names (userId, search)
		$sql = "SELECT * from Contacts where ID=" . $indata["userId"] . " and (FirstName like '%" . $indata["search"] . "%' or LastName like '%" . $indata["search"] . "%' or PhoneNumber like '%" . $indata["search"] . "%' or Email like '%" . $indata["search"] . "%')";
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
				$searchResults .= '{"firstName":"' . $row["FirstName"] . '", "lastName":"' . $row["LastName"] . '", "phoneNumber":"' . $row["PhoneNumber"] . '", "email":"' . $row["Email"] . '"}';
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