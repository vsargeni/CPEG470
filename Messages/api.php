<?php
  $verb = $_SERVER["REQUEST_METHOD"];
  
  if ($verb == "GET"){
        //this is the basic way of getting a database handler from PDO, PHP's built in quasi-ORM
        $dbhandle = new PDO("sqlite:database.db") or die("Failed to open DB");
        if (!$dbhandle) die ($error);
        //this is a sample query which gets some data, the order by part shuffles the results
        //the limit 0, 10 takes the first 10 results.
        // you might want to consider taking more results, implementing "pagination", 
        // ordering by rank, etc.
        $query = "SELECT * FROM Messages";
        //this next line could actually be used to provide user_given input to the query to 
        //avoid SQL injection attacks
        $statement = $dbhandle->prepare($query);
        $statement->execute();
        //The results of the query are typically many rows of data
        //there are several ways of getting the data out, iterating row by row,
        //I chose to get associative arrays inside of a big array
        //this will naturally create a pleasant array of JSON data when I echo in a couple lines
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        //this part is perhaps overkill but I wanted to set the HTTP headers and status code
        //making to this line means everything was great with this request
        header('HTTP/1.1 200 OK');
        //this lets the browser know to expect json
        header('Content-Type: application/json');
        //this creates json and gives it back to the browser
        echo json_encode($results);
        
  } else if ($verb == "POST"){
        $dbhandle = new PDO("sqlite:database.db") or die("Failed to open DB");
        if (!$dbhandle) die ($error);
        $author = "anonymous";
        $content = "secret message";
        if (isset($_POST["author"])){
          $author = $_POST["author"];
        }
        if (isset($_POST["content"])){
          $content = $_POST["content"];
        }
        echo "$author: $content";
        $query = "INSERT INTO Messages($author,$content);
        $statement = $dbhandle->prepare($query);
        $statement->execute();
        if (isset($_POST["author"])){
          $author = $_POST["author"];
        }
        if (isset($_POST["content"])){
          $content = $_POST["content"];
        }
        echo "$author: $content";
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode($results);
  } else {
        echo "USAGE GET or POST";
  }
?>
