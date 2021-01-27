<?php
	$indata = getRequestInfo();

	// API Parameter Variables
	$firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$login = $indata["login"];
	$password = $indata["password"];

	// API Response Variables
	$id = 0;

	// Connect to database
	$db = mysqli_connect("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	if ($db->connect_error)
	{
		returnWithError( $db->connect_error );
	}
	else
	{
		// Check that username is not already taken
		$sql = "SELECT ID,FirstName,LastName FROM Users where Login='{$login}'";
		$result = $db->query($sql);

		if ($result->num_rows == 0)
		{
			// Add user to database
			$sql = "INSERT into Users (FirstName,LastName,Login,Password) VALUES ('{$firstName}', '{$lastName}', '{$login}', '{$password}')";
			$result = $db->query($sql);

			if ($result)
			{
				// Search for ID of new user
				$sql = "SELECT ID from Users where Login='{$login}' and Password='{$password}'";
				$result = $db->query($sql);

				if ($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();

					$id = $row["ID"];

					returnWithInfo($id);
				}
				else
				{
					returnWithError( "No Records Found" );
				}
			}
			else
			{
				returnWithError( $db->error );
			}
		}
		else
		{
			returnWithError( "User \"{$login}\" Already Exists" );
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
		$retValue = json_encode( ['id' => 0, 'error' => $error] );
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $id )
	{
		$retValue = json_encode( ['id' => $id, 'error' => ""] );
		sendResultInfoAsJson( $retValue );
	}
?>