<?php

function My_Connect_DB()
{
$servername = "localhost";
$username = "thebestwebsiteever";
$password = "";
$dbname = "my_thebestwebsiteever";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn)
    die("connection to db failed: " .mysqli_connect_error()."<br/>");

return $conn;

}

function uploadfile($fname, $fileAllowed, $sizeAllowed, $overwriteAllowed)
{

    $uploadOK = true;
    //bolean true = 1
    //bolean false = "

    $dir = "upload/";

    $file = $dir . basename($_FILES[$fname]["name"]);
    $fileType = pathinfo($file, PATHINFO_EXTENSION);
    $filesize = $_FILES[$fname]["size"];

    if($filesize > $sizeAllowed) { echo "file is to big<br/>"; $uploadOK = false; }
    if(!stristr($fileAllowed, $fileType)) { echo "file type is Not Allowed<br/>"; $uploadOK = false; }
    if(!$overwriteAllowed && file_exists($file))  { echo "File alrealdy exists<br/>"; $uploadOK = false; }
    if($uploadOK == true) 
    {
        if(!move_uploaded_file($_FILES[$fname]["tmp_name"], $file)) $uploadOK = false;
    }
     if($uploadOK == true)  return  $file;
    else
    echo "Something wrong";


}




function my_SQL_EXE($conn, $sql)
{
    if($result = mysqli_query($conn, $sql))
        echo "SQL is done succesfully <br/>";
    else 
        echo "Error running in sql: " .$sql. " with error:  " .mysqli_error($conn)."<br/>";

    return $result;

      
}

function Run_Select_Show_Table($conn, $sql, $table)
{
    $result = my_SQL_EXE($conn, $sql);
    echo "<table border = 1>";
        echo "<tr>";
            while($fieldinfo = mysqli_fetch_field($result))
            {
                echo "<td>";
                    echo $fieldinfo->name;
                echo "</td>";

            }
        echo "</tr>";


        while($row = mysqli_fetch_assoc($result))
        {
            echo "<tr>";

              foreach ($row as $key => $value) 
              {
                  echo "<td> ".$value." </td>";
              }

            echo "</tr>";

        }
    
    echo "</table>";

    echo "Total Rows: ". mysqli_num_rows($result). "<br/>";

}



function Run_SQL_Show_Table($conn, $sql, $table) //non select sql statement
{
    $result = my_SQL_EXE($conn, $sql);

    $sql = "SELECT * FROM ".$table.";";

    Run_Select_Show_Table($conn, $sql, $table);



}
