<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class genre_model extends CI_Model
{
    public function create($name)
    {
        $data=array("name" => $name);
        $query=$this->db->insert( "movie_genre", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_genre")->row();
        return $query;
    }
    function getsinglegenre($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_genre")->row();
        return $query;
    }
    public function edit($id,$name)
    {
        $data=array("name" => $name);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_genre", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_genre` WHERE `id`='$id'");
        return $query;
    }
    
    public function getgenredropdown()
	{
		$query=$this->db->query("SELECT * FROM `movie_genre`  ORDER BY `id` ASC")->result();
		$return=array(
		);
		foreach($query as $row)
		{
			$return[$row->id]=$row->name;
		}
		
		return $return;
	}
}
?>
