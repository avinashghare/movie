<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class expert_model extends CI_Model
{
    public function create($name,$status)
    {
        $data=array("name" => $name,"status" => $status);
        $query=$this->db->insert( "movie_expert", $data );
        $id=$this->db->insert_id();
        if(!$query)
            return  0;
        else
            return  $id;
    }
    public function beforeedit($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_expert")->row();
        return $query;
    }
    function getsingleexpert($id)
    {
        $this->db->where("id",$id);
        $query=$this->db->get("movie_expert")->row();
        return $query;
    }
    public function edit($id,$name,$status)
    {
        $data=array("name" => $name,"status" => $status);
        $this->db->where( "id", $id );
        $query=$this->db->update( "movie_expert", $data );
        return 1;
    }
    public function delete($id)
    {
        $query=$this->db->query("DELETE FROM `movie_expert` WHERE `id`='$id'");
        return $query;
    }
    
	public function getstatusdropdown()
	{
		$status= array(
			 "1" => "Enabled",
			 "0" => "Disabled",
			);
		return $status;
	}
    
    public function getexpertdropdown()
	{
		$query=$this->db->query("SELECT * FROM `movie_expert`  ORDER BY `id` ASC")->result();
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
