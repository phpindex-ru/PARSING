<?php

include('scrape_imdb.php');
$scrape = new scrap();
$data = $scrape->scrape_imdb(1999, 2017, 1, 2);

$host = 'localhost';
$user = 'homestead';
$pass = 'secret';
$db = 'movies';

$mysqli = new mysqli( $host,$user,$pass,$db );

for ($i = 0; $i < count($data); $i++ ) {
    
    $description = $mysqli->escape_string($data[$i]['description']);
    $title = $mysqli->escape_string($data[$i]['title']);
    
    $votes = intval($data[$i]['votes']);
    $gross = intval(str_replace(',','',$data[$i]['gross']));

    //insert movies
    $sql = "INSERT IGNORE INTO movies (title,year,image_url,certificate,runtime,
            imdb_rating,metascore,description,votes,gross)
            VALUES ('$title','{$data[$i]['year']}','{$data[$i]['image']}',
            '{$data[$i]['certificate']}','{$data[$i]['runtime']}','{$data[$i]['imdb_rating']}',
            '{$data[$i]['metascore']}','$description','$votes','$gross')";
            
    $mysqli->query($sql) or die($mysqli->error);
        
    $movies_id = $mysqli->insert_id; //get last key of the movie id, returns 0 if insert failed
    
    if ( !$movies_id ) { //duplicate movie
        continue; 
    }
        
    $directors = explode(",",$data[$i]['directors']);
    $stars = explode(",",$data[$i]['stars']);
    $genres = explode(",",$data[$i]['genres']);
    
    //insert directors
    for ($c = 0; $c < count($directors); $c++)
    {
        $director = $mysqli->escape_string(trim($directors[$c]));
        
        $sql = "INSERT IGNORE INTO directors (name) VALUES ('$director')";

        $mysqli->query($sql) or die($mysqli->error);
        $directors_id = $mysqli->insert_id;
        
        //if new director has been added, else if the director exists the id will be 0
        if ( $directors_id ){
            //insert movies_directors
            $sql = "INSERT INTO movies_directors (movies_id, directors_id) "
                    . "VALUES ('$movies_id','$directors_id')";
            $mysqli->query($sql);
        }
        else {
            //select director id by name
            $sql = "SELECT id FROM directors WHERE name='$director'";
            $result = $mysqli->query($sql) or die($mysqli->error);
            $row = $result->fetch_assoc();
            $directors_id = $row['id'];
            
            $sql = "INSERT INTO movies_directors (movies_id, directors_id) "
                . "VALUES ('$movies_id','$directors_id')";
            
            //if this fails, continue running, because the pair may already exist
            $mysqli->query($sql);  
        }
    }
    

    //insert stars
    for ($c = 0; $c < count($stars); $c++)
    {
        $star = $mysqli->escape_string(trim($stars[$c]));
        $sql = "INSERT IGNORE INTO stars (name) VALUES ('$star')";
        $mysqli->query($sql) or die($mysqli->error);
        $stars_id = $mysqli->insert_id;
        
        if ( $stars_id ){
            //insert movies_stars
            $sql = "INSERT INTO movies_stars (movies_id, stars_id) "
                    . "VALUES ('$movies_id','$stars_id')";
            $mysqli->query($sql);
        }
        else {
            $sql = "SELECT id FROM stars WHERE name='$star'";
            $result = $mysqli->query($sql) or die($mysqli->error);
            $row = $result->fetch_assoc();
            $stars_id = $row['id'];
            
            $sql = "INSERT INTO movies_stars (movies_id, stars_id) "
                . "VALUES ('$movies_id','$stars_id')";
            
            //if this fails, continue running, because the pair may already exist
            $mysqli->query($sql);
        }
    }
    
    //insert genres
    for ($c = 0; $c < count($genres); $c++)
    {
        $genre = $mysqli->escape_string(trim($genres[$c]));
        $sql = "INSERT IGNORE INTO genres (name) VALUES ('$genre')";
        $mysqli->query($sql) or die($mysqli->error);
        $genres_id = $mysqli->insert_id;
        
        if ( $genres_id ){
            //insert movies_genres
            $sql = "INSERT INTO movies_genres (movies_id, genres_id) "
                    . "VALUES ('$movies_id','$genres_id')"; 
            $mysqli->query($sql);
        }
        else {
            $sql = "SELECT id FROM genres WHERE name='$genre'";
            $result = $mysqli->query($sql) or die($mysqli->error);
            $row = $result->fetch_assoc();
            $genres_id = $row['id'];
            
            $sql = "INSERT INTO movies_genres (movies_id, genres_id) "
                . "VALUES ('$movies_id','$genres_id')";
            
            //if this fails, continue running, because the pair may already exist
            $mysqli->query($sql);    
        }
    }
}
?>