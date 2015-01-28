<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class movie_model extends CI_Model
{
    public function create($name,$duration,$dateofrelease,$rating,$director,$writer,$casteandcrew,$summary,$twittertrack,$trailer,$isfeatured,$isintheator,$iscommingsoon,$genre)
    {
        $data=array(
            "name" => $name,
            "duration" => $duration,
            "dateofrelease" => $dateofrelease,
            "rating" => $rating,
            "director" => $director,
            "writer" => $writer,
            "casteandcrew" => $casteandcrew,
            "summary" => $summary,
            "twittertrack" => $twittertrack,
            "trailer" => $trailer,
            "isfeatured" => $isfeatured,
            "isintheator" => $isintheator,
            "iscommingsoon" => $iscommingsoon
        );
        $query=$this->db->insert( "movie_movie", $data );
        $movieid=$this->db->insert_id();
        
    foreach($genre AS $key=>$value)
        {
            $this->movie_model->createmoviegenre($value,$movieid);
        }
    
        if(!$query)
            return  0;
        else
            return  $movieid;
    }
    public function createmoviegenre($value,$movieid)
	{
		$data  = array(
			'genre' => $value,
			'movie' => $movieid
		);
		$query=$this->db->insert( 'moviegenre', $data );
		return  1;
	}
    
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_movie")->row();
        return $query;
    }
    function getsinglemovie($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_movie")->row();
        return $query;
    }
    public function edit($id,$name,$duration,$dateofrelease,$rating,$director,$writer,$casteandcrew,$summary,$twittertrack,$trailer,$isfeatured,$isintheator,$iscommingsoon,$genre)
    {
        $data=array("name" => $name,"duration" => $duration,"dateofrelease" => $dateofrelease,"rating" => $rating,"director" => $director,"writer" => $writer,"casteandcrew" => $casteandcrew,"summary" => $summary,"twittertrack" => $twittertrack,"trailer" => $trailer,"isfeatured" => $isfeatured,"isintheator" => $isintheator,"iscommingsoon" => $iscommingsoon);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_movie", $data );
        $querydelete=$this->db->query("DELETE FROM `moviegenre` WHERE `movie`='$id'");
        foreach($genre AS $key=>$value)
        {
            $this->movie_model->createmoviegenre($value,$id);
        }
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_movie` WHERE `id`='$id'");
        return $query;
    }
    public function getisfeatureddropdown()
	{
		$isfeatured= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $isfeatured;
	}
    public function getisintheatordropdown()
	{
		$isintheator= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $isintheator;
	}
    public function getiscommingsoondropdown()
	{
		$iscommingsoon= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $iscommingsoon;
	}
    
    public function getstatusdropdown()
	{
		$status= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $status;
	}
    
     public function getgenrebymovie($id)
	{
//         echo "Hello";
         $return=array();
		$query=$this->db->query("SELECT `id`,`movie`,`genre` FROM `moviegenre`  WHERE `movie`='$id'");
        if($query->num_rows() > 0)
        {
            $query=$query->result();
            foreach($query as $row)
            {
                $return[]=$row->genre;
            }
        }
         return $return;
	}
    
    public function getmoviedropdown()
	{
		$query=$this->db->query("SELECT * FROM `movie_movie`  ORDER BY `id` ASC")->result();
		$return=array(
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
    
	public function gettrailerbyid($id)
	{
		$query=$this->db->query("SELECT `trailer` FROM `movie_movie` WHERE `id`='$id'")->row();
		return $query;
	}
}
?>
