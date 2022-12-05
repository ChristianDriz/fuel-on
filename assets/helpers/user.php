<?php  

function getUser($userID, $conn){
   $sql = "SELECT * FROM tbl_users 
           WHERE userID = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$userID]);

   if ($stmt->rowCount() === 1) {
   	 $user = $stmt->fetch();
   	 return $user;
   }else {
   	$user = [];
   	return $user;
   }
}