<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$search = $indata["search"];
	$userId = $indata["userId"];

	// API Response Variables
	$searchResults = [];

	$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($db->connect_error)
	{
		returnWithError( $db->connect_error );
	}
	else
	{
		$sql = "SELECT * from Contacts where ID={$userId} and (FirstName like '%{$search}%' or LastName like '%{$search}%' or PhoneNumber like '%{$search}%' or Email like '%{$search}%')";
		$result = $db->query($sql);

		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$searchResults[] = array(
					'firstName' => $row["FirstName"],
					'lastName' => $row["LastName"],
					'phoneNumber' => $row["PhoneNumber"],
					'email' => $row["Email"] );
			}

			returnWithInfo( $searchResults );
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
		$retValue = json_encode( ['results' => [], 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = json_encode( ['results' => $searchResults, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>