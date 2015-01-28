<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class expertrating_model extends CI_Model
{
    public function create($expert,$movie,$rating)
    {
        $data=array("expert" => $expert,"movie" => $movie,"rating" => $rating);
        $query=$this->db->insert( "movie_expertrating", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_expertrating")->row();
        return $query;
    }
    function getsingleexpertrating($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_expertrating")->row();
        return $query;
    }
    public function edit($id,$expert,$movie,$rating)
    {
        $data=array("expert" => $expert,"movie" => $movie,"rating" => $rating);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_expertrating", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_expertrating` WHERE `id`='$id'");
        return $query;
    }
}
?>
