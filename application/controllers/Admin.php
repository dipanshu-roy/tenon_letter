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

		if($this->session->userdata('s_admin')->id==3){
			$roll_menu=(object)array('refyne-delivery-reports'=>'Refyne Delivery Reports');
		}else{

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

			'employee-exit'					=>'employee Exit',
			
			'pending-letter-reports'		=>'Pending Letter Reports',

			'other-letters'					=>'Issue Other Letters',
			
			'advance-salary-report'			=>'Advance Salary Report',
			
			'advance-salary-withdrawal'		=>'Advance Salary Withdrawal Report',
			
			'advance-salary-apilog'			=>'Advance Salary Api Log',

			'create-notification'			=>'Create Notification',
			'notification-history'			=>'Notification History',

			'create-refine-notification'	=>'Create Refine Notification',
			'refyne-delivery-reports'		=>'Refyne Delivery Reports',

		);
		}

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
	    $query ="SELECT a.*,b.name,l.name as branch_name FROM `advance_salary_transactions` as a INNER JOIN guard_master as b on a.employeeId=b.code INNER JOIN default_cio_locations as l on l.id=b.branch ";
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
	
	public function upload_cards(){
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
				$this->session->set_flashdata('success','Uploaded successfully.');
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
		$page='issue-warning-letters';
		$data_page['letter_master']=$this->Allmodel->getresult('lt_letter_master');
		$data_page['resions']=$this->Allmodel->getresult('lt_reason');
		$data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));
		$data_page['result']=$this->Allmodel->join_result("SELECT a.*,b.name as branch_name,c.name,d.letter_name,e.template_name,f.reason,g.lang_code,g.name as lang_name,h.name as site_name FROM `lt_issue_warning` as a INNER JOIN default_cio_locations as b on a.branch_id=b.id INNER JOIN guard_master as c on a.employee_id=c.id INNER JOIN lt_letter_master as d on a.letter_id=d.id INNER JOIN lt_letter_templates as e on a.letter_template_id=e.id INNER JOIN lt_reason as f on a.warning_reason=f.id INNER JOIN lt_languages as g on e.language=g.id INNER JOIN default_cio_locations as h on a.site_id=h.id ORDER BY a.id DESC;");
		if(!empty($this->uri->segment(2))){
			$updates=$this->Allmodel->selectrow('lt_issue_warning',['id'=>$this->uri->segment(2)]);
			$data_page['staff']=$this->Allmodel->getresult('guard_master',['id'=>$updates->employee_id]);
			$data_page['up_letter_template']=$this->Allmodel->join_result('SELECT a.id,a.template_name,b.lang_code,b.name FROM `lt_letter_templates` as a INNER JOIN lt_languages as b on a.language=b.id');
			$data_page['cio_locations']=$this->Allmodel->join_result("SELECT c.id,c.name FROM `guard_site_map` as a INNER JOIN guard_master as b on b.id=a.user_id INNER JOIN default_cio_locations as c on a.site_id=c.id WHERE b.id=$updates->employee_id");
			$data_page['updates']=$updates;
		}
		$this->form_validation->set_rules('branch_id', 'branch Name', 'required');
		$this->form_validation->set_rules('employee_id', 'Employee Name', 'required');
		$this->form_validation->set_rules('letter_id', 'Letter Name', 'required');
		$this->form_validation->set_rules('letter_template_id', 'Letter Template Name', 'required');
		$this->form_validation->set_rules('warning_reason', 'Warning Reason', 'required');
		$this->form_validation->set_rules('site_id', 'Site Name', 'required');
		if($this->form_validation->run()==FALSE){
			$this->page('issue_warning_letters',$data_page);
		}else{
			$data['branch_id']=$_POST['branch_id'];
			$data['employee_id']=$_POST['employee_id'];
			$data['letter_id']=$_POST['letter_id'];
			$data['letter_template_id']=$_POST['letter_template_id'];
			$data['warning_reason']=$_POST['warning_reason'];
			$data['remark']=$_POST['remark'];
			$data['incident_date']=date('Y-m-d');
			$data['site_id']=$_POST['site_id'];
			$name=$this->Allmodel->join_row("SELECT company FROM `guard_master` WHERE id=".$data['employee_id']."");
			$company=$this->Allmodel->selectrow('lt_tbl_company',['company_name'=>$name->company]);
			$table_cheack=[
				'employee_id'		=>$_POST['employee_id'],
				'letter_id'			=>$_POST['letter_id'],
				'letter_template_id'=>$_POST['letter_template_id'],
				'branch_id'			=>$_POST['branch_id'],
				'company_id'		=>$company->id,
				'complate_status'	=>1,
			];
			$temporary_table=$this->Allmodel->selectrow('lt_employee_temporary_table',$table_cheack);
			if(empty($temporary_table)){
				$insert = $this->Allmodel->insert('lt_employee_temporary_table',$table_cheack);
			}
			if(!empty($_POST['id'])){
				$ids=$_POST['id'];
				if(!empty($this->session->userdata('s_admin'))){
					$users=$this->session->userdata('s_admin');
					$data['updated_by']=$users->id;
				}elseif(!empty($this->session->userdata('system_user'))){
					$users=$this->session->userdata('system_user');
					$data['updated_by']=$users->staff_id;
				}
				$data['updated_date']=date('Y-m-d H:i:s');
				$update=$this->Allmodel->update('lt_issue_warning',$data,array('id'=>$ids));
				if($update>0){
					$this->session->set_flashdata('success','Updated Successfully');redirect($page);
				}else{
					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
				}
			}else{
				if(!empty($this->session->userdata('s_admin'))){
					$users=$this->session->userdata('s_admin');
					$data['created_by']=$users->id;
				}elseif(!empty($this->session->userdata('system_user'))){
					$users=$this->session->userdata('system_user');
					$data['created_by']=$users->staff_id;
				}
				$data['created_date']=date('Y-m-d H:i:s');
				$insert = $this->Allmodel->insert('lt_issue_warning',$data);
				if($insert){
					$this->session->set_flashdata('success','Saved Successfully');redirect($page);
				}else{
					$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
				}
			}
		}
	}

	public function employee_exit()
	{
		$page='employee-exit';
		if(!empty($this->uri->segment(2))){
			$lt_exit=$this->Allmodel->selectrow('lt_exit',['id'=>$this->uri->segment(2)]);
			$data_page['guard_update']=$this->Allmodel->selectrow('guard_master',['id'=>$lt_exit->gaurd_id]);
			$data_page['update']=$lt_exit;
		}
		$data_page['result']=$this->Allmodel->join_result('SELECT a.*,b.name,b.code FROM `lt_exit` as a INNER JOIN guard_master as b on a.gaurd_id=b.id;');
		if(!empty($_POST['gaurd_id']) && !empty($_POST['date_of_resignation']) && !empty($_POST['date_of_last_working'])){
			if(!empty($_FILES['filedata']['name'])){
				$count=count($_FILES['filedata']['name']);
				$filename=$_POST['filename'];
				$config['upload_path'] 				= 'assets/media/image/';
		        $config['allowed_types'] 			= '*';
				$config['file_name']				= '';
				for($i=0;$i<$count;$i++){
					$_FILES['filedat']['name']     	= $_FILES['filedata']['name'][$i]; 
                    $_FILES['filedat']['type']     	= $_FILES['filedata']['type'][$i]; 
                    $_FILES['filedat']['tmp_name'] 	= $_FILES['filedata']['tmp_name'][$i]; 
                    $_FILES['filedat']['error']     = $_FILES['filedata']['error'][$i]; 
                    $_FILES['filedat']['size']     	= $_FILES['filedata']['size'][$i]; 
					$config['file_name'] 			= url_title($_FILES['filedat']['name'], 'dash', true).rand(999,1000000);
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('filedat')) {
						$dat = $this->upload->data();
						$da['file_name'] 	= $filename[$i];
						$da['filedata'] 	= base_url('assets/media/image/'.$dat['file_name']);
						$return[]			= $da;
					}
				}
				$data=[
					'gaurd_id'				=>$_POST['gaurd_id'],
					'letter_id'	            =>$_POST['letter_id'],
					'letter_template_id'	=>$_POST['letter_template_id'],
					'date_of_resignation'	=>date_format(date_create($_POST['date_of_resignation']),"Y-m-d"),
					'date_of_last_working'	=>date_format(date_create($_POST['date_of_last_working']),"Y-m-d"),
					'document'				=>json_encode($return),
				];
				if(!empty($_POST['id'])){
					$ids=$_POST['id'];
					if(!empty($this->session->userdata('s_admin'))){
						$users=$this->session->userdata('s_admin');
						$data['updated_by']=$users->id;
					}elseif(!empty($this->session->userdata('system_user'))){
						$users=$this->session->userdata('system_user');
						$data['updated_by']=$users->staff_id;
					}
					$data['updated_date']=date('Y-m-d H:i:s');
					$update=$this->Allmodel->update('lt_exit',$data,array('id'=>$ids));
					if($update>0){
						$this->session->set_flashdata('success','Updated Successfully');redirect($page);
					}else{
						$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
					}
				}else{
					if(!empty($this->session->userdata('s_admin'))){
						$users=$this->session->userdata('s_admin');
						$data['created_by']=$users->id;
					}elseif(!empty($this->session->userdata('system_user'))){
						$users=$this->session->userdata('system_user');
						$data['created_by']=$users->staff_id;
					}
					$data['created_date']=date('Y-m-d H:i:s');
					$insert = $this->Allmodel->insert('lt_exit',$data);
					if($insert){
						$this->session->set_flashdata('success','Saved Successfully');redirect($page);
					}else{
						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
					}
				}
			}
		}
		$this->page('employee_exit',$data_page);
	}
	public function generateletter()
	{
		$page='employee-exit';
		if(!empty($this->uri->segment(3))){
			$name=$this->Allmodel->join_row("SELECT id,code,company,branch,phone_number,`name` FROM `guard_master` WHERE id=".$this->uri->segment(4)."");
			$company=$this->Allmodel->selectrow('lt_tbl_company',['company_name'=>$name->company]);
			$exit=$this->Allmodel->selectrow('lt_exit',['gaurd_id'=>$name->id]);
			$letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>11));    
			$last_letter_id =$this->Allmodel->join_row("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
			$link=base_url('chkltr');
			$shortlink=$this->createShortLink($link);
			$new_letter_id = @$last_letter_id->id+1;
			$letter_serial_number =  date("Y").'/'.@$name->branch.'/'.@$letter_type->letter_code.'/'.$new_letter_id;

			$msg=urlencode('Dear '.$name->name.' ('.$name->code.'), your exp. letter is generated. Click here to view . '.$shortlink.'');
			$responce=$this->get_content("http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text=$msg&mobile=$name->phone_number");

			$table_cheack=[
				'employee_id'			=>$this->uri->segment(4),
				'letter_issue_date'		=>date('Y-m-d'),
				'letter_id'				=>$exit->letter_id,
				'letter_template_id'	=>$exit->letter_template_id,
				'confirmation_status'	=>0,
				'message'				=>@$link,
				'letter_status'			=>1,
				'approved_by'			=>'Na',
				'letter_serial_number'	=>@$letter_serial_number,
				'branch_id'				=>@$name->branch,
				'company_id'			=>@$company->id,
				'confirmation_status'	=>1,
				'sms_report'			=>@$responce,
			];
			$track_letters=$this->Allmodel->selectrow('lt_track_letters',['employee_id'=>$name->id,'letter_id'=>$exit->letter_id,'letter_template_id'=>$exit->letter_template_id]);
			if(empty($track_letters)){
				$insert = $this->Allmodel->insert('lt_track_letters',$table_cheack);
			}
            $this->Allmodel->update('guard_master',['active'=>'N'],array('id'=>$this->uri->segment(4)));
			$update=$this->Allmodel->update('lt_exit',['exit_satus'=>1],array('id'=>$this->uri->segment(3)));
			if($update>0){
				$this->session->set_flashdata('success','Experience letter generated successfully & sent via SMS to the employee');redirect($page);
			}else{
				$this->session->set_flashdata('error','Make Generate');redirect($page);
			}
		}
	}
	public function lt_exit_delete(){
		$this->Allmodel->delete('lt_issue_warning',['id'=>1]);
		$this->Allmodel->delete('lt_exit',['id'=>1]);
	}
	function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
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

	

	
	public function letter_preview(){
	    $employee_id=$this->uri->segment(2);
	    $id = $this->uri->segment(4);
	    $employee_details =$this->Allmodel->selectrow('guard_master',array('id'=>$employee_id));
	    $letter_details =$this->Allmodel->selectrow('lt_letter_templates',array('id'=>$id));
	    if($employee_details->company =='Peregrine'){
            $compnay_id = 2;
        }else{
            $compnay_id = 1;
        }
	    $data['header_footer'] =$this->Allmodel->selectrow('lt_header_footer',array('company_id'=>$compnay_id));
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
	                $que = str_replace('$letter_id', $this->uri->segment(3) , $ques);
	                $qu = str_replace('$letter_template_id', $id , $que);
	                $var_value = $this->Allmodel->veriable_query($qu);
	                if(empty($var_value->vals)){
	                    $var_value=(object)array(
							'vals'=>'space',
						);
	                }
	                
	                $new_veriable_array[$var_details->column_name] =$var_value->vals; 
	            }else{
                    $que = str_replace('$emp_id', $employee_id, $var_details->sql_query);
	                $var_value = $this->Allmodel->veriable_query($que);
	                if(empty($var_value->vals) || $var_value->vals ==null ){
	                    $var_value=(object)array(
							'vals'=>'space',
						);
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
	public function otherLetters(){
		$page='other-letters';
		$upid=$this->uri->segment(2);
		$ids=$this->input->post('id');
		if($upid>0){
			$data_page['updates']=$this->Allmodel->selectrow('lt_other_letter',array('id'=>$upid));
			$updatedata=$data_page['updates'];
			$data_page['guard']=$this->Allmodel->join_result("SELECT id,name,code FROM `guard_master` WHERE branch='$updatedata->branch_id' AND id in ($updatedata->employee_id) ORDER BY name ASC");
		}
		// $data_page['company']=$this->Allmodel->getresult('lt_tbl_company');
		$data_page['default_cio']=$this->Allmodel->getresultOrderByNameAsc('default_cio_locations',array('type'=>'Office'));
		$data_page['result']=$this->Allmodel->join_result('SELECT a.*,b.name FROM `lt_other_letter` as a INNER JOIN default_cio_locations as b on a.branch_id=b.id ORDER BY a.id DESC;');
		$this->form_validation->set_rules('branch_id', 'Select Company', 'required');
		$this->form_validation->set_rules('letter_title', 'Letter Title', 'required');
		if($this->form_validation->run()==FALSE){
			$this->page('otherLetters',$data_page);
		}else{
		    $s_admin=$this->session->userdata('s_admin');
			$data['branch_id']=$this->input->post('branch_id');
			$data['employee_id']=implode(',',$this->input->post('employee_id'));
			$data['letter_title']=$this->input->post('letter_title');
			if(!empty($_FILES['letter']['name'])){
				$config['file_name'] = url_title($data['letter_title'],'dash',true).rand(999,1000000);
				$config['upload_path'] = 'assets/OtherLetters/';
				$config['allowed_types'] = '*';
		        $this->load->library('upload', $config);
		        if ($this->upload->do_upload('letter')) {
		        	$dat = $this->upload->data();
					$data['letter'] = $dat['file_name'];
				}
			}
			if($ids>0){
			    $data['updated_by']=$s_admin->id;
				$data['update_date']=date('Y-m-d H:i:s');
				$update=$this->Allmodel->update('lt_other_letter',$data,array('id'=>$ids));
				if($update>0){
					$this->session->set_flashdata('success','Updated Successfully');redirect($page);
				}else{
					$this->session->set_flashdata('error','Make Changes To Updated');redirect($page);
				}
			}else{
				$numrow=$this->Allmodel->selectrow('lt_other_letter',['branch_id'=>$data['branch_id'],'letter_title'=>$data['letter_title']]);
				if(!empty($numrow)){
					$this->session->set_flashdata('error','Already Exist');redirect($page);
				}else{
				    $data['created_by']=$s_admin->id;
					$data['created_date']=date('Y-m-d H:i:s');
					$data['update_date']=date('Y-m-d H:i:s');
					$insert = $this->Allmodel->insert('lt_other_letter',$data);
					if($insert){
						$this->session->set_flashdata('success','Saved Successfully');redirect($page);
					}else{
						$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
					}
				}
			}
		}
	}
	/*******************************************
    * Notiication Starts
    ********************************************/


	public function create_notification(){
		$page='create-notification';
		$s_admin=$this->session->userdata('s_admin');
		if(!empty($_POST['msg_title']) && !empty($_POST['notication_type'])){
			if($_POST['notication_type']==1){
				$data['branch_id']=implode(',',$_POST['datatypes1']);
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
			$this->session->set_flashdata('success','Send Successfully');redirect($page);
		}else{
			$data_page['result']=$this->Allmodel->join_result("SELECT * FROM `lt_notification` ORDER BY id DESC");
			$this->page('create_notification',$data_page);
		}
	}
	public function notification_history(){
		$s_admin=$this->session->userdata('s_admin');
		$data_page['result']=$this->Allmodel->getresult('lt_sent_notification');
		$this->page('notification_history',$data_page);
	}
	public function export_in_csv(){
	    $unique_id=$this->uri->segment(3);
		if($this->uri->segment(4)==1){
			$table = 'staff_master';
		}else{
			$table = 'guard_master';
		}
	    $row=$this->db->query("SELECT a.id,a.msg_id,a.msg_status,a.employe_id,b.name FROM `lt_sent_notification_history` as a INNER JOIN $table as b on a.employe_id=b.id WHERE a.msg_id='$unique_id'")->result_array();
	    $delimiter = ","; 
		$filename = "notification_report_".$unique_id.".csv"; 
		$f = fopen('php://memory', 'w'); 
		$fields = array('UNIQUE ID','STATUS','EMP ID','EMP NAME'); 
    	fputcsv($f, $fields, $delimiter); 
		foreach($row as $emp){
			$emps['unique_id']=$emp['msg_id'];
			$emps['status']=$emp['msg_status'];
			$emps['employee_id']=$emp['employe_id'];
			$emps['name']=$emp['name'];
			fputcsv($f, $emps, $delimiter); 
		}
		fseek($f, 0); 
		header('Content-Type: text/csv'); 
		header('Content-Disposition: attachment; filename="' . $filename . '";'); 
		fpassthru($f); 
	}
	public function export_csv(){
		$row=$this->Allmodel->selectrow('lt_sent_notification',['id'=>$this->uri->segment(3)]);
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
		ini_set('max_execution_time', '1200');
		if($row->branch_id>0 && !empty($row->branch_id)){
			$branchname=implode("','", explode(",", $row->branch_id));
			if($row->tenon_connect==0){
				$db_name='guard_master';
			}else{
				$db_name='staff_master';
			}
			$results=$this->db->query("SELECT id,gcm_id from $db_name where gcm_id!='' AND branch IN ($row->branch_id)")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function CompanyNotification($row){
		ini_set('max_execution_time', '1200');
		if($row->company_id>0){
			$company=$this->Allmodel->selectrow('lt_tbl_company',['id'=>$row->company_id]);
			$results=$this->db->query("SELECT id,gcm_id FROM guard_master where gcm_id!='' AND company='$company->company_name'")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function CustomerNotification($row){
		
		if(!empty($row->customer_id)){
			$results=$this->db->query("SELECT DISTINCT g.id,g.gcm_id from guard_master g join guard_site_map m on m.user_id = g.id join default_cio_locations l on l.id = m.site_id where g.gcm_id!='' AND l.customer_id in ($row->customer_id)")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function EmployeesNotification($row){
		if(!empty($row->employe_id)){
			$variable=explode(",", $row->employe_id);
			$code=implode("','", $variable);
			if($row->tenon_connect==0){
				$table='guard_master';
			}else{
				$table='staff_master';
			}
			$results=$this->db->query("SELECT id,gcm_id FROM $table WHERE gcm_id!='' AND code in('$code')")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function GroupNotification($row){
		if(!empty($row->group_id)){
			$variable=explode(",", $row->group_id);
			$code=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE `group` in ('$code') AND gcm_id!=''")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function TitleNotification($row){
		if(!empty($row->title_id)){
			$variable=explode(",", $row->title_id);
			$code=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE `title` in ('$code') AND gcm_id!=''")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	public function ClientVisitAllowedNotification($row){
		if($row->visit_allowed_id==0 || $row->visit_allowed_id==1){
			$results=$this->db->query("SELECT id,gcm_id FROM `staff_master` WHERE Customer_Visit_Allowed=$row->visit_allowed_id AND gcm_id!=''")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= base_url('assets/media/image/'.$row->image.'');
				}
				$insertdata=[
					'notication_type'	=>@$row->notication_type,
					'branch_id'			=>@$row->branch_id,
					'tenon_connect'		=>@$row->tenon_connect,
					'msg_title'			=>@$row->msg_title,
					'msg_desc'			=>@$row->msg_desc,
					'company_id'		=>@$row->company_id,
					'user_id'			=>@$row->user_id,
					'image'				=>@$row->image,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@$row->created_at
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
				$not_id="NOTF0".$ins_id;
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if($result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'Failed';
						}
						$this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$staf->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
					}
				}
				$success = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Success'");
				$failure = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$not_id' AND msg_status='Failed'");
				$updatedata=[
					'msg_id'			=>@$not_id,
					'totat_sent'		=>@$success->id,
					'totat_failure'		=>@$failure->id,
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
			}
		}
	}
	function send_notification($alldata,$fcm_id){
        $head=array(
            'Authorization: key='.API_ACCESS_KEY,
            'Content-Type: application/json'
        );
        $fields = array(
            'registration_ids'=> array (
                $fcm_id
            ),
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
	public function delete_refyne_notification()
	{
		$page='create-refine-notification';
		$ids=$this->uri->segment(3);
		$query=$this->Allmodel->delete('lt_refyne_notification',array('id'=>$ids));
		if($query==true){
			$this->session->set_flashdata('success','Sucseffully Deleted');redirect($page);
		}else{
			$this->session->set_flashdata('success','Sorry ! Not Deleted');redirect($page);
		}
	}
	/*******************************************
    * Notiication Ends
    ********************************************/
	public function get_data_base(){
		$th=$this->Allmodel->join_result("SELECT id,gcm_id,code,name FROM `guard_master` WHERE name LIKE '%Palak Mehrotra%'");
		print_r($th);die;
	}
	public function delete_exit(){
		$this->Allmodel->delete("lt_exit",['id'=>28]);
		//$this->Allmodel->delete("lt_track_letters",['id'=>3339]);
	}
	public function createShortLink($url){		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api-ssl.bitly.com/v4/shorten',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"group_guid": "",
			"domain": "bit.ly",
			"long_url": "'.$url.'"
		  }',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Bearer 346437dbe5ddb651cb7ebda0edcad49d28b799d0'
		  ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$jsonArray = json_decode($response,true);
		return $jsonArray['link'];
	}

	public function createRefineNotification(){
		$page='create-refine-notification';
		$s_admin=$this->session->userdata('s_admin');
		if(!empty($_POST['msg_title'])){
			if($_POST['tenon_connect']!=1){
				if(empty($_POST['group_id'])){
					$this->session->set_flashdata('error','Please select at least one branch');redirect($page);
				}
			}else{
				$data['company_id']=$_POST['company_id'];
			}
			$data['template_id']=$_POST['template_id'];
			$data['unique_id']=$_POST['unique_id'];
			$data['tenon_connect']=!empty($_POST['tenon_connect']) ? $_POST['tenon_connect']:0;
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
			if(!empty($_POST['images'])){
			    $data['image']=$_POST['images'];
			}
			$data['msg_title']=$_POST['msg_title'];
			$data['msg_desc']=$_POST['description'];
			$data['user_id']=@$s_admin->id;
			$data['group_id']=!empty($_POST['group_id']) ? implode(',',$_POST['group_id']):'';
			$data['created_at']=date('Y-m-d H:i:s');
			$insert = $this->Allmodel->insert('lt_refyne_notification',$data);
			if($insert){
				$this->session->set_flashdata('success','Saved Successfully');redirect($page);
			}else{
				$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
			}
		}else{
			if (isset($_GET['pageno'])) {
				$pageno = $_GET['pageno'];
			} else {
				$pageno = 1;
			}
			$no_of_records_per_page = 10;
			$offset = ($pageno-1) * $no_of_records_per_page;
			$total_rows=$this->db->query("SELECT COUNT(id) as id FROM `lt_refyne_notification`")->row()->id;
			$total_pages = ceil($total_rows / $no_of_records_per_page);
			$data_page['total_pages']=$total_pages;
			$data_page['pageno']=$pageno;

			$data_page['result']=$this->Allmodel->join_result("SELECT * FROM `lt_refyne_notification` ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");
			$data_page['branch']=$this->Allmodel->getresult_asc('default_cio_locations',array('type'=>'Office'));
			$this->page('create_refine_notification',$data_page);
		}
	}
	public function refyneDeliveryReports(){
		if (isset($_GET['pageno'])) {
			$pageno = $_GET['pageno'];
		} else {
			$pageno = 1;
		}
		$no_of_records_per_page = 10;
		$offset = ($pageno-1) * $no_of_records_per_page;
		$total_rows=$this->db->query("SELECT COUNT(id) as id FROM `lt_refyne_notification`")->row()->id;
		$total_pages = ceil($total_rows / $no_of_records_per_page);
		$data_page['total_pages']=$total_pages;
		$data_page['pageno']=$pageno;


		$s_admin=$this->session->userdata('s_admin');
		$data_page['result']=$this->Allmodel->join_result("SELECT a.id,a.unique_id,a.template_id,a.msg_title,a.msg_desc,a.image,a.group_id,b.trigger_at FROM `lt_refyne_notification` as a INNER JOIN lt_refyne_notification_trigger as b on a.unique_id=b.unique_id WHERE b.hit_status=1 AND a.status=0 ORDER BY a.id DESC LIMIT $offset, $no_of_records_per_page");
		$this->page('refyne_delivery_reports',$data_page);
	}
	public function savedata()
	{
		$select = $this->Allmodel->join_result("SELECT id FROM `default_cio_locations` WHERE type='Office' AND company='Tenon'");
		if(!empty($select)){
			foreach($select as $row){
				$data=$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$row->id,'letter_mater_id'=>11,'letter_template_id'=>13]);
				//print_r($data);die;
				if(empty($data)){
					$this->Allmodel->insert('lt_letter_branch_mapping',[
						'branch_id'=>$row->id,
						'letter_mater_id'=>11,
						'letter_template_id'=>13,
						'is_admin'=>1,
						'created_by'=>2,
						'officer_id'=>504,
						'created_date'=>date('Y-m-d H:i:s'),
						'updated_at'=>date('Y-m-d H:i:s'),
					]);
					// $this->Allmodel->update('lt_letter_branch_mapping',[
					// 	'branch_id'=>$row->id,
					// 	'letter_mater_id'=>11,
					// 	'letter_template_id'=>13,
					// 	'is_admin'=>1,
					// 	'created_by'=>2,
					// 	'officer_id'=>8197,
					// 	'created_date'=>date('Y-m-d H:i:s'),
					// 	'updated_at'=>date('Y-m-d H:i:s'),
					// ],['id'=>$data->id]);
				}
			}
		}
	}

}

