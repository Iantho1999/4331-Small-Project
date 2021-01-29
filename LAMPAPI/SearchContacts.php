<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$search = $indata["search"];
	$userId = $indata["userId"];

	// API Response Variables
	$searchResults = [];

	try
	{
		// Connect to database
		$db = mysqli_connect("localhost", "TheBeast", "group13sql", "COP4331");

			if ($db->connect_error)
				throw new Exception( $db->connect_error );

		// Search for contacts that match the given search
		$sql = "SELECT ID, FirstName, LastName, PhoneNumber, Email from Contacts where userID={$userId} and (FirstName like '%{$search}%' or LastName like '%{$search}%' or PhoneNumber like '%{$search}%' or Email like '%{$search}%')";
		$result = $db->query($sql);

			if ($result->num_rows == 0)
				throw new Exception( "No Records Found" );

		// Compile search results
		$searchResults = $result->fetch_all(MYSQLI_ASSOC);

		// Return search results
		returnWithInfo($searchResults);

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
		$retValue = json_encode( ['results' => [], 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = json_encode( ['results' => $searchResults, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>