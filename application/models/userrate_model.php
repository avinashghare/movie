<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class userrate_model extends CI_Model
{
    public function create($user,$movie,$rating)
    {
        $data=array("user" => $user,"movie" => $movie,"rating" => $rating);
        $query=$this->db->insert( "movie_userrate", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userrate")->row();
        return $query;
    }
    function getsingleuserrate($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userrate")->row();
        return $query;
    }
    public function edit($id,$user,$movie,$rating)
    {
        $data=array("user" => $user,"movie" => $movie,"rating" => $rating);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_userrate", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_userrate` WHERE `id`='$id'");
        return $query;
    }
}
?>
