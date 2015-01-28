<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class userrecommend_model extends CI_Model
{
    public function create($user,$movie,$recommendfriend)
    {
        $data=array("user" => $user,"movie" => $movie,"recommendfriend" => $recommendfriend);
        $query=$this->db->insert( "movie_userrecommend", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userrecommend")->row();
        return $query;
    }
    function getsingleuserrecommend($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_userrecommend")->row();
        return $query;
    }
    public function edit($id,$user,$movie,$recommendfriend)
    {
        $data=array("user" => $user,"movie" => $movie,"recommendfriend" => $recommendfriend);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_userrecommend", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_userrecommend` WHERE `id`='$id'");
        return $query;
    }
}
?>
