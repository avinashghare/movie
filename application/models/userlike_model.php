<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class userlike_model extends CI_Model
{
    public function create($user,$movie,$status)
    {
        $data=array("user" => $user,"movie" => $movie,"status" => $status);
        $query=$this->db->insert( "movie_userlike", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userlike")->row();
        return $query;
    }
    function getsingleuserlike($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userlike")->row();
        return $query;
    }
    public function edit($id,$user,$movie,$status)
    {
        $data=array("user" => $user,"movie" => $movie,"status" => $status);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_userlike", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_userlike` WHERE `id`='$id'");
        return $query;
    }
}
?>
