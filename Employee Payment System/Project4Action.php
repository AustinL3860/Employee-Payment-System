<html>

<body>

    <head>
        <title>
            Project 4
        </title>
    </head>

    <body>
        <?php
        include "My-DB-Functions.php";

        $employe_name_error_msg = "";
        $payment_amount_error_msg = "";

        if (isset($_REQUEST['submit1'])) {

            // Break conditions: 
            //we dont want to 
            // run the SQL if the user doesnt give in a employye name or 
            // they input a salary greater then the limit (999999)
            if (empty($_REQUEST['e_name'])) $employe_name_error_msg = "Name is required";
            else if ($_REQUEST['amount'] > 999999) $employe_name_error_msg = "Please put an amount within the limit";



            else {
                //Otherwise add the valid employee data to the database
                $conn = My_Connect_DB();
                $sql = "INSERT INTO Employee2(Name, Type, amount)
    VALUES('" . $_REQUEST['e_name'] . "', '" . $_REQUEST['type'] . "', '" . $_REQUEST['amount'] . "');"; //PAymentID auto increments
                $table = "Employee2"; //Spec used for this proj
                Run_Select_Show_Table($conn, $sql, $table);
            }
        }



        ?>


        <h1>Employee payment system</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])  ?>">
            <!--Employee id: <input type="text" name="id"><br><br>-->
            Employee name: <input type="text" name="e_name">
            <font color=red>* <?php echo $employe_name_error_msg ?></font><br><br>
            Payment type:
            <select name="type">
                <option value="salary">Salary</option>
                <option value="bonus">Bonus</option>
                <option value="other">Other</option>
            </select><br><br>
            Payment amount <input type="text" name="amount" maxlength=6>
            <font color=red>(max 999999) <?php echo $payment_amount_error_msg ?></font>
            <br><br>
            <input type="submit" name="submit1" value="submit"><br>
        </form>
        <hr />


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])  ?>">
            <table style="background-color:pink;margin:auto;" border=0>
                <thead align="center">
                    <tr>
                        <td colspan="2">Display information in different ways</td>
                    </tr>
                </thead>
                <tr>
                    <td colspan=2>
                        <hr />
                    </td>
                    <td>
                <tr>
                    <td align=right><input type=radio name="sortby" value="bynoway" checked></td>
                    <td>Display information in its orignal way</td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="byname"></td>
                    <td>Sort by name in ascending order </td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="byone"></td>
                    <td>Find someone makes how much money (name: <input type="text" name="name">) </td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="bytotal"></td>
                    <td>Find each one makes how much money in total and sort results in descending order</td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="bytype"></td>
                    <td>Find payments for different types (type: <select name="type">
                            <option value="salary">Salary</option>
                            <option value="bonus">Bonus</option>
                            <option value="other">Other</option>
                        </select>) </td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="bymax"></td>
                    <td>Find the employee with highest bonus </td>
                </tr>
                <tr>
                    <td align=right><input type=radio name="sortby" value="bycat"></td>
                    <td>Find the total payments for the 3 catagories</td>
                </tr>
                <tr>
                    <td colspan=2 align="center"><input name="submit2" alt="Login" type="submit" value="display"> </td>
                </tr>
            </table>
        </form>
        <hr />


        <?php

        if ($_REQUEST['submit2']) {

            switch ($_REQUEST['sortby']) {
                case 'bynoway':
                    displayEverything();
                    break;
                case 'byname':
                    sortByNameASC();
                    break;
                case 'byone':
                    findSalryByName($_REQUEST['name']);
                    break;
                case 'bytotal':
                    sortByTotal();
                    break;
                case 'bytype':
                    sortByType($_REQUEST['type']);
                    break;
                case 'bymax':
                    highestBonus();
                    break;
                case 'bycat':
                    totalPaymentByCatagory();
                    break;

                default:
                    "none";
            }
        }




        //Functions the switch case calls
        function displayEverything()
        {
            $conn = My_Connect_DB();
            $sql = "SELECT *
            FROM Employee2;";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function sortByNameASC()
        {
            $conn = My_Connect_DB();
            $sql = "SELECT *
            FROM Employee2
            ORDER BY name ASC;";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function findSalryByName($name)
        {
            $conn = My_Connect_DB();
            $sql = "SELECT *
            FROM Employee2
            WHERE Name = '" . $name . "';";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function sortByTotal()
        {
            $conn = My_Connect_DB();
            $sql = "SELECT Name, SUM(amount) AS Total
            FROM Employee2
            GROUP BY Name
            ORDER BY amount DESC;";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function sortByType($type)
        {
            $conn = My_Connect_DB();
            $sql = "SELECT *
            FROM Employee2
            WHERE Type = '" . $type . "';";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function highestBonus()
        {
            $conn = My_Connect_DB();
            $sql = "SELECT Name, amount
            FROM Employee2
            WHERE Type = 'bonus'
            AND amount = 
            (SELECT MAX(amount)
            FROM Employee2
            WHERE Type = 'bonus');";


            //  $sql =   "SELECT MAX(amount)
            //         FROM Employee2
            //         WHERE Type = 'bonus';";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }

        function totalPaymentByCatagory()
        {
            $conn = My_Connect_DB();
            $sql = "SELECT Type, SUM(amount) AS Total_Payment
            FROM Employee2
            GROUP BY Type;";
            $table = "Employee2";

            Run_Select_Show_Table($conn, $sql, $table);
        }





        ?>

    </body>

</html>