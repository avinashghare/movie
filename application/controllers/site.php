<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller 
{
	public function __construct( )
	{
		parent::__construct();
		
		$this->is_logged_in();
	}
	function is_logged_in( )
	{
		$is_logged_in = $this->session->userdata( 'logged_in' );
		if ( $is_logged_in !== 'true' || !isset( $is_logged_in ) ) {
			redirect( base_url() . 'index.php/login', 'refresh' );
		} //$is_logged_in !== 'true' || !isset( $is_logged_in )
	}
	function checkaccess($access)
	{
		$accesslevel=$this->session->userdata('accesslevel');
		if(!in_array($accesslevel,$access))
			redirect( base_url() . 'index.php/site?alerterror=You do not have access to this page. ', 'refresh' );
	}
	public function index()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$data[ 'page' ] = 'dashboard';
		$data[ 'title' ] = 'Welcome';
		$this->load->view( 'template', $data );	
	}
	public function createuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'createuser';
		$data[ 'title' ] = 'Create User';
		$this->load->view( 'template', $data );	
	}
	function createusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
            $data['category']=$this->category_model->getcategorydropdown();
            $data[ 'page' ] = 'createuser';
            $data[ 'title' ] = 'Create User';
            $this->load->view( 'template', $data );	
		}
		else
		{
            $name=$this->input->post('name');
            $email=$this->input->post('email');
            $password=$this->input->post('password');
            $accesslevel=$this->input->post('accesslevel');
            $status=$this->input->post('status');
            $socialid=$this->input->post('socialid');
            $logintype=$this->input->post('logintype');
            $json=$this->input->post('json');
//            $category=$this->input->post('category');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
			if($this->user_model->create($name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json)==0)
			$data['alerterror']="New user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			$data['redirect']="site/viewusers";
			$this->load->view("redirect",$data);
		}
	}
    function viewusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewusers';
        $data['base_url'] = site_url("site/viewusersjson");
        
		$data['title']='View Users';
		$this->load->view('template',$data);
	} 
    function viewusersjson()
	{
		$access = array("1");
		$this->checkaccess($access);
        
        
        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        
        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`user`.`socialid`";
        $elements[3]->sort="1";
        $elements[3]->header="SocialId";
        $elements[3]->alias="socialid";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`logintype`.`name`";
        $elements[4]->sort="1";
        $elements[4]->header="Logintype";
        $elements[4]->alias="logintype";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`json`";
        $elements[5]->sort="1";
        $elements[5]->header="Json";
        $elements[5]->alias="json";
       
        $elements[6]=new stdClass();
        $elements[6]->field="`accesslevel`.`name`";
        $elements[6]->sort="1";
        $elements[6]->header="Accesslevel";
        $elements[6]->alias="accesslevelname";
       
        $elements[7]=new stdClass();
        $elements[7]->field="`statuses`.`name`";
        $elements[7]->sort="1";
        $elements[7]->header="Status";
        $elements[7]->alias="status";
       
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }
        
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
       
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` LEFT OUTER JOIN `logintype` ON `logintype`.`id`=`user`.`logintype` LEFT OUTER JOIN `accesslevel` ON `accesslevel`.`id`=`user`.`accesslevel` LEFT OUTER JOIN `statuses` ON `statuses`.`id`=`user`.`status`");
        
		$this->load->view("json",$data);
	} 
    
    
	function edituser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='edituser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('templatewith2',$data);
	}
	function editusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)	
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
			$data['before']=$this->user_model->beforeedit($this->input->post('id'));
			$data['page']='edituser';
//			$data['page2']='block/userblock';
			$data['title']='Edit User';
			$this->load->view('template',$data);
		}
		else
		{
            
            $id=$this->input->get_post('id');
            $name=$this->input->get_post('name');
            $email=$this->input->get_post('email');
            $password=$this->input->get_post('password');
            $accesslevel=$this->input->get_post('accesslevel');
            $status=$this->input->get_post('status');
            $socialid=$this->input->get_post('socialid');
            $logintype=$this->input->get_post('logintype');
            $json=$this->input->get_post('json');
//            $category=$this->input->get_post('category');
            
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];
                
                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r); 
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }  
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }
                
			}
            
            if($image=="")
            {
            $image=$this->user_model->getuserimagebyid($id);
               // print_r($image);
                $image=$image->image;
            }
            
			if($this->user_model->edit($id,$name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json)==0)
			$data['alerterror']="User Editing was unsuccesful";
			else
			$data['alertsuccess']="User edited Successfully.";
			
			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);
			
		}
	}
	
	function deleteuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
//		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="User Deleted Successfully";
		$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
		$this->load->view("redirect",$data);
	}
	function changeuserstatus()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->changestatus($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="Status Changed Successfully";
		$data['redirect']="site/viewusers";
        $data['other']="template=$template";
        $this->load->view("redirect",$data);
	}
    
    
    
    public function viewuserlike()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewuserlike";
		$data['page2']='block/userblock';
        $data['userid']=$this->input->get('id');
        $userid=$this->input->get('id');
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
        $data["base_url"]=site_url("site/viewuserlikejson?id=".$userid);
        $data["title"]="View userlike";
        $this->load->view("templatewith2",$data);
    }
    function viewuserlikejson()
    {
        $userid=$this->input->get('id');
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_userlike`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_userlike`.`user`";
        $elements[1]->sort="1";
        $elements[1]->header="User";
        $elements[1]->alias="user";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_userlike`.`movie`";
        $elements[2]->sort="1";
        $elements[2]->header="Movie";
        $elements[2]->alias="movie";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_userlike`.`status`";
        $elements[3]->sort="1";
        $elements[3]->header="Status";
        $elements[3]->alias="status";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_userlike`","WHERE `movie_userlike`.`user`='$userid'");
        $this->load->view("json",$data);
    }

    public function createuserlike()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['status']=$this->movie_model->getstatusdropdown();
        $data['userid']=$this->input->get('id');
        $data["page"]="createuserlike";
        $data["title"]="Create userlike";
        $this->load->view("template",$data);
    }
    public function createuserlikesubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("status","Status","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['status']=$this->movie_model->getstatusdropdown();
            $data['userid']=$this->input->post('user');
            $data["page"]="createuserlike";
            $data["title"]="Create userlike";
            $this->load->view("template",$data);
        }
        else
        {
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $status=$this->input->get_post("status");
            if($this->userlike_model->create($user,$movie,$status)==0)
                $data["alerterror"]="New userlike could not be created.";
            else
                $data["alertsuccess"]="userlike created Successfully.";
            $data["redirect"]="site/viewuserlike?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function edituserlike()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="edituserlike";
        $data["title"]="Edit userlike";
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['status']=$this->movie_model->getstatusdropdown();
        $data['userid']=$this->input->get('id');
        $data['userlikeid']=$this->input->get('userlikeid');
        $data["before"]=$this->userlike_model->beforeedit($this->input->get("userlikeid"));
        $this->load->view("template",$data);
    }
    public function edituserlikesubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("status","Status","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="edituserlike";
            $data["title"]="Edit userlike";
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['status']=$this->movie_model->getstatusdropdown();
            $data['userid']=$this->input->post('user');
            $data['userlikeid']=$this->input->post('id');
            $data["before"]=$this->userlike_model->beforeedit($this->input->post("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $status=$this->input->get_post("status");
            if($this->userlike_model->edit($id,$user,$movie,$status)==0)
                $data["alerterror"]="New userlike could not be Updated.";
            else
                $data["alertsuccess"]="userlike Updated Successfully.";
            $data["redirect"]="site/viewuserlike?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function deleteuserlike()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['userid']=$this->input->get('id');
        $data['userlikeid']=$this->input->get('userlikeid');
        $this->userlike_model->delete($this->input->get("userlikeid"));
        $data["redirect"]="site/viewuserlike?id=".$this->input->get('id');
        $this->load->view("redirect2",$data);
    }
    public function viewuserrate()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewuserrate";
		$data['page2']='block/userblock';
        $data['userid']=$this->input->get('id');
        $userid=$this->input->get('id');
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
        $data["base_url"]=site_url("site/viewuserratejson?id=".$userid);
        $data["title"]="View userrate";
        $this->load->view("templatewith2",$data);
    }
    function viewuserratejson()
    {
        $userid=$this->input->get('id');
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_userrate`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_userrate`.`user`";
        $elements[1]->sort="1";
        $elements[1]->header="User";
        $elements[1]->alias="user";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_userrate`.`movie`";
        $elements[2]->sort="1";
        $elements[2]->header="Movie";
        $elements[2]->alias="movie";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_userrate`.`rating`";
        $elements[3]->sort="1";
        $elements[3]->header="Rating";
        $elements[3]->alias="rating";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_userrate`","WHERE `movie_userrate`.`user`='$userid'");
        $this->load->view("json",$data);
    }

    public function createuserrate()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['userid']=$this->input->get('id');
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['status']=$this->movie_model->getstatusdropdown();
        $data["page"]="createuserrate";
        $data["title"]="Create userrate";
        $this->load->view("template",$data);
    }
    public function createuserratesubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="createuserrate";
            $data["title"]="Create userrate";
            $this->load->view("template",$data);
        }
        else
        {
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $rating=$this->input->get_post("rating");
            if($this->userrate_model->create($user,$movie,$rating)==0)
                $data["alerterror"]="New userrate could not be created.";
            else
                $data["alertsuccess"]="userrate created Successfully.";
            $data["redirect"]="site/viewuserrate?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function edituserrate()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['status']=$this->movie_model->getstatusdropdown();
        $data['userid']=$this->input->get('id');
        $data['userrateid']=$this->input->get('userrateid');
        $data["page"]="edituserrate";
        $data["title"]="Edit userrate";
        $data["before"]=$this->userrate_model->beforeedit($this->input->get("userrateid"));
        $this->load->view("template",$data);
    }
    public function edituserratesubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['status']=$this->movie_model->getstatusdropdown();
            $data['userid']=$this->input->post('user');
            $data['userrateid']=$this->input->post('id');
            $data["page"]="edituserrate";
            $data["title"]="Edit userrate";
            $data["before"]=$this->userrate_model->beforeedit($this->input->get("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $rating=$this->input->get_post("rating");
            if($this->userrate_model->edit($id,$user,$movie,$rating)==0)
            $data["alerterror"]="New userrate could not be Updated.";
            else
            $data["alertsuccess"]="userrate Updated Successfully.";
            $data["redirect"]="site/viewuserrate?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function deleteuserrate()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['userid']=$this->input->get('id');
        $data['userrateid']=$this->input->get('userrateid');
        $this->userrate_model->delete($this->input->get("userrateid"));
        $data["redirect"]="site/viewuserrate?id=".$this->input->get('id');
        $this->load->view("redirect2",$data);
    }
    public function viewusercomment()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewusercomment";
		$data['page2']='block/userblock';
        $data['userid']=$this->input->get('id');
        $userid=$this->input->get('id');
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
        $data["base_url"]=site_url("site/viewusercommentjson?id=".$this->input->get('id'));
        $data["title"]="View usercomment";
        $this->load->view("templatewith2",$data);
    }
    function viewusercommentjson()
    {
        $userid=$this->input->get('id');
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_usercomment`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";

        $elements[1]=new stdClass();
        $elements[1]->field="`movie_usercomment`.`user`";
        $elements[1]->sort="1";
        $elements[1]->header="User";
        $elements[1]->alias="user";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_usercomment`.`movie`";
        $elements[2]->sort="1";
        $elements[2]->header="Movie";
        $elements[2]->alias="movie";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_usercomment`.`comment`";
        $elements[3]->sort="1";
        $elements[3]->header="Comment";
        $elements[3]->alias="comment";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_usercomment`","WHERE `movie_usercomment`.`user`='$userid'");
        $this->load->view("json",$data);
    }

    public function createusercomment()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="createusercomment";
        $data["title"]="Create usercomment";
        $data['userid']=$this->input->get('id');
        $data['movie']=$this->movie_model->getmoviedropdown();
        $this->load->view("template",$data);
    }
    public function createusercommentsubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("comment","Comment","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="createusercomment";
            $data["title"]="Create usercomment";
            $data['userid']=$this->input->post('id');
            $data['movie']=$this->movie_model->getmoviedropdown();
            $this->load->view("template",$data);
        }
        else
        {
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $comment=$this->input->get_post("comment");
            if($this->usercomment_model->create($user,$movie,$comment)==0)
                $data["alerterror"]="New usercomment could not be created.";
            else
                $data["alertsuccess"]="usercomment created Successfully.";
            $data["redirect"]="site/viewusercomment?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function editusercomment()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="editusercomment";
        $data["title"]="Edit usercomment"; 
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['userid']=$this->input->get('id');
        $data['usercommentid']=$this->input->get('usercommentid');
        $data["before"]=$this->usercomment_model->beforeedit($this->input->get("usercommentid"));
        $this->load->view("template",$data);
    }
    public function editusercommentsubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("comment","Comment","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="editusercomment";
            $data["title"]="Edit usercomment"; 
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['userid']=$this->input->post('usercommentid');
            $data['usercommentid']=$this->input->post('id');
            $data["before"]=$this->usercomment_model->beforeedit($this->input->post("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $comment=$this->input->get_post("comment");
            if($this->usercomment_model->edit($id,$user,$movie,$comment)==0)
                $data["alerterror"]="New usercomment could not be Updated.";
            else
                $data["alertsuccess"]="usercomment Updated Successfully.";
            $data["redirect"]="site/viewusercomment?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function deleteusercomment()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['userid']=$this->input->get('id');
        $data['usercommentid']=$this->input->get('usercommentid');
        $this->usercomment_model->delete($this->input->get("usercommentid"));
        $data["redirect"]="site/viewusercomment?id=".$this->input->get('id');
        $this->load->view("redirect2",$data);
    }
    public function viewuserrecommend()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewuserrecommend";
        
        $data['page2']='block/userblock';
        $data['userid']=$this->input->get('id');
        $userid=$this->input->get('id');
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
        $data['recommendfriend']=$this->user_model->getuserdropdown();
        $data["base_url"]=site_url("site/viewuserrecommendjson?id=".$userid);
        $data["title"]="View userrecommend";
        $this->load->view("templatewith2",$data);
    }
    function viewuserrecommendjson()
    {
        $userid=$this->input->get('id');
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_userrecommend`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_userrecommend`.`user`";
        $elements[1]->sort="1";
        $elements[1]->header="User";
        $elements[1]->alias="user";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_userrecommend`.`movie`";
        $elements[2]->sort="1";
        $elements[2]->header="Movie";
        $elements[2]->alias="movie";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_userrecommend`.`recommendfriend`";
        $elements[3]->sort="1";
        $elements[3]->header="Recommendfriend";
        $elements[3]->alias="recommendfriend";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_userrecommend`","WHERE `movie_userrecommend`.`user`='$userid'");
        $this->load->view("json",$data);
    }

    public function createuserrecommend()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="createuserrecommend";
        $data["title"]="Create userrecommend";
        $data['userid']=$this->input->get('id');
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['recommendfriend']=$this->user_model->getuserdropdown();
        $this->load->view("template",$data);
    }
    public function createuserrecommendsubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("recommendfriend","Recommendfriend","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="createuserrecommend";
            $data["title"]="Create userrecommend";
            $data['userid']=$this->input->get('id');
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['recommendfriend']=$this->user_model->getuserdropdown();
            $this->load->view("template",$data);
        }
        else
        {
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $recommendfriend=$this->input->get_post("recommendfriend");
            if($this->userrecommend_model->create($user,$movie,$recommendfriend)==0)
                $data["alerterror"]="New userrecommend could not be created.";
            else
                $data["alertsuccess"]="userrecommend created Successfully.";
            $data["redirect"]="site/viewuserrecommend?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function edituserrecommend()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="edituserrecommend";
        $data["title"]="Edit userrecommend";
        $data['movie']=$this->movie_model->getmoviedropdown();
        $data['userid']=$this->input->get('id');
        $data['userrecommendid']=$this->input->get('userrecommendid');
        $data['recommendfriend']=$this->user_model->getuserdropdown();
        $data["before"]=$this->userrecommend_model->beforeedit($this->input->get("userrecommendid"));
        $this->load->view("template",$data);
    }
    public function edituserrecommendsubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("user","User","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("recommendfriend","Recommendfriend","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="edituserrecommend";
            $data["title"]="Edit userrecommend";
            $data["before"]=$this->userrecommend_model->beforeedit($this->input->get("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $user=$this->input->get_post("user");
            $movie=$this->input->get_post("movie");
            $recommendfriend=$this->input->get_post("recommendfriend");
            if($this->userrecommend_model->edit($id,$user,$movie,$recommendfriend)==0)
                $data["alerterror"]="New userrecommend could not be Updated.";
            else
                $data["alertsuccess"]="userrecommend Updated Successfully.";
            $data["redirect"]="site/viewuserrecommend?id=".$user;
            $this->load->view("redirect2",$data);
        }
    }
    public function deleteuserrecommend()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['userid']=$this->input->get('id');
        $data['userrecommendid']=$this->input->get('userrecommendid');
        $this->userrecommend_model->delete($this->input->get("userrecommendid"));
        $data["redirect"]="site/viewuserrecommend?id=".$this->input->get('id');
        $this->load->view("redirect2",$data);
    }
    public function viewgenre()
    {
    $access=array("1");
    $this->checkaccess($access);
    $data["page"]="viewgenre";
    $data["base_url"]=site_url("site/viewgenrejson");
    $data["title"]="View genre";
    $this->load->view("template",$data);
    }
    function viewgenrejson()
    {
    $elements=array();
    $elements[0]=new stdClass();
    $elements[0]->field="`movie_genre`.`id`";
    $elements[0]->sort="1";
    $elements[0]->header="ID";
    $elements[0]->alias="id";
    $elements[1]=new stdClass();
    $elements[1]->field="`movie_genre`.`name`";
    $elements[1]->sort="1";
    $elements[1]->header="Name";
    $elements[1]->alias="name";
    $search=$this->input->get_post("search");
    $pageno=$this->input->get_post("pageno");
    $orderby=$this->input->get_post("orderby");
    $orderorder=$this->input->get_post("orderorder");
    $maxrow=$this->input->get_post("maxrow");
    if($maxrow=="")
    {
    $maxrow=20;
    }
    if($orderby=="")
    {
    $orderby="id";
    $orderorder="ASC";
    }
    $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_genre`");
    $this->load->view("json",$data);
    }

    public function creategenre()
    {
    $access=array("1");
    $this->checkaccess($access);
    $data["page"]="creategenre";
    $data["title"]="Create genre";
    $this->load->view("template",$data);
    }
    public function creategenresubmit() 
    {
    $access=array("1");
    $this->checkaccess($access);
    $this->form_validation->set_rules("name","Name","trim");
    if($this->form_validation->run()==FALSE)
    {
    $data["alerterror"]=validation_errors();
    $data["page"]="creategenre";
    $data["title"]="Create genre";
    $this->load->view("template",$data);
    }
    else
    {
    $name=$this->input->get_post("name");
    if($this->genre_model->create($name)==0)
    $data["alerterror"]="New genre could not be created.";
    else
    $data["alertsuccess"]="genre created Successfully.";
    $data["redirect"]="site/viewgenre";
    $this->load->view("redirect",$data);
    }
    }
    public function editgenre()
    {
    $access=array("1");
    $this->checkaccess($access);
    $data["page"]="editgenre";
    $data["title"]="Edit genre";
    $data["before"]=$this->genre_model->beforeedit($this->input->get("id"));
    $this->load->view("template",$data);
    }
    public function editgenresubmit()
    {
    $access=array("1");
    $this->checkaccess($access);
    $this->form_validation->set_rules("id","ID","trim");
    $this->form_validation->set_rules("name","Name","trim");
    if($this->form_validation->run()==FALSE)
    {
    $data["alerterror"]=validation_errors();
    $data["page"]="editgenre";
    $data["title"]="Edit genre";
    $data["before"]=$this->genre_model->beforeedit($this->input->get("id"));
    $this->load->view("template",$data);
    }
    else
    {
    $id=$this->input->get_post("id");
    $name=$this->input->get_post("name");
    if($this->genre_model->edit($id,$name)==0)
    $data["alerterror"]="New genre could not be Updated.";
    else
    $data["alertsuccess"]="genre Updated Successfully.";
    $data["redirect"]="site/viewgenre";
    $this->load->view("redirect",$data);
    }
    }
    public function deletegenre()
    {
    $access=array("1");
    $this->checkaccess($access);
    $this->genre_model->delete($this->input->get("id"));
    $data["redirect"]="site/viewgenre";
    $this->load->view("redirect",$data);
    }
    public function viewmovie()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewmovie";
        $data["base_url"]=site_url("site/viewmoviejson");
        $data["title"]="View movie";
        $this->load->view("template",$data);
    }
    function viewmoviejson()
    {
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_movie`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_movie`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_movie`.`duration`";
        $elements[2]->sort="1";
        $elements[2]->header="Duration";
        $elements[2]->alias="duration";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_movie`.`dateofrelease`";
        $elements[3]->sort="1";
        $elements[3]->header="Dateofrelease";
        $elements[3]->alias="dateofrelease";
        
        $elements[4]=new stdClass();
        $elements[4]->field="`movie_movie`.`rating`";
        $elements[4]->sort="1";
        $elements[4]->header="Rating";
        $elements[4]->alias="rating";
        
        $elements[5]=new stdClass();
        $elements[5]->field="`movie_movie`.`director`";
        $elements[5]->sort="1";
        $elements[5]->header="Director";
        $elements[5]->alias="director";
        
        $elements[6]=new stdClass();
        $elements[6]->field="`movie_movie`.`writer`";
        $elements[6]->sort="1";
        $elements[6]->header="Writer";
        $elements[6]->alias="writer";
        
        $elements[7]=new stdClass();
        $elements[7]->field="`movie_movie`.`casteandcrew`";
        $elements[7]->sort="1";
        $elements[7]->header="Casteandcrew";
        $elements[7]->alias="casteandcrew";
        
        $elements[8]=new stdClass();
        $elements[8]->field="`movie_movie`.`summary`";
        $elements[8]->sort="1";
        $elements[8]->header="Summary";
        $elements[8]->alias="summary";
        
        $elements[9]=new stdClass();
        $elements[9]->field="`movie_movie`.`twittertrack`";
        $elements[9]->sort="1";
        $elements[9]->header="Twittertrack";
        $elements[9]->alias="twittertrack";
        
        $elements[10]=new stdClass();
        $elements[10]->field="`movie_movie`.`trailer`";
        $elements[10]->sort="1";
        $elements[10]->header="Trailer";
        $elements[10]->alias="trailer";
        
        $elements[11]=new stdClass();
        $elements[11]->field="`movie_movie`.`isfeatured`";
        $elements[11]->sort="1";
        $elements[11]->header="Isfeatured";
        $elements[11]->alias="isfeatured";
        
        $elements[12]=new stdClass();
        $elements[12]->field="`movie_movie`.`isintheator`";
        $elements[12]->sort="1";
        $elements[12]->header="Isintheator";
        $elements[12]->alias="isintheator";
        
        $elements[13]=new stdClass();
        $elements[13]->field="`movie_movie`.`iscommingsoon`";
        $elements[13]->sort="1";
        $elements[13]->header="Iscommingsoon";
        $elements[13]->alias="iscommingsoon";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_movie`");
        $this->load->view("json",$data);
    }

    public function createmovie()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['isfeatured']=$this->movie_model->getisfeatureddropdown();
        $data['isintheator']=$this->movie_model->getisintheatordropdown();
        $data['iscommingsoon']=$this->movie_model->getiscommingsoondropdown();
        $data['genre']=$this->genre_model->getgenredropdown();
        $data["page"]="createmovie";
        $data["title"]="Create movie";
        $this->load->view("template",$data);
    }
    public function createmoviesubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("name","Name","trim");
        $this->form_validation->set_rules("duration","Duration","trim");
        $this->form_validation->set_rules("dateofrelease","Dateofrelease","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        $this->form_validation->set_rules("director","Director","trim");
        $this->form_validation->set_rules("writer","Writer","trim");
        $this->form_validation->set_rules("casteandcrew","Casteandcrew","trim");
        $this->form_validation->set_rules("summary","Summary","trim");
        $this->form_validation->set_rules("twittertrack","Twittertrack","trim");
        $this->form_validation->set_rules("trailer","Trailer","trim");
        $this->form_validation->set_rules("isfeatured","Isfeatured","trim");
        $this->form_validation->set_rules("isintheator","Isintheator","trim");
        $this->form_validation->set_rules("iscommingsoon","Iscommingsoon","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="createmovie";
            $data["title"]="Create movie";
            $data['isfeatured']=$this->movie_model->getisfeatureddropdown();
            $data['isintheator']=$this->movie_model->getisintheatordropdown();
            $data['iscommingsoon']=$this->movie_model->getiscommingsoondropdown();
            $this->load->view("template",$data);
        }
        else
        {
            $name=$this->input->get_post("name");
            $duration=$this->input->get_post("duration");
            $dateofrelease=$this->input->get_post("dateofrelease");
            $rating=$this->input->get_post("rating");
            $director=$this->input->get_post("director");
            $writer=$this->input->get_post("writer");
            $casteandcrew=$this->input->get_post("casteandcrew");
            $summary=$this->input->get_post("summary");
            $twittertrack=$this->input->get_post("twittertrack");
            $trailer=$this->input->get_post("trailer");
            $isfeatured=$this->input->get_post("isfeatured");
            $isintheator=$this->input->get_post("isintheator");
            $iscommingsoon=$this->input->get_post("iscommingsoon");
            $genre=$this->input->get_post("genre");
            if($this->movie_model->create($name,$duration,$dateofrelease,$rating,$director,$writer,$casteandcrew,$summary,$twittertrack,$trailer,$isfeatured,$isintheator,$iscommingsoon,$genre)==0)
                $data["alerterror"]="New movie could not be created.";
            else
                $data["alertsuccess"]="movie created Successfully.";
            $data["redirect"]="site/viewmovie";
            $this->load->view("redirect",$data);
        }
    }
    public function editmovie()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="editmovie";
        $data["page2"]="block/movieblock";
        $data["title"]="Edit movie";
        $data['isfeatured']=$this->movie_model->getisfeatureddropdown();
        $data['isintheator']=$this->movie_model->getisintheatordropdown();
        $data['iscommingsoon']=$this->movie_model->getiscommingsoondropdown();
        $data['genre']=$this->genre_model->getgenredropdown();
        $data['selectedgenre']=$this->movie_model->getgenrebymovie($this->input->get_post('id'));
        $data["before"]=$this->movie_model->beforeedit($this->input->get("id"));
        $this->load->view("templatewith2",$data);
    }
    public function editmoviesubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("name","Name","trim");
        $this->form_validation->set_rules("duration","Duration","trim");
        $this->form_validation->set_rules("dateofrelease","Dateofrelease","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        $this->form_validation->set_rules("director","Director","trim");
        $this->form_validation->set_rules("writer","Writer","trim");
        $this->form_validation->set_rules("casteandcrew","Casteandcrew","trim");
        $this->form_validation->set_rules("summary","Summary","trim");
        $this->form_validation->set_rules("twittertrack","Twittertrack","trim");
        $this->form_validation->set_rules("trailer","Trailer","trim");
        $this->form_validation->set_rules("isfeatured","Isfeatured","trim");
        $this->form_validation->set_rules("isintheator","Isintheator","trim");
        $this->form_validation->set_rules("iscommingsoon","Iscommingsoon","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="editmovie";
            $data["title"]="Edit movie";
            $data['isfeatured']=$this->movie_model->getisfeatureddropdown();
            $data['isintheator']=$this->movie_model->getisintheatordropdown();
            $data['iscommingsoon']=$this->movie_model->getiscommingsoondropdown();
            $data['genre']=$this->genre_model->getgenredropdown();
            $data['selectedgenre']=$this->movie_model->getgenrebymovie($this->input->get_post('id'));
            $data["before"]=$this->movie_model->beforeedit($this->input->get("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $name=$this->input->get_post("name");
            $duration=$this->input->get_post("duration");
            $dateofrelease=$this->input->get_post("dateofrelease");
            $rating=$this->input->get_post("rating");
            $director=$this->input->get_post("director");
            $writer=$this->input->get_post("writer");
            $casteandcrew=$this->input->get_post("casteandcrew");
            $summary=$this->input->get_post("summary");
            $twittertrack=$this->input->get_post("twittertrack");
            $trailer=$this->input->get_post("trailer");
            $isfeatured=$this->input->get_post("isfeatured");
            $isintheator=$this->input->get_post("isintheator");
            $iscommingsoon=$this->input->get_post("iscommingsoon");
            $genre=$this->input->get_post("genre");
            
            if($this->movie_model->edit($id,$name,$duration,$dateofrelease,$rating,$director,$writer,$casteandcrew,$summary,$twittertrack,$trailer,$isfeatured,$isintheator,$iscommingsoon,$genre)==0)
                $data["alerterror"]="New movie could not be Updated.";
            else
                $data["alertsuccess"]="movie Updated Successfully.";
            $data["redirect"]="site/viewmovie";
            $this->load->view("redirect",$data);
        }
    }
    public function deletemovie()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->movie_model->delete($this->input->get("id"));
        $data["redirect"]="site/viewmovie";
        $this->load->view("redirect",$data);
    }
    public function viewexpert()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewexpert";
        $data["base_url"]=site_url("site/viewexpertjson");
        $data["title"]="View expert";
        $this->load->view("template",$data);
    }
    function viewexpertjson()
    {
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_expert`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_expert`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_expert`.`status`";
        $elements[2]->sort="1";
        $elements[2]->header="Status";
        $elements[2]->alias="status";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_expert`");
        $this->load->view("json",$data);
    }

    public function createexpert()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="createexpert";
        $data["title"]="Create expert";
        $data['status']=$this->expert_model->getstatusdropdown();
        $this->load->view("template",$data);
    }
    public function createexpertsubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("name","Name","trim");
        $this->form_validation->set_rules("status","Status","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="createexpert";
            $data["title"]="Create expert";
            $data['status']=$this->expert_model->getstatusdropdown();
            $this->load->view("template",$data);
        }
        else
        {
            $name=$this->input->get_post("name");
            $status=$this->input->get_post("status");
            if($this->expert_model->create($name,$status)==0)
                $data["alerterror"]="New expert could not be created.";
            else
                $data["alertsuccess"]="expert created Successfully.";
            $data["redirect"]="site/viewexpert";
            $this->load->view("redirect",$data);
        }
    }
    public function editexpert()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="editexpert";
        $data["page2"]="block/expertblock";
        $data["title"]="Edit expert";
        $data['status']=$this->expert_model->getstatusdropdown();
        $data["before"]=$this->expert_model->beforeedit($this->input->get("id"));
        $this->load->view("template",$data);
    }
    public function editexpertsubmit()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("id","ID","trim");
        $this->form_validation->set_rules("name","Name","trim");
        $this->form_validation->set_rules("status","Status","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="editexpert";
            $data["title"]="Edit expert";
            $data['status']=$this->expert_model->getstatusdropdown();
            $data["before"]=$this->expert_model->beforeedit($this->input->get("id"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $name=$this->input->get_post("name");
            $status=$this->input->get_post("status");
            if($this->expert_model->edit($id,$name,$status)==0)
                $data["alerterror"]="New expert could not be Updated.";
            else
                $data["alertsuccess"]="expert Updated Successfully.";
            $data["redirect"]="site/viewexpert";
            $this->load->view("redirect",$data);
        }
    }
    public function deleteexpert()
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->expert_model->delete($this->input->get("id"));
        $data["redirect"]="site/viewexpert";
        $this->load->view("redirect",$data);
    }
    public function viewexpertrating()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="viewexpertrating";
        $data["page2"]="block/movieblock";
        $movieid=$this->input->get('id');
        $data["before"]=$this->movie_model->beforeedit($this->input->get("id"));
        $data["base_url"]=site_url("site/viewexpertratingjson?id=".$movieid);
        $data["title"]="View expertrating";
        $this->load->view("templatewith2",$data);
    }
    function viewexpertratingjson()
    {
        $movieid=$this->input->get('id');
        $elements=array();
        
        $elements[0]=new stdClass();
        $elements[0]->field="`movie_expertrating`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";
        
        $elements[1]=new stdClass();
        $elements[1]->field="`movie_expertrating`.`expert`";
        $elements[1]->sort="1";
        $elements[1]->header="Expert";
        $elements[1]->alias="expert";
        
        $elements[2]=new stdClass();
        $elements[2]->field="`movie_expertrating`.`movie`";
        $elements[2]->sort="1";
        $elements[2]->header="Movie";
        $elements[2]->alias="movie";
        
        $elements[3]=new stdClass();
        $elements[3]->field="`movie_expertrating`.`rating`";
        $elements[3]->sort="1";
        $elements[3]->header="Rating";
        $elements[3]->alias="rating";
        
        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        
        if($maxrow=="")
        {
            $maxrow=20;
        }
        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }
        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `movie_expertrating`","WHERE `movie_expertrating`.`movie`='$movieid'");
        $this->load->view("json",$data);
    }

    public function createexpertrating()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="createexpertrating";
        $data["title"]="Create expertrating";
        $data['expert']=$this->expert_model->getexpertdropdown();
        $data['movieid']=$this->input->get('id');
        $this->load->view("template",$data);
    }
    public function createexpertratingsubmit() 
    {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("expert","Expert","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        if($this->form_validation->run()==FALSE)
        {
            $access=array("1");
            $this->checkaccess($access);
            $data["page"]="createexpertrating";
            $data["title"]="Create expertrating";
            $data['expert']=$this->expert_model->getexpertdropdown();
            $data['movieid']=$this->input->post('movie');
            $this->load->view("template",$data);
        }
        else
        {
            $expert=$this->input->get_post("expert");
            $movie=$this->input->get_post("movie");
            $rating=$this->input->get_post("rating");
            if($this->expertrating_model->create($expert,$movie,$rating)==0)
                $data["alerterror"]="New expertrating could not be created.";
            else
                $data["alertsuccess"]="expertrating created Successfully.";
            $data["redirect"]="site/viewexpertrating?id=".$movie;
            $this->load->view("redirect2",$data);
        }
    }
    public function editexpertrating()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data["page"]="editexpertrating";
        $data["title"]="Edit expertrating";
        $data['expert']=$this->expert_model->getexpertdropdown();
        $data['movieid']=$this->input->get('id');
        $data['expertratingid']=$this->input->get("expertratingid");
        $data["before"]=$this->expertrating_model->beforeedit($this->input->get("expertratingid"));
        $this->load->view("template",$data);
    }
    public function editexpertratingsubmit()
        {
        $access=array("1");
        $this->checkaccess($access);
        $this->form_validation->set_rules("expert","Expert","trim");
        $this->form_validation->set_rules("movie","Movie","trim");
        $this->form_validation->set_rules("rating","Rating","trim");
        if($this->form_validation->run()==FALSE)
        {
            $data["alerterror"]=validation_errors();
            $data["page"]="editexpertrating";
            $data["title"]="Edit expertrating";
            $data['movie']=$this->movie_model->getmoviedropdown();
            $data['expertid']=$this->input->get('id');
            $data['expertratingid']=$this->input->get("expertratingid");
            $data["before"]=$this->expertrating_model->beforeedit($this->input->get("expertratingid"));
            $this->load->view("template",$data);
        }
        else
        {
            $id=$this->input->get_post("id");
            $expert=$this->input->get_post("expert");
            $movie=$this->input->get_post("movie");
            $rating=$this->input->get_post("rating");
            if($this->expertrating_model->edit($id,$expert,$movie,$rating)==0)
                $data["alerterror"]="New expertrating could not be Updated.";
            else
                $data["alertsuccess"]="expertrating Updated Successfully.";
            $data["redirect"]="site/viewexpertrating?id=".$movie;
            $this->load->view("redirect2",$data);
        }
    }
    public function deleteexpertrating()
    {
        $access=array("1");
        $this->checkaccess($access);
        $data['movieid']=$this->input->get('id');
        $data['expertratingid']=$this->input->get("expertratingid");
        $this->expertrating_model->delete($this->input->get("expertratingid"));
        $data["redirect"]="site/viewexpertrating?id=".$this->input->get('id');
        $this->load->view("redirect2",$data);
    }

}
?>
