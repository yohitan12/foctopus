<?php

class Model
{   
    
    
    /**
     * Get simple "stats". This is just a simple demo to show
     * how to use more than one model in a controller (see application/controller/songs.php for more)
     */
    public function getAmountOfSongs()
    {
        $sql   = "SELECT COUNT(id) AS amount_of_songs FROM song";
        $query = $this->db->prepare($sql);
        $query->execute();
        
        // fetch() is the PDO method that get exactly one result
        return $query->fetch()->amount_of_songs;
    }
}
