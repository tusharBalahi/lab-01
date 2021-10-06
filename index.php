<?php

  require "./src/Connection.php";
  require "./src/QueryBuilder.php";

  $conn = (new Connection(
    "root",
    "",
    "localhost",
    "superheroes",
    3308
  ))->get_connection();

  $query_builder = new QueryBuilder($conn);
  $get_row = $query_builder->superhero->find_by_superhero_name("Incredible Hulk"); // Getting corresponding row result and storing into $get_row variable

  /**
   * Step 3:
   *  Using your new find_by_superhero_name method:
   *    - Find a superhero in the database and store
   *    - it in a variable
   */

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superhero</title>
  </head>

  <body>
    <!-- Step 4: -->
    <!-- 
      Using the superhero row, create a view
      outputting the data from the row. You
      won't need a foreach here as it is a
      single row, but you will need a few
      echo statements.
     -->
     <?php echo $get_row->name; //Printing name of the superhero ?>
  </body> 
</html>