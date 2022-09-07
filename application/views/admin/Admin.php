<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public $header = 'admin/header';
	public $footer = 'admin/footer';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Allmodel');
		date_default_timezone_set('Asia/Kolkata');
	}
	public function page($mpage,$data='')
	{

		if(!empty($this->session->userdata('s_admin'))){
			$s_admin=$this->session->userdata('s_admin');
			$data['usersdata']=$this->Allmodel->selectrow('tbl_admin',array('id'=>$s_admin->id));
			$data['permissions']=1;
			
		}elseif(!empty($this->session->userdata('system_user'))){
			$system_user=$this->session->userdata('system_user');
			$usersdata=$this->Allmodel->selectrow('lt_system_users',array('id'=>$system_user->id));
			$permissions=$this->Allmodel->getresult_asc('lt_permissions',array('role_id'=>$system_user->role_id));
			$data['usersdata']=$usersdata;
			$data['permissions']=$permissions;
		}else{
			$data=array();
		}
		$this->load->view($this->header,$data);
		$this->load->view('admin/'.$mpage,$data);
		$this->load->view($this->footer,$data);

	}

	public function admin_dashboard()
	{
		$this->page('admin_dashboard',array());
	}
	
	public function profile()
	{
	    if(!empty($_POST)){
	        if(!empty($this->session->userdata('s_admin'))){
    			$users=$this->session->userdata('s_admin');
    			$user_id = $users->id;
                $ids = $this->input->post('id');
    			$data['name']=$this->input->post('name');
    			$data['email']=$this->input->post('email');
    			if(!empty($this->input->post('password'))){
    				$pass = $this->Allmodel->encrypt($this->input->post('password'),KEY);
    				$data['password']=$pass;
    			}
    			if(!empty($this->input->post('mobile'))){
    				$data['mobile']=$this->input->post('mobile');
    			}
    			if(!empty($this->input->post('address'))){
    				$data['address']=$this->input->post('address');
    			}
    			$data['updated_by']=$users->id;
				$data['updated_at']=date('Y-m-d H:i:s');
    			$update=$this->Allmodel->update('tbl_admin',$data,array('id'=>$ids));
    		}elseif(!empty($this->session->userdata('system_user'))){
    			$users=$this->session->userdata('system_user');
    			$ids = $this->input->post('id');
    			$data['name']=$this->input->post('name');
    			$data['email']=$this->input->post('email');
    			if(!empty($this->input->post('password'))){
    			    $pass = $this->Allmodel->encrypt($this->input->post('password'),KEY);
    				$data['password']=$pass;
    			}
    			if(!empty($this->input->post('mobile'))){
    				$data['mobile']=$this->input->post('mobile');
    			}
    			if(!empty($this->input->post('address'))){
    				$data['address']=$this->input->post('address');
    			}
    			$data['updated_by']=$users->staff;
				$data['updated_at']=date('Y-m-d H:i:s');
    		    $update=$this->Allmodel->update('lt_system_users',$data,array('id'=>$ids));
    		}
    		if($update>0){
				$this->session->set_flashdata('success','Updated Successfully');redirect($page);
			}else{
				$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
			}
	    }
	    
	    if(!empty($this->session->userdata('s_admin'))){
			$users=$this->session->userdata('s_admin');
			$user_id = $users->id;
			$query = "SELECT a.* FROM `tbl_admin` as a WHERE a.id='$user_id'";
		}elseif(!empty($this->session->userdata('system_user'))){
			$users=$this->session->userdata('system_user');
			$user_id = $users->id;
			$query = "SELECT a.*,b.name as roll_name FROM `lt_system_users` as a INNER JOIN lt_roles as b on a.role_id=b.id WHERE a.id='$user_id'";
		}
		
		$data_page['result']=$this->Allmodel->join_result($query);
		$this->page('profile',$data_page);
	}
	
	
	public function pending_letter_reports()
	{
    	$data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');
		$data_page['default_cio']=$this->Allmodel->getresult('default_cio_locations',array('type'=>'Office'));
		if(!empty($_POST)){
    		$query = "SELECT a.id,b.name,b.code,b.doj,b.company,c.name as branch_name,d.letter_name, e.template_name, a.complate_status, a.created_at,b.sync_datetime FROM `lt_employee_temporary_table` as a LEFT JOIN `guard_master` as b on b.id=a.employee_id LEFT JOIN default_cio_locations as c on a.branch_id=c.id LEFT JOIN lt_letter_master as d on d.id=a.letter_id LEFT JOIN lt_letter_templates as e on e.id=a.letter_template_id WHERE a.complate_status IS NOT NULL";
    	
    		
    		if(!empty($_POST['branch_id'])){
    			$branch_id=$_POST['branch_id'];
    			$query .=" AND a.branch_id ='$branch_id'";
    		}
    		if(!empty($_POST['letter_type'])){
    			$letter_type=$_POST['letter_type'];
    			$query .=" AND a.letter_id ='$letter_type'";
    		}
    		if(!empty($_POST['doj'])){
    			$doj=$_POST['doj'];
    			$query .=" AND b.doj ='$doj'";
    		}
			if(!empty($_POST['status'])){    			
				if($_POST['status']=="2"){
    			    $query .=" AND a.complate_status =0";
    			}else{
					$query .=" AND a.complate_status =1";
				}
    			
    		}
    		
    		$query .=" order by a.id DESC";

    		$data_page['guard']=$this->Allmodel->join_result($query);
		}else{
		  // $data_page['guard']=$this->Allmodel->join_result("SELECT a.id,b.name,b.code,b.doj,b.company,c.name as branch_name,d.letter_name, e.template_name, a.complate_status, a.created_at,b.sync_datetime FROM `lt_employee_temporary_table` as a LEFT JOIN `guard_master` as b on b.id=a.employee_id LEFT JOIN default_cio_locations as c on a.branch_id=c.id LEFT JOIN lt_letter_master as d on d.id=a.letter_id LEFT JOIN lt_letter_templates as e on e.id=a.letter_template_id");
		}
		
		$this->page('pending_letter_reports',$data_page);
	}
	
	
	public function onboard(){
		if(!empty($_POST)){
			$ids=$_POST['id'];
			$data['On_Boarded_Through_App']=$_POST['On_Boarded_Through_App'];
			$data['approval_status']=$_POST['approval_status'];
			$update=$this->Allmodel->update('guard_master',$data,array('id'=>$ids));
			if($update>0){
				$this->session->set_flashdata('success','Updated Successfully');redirect('pending-letter-reports');
			}else{
				$this->session->set_flashdata('error','Make Changes To Updated');redirect('pending-letter-reports');
			}
		}
	}
	
	
	public function create_roles()
	{

		$page='create-roles';
		$upid=$this->uri->segment(2);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_roles',array('id'=>$upid));

		}

		$data_page['result']=$this->Allmodel->getresult_asc('lt_roles');

		$this->form_validation->set_rules('name', 'Roll Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('create_roles',$data_page);

		}else{

			$s_admin=$this->session->userdata('s_admin');

			$data['name']=$this->input->post('name');

			$data['status']=$this->input->post('status');

			

			if($ids>0){

			    $data['updated_by']=$s_admin->id;

				$data['updated_at']=date('Y-m-d H:i:s');

				$update=$this->Allmodel->update('lt_roles',$data,array('id'=>$ids));

				if($update>0){

					$this->session->set_flashdata('success','Updated Successfully');redirect($page);

				}else{

					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

				}

			}else{

				$numrow=$this->Allmodel->selectrow('lt_roles',array('name'=>$data['name']));

				if(!empty($numrow)){

					$this->session->set_flashdata('error',''.$data['name'].' Already Exist');redirect($page);

				}else{

				    $data['created_by']=$s_admin->id;

					$data['created_date']=date('Y-m-d H:i:s');

					$insert = $this->Allmodel->insert('lt_roles',$data);

					if($insert){

						$this->session->set_flashdata('success','Saved Successfully');redirect($page);

					}else{

						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

					}

				}

			}

		}

	}

	public function map_user_role()

	{

		$page='create-roles';

		$upid=$this->uri->segment(3);

		if($upid>0){

			$data_page['updates']=$this->Allmodel->getresult('lt_permissions',array('role_id'=>$upid));

		}

		$roll_menu=(object)array(

			'create-roles'					=>'Create roles',

			'system-users'					=>'System Users',

			'create-letter'					=>'Create Letter Master',

			'create-templates'				=>'Create Letter Templates',

			'create-officer-signature'		=>'Create officer with Signature',

			'map-officer-letter'		   =>'Map Letter Templates With Branch',

			'absenteeism-letter-setting'	=>'Absenteeism Letter Setting',

			'approval-to-termination'		=>'Approval to Termination',

			'upload-cards'					=>'Upload Cards (ESIC &amp; GHI)',

			'letter-reports-with-filters'	=>'Letters Acceptance Status',

			'issue-warning-letters'			=>'Issue Warning &amp; Other Letters',

			'approval-to-exit'				=>'Approval to Exit',
			
			'pending-letter-reports'		=>'Pending Letter Reports',

			'other-letters'		=>'Issue Other Letters',
			
			'advance-salary-report'		=>'Advance Salary Report',
			
			'advance-salary-withdrawal'		=>'Advance Salary Withdrawal Report',
			
			'advance-salary-apilog'		=>'Advance Salary Api Log',

			'create-notification'		=>'Create Notification',

		);

		$data_page['roll_menu']=$roll_menu;

		$data_page['result']=$this->Allmodel->getresult('lt_permissions');

		$this->form_validation->set_rules('role_id', 'Roll Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('map_user_role',$data_page);

		}else{

			$data['role_id']=$this->input->post('role_id');

			$this->Allmodel->delete('lt_permissions',array('role_id'=>$data['role_id']));

			$data['created_at']=date('Y-m-d H:i:s');

			$data['updated_at']=date('Y-m-d H:i:s');

			$count=count($this->input->post('key'));

			for($i=0;$i<$count;$i++){

				foreach($roll_menu as $key=>$rows){

					if($key==$_POST['key'][$i]){

						$data['menu_name']=$rows;

					}

				}

				$data['key']=$_POST['key'][$i];

				$select=$this->Allmodel->selectrow('lt_permissions',array('key'=>$data['key'],'role_id'=>$data['role_id']));

				if(empty($select)){

					$insert=$this->Allmodel->insert('lt_permissions',$data);

				}	

			}

			if($insert){

				$this->session->set_flashdata('success','Saved Successfully');redirect($page);

			}else{

				$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

			}

		}

	}

	public function system_users()

	{

		$page='system-users';

		$upid=$this->uri->segment(2);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_system_users',array('id'=>$upid));

		}

		$data_page['roles']=$this->Allmodel->getresult('lt_roles');

		$data_page['staff']=$this->Allmodel->getresultOrderByNameAsc('staff_master',array('Active'=>'Y','site_supervisor'=>0));

		$data_page['get_branch']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));

		$data_page['result']=$this->Allmodel->join_result('SELECT a.*,b.name as roll_name, s.code as staff_code FROM `lt_system_users` as a INNER JOIN lt_roles as b on a.role_id=b.id INNER JOIN staff_master as s on a.staff_id=s.id');

		$this->form_validation->set_rules('role_id', 'Select Roll', 'required');

		$this->form_validation->set_rules('staff_id', 'Staff Name', 'required');

		$this->form_validation->set_rules('name', 'Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('system_users',$data_page);

		}else{

		    $s_admin=$this->session->userdata('s_admin');

			$data['role_id']=$this->input->post('role_id');

			$data['staff_id']=$this->input->post('staff_id');

			$data['name']=$this->input->post('name');

			$data['branch_id']= implode(',',$this->input->post('branch_ids'));

			if(!empty($this->input->post('email')))

				$data['email']=$this->input->post('email');

			if(!empty($this->input->post('password')))

				$data['password']=$this->input->post('password');

			if(!empty($this->input->post('mobile')))

				$data['mobile']=$this->input->post('mobile');

			if(!empty($this->input->post('address')))

				$data['address']=$this->input->post('address');

			

			$data['status']=$this->input->post('status');

			if($ids>0){

			     $data['updated_by']=$s_admin->id;

				$data['updated_at']=date('Y-m-d H:i:s');

				$update=$this->Allmodel->update('lt_system_users',$data,array('id'=>$ids));

				if($update>0){

					$this->session->set_flashdata('success','Updated Successfully');redirect($page);

				}else{

					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

				}

			}else{

				$numrow=$this->Allmodel->selectrow('lt_system_users',array('role_id'=>$data['role_id'],'staff_id'=>$data['staff_id']));

				if(!empty($numrow)){

					$this->session->set_flashdata('error','Already Exist');redirect($page);

				}else{

				     $data['created_by']=$s_admin->id;

					$data['created_at']=date('Y-m-d H:i:s');

					$insert = $this->Allmodel->insert('lt_system_users',$data);

					if($insert){

						$this->session->set_flashdata('success','Saved Successfully');redirect($page);

					}else{

						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

					}

				}

			}

		}

	}



    public function create_templates()

	{

	    $page='create-templates';

		$data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');

		$data_page['get_branch']=$this->Allmodel->getresult('default_cio_locations',array('type'=>'Office'));

		$data_page['get_language']=$this->Allmodel->getresultOrderByAsc('lt_languages');

		$data_page['get_letter_variable_list']=$this->Allmodel->getresultOrderByAsc('lt_templates_letter_variables');

		

		if($_POST){

		    $s_admin=$this->session->userdata('s_admin');

    		$data['template_name']=$this->input->post('template_name');

    		$data['letter_master_id']=$this->input->post('letter_master_id');

    	// 		$data['branch_id']=$this->input->post('branch_id');

    		$data['content']=$this->input->post('description');

    		$data['veriables']=implode(',',$this->input->post('veriables'));

    		$data['status']=$this->input->post('status');

    		$data['language']=$this->input->post('language');

    		$data['created_by']=$s_admin->id;

    	    

    	    $numrow=$this->Allmodel->selectrow('lt_letter_templates',array('template_name'=>$data['template_name']));

    		if(!empty($numrow)){

    			$this->session->set_flashdata('error',''.$data['template_name'].' Already Exist');redirect($page);

    		}else{

    			$data['created_date']=date('Y-m-d H:i:s');

    			$data['effective_date']=date('Y-m-d H:i:s');

    			$insert = $this->Allmodel->insert('lt_letter_templates',$data);

    			if($insert){

    				$this->session->set_flashdata('success','Saved Successfully');redirect($page);

    			}else{

    				$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

    			}

    		} 

		}else{

		    $this->page('letter-templates/create_templates',$data_page);

		}

		



	}

	

	public function letter_template_list(){

	    $page='letter-template';

		$data_page['result']=$this->Allmodel->getresult('lt_letter_templates');

		$data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');

		$data_page['get_branch']=$this->Allmodel->getresult('default_cio_locations',array('type'=>'Office'));

		$data_page['get_language']=$this->Allmodel->getresult('lt_languages');

		$this->page('letter-templates/letter_templates',$data_page);

	}

	public function letter_template_edit(){

	    $page='create-templates/letter-template-list';

		$upid=$this->uri->segment(3);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_letter_templates',array('id'=>$upid));

			if($_POST){

    		    $s_admin=$this->session->userdata('s_admin');

        		$data['template_name']=$this->input->post('template_name');

        		$data['letter_master_id']=$this->input->post('letter_master_id');

        // 		$data['branch_id']=$this->input->post('branch_id');

        		$data['content']=$this->input->post('description');

        		$data['veriables']=implode(',',$this->input->post('veriables'));

        		$data['status']=$this->input->post('status');

        		$data['language']=$this->input->post('language');

        		$data['updated_by']=$s_admin->id;

        		$data['updated_at']=date('Y-m-d H:i:s');

        		$data['effective_date']=date('Y-m-d H:i:s');

				$update=$this->Allmodel->update('lt_letter_templates',$data,array('id'=>$ids));

				if($update>0){

					$this->session->set_flashdata('success','Updated Successfully');redirect($page);

				}else{

					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

				}

			}

			

		}

		$data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');

		$data_page['get_branch']=$this->Allmodel->getresult('default_cio_locations',array('type'=>'Office'));

		$data_page['get_language']=$this->Allmodel->getresult('lt_languages');

		$data_page['get_letter_variable_list']=$this->Allmodel->getresultOrderByAsc('lt_templates_letter_variables');

		$this->page('letter-templates/letter_templates_edit',$data_page);

	}

	

	public function delete_letter_template()

	{

		$page='create-templates/letter-template-list';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_letter_templates',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Successfully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}

	

	

	public function create_letter()

	{

	    

	    $page='create-letter';

		$upid=$this->uri->segment(2);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_letter_master',array('id'=>$upid));

		}

		$data_page['result']=$this->Allmodel->getresult('lt_letter_master');

	    $this->form_validation->set_rules('letter_name', 'Letter Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('create_letter',$data_page);

		}else{

    		$s_admin=$this->session->userdata('s_admin');

    		$data['letter_name']=$this->input->post('letter_name');

    		$data['mode']=$this->input->post('mode');

    		$data['status']=$this->input->post('status');

    		

    		if($ids>0){

    		    $data['updated_by']=$s_admin->id;

    			$data['update_date']=date('Y-m-d H:i:s');

    			$update=$this->Allmodel->update('lt_letter_master',$data,array('id'=>$ids));

    			if($update>0){

    				$this->session->set_flashdata('success','Updated Successfully');redirect($page);

    			}else{

    				$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

    			}

    		}else{

    			$numrow=$this->Allmodel->selectrow('lt_letter_master',array('letter_name'=>$data['letter_name']));

    			if(!empty($numrow)){

    				$this->session->set_flashdata('error',''.$data['letter_name'].' Already Exist');redirect($page);

    			}else{

    			    $data['created_by']=$s_admin->id;

    				$data['created_date']=date('Y-m-d H:i:s');

    				$insert = $this->Allmodel->insert('lt_letter_master',$data);

    				if($insert){

    					$this->session->set_flashdata('success','Saved Successfully');redirect($page);

    				}else{

    					$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

    				}

    			}

    		}

		}

	}

	

	public function delete_letter()

	{

		$page='create-letter';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_letter_master',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Successfully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}

	

	public function create_officer_signature()
	{

		$page='create-officer-signature';

		$upid=$this->uri->segment(2);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_letter_officer_master',array('id'=>$upid));

		}

		$data_page['staff']=$this->Allmodel->getresultOrderByNameAsc('staff_master',array('Active'=>'Y','site_supervisor'=>0));

		$data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));

		$data_page['result']=$this->Allmodel->join_result('SELECT a.id,b.name,a.signature_image,b.email,c.name as branch_name,a.created_date,a.created_by,a.update_by,a.updated_at FROM `lt_letter_officer_master` as a INNER JOIN staff_master as b on a.officer_id=b.id INNER JOIN default_cio_locations as c on a.branch_id=c.id');

		$this->form_validation->set_rules('officer_id', 'Officer Name', 'required');

		$this->form_validation->set_rules('branch_id', 'Branch Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('create_officer_signature',$data_page);

		}else{

			$data['officer_id']=$this->input->post('officer_id');

			$data['branch_id']=$this->input->post('branch_id');

			if(!empty($this->session->userdata('s_admin'))){

				$users=$this->session->userdata('s_admin');

				$data['is_admin']=1;

				$data['created_by']=$users->id;

			}elseif(!empty($this->session->userdata('system_user'))){

				$users=$this->session->userdata('system_user');

				$data['created_by']=$users->staff_id;

			}

			if(!empty($_FILES['signature_image']['name'])){

        		$config['file_name'] = 'signature_image'.rand(999,1000000);

				$config['upload_path'] = 'assets/media/signature_image/';

		        $config['allowed_types'] = 'jpeg|jpg|png';

		        $this->load->library('upload', $config);

		        if ($this->upload->do_upload('signature_image')) {

		        	$dat = $this->upload->data();

					$data['signature_image'] = $dat['file_name'];

				}

			}

			if($ids>0){
                
                if(!empty($this->session->userdata('s_admin'))){

    				$users=$this->session->userdata('s_admin');
    
    				$data['is_admin']=1;
    
    				$data['update_by']=$users->id;
    
    			}elseif(!empty($this->session->userdata('system_user'))){
    
    				$users=$this->session->userdata('system_user');
    
    				$data['update_by']=$users->staff_id;
    
    			}
				$data['updated_at']=date('Y-m-d H:i:s');

				$update=$this->Allmodel->update('lt_letter_officer_master',$data,array('id'=>$ids));

				if($update>0){

					$this->session->set_flashdata('success','Updated Successfully');redirect($page);

				}else{

					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

				}

			}else{

				$numrow=$this->Allmodel->selectrow('lt_letter_officer_master',array('officer_id'=>$data['officer_id'],'branch_id'=>$data['branch_id']));

				if(!empty($numrow)){

					$this->session->set_flashdata('error','Already Exist');redirect($page);

				}else{

					$data['created_date']=date('Y-m-d H:i:s');

					$insert = $this->Allmodel->insert('lt_letter_officer_master',$data);

					if($insert){

						$this->session->set_flashdata('success','Saved Successfully');redirect($page);

					}else{

						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);

					}

				}

			}

		}

	}
	
	public function header_footer()
	{
		$page='header-footer';
		$upid=$this->uri->segment(2);
		$ids=$this->input->post('id');
		$data_page['company']=$this->Allmodel->getresult('lt_tbl_company');
		if($upid>0){
			$data_page['updates']=$this->Allmodel->selectrow('lt_header_footer',array('id'=>$upid));
		}
	
		$data_page['result']=$this->Allmodel->join_result('SELECT a.*,b.company_name FROM `lt_header_footer` as a INNER JOIN lt_tbl_company as b on a.company_id=b.id');
		$this->form_validation->set_rules('company_id', 'letter Name', 'required');
		if($this->form_validation->run()==FALSE){
			$this->page('header_footer',$data_page);
		}else{
			$data['company_id']=$this->input->post('company_id');
			if(!empty($this->session->userdata('s_admin'))){
				$users=$this->session->userdata('s_admin');
				$data['is_admin']=1;
				$data['created_by']=$users->id;
			}elseif(!empty($this->session->userdata('system_user'))){
				$users=$this->session->userdata('system_user');
				$data['created_by']=$users->staff_id;
			}
			$config['file_name'] = 'header_footer'.rand(999,1000000);
			$config['upload_path'] = 'assets/media/header_footer/';
			$config['allowed_types'] = 'jpeg|jpg|png';
			if(!empty($_FILES['header']['name'])){
		        $this->load->library('upload', $config);
		        if ($this->upload->do_upload('header')) {
		        	$dat = $this->upload->data();
					$data['header'] = $dat['file_name'];
				}
			}
			if(!empty($_FILES['footer']['name'])){
		        $this->load->library('upload', $config);
		        if ($this->upload->do_upload('footer')) {
		        	$dat = $this->upload->data();
					$data['footer'] = $dat['file_name'];
				}
			}
			if($ids>0){
				$data['updated_at']=date('Y-m-d H:i:s');
				$data['update_by']=$data['created_by'];
				$update=$this->Allmodel->update('lt_header_footer',$data,array('id'=>$ids));
				if($update>0){
					$this->session->set_flashdata('success','Updated Successfully');redirect($page);
				}else{
					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
				}
			}else{
				$data['created_at']=date('Y-m-d H:i:s');
				$data['updated_at']=date('Y-m-d H:i:s');
				$insert = $this->Allmodel->insert('lt_header_footer',$data);
				if($insert){
					$this->session->set_flashdata('success','Saved Successfully');redirect($page);
				}else{
					$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
				}
			}
		}
	}
	
	public function map_officer_letter()
	{
		$page='map-officer-letter';
		$upid=$this->uri->segment(2);
		$ids=$this->input->post('id');
		if($upid>0){
			$updates=$this->Allmodel->selectrow('lt_letter_branch_mapping',array('id'=>$upid));
			$data_page['up_letter_template'] = $this->Allmodel->join_result("SELECT a.id,a.template_name,b.lang_code,b.name FROM lt_letter_templates as a INNER JOIN lt_languages as b on a.language=b.id WHERE a.letter_master_id=$updates->letter_template_id and a.status=1");
			$data_page['updates_authorised_officere']=$this->Allmodel->join_result("SELECT a.officer_id, b.name FROM  lt_letter_officer_master as a INNER JOIN staff_master as b on a.officer_id =b.id WHERE a.branch_id = $updates->branch_id  ORDER BY b.name ASC");
			$data_page['updates']=$updates;
		}
		$data_page['staff']=$this->Allmodel->join_result("SELECT a.officer_id, b.name FROM  lt_letter_officer_master as a INNER JOIN staff_master as b on a.officer_id =b.id  ORDER BY b.name ASC");
		$data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));
		$data_page['letter_templates']=$this->Allmodel->getresult('lt_letter_templates');
		$data_page['letter_master']=$this->Allmodel->getresult_asc('lt_letter_master');

		$data_page['result']=$this->Allmodel->join_result('SELECT a.id,a.status,b.name as branch_name,c.template_name,d.name as officer_name,a.created_date,a.created_by,a.updated_by,a.updated_at,e.letter_name,f.lang_code,f.name as lang_name FROM `lt_letter_branch_mapping` as a INNER JOIN default_cio_locations as b on a.branch_id=b.id INNER JOIN lt_letter_templates as c on a.letter_template_id=c.id INNER JOIN staff_master as d on a.officer_id=d.id INNER JOIN lt_letter_master as e on a.letter_mater_id=e.id INNER JOIN lt_languages as f on f.id=c.language');

		$this->form_validation->set_rules('officer_id', 'Officer Name', 'required');
		$this->form_validation->set_rules('branch_id', 'Branch Name', 'required');
		$this->form_validation->set_rules('letter_template_id', 'Letter Template', 'required');
		if($this->form_validation->run()==FALSE){
			$this->page('map_officer_letter',$data_page);
		}else{
			$data['officer_id']=$this->input->post('officer_id');
			$data['branch_id']=$this->input->post('branch_id');
			$data['letter_template_id']=$this->input->post('letter_template_id');
			$data['letter_mater_id']=$this->input->post('letter_mater_id');
			if($ids>0){
			    if(!empty($this->session->userdata('s_admin'))){
				    $users=$this->session->userdata('s_admin');
    				$data['is_admin']=1;
    				$data['updated_by']=$users->id;
    			}elseif(!empty($this->session->userdata('system_user'))){
    				$users=$this->session->userdata('system_user');
    				$data['updated_by']=$users->staff_id;
    			}
				$data['updated_at']=date('Y-m-d H:i:s');
                $numrow=$this->Allmodel->selectrow('lt_letter_branch_mapping',array('branch_id'=>$data['branch_id'],'letter_template_id'=>$data['letter_template_id'],'letter_mater_id'=>$data['letter_mater_id']));
				if(!empty($numrow)){
					$this->session->set_flashdata('error','Already officer selected for same branch, letter & templates ');
					redirect($page); 
				}else{
					$update=$this->Allmodel->update('lt_letter_branch_mapping',$data,array('id'=>$ids));
					if($update>0){
						$this->session->set_flashdata('success','Updated Successfully');redirect($page);
					}else{
						$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
					}
				}
			}else{
				$numrow=$this->Allmodel->selectrow('lt_letter_branch_mapping',array('officer_id'=>$data['officer_id'],'branch_id'=>$data['branch_id'],'letter_template_id'=>$data['letter_template_id'],'letter_mater_id'=>$data['letter_mater_id']));
				if(!empty($numrow)){
					$this->session->set_flashdata('error','Already Exist');redirect($page);
				}else{
				    if(!empty($this->session->userdata('s_admin'))){
        				$users=$this->session->userdata('s_admin');
        				$data['is_admin']=1;
        				$data['created_by']=$users->id;
        			}elseif(!empty($this->session->userdata('system_user'))){
        				$users=$this->session->userdata('system_user');
        				$data['created_by']=$users->staff_id;
        			}
					$data['created_date']=date('Y-m-d H:i:s');
					$insert = $this->Allmodel->insert('lt_letter_branch_mapping',$data);
					if($insert){
						$this->session->set_flashdata('success','Saved Successfully');redirect($page);
					}else{
						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
					}
				}
			}
		}
	}
	
	public function absenteeism_letter_setting()
	{

		$page='absenteeism-letter-setting';

		$upid=$this->uri->segment(2);

		$ids=$this->input->post('id');

		if($upid>0){

			$data_page['updates']=$this->Allmodel->selectrow('lt_letter_issuing_days',array('id'=>$upid));

		}

		$data_page['company']=$this->Allmodel->getresult('lt_tbl_company');

		$data_page['letter_templates']=$this->Allmodel->getresult('lt_letter_master');
		

		$data_page['result']=$this->Allmodel->join_result('SELECT a.id,a.first_warning_days,a.second_warning_days,a.status,a.created_by,a.updated_by,a.termination_days,b.company_name,c.letter_name as template_name1,d.letter_name as template_name2,e.letter_name as template_name3,a.created_date,a.updated_at FROM `lt_letter_issuing_days` as a INNER JOIN lt_tbl_company as b on a.company_id=b.id INNER JOIN lt_letter_master as c on a.first_warning_letter_id=c.id INNER JOIN lt_letter_master as d on a.second_warning_letter_id=d.id INNER JOIN lt_letter_master as e on a.termination_letter_id=e.id');

		$this->form_validation->set_rules('company_id', 'Officer Name', 'required');

		if($this->form_validation->run()==FALSE){

			$this->page('absenteeism_letter_setting',$data_page);

		}else{

			$data['company_id']=$this->input->post('company_id');

			if(!empty($this->input->post('first_warning_days')))

				$data['first_warning_days']=$this->input->post('first_warning_days');

			if(!empty($this->input->post('second_warning_days')))

				$data['second_warning_days']=$this->input->post('second_warning_days');

			if(!empty($this->input->post('termination_days')))

				$data['termination_days']=$this->input->post('termination_days');

			if(!empty($this->input->post('first_warning_letter_id')))

				$data['first_warning_letter_id']=$this->input->post('first_warning_letter_id');

			if(!empty($this->input->post('second_warning_letter_id')))

				$data['second_warning_letter_id']=$this->input->post('second_warning_letter_id');

			if(!empty($this->input->post('termination_letter_id')))

				$data['termination_letter_id']=$this->input->post('termination_letter_id');	

				$data['status']=$this->input->post('status');

			

			if($ids>0){

			    if(!empty($this->session->userdata('s_admin'))){

    				$users=$this->session->userdata('s_admin');

    				$data['updated_by']=$users->id;

    			}elseif(!empty($this->session->userdata('system_user'))){

    				$users=$this->session->userdata('system_user');

    				$data['updated_by']=$users->staff_id;

    			}

				$data['updated_at']=date('Y-m-d H:i:s');

				$update=$this->Allmodel->update('lt_letter_issuing_days',$data,array('id'=>$ids));

				if($update>0){

					$this->session->set_flashdata('success','Updated Successfully');redirect($page);

				}else{

					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);

				}

			}else{

				$numrow=$this->Allmodel->selectrow('lt_letter_issuing_days',array('company_id'=>$data['company_id']));

				if(!empty($numrow)){

					$this->session->set_flashdata('error','Already Exist');redirect($page);

				}else{

				    if(!empty($this->session->userdata('s_admin'))){

        				$users=$this->session->userdata('s_admin');

        				$data['created_by']=$users->id;

        			}elseif(!empty($this->session->userdata('system_user'))){

        				$users=$this->session->userdata('system_user');

        				$data['created_by']=$users->staff_id;

        			}

				    

					$data['created_date']=date('Y-m-d H:i:s');

					$insert = $this->Allmodel->insert('lt_letter_issuing_days',$data);

					if($insert){

						$this->session->set_flashdata('success','Saved Successfully');redirect($page);

					}else{

						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
					}
				}
			}
		}
	}
	
	
	public function approval_to_termination()
	{
		$data_page=array();
		$this->page('approval_to_termination',$data_page);
	}
	
	
	/**************************Updated By Suresh 21-12-2021**************************************/
	public function letter_reports_with_filters()
	{
		if(!empty($_POST)){
		
			$data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');
			$data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));
		
		      $query ="SELECT a.id,a.sms_report,a.letter_serial_number,a.employee_id,a.approved_date,a.letter_issue_date,a.confirmation_status,b.name,b.phone_number,b.code,b.company,b.doj,b.address,b.city,b.state,b.role,l.ouinstname as branch_name, m.letter_name  FROM `lt_track_letters` as a INNER JOIN guard_master as b on a.employee_id=b.id INNER JOIN default_cio_locations as l on l.id=b.branch INNER JOIN lt_letter_master as m on m.id=a.letter_id WHERE a.letter_status =1 ";

			if(!empty($_POST['branch_id'])){
				$branch_id=$_POST['branch_id'];
				$query .=" AND a.branch_id ='$branch_id'";
			}
			if(!empty($_POST['letter_type'])){
				$letter_type=$_POST['letter_type'];
				$query .=" AND a.letter_id ='$letter_type'";
			}
			if(!empty($_POST['from_date']) && !empty($_POST['to_date'])){
			    $from_date =$_POST['from_date'];
			    $to_date =$_POST['to_date'];
			    $query .=" AND (b.letter_issue_date BETWEEN '$to_date' AND '$from_date')";
			}

			if(!empty($_POST['join_from_date']) && !empty($_POST['join_to_date'])){
			    $join_from_date =$_POST['join_from_date'];
			    $join_to_date =$_POST['join_to_date'];
			    $query .=" AND (b.doj BETWEEN '$join_from_date' AND '$join_to_date')";
			}


			if(!empty($_POST['sms_status'])){
			    $sms_status = $_POST['sms_status'];
				// $query .=" AND a.sms_report ='$sms_status'";
			}
			if($_POST['confirm_status']){
			    $conf_status = $_POST['confirm_status'];
			    if($conf_status ==2){
			        $query .=" AND a.confirmation_status =0";
			    }else{
			      $query .=" AND a.confirmation_status ='$conf_status'";  
			    }		        
			}
			if(!empty($_POST['emp_code'])){
			    $emp_code =$_POST['emp_code'];
			    $query .=" AND (b.name LIKE '%$emp_code%' OR b.code LIKE '%$emp_code%') ";
			}

			if(empty($_POST['branch_id']) && empty($_POST['emp_code']) && empty($_POST['confirm_status']) && empty($_POST['sms_status']) && empty($_POST['from_date']) && empty($_POST['letter_type'])){
				if(!empty($this->session->userdata('s_admin'))){
					$users=$this->session->userdata('s_admin');
				}elseif(!empty($this->session->userdata('system_user'))){
					$users=$this->session->userdata('system_user');
					$query .=" AND a.branch_id IN ($users->branch_id) ";
				}
				
			}
			$data_page['result']=$this->Allmodel->join_result($query);
		}else{
		    $data_page['get_letters']=$this->Allmodel->getresult('lt_letter_master');
		    $data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));
			// $data_page['result']=$this->Allmodel->join_result('SELECT a.id,a.sms_report,a.letter_serial_number,a.approved_date,a.employee_id,a.letter_issue_date,a.confirmation_status,b.name,b.phone_number,b.code,b.company,b.doj,b.address,b.city,b.state,b.role,l.name as branch_name, m.letter_name  FROM `lt_track_letters` as a INNER JOIN guard_master as b on a.employee_id=b.id INNER JOIN default_cio_locations as l on l.id=b.branch INNER JOIN lt_letter_master as m on m.id=a.letter_id ORDER by a.id DESC');
			$data_page['result']=[];
		}
		$this->page('letter_reports_with_filters',$data_page);
	}

	public function advanceSalaryReport()
	{
	    //$query ="SELECT a.*,b.name,l.name as branch_name FROM `advance_salary_transactions` as a INNER JOIN guard_master as b on a.employeeId=b.code INNER JOIN default_cio_locations as l on l.id=b.branch ";
//$query ="SELECT a.*,b.name,l.name as branch_name FROM `advance_salary_transactions` as a INNER JOIN guard_master as b on a.employeeId=b.code INNER JOIN default_cio_locations as l on l.id=b.branch ";
		$data_page['result']=$this->Allmodel->join_result($query);
		$this->page('advance-salary-report',$data_page);
	}
	
	public function advanceSalaryApiLog()
	{
		$query ="SELECT * FROM `advance_salary_log` ORDER BY id desc";
		$data_page['result']=$this->Allmodel->join_result($query);
	
		$this->page('advance-salary-apilog',$data_page);
	}
	public function advanceSalaryWithdrawal()
	{
	    $query ="SELECT a.*,b.name,l.name as branch_name FROM `advance_salary_withdrawal_limits` as a INNER JOIN guard_master as b on a.employeeId=b.code INNER JOIN default_cio_locations as l on l.id=b.branch";
		$data_page['result']=$this->Allmodel->join_result($query);
		$this->page('advance-salary-withdrawal',$data_page);
	}
	
	/***************************************************************/
	
	public function upload_cards()
	{
		$data_page=array();
		$count=0;
		if(!empty($_FILES['doc_file']['name'])){
			$fp = fopen($_FILES['doc_file']['tmp_name'],'r') or die("can't open file");
			while($csv_line = fgetcsv($fp,1024)){
				$count++;
				if($count == 1){
					continue;
				}
				for($i = 0, $j = count($csv_line); $i < $j; $i++){
					$insert_csv = array();
					$data['employee_id'] = $csv_line[0];
					$data['document_image'] = !empty($csv_line[1])? $csv_line[1]:'';
				}
				$i++;
				if(!empty($this->session->userdata('s_admin'))){
					$users=$this->session->userdata('s_admin');
					$data['created_by']=$users->id;
				}elseif(!empty($this->session->userdata('system_user'))){
					$users=$this->session->userdata('system_user');
					$data['created_by']=$users->staff_id;
				}
				$data['document_type'] =$_POST['document_type'];
				$data['created_at']=date('Y-m-d H:i:s');
				$data['updated_at']=date('Y-m-d H:i:s');
				$this->Allmodel->insert('lt_upload_documents', $data);
			}
			fclose($fp) or die("can't close file");
		}
		$this->page('upload_cards',$data_page);
	}
	
	public function extract(){
		  if(!empty($_FILES['file']['name'])){
			  if($_POST['document_type']=='ESI_Card'){
				$vars='esi_card';
			  }elseif($_POST['document_type']=='GHI_Card'){
				$vars='ghi_card';
			  }
			 $config['upload_path'] = 'assets/'.$vars.'/'; 
			 $config['allowed_types'] = 'zip'; 
			 $config['max_size'] = '5120';
			 $config['file_name'] = $_FILES['file']['name'];
			 $this->load->library('upload',$config); 
			 if($this->upload->do_upload('file')){ 
				$uploadData = $this->upload->data(); 
				$filename = $uploadData['file_name'];
				$zip = new ZipArchive;
				$res = $zip->open("assets/$vars/".$filename);
				if ($res === TRUE) {
				  $extractpath = "assets/$vars/";
				  $zip->extractTo($extractpath);
				  $zip->close();
				  $this->session->set_flashdata('success','Upload & Extract successfully.');
				} else {
				  $this->session->set_flashdata('error','Failed to extract.');
				}
			}else{ 
			   $this->session->set_flashdata('error','Failed to upload');
			} 
		}else{ 
			$this->session->set_flashdata('error','Failed to upload');
		} 
		redirect('upload-cards');
	}
	
	public function issue_warning_letters()
	{

		$data_page=array();

		$this->page('issue_warning_letters',$data_page);

	}

	public function approval_to_exit()
	{
		$data_page=array();
		$this->page('approval_to_exit',$data_page);
	}

	/*
	Delete Functions
	*/

	public function delete_system_users()
	{
		$page='system-users';
		$ids=$this->uri->segment(3);
		$query=$this->Allmodel->delete('lt_system_users',array('id'=>$ids));
		if($query==true){
			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);
		}else{
			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);
		}
	}

	public function delete_roles()
	{
		$page='create-roles';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_roles',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}

	public function delete_officer_signature()

	{

		$page='create-officer-signature';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_letter_officer_master',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}

	public function delete_map_officer_letter()

	{

		$page='map-officer-letter';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_letter_branch_mapping',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}

	public function delete_absenteeism_letter_setting()

	{

		$page='absenteeism-letter-setting';

		$ids=$this->uri->segment(3);

		$query=$this->Allmodel->delete('lt_letter_issuing_days',array('id'=>$ids));

		if($query==true){

			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);

		}else{

			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);

		}

	}
	public function delete_header_footer()
	{
		$page='header-footer';
		$ids=$this->uri->segment(3);
		
		$query=$this->Allmodel->delete('lt_header_footer',array('id'=>$ids));
		if($query==true){
			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);
		}else{
			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);
		}
	}

	/*

	

	Logout Functions

	

	*/

	public function logout()

	{

		$this->session->sess_destroy();

		$this->session->set_flashdata('msg','Logout Successfully');

		redirect('admin/index');

	}

	

	

	public function preview(){

	    $employee_id=1140;

	    $id = $this->uri->segment(2); // dumy letter template id

	    $employee_details =$this->Allmodel->selectrow('guard_master',array('id'=>$employee_id));

	    

	    $letter_details =$this->Allmodel->selectrow('lt_letter_templates',array('id'=>$id));

	    $letter_veriables = explode(',', $letter_details->veriables);

	    

	    $new_veriable_array = [];

	    foreach($letter_veriables as $var){

	        $var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));

	        if(!empty($var_details->sql_query)){

	            if($var_details->column_name == 'current_date'){
	                $var_value = date('d-M-Y');
	                $new_veriable_array[$var_details->column_name] =$var_value;
	            }elseif($var_details->column_name == 'officer_signature'){
	                
	                $branch_data = $this->Allmodel->veriable_query("select branch from guard_master where id =$employee_id");
	                
	                $ques = str_replace('$branch_id', $branch_data->branch , $var_details->sql_query);
	                
	                $que = str_replace('$letter_id', 1 , $ques);
					
	                $var_value = $this->Allmodel->veriable_query($que);
	                if(empty($var_value->vals)){
	                    $var_value->vals = 'space';
	                }
	                $new_veriable_array[$var_details->column_name] =$var_value->vals; 
	            }else{
	                
                    $que = str_replace('$emp_id', $employee_id, $var_details->sql_query);
	                $var_value = $this->Allmodel->veriable_query($que);
	                
	                if(empty($var_value->vals)){
	                    $var_value->vals = 'space';
	                }
	                
	                $new_veriable_array[$var_details->column_name] =$var_value->vals; 

	            }

	        }else{

	      

	                $new_veriable_array[$var_details->column_name] =''; 

	        }

	    }

        $data['message']=$this->getContent($new_veriable_array,$letter_details->content);



	    $this->load->view('appointment-letter-preview',$data);

      

	}

	

	

	public function getContent($details,$template){
		
        foreach ($details as $key => $value) {
			
            if($value !='' && $value !='space' && $key !='officer_signature' && $key !='employee_joining_date'){

                $template = str_replace('{{' . $key . '}}', '<b style="color: #7c1073;">'.$value.'</b>', $template);

            }elseif($value =='space'){

                  $template = str_replace('{{' . $key . '}}', '<b class="text-danger">...............................................</b>', $template);

            }elseif($key =='officer_signature'){

                  $template = str_replace('{{' . $key . '}}', '<img src="'.base_url('assets/media/signature_image/'.$value).'" style="width:234px;height:80px;">', $template);

            }elseif($key =='employee_joining_date'){
                    $date=date_create($value);
                    $doj = date_format($date,"d-M-Y");
                  $template = str_replace('{{' . $key . '}}', '<b style="color: #7c1073;">' . $doj . '</b>', $template);

            }else{

                $template = str_replace('{{' . $key . '}}', '<b class="text-danger">{{' . $key . '}}</b>', $template);

            }

            

        }

        return $template;

    }
	/*******************************************
    * Notiication Starts
    ********************************************/


	public function create_notification(){
		$page='create-notification';
		$s_admin=$this->session->userdata('s_admin');
		if(!empty($_POST['msg_title']) && !empty($_POST['notication_type'])){
			if($_POST['notication_type']==1){
				$data['branch_id']=$_POST['datatypes'];
			}
			if($_POST['notication_type']==2){
				$data['customer_id']=implode(',',$_POST['datatypes1']);
			}
			if($_POST['notication_type']==3){
				$data['company_id']=$_POST['datatypes'];
			}
			if($_POST['notication_type']==4){
				$data['employe_id']=$_POST['datatypes2'];
			}
			if($_POST['notication_type']==5){
				$data['group_id']=implode(',',$_POST['datatypes1']);
			}
			if($_POST['notication_type']==6){
				$data['title_id']=implode(',',$_POST['datatypes1']);
			}
			if($_POST['notication_type']==7){
				$data['visit_allowed_id']=$_POST['datatypes'];
			}
			if(!empty($_FILES['image']['name'])){
        		$config['file_name'] = 'image'.rand(999,1000000);
				$config['upload_path'] = 'assets/media/image/';
		        $config['allowed_types'] = 'jpeg|jpg|png';
		        $this->load->library('upload', $config);
		        if ($this->upload->do_upload('image')) {
		        	$dat = $this->upload->data();
					$data['image'] = $dat['file_name'];
				}
			}
			if(!empty($_POST['tenon_connect']))
				$data['tenon_connect']=$_POST['tenon_connect'];
			$data['notication_type']=$_POST['notication_type'];
			$data['msg_title']=$_POST['msg_title'];
			$data['msg_desc']=$_POST['description'];
			$data['user_id']=$s_admin->id;
			$data['created_at']=date('Y-m-d H:i:s');
			$insert = $this->Allmodel->insert('lt_notification',$data);
			if($insert){
				$this->session->set_flashdata('success','Saved Successfully');redirect($page);
			}else{
				$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
			}
		}else{
			$data_page['result']=$this->Allmodel->join_result("SELECT * FROM `lt_notification` ORDER BY id DESC");
			$this->page('create_notification',$data_page);
		}
	}
	public function notification_history(){
		$s_admin=$this->session->userdata('s_admin');
		$data_page['result']=$this->Allmodel->getresult('tl_sent_notification');
		$this->page('notification_history',$data_page);
	}
	public function export_csv(){
		$row=$this->Allmodel->selectrow('tl_sent_notification',['id'=>$this->uri->segment(3)]);
		if($row->tenon_connect==0){
			$db_name='guard_master';
		}else{
			$db_name='staff_master';
		}
		$sql = "SELECT code,name FROM $db_name WHERE id IN(".$row->employe_id.")";
      	$employe=$this->db->query($sql)->result_array();
		$delimiter = ","; 
		$filename = "notification_report_NOTF0".$row->id.".csv"; 
		$f = fopen('php://memory', 'w'); 
		$fields = array('MSGID','EMP CODE','EMP NAME'); 
    	fputcsv($f, $fields, $delimiter); 
		foreach($employe as $emp){
			$emps['msg_id']='NOTF0'.$row->id;
			$emps['code']=$emp['code'];
			$emps['name']=$emp['name'];
			fputcsv($f, $emps, $delimiter); 
		}
		fseek($f, 0); 
		header('Content-Type: text/csv'); 
		header('Content-Disposition: attachment; filename="' . $filename . '";'); 
		fpassthru($f); 
	}
	public function schedule_notification(){
		$select=$this->Allmodel->getresult('lt_notification',['status'=>0]);
		if(!empty($select)){
			foreach($select as $row){
				/*********************************
				* Branch Wise Staff gcm_id
				**********************************/
				if($row->notication_type==1){
					$this->BranchNotification($row);
				}
				/*********************************
				* Customer Wise 
				**********************************/	
				if($row->notication_type==2){
					$this->CustomerNotification($row);
				}
				/*********************************
				* Company Wise Staff gcm_id
				**********************************/
				if($row->notication_type==3){
					$this->CompanyNotification($row);
				}
				/*********************************
				* Employees Wise
				**********************************/	
				if($row->notication_type==4){
					$this->EmployeesNotification($row);
				}
				/*********************************
				* Group Wise
				**********************************/	
				if($row->notication_type==5){
					$this->GroupNotification($row);
				}
				/*********************************
				* Title Wise
				**********************************/	
				if($row->notication_type==6){
					$this->TitleNotification($row);
				}
				/*********************************
				* Client Visit AllowedNotification  Wise
				**********************************/	
				if($row->notication_type==7){
					$this->ClientVisitAllowedNotification($row);
				}
				$this->Allmodel->delete('lt_notification',['id'=>$row->id]);
			}
		}
	}
	public function BranchNotification($row){
		if($row->branch_id>0){
			if($row->tenon_connect==0){
				$db_name='guard_master';
			}else{
				$db_name='staff_master';
			}
			$results=$this->db->query("SELECT id,gcm_id from $db_name where gcm_id!='' AND branch=$row->branch_id")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique				=array_unique($fcm_id);
				$employe_id			=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'branch_id'			=>$row->branch_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function CompanyNotification($row){
		if($row->company_id>0){
			$company=$this->Allmodel->selectrow('lt_tbl_company',['id'=>$row->company_id]);
			$results=$this->db->query("SELECT id,gcm_id FROM guard_master where gcm_id!='' AND company='$company->company_name'")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'company_id'		=>$row->company_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function CustomerNotification($row){
		if(!empty($row->customer_id)){
			$results=$this->db->query("SELECT DISTINCT g.id,g.gcm_id from guard_master g join guard_site_map m on m.user_id = g.id join default_cio_locations l on l.id = m.site_id where g.gcm_id!='' AND l.customer_id in ($row->customer_id)")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data1['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$total = $result['success']+$result['failure'];
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'customer_id'		=>$row->customer_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function EmployeesNotification($row){
		if(!empty($row->employe_id)){
			$variable=explode(",", $row->employe_id);
			$code=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM guard_master WHERE gcm_id!='' AND code in('$code')")->result();
			if(!empty($results)){
				foreach($results as $staf){
					$employe[]	=$staf->id;
					$fcm_id[]	=$staf->gcm_id;
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data1['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				//print_r($unique);die;
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$total = $result['success']+$result['failure'];
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'emp_id'			=>$row->employe_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function GroupNotification($row){
		if(!empty($row->group_id)){
			$variable=explode(",", $row->group_id);
			$code=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE `group` in ('$code') AND gcm_id!=''")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data1['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$total = $result['success']+$result['failure'];
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'group_id'			=>$row->group_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function TitleNotification($row){
		if(!empty($row->title_id)){
			$variable=explode(",", $row->title_id);
			$code=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE `title` in ('$code') AND gcm_id!=''")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data1['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$total = $result['success']+$result['failure'];
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'title_id'			=>$row->title_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	public function ClientVisitAllowedNotification($row){
		if($row->visit_allowed_id==0 || $row->visit_allowed_id==1){
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE Customer_Visit_Allowed=$row->visit_allowed_id AND gcm_id!=''")->result();
			if(!empty($results)){
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$employe[]	=$staf->id;
						$fcm_id[]	=$staf->gcm_id;
					}
				}
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data1['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$unique			=array_unique($fcm_id);
				$employe_id		=array_unique($employe);
				if(count($unique)>0){
					$responce=$this->send_notification($data,$unique);
					$result = json_decode($responce,true);
					$total = $result['success']+$result['failure'];
					$insertdata=[
						'employe_id'		=>implode(',',$employe_id),
						'notication_type'	=>$row->notication_type,
						'visit_allowed_id'	=>$row->visit_allowed_id,
						'tenon_connect'		=>$row->tenon_connect,
						'msg_title'			=>$row->msg_title,
						'msg_desc'			=>$row->msg_desc,
						'user_id'			=>$row->user_id,
						'image'				=>$row->image,
						'totat_sent'		=>!empty($result['success']) ? $result['success']:0,
						'totat_failure'		=>!empty($result['failure']) ? $result['failure']:0,
						'created_at'		=>date('Y-m-d h:i:s'),
						'shoot_date'		=>$row->created_at
					];
					$this->Allmodel->insert('tl_sent_notification',$insertdata);
				}
			}
		}
	}
	function send_notification($alldata,$fcm_id){
        $head=array(
            'Authorization: key=' .API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $fields = array(
            'registration_ids'=> $fcm_id,
            'notification'=> $alldata,
			'priority' => 'high'
        );
        $result = $this->curl_control($head,$fields);
		return $result;
    }
    function curl_control($head,$fields)
    {
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, FCM_URL );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $head );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

        $result = curl_exec($ch );
        curl_close( $ch );
        return $result;
    }
	/*******************************************
    * Notiication Ends
    ********************************************/


}

