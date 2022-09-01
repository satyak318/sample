<?php include "../inc/dbinfo.inc"; ?>
<html>
<body>
<center><h1 style="background-color:DodgerBlue;">Amazon Web Services Project Work (Ankit Narula)</h1></center>
<body style="background-color:grey;">

<?php

  /* Connect to MySQL and select the database. */
  $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

  if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

  $database = mysqli_select_db($connection, DB_DATABASE);

  /* Ensure that the EMPLOYEES table exists. */
  VerifyEmployeesTable($connection, DB_DATABASE);

  /* If input fields are populated, add a row to the EMPLOYEES table. */
  $employee_name = htmlentities($_POST['NAME']);
  $employee_address = htmlentities($_POST['ADDRESS']);
  $employee_qualification = htmlentities($_POST['QUALIFICATION']);
  $employee_country = htmlentities($_POST['COUNTRY']);

  if (strlen($employee_name) || strlen($employee_address) || strlen($employee_qualification) || strlen($employee_country)) {
    AddEmployee($connection, $employee_name, $employee_address, $employee_qualification, $employee_country);
  }
?>

<!-- Input form -->
<form action="<?PHP echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
<table>
<tr>
<td>NAME</td>
<td><input type="text" name="NAME" maxlength="45" size="30" /> </td>
</tr>
<tr>
<td>ADDRESS</td>
<td><input type="text" name="ADDRESS" maxlength="90" size="60" /></td>
</tr>
<tr>
<td>QUALIFICATION</td>
<td><input type="text" name="QUALIFICATION" maxlength="45" size="60" /> </td>
</tr>
<tr>
<td>COUNTRY</td>
<td><input type="text" name="COUNTRY" maxlength="45" size="60" /> </td>
</tr>
<tr>
<td><input type="submit" value="Submit" /></td>
</tr>

  </table>
</form>

<?php



while($query_data = mysqli_fetch_row($result)) {
  echo "<tr>";
  echo "<td>",$query_data[0], "</td>",
       "<td>",$query_data[1], "</td>",
       "<td>",$query_data[2], "</td>";
  echo "</tr>";
}
?>

</table>

<!-- Clean up. -->
<?php

  mysqli_free_result($result);
  mysqli_close($connection);

?>

</body>
</html>


<?php

/* Add an employee to the table. */
function AddEmployee($connection, $name, $address, $qualification, $country) {
   $n = mysqli_real_escape_string($connection, $name);
   $a = mysqli_real_escape_string($connection, $address);
   $b = mysqli_real_escape_string($connection, $qualification);
   $c = mysqli_real_escape_string($connection, $country);

   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS, QUALIFICATION, COUNTRY) VALUES ('$n', '$a', '$b', '$c');";

   if(!mysqli_query($connection, $query)) echo("<p>Error adding employee data.</p>");
}

/* Check whether the table exists and, if not, create it. */
function VerifyEmployeesTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName))
  {
     $query = "CREATE TABLE EMPLOYEES (
         ID int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
         NAME VARCHAR(45),
         ADDRESS VARCHAR(90),
		 QUALIFICATION Varchar(45),
		 COUNTRY Varchar(50)
       )";

     if(!mysqli_query($connection, $query)) echo("<p>Error creating table.</p>");
  }
}

/* Check for the existence of a table. */
function TableExists($tableName, $connection, $dbName) {
  $t = mysqli_real_escape_string($connection, $tableName);
  $d = mysqli_real_escape_string($connection, $dbName);

  $checktable = mysqli_query($connection,
      "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t' AND TABLE_SCHEMA = '$d'");

  if(mysqli_num_rows($checktable) > 0) return true;

  return false;
}
?>
