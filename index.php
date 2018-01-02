<?php
//database connection
$host = 'localhost';
$user = 'homestead';
$pass = 'secret';
$db = 'movies';

/* 1) mysqli and mysqli result objects */

//$mysqli is object of mysqli class
$mysqli = new mysqli($host,$user,$pass,$db); 
//print_r($mysqli);die;

//call query method of $mysqli object
$result = $mysqli->query 
        //SELECT queries are always return as mysqli result objects
        ("SELECT * FROM movies WHERE year BETWEEN 2000 AND 2016 ORDER BY rand() LIMIT 20") 
        or die($mysqli->error); 

//$result is an object
/* whenever we have an object variable, it means we can call methods on it
 * and the objects can also have properties
 */

?> <div class='main-container'> <?php

/* 2) getting data from mysqli result object */

//call fetch_assoc() method of $result object
//keep getting row's column data as associative array until NULL (no rows is returned)
//fetch_assoc() will automatically keep fetching next row when called again
//print_r($result->fetch_assoc());
//print_r($result->fetch_assoc()); die;

//this is why we can put it directly in the loop and keep getting new rows until the end
while ($movie = $result->fetch_assoc()): ?> 
        <div class='movie-container'>
            <div class='header'>
            <h1><?= $movie['title'] ?></h1>
            <span class='year'>( <?= $movie['year'] ?> )</span>
            </div>
            <div class='content'>
            <div class='left-column'>
            <!-- Image width and height multiplied by 1.3 (to make them a bit bigger) -->
            <img width='<?php 67*1.3 ?>' height='<?= 98*1.3 ?>' src='<?= $movie['image_url'] ?>'>
            <div id='ratings'>
            <!-- If imdb_rating for the movie exists, print it, otherwise don't, same for metascore -->       
            <div class='imdb'><?= $movie['imdb_rating'] ? $movie['imdb_rating'] : '' ?></div>
            <div class='metascore'><?= $movie['metascore'] ? $movie['metascore'] : '' ?></div>
            </div>
            </div>
            <div class='right-column'>
                
            <span class='content blue'>
            <?= $movie['certificate']; ?>
            </span>

            <?php 
            //note: we're only printing the pipe here |, not the actual certificate
            echo $movie['certificate'] ? ' |' : ''; 
            ?>

            <span class='content blue'>
            <?= $movie['runtime'] .' min'; ?>
            </span>

            <?php
            //genres
            $result2 = $mysqli->query
                    ("SELECT genres_id FROM movies_genres WHERE movies_id={$movie['id']}") or
            die($mysqli->error);

            //fetch_all returns multi-dimensional array
            $genres = $result2->fetch_all();

            //array_column introduced in PHP 5.6, convert multi-dimensional array to single
            //clean it up
            $genres = array_column($genres, 0); //removes 0 array key

            //print_r($genres);die;

            //loop through genres id's and get the records from genres table
            for ($i = 0; $i < count($genres);$i++)
            {
            $genre = $mysqli->query("SELECT name from genres where id = '{$genres[$i]}'")->fetch_assoc();
            //print_r($genre);die; //single genre lives here
            
            //print pipe before every first genre
            echo $i == 0 ? ' | ' : ''; 
            echo "<span class='content blue'>".$genre['name']."</span>";
            echo $genres[$i] != end($genres) ? ', ' : ''; //if NOT at the end of genres, print comma
            }

            ?>

            <div class='content description'><?= $movie['description'] ?></div>

            <?php
            //get directors
            $result3 = $mysqli->query
                    ("SELECT directors_id FROM movies_directors WHERE movies_id={$movie['id']}") or
            die($mysqli->error);

            $directors = $result3->fetch_all();
            $directors = array_column($directors, 0);

            //get stars
            $result4 = $mysqli->query
                    ("SELECT stars_id FROM movies_stars WHERE movies_id={$movie['id']}") or
            die($mysqli->error);

            $stars = $result4->fetch_all();
            $stars = array_column($stars, 0);
            
            //print_r($directors);
            //print_r($stars);die;
            ?>

            <div>

            <?php

            //loop through directors
            for ($i = 0; $i < count($directors);$i++)
            {
                $director = $mysqli->query
                        ("SELECT name from directors where id = '{$directors[$i]}'")->fetch_assoc();
                        
                //if there are more than 1 directors, put letter s insdie $s variable :)
                $s = count($directors) > 1 ? 's' : '';
                
                //put $s variable at the end of Director, will be plural if multiple directors
                echo $i == 0 ? "<span class='content yellow'>Director$s: </span>" : ''; 
                echo "<span class='content text'>".$director['name']."</span>";
                
                //not at the end of directors, print comma
                if ($directors[$i] != end($directors)){
                    echo ', ';
                }
                else {
                    //at the end of directors, print pipe, but only if there are stars
                    if (count($stars) > 0) 
                    {
                        echo ' | ';
                    }
                }
            }
            ?>

            <?php

            //loop through stars
            for ($i = 0; $i < count($stars);$i++)
            {
            $star = $mysqli->query("SELECT name from stars where id = '{$stars[$i]}'")->fetch_assoc();
            $s = count($stars) > 1 ? 's' : ''; //same s trick as with directors
            echo $i == 0 ? "<span class='content yellow'>Star$s: </span>" : ''; 
            echo "<span>".$star['name']."</span>";
            echo $stars[$i] != end($stars) ? ', ' : ''; //print comma if not at the end of stars
            }
            ?>
            </div>

            <div class='bottom'>
            <?php 

            //check if votes exists
            if ($movie['votes']) {
                echo "<span class='content yellow'>Votes: </span>".number_format($movie['votes']);
                //if gross exists print pipe after votes
                //we already know votes exists with if statement above
                echo $movie['gross'] ? ' | ' : '';
            }
            ?>

            <span class='content green'>
            <?= $movie['gross'] ? "<span class='content yellow'>Gross: </span>$".
                     number_format($movie['gross']) : '' ?>
            </span>
            </div>
                
            </div>
            </div>
        </div>
</div>
<?php endwhile; ?>