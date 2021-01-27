<?php
	$indata = getRequestInfo();

	$searchResults = array();
	$searchCount = 0;

	$connection = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($connection->connect_error)
	{
		returnWithError( $connection->connect_error );
	}
	else
	{
		$sql = "SELECT * from Contacts where ID=" . $indata["userId"] . " and (FirstName like '%" . $indata["search"] . "%' or LastName like '%" . $indata["search"] . "%' or PhoneNumber like '%" . $indata["search"] . "%' or Email like '%" . $indata["search"] . "%')";
		$result = $connection->query($sql);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$searchResults[] = array(
					'firstName' => $row["FirstName"],
					'lastName' => $row["LastName"],
					'phoneNumber' => $row["PhoneNumber"],
					'email' => $row["Email"] );

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
		$retValue = json_encode(array( 'results' => array(), 'error' => $error ));
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = json_encode(array( 'results' => $searchResults, 'error' => "" ));
		sendResultInfoAsJson( $retValue );
	}
?>