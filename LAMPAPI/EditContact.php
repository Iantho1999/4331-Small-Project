<?php
  $indata = getRequestInfo();
  
  // API Parameter Variables
  $id = $indata["id"];
  $firstName = $indata["firstName"];
	$lastName = $indata["lastName"];
	$phoneNumber = $indata["phoneNumber"];
	$email = $indata["email"];
	$userId = $indata["userId"];
 
  try
  {
    // Connect to database
    $db = mysqli_connect("localhost", "TheBeast", "group13sql", "COP4331");
   
    if ($db->connect_error)
    {
      throw new Exception($db->connect_error);
    }
   
    // Check that the user with the given ID exists
    $sql = "SELECT * from Users where ID={$userId}";
    $result = $db->query($sql);
   
    if ($result->num_rows == 0)
    {
      throw new Exception("Invalid User ID");
    }
   
    // Edit the contact
    $sql = "UPDATE Contacts SET FirstName = '{$firstName}', LastName = '{$lastName}', PhoneNumber = '{$phoneNumber}', Email = '{$email}' WHERE userID = {$userId} AND ID={$id}";
    $result = $db->query($sql);
   
    if (!$result)
    {
      throw new Exception($db->error);
    }
   
    // Close database connection
    $db->close();
  }
  catch(Exception $exception)
  {
     returnWithError($exception->getMessage());
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