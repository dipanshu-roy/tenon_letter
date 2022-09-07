<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Allmodel');
		date_default_timezone_set('Asia/Kolkata');
	}
	public function get_staff_master()
	{
		if(!empty($_POST)){
            $updates=$this->Allmodel->selectrow('staff_master',array('id'=>$_POST['id']));{
                ?>
                <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="hidden" name="name" class="form-control" value="<?php echo !empty($updates->name)? $updates->name:''?>">
                        <input type="hidden" name="password" class="form-control" value="<?php echo !empty($updates->password)? $updates->password:''?>">
                        <input type="email" name="email" class="form-control" value="<?php echo !empty($updates->email)? $updates->email:set_value('email');?>" placeholder="Enter email" readonly>
                        <div class="invalid-feedback d-block"> <?php echo form_error('email'); ?></div>
                    </div>          
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" value="<?php echo !empty($updates->phone_number)? $updates->phone_number:set_value('mobile');?>" placeholder="Enter Mobile Number" pattern="[0-9]*" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" readonly>
                        <div class="invalid-feedback d-block"> <?php echo form_error('mobile'); ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo !empty($updates->address)? $updates->address:set_value('address');?>" placeholder="Enter Address" readonly>
                        <div class="invalid-feedback d-block"> <?php echo form_error('address'); ?></div>
                    </div>
                </div>
                </div>
                <?php
            }
        }
	}
    public function map_user_role()
    {
        if(!empty($_POST)){
            $data['roll_id']=$this->input->post('roll_id');
            $data['key']=$this->input->post('key');
			$data['created_at']=date('Y-m-d H:i:s');
			$data['updated_at']=date('Y-m-d H:i:s');
			$select=$this->Allmodel->selectrow('lt_permissions',array('key'=>$data['key'],'roll_id'=>$data['roll_id']));
            if(empty($select)){
                $insert=$this->Allmodel->insert('lt_permissions',$data);
            }else{
                $this->Allmodel->delete('lt_permissions',array('id'=>$select->id));
            }
			if($insert){
				$this->session->set_flashdata('success','Saved Successfully');redirect($page);
			}else{
				$this->session->set_flashdata('error','Make Changes To Saved');redirect($page);
			}
        }
    }
    public function map_officer_letter_status()
    {
        if($this->input->post('status') ==0){
             $status=1;
        }else{
             $status=0;
        }
       
        $id=$this->input->post('id');
        $this->Allmodel->update('lt_letter_branch_mapping',array('status'=>$status),array('id'=>$id));
    }
    public function letter_template_list_status()
    {
        $status=$this->input->post('status');
        $id=$this->input->post('id');
        $this->Allmodel->update('lt_letter_templates',array('status'=>$status),array('id'=>$id));
    }
    public function header_footer_status()
    {
        $status=$this->input->post('status');
        $id=$this->input->post('id');
        $this->Allmodel->update('lt_header_footer',array('status'=>$status),array('id'=>$id));
    }
    public function getOfficer()
    {
        $branch_id=$this->input->post('id');
        // $data =  $this->Allmodel->join_result("select id, code, name from peregrine_new.staff_master where branch in (WITH RECURSIVE category_path (id, name, path,lvl) AS ( SELECT id, name, name as path, 0 lvl FROM default_cio_locations WHERE parent_id ='".$branch_id."' and type='office'  UNION ALL SELECT c.id, c.name, CONCAT(cp.name, ' > ', c.name), cp.lvl + 1 lvl FROM category_path AS cp JOIN default_cio_locations AS c ON cp.id = c.parent_id  where type='office') SELECT id FROM category_path ORDER BY path) and active='Y'");
        $data = $this->Allmodel->getresult('staff_master',array('site_id'=>$branch_id,'active'=>'Y'));
        foreach($data as $staff){
            echo '<option value="'.$staff->id.'">'.$staff->name.' ('.$staff->code.')</option>';
        }
    }
    public function getGuard()
    {
        $branch_id=$this->input->post('id');
        $data = $this->Allmodel->getresult('guard_master',array('branch'=>$branch_id,'active'=>'Y'));
        echo '<option value="">Select</option>';
        foreach($data as $staff){
            echo '<option value="'.$staff->id.'">'.$staff->name.' ('.$staff->code.')</option>';
        }
    }
    public function getTemplates()
    {
        $letter_master_id=$this->input->post('id');
        $data = $this->Allmodel->join_result("SELECT a.id,a.template_name,b.lang_code,b.name FROM lt_letter_templates as a INNER JOIN lt_languages as b on a.language=b.id WHERE a.letter_master_id=$letter_master_id and a.status=1");
        foreach($data as $row){
            echo '<option value="'.$row->id.'">'.$row->template_name.' ( '.$row->lang_code.' - '.$row->name.' )</option>';
        }
    }
	public function send_otp(){
        if(!empty($_POST)){
            $otp=sprintf( "%04d", rand(0,9999));
            $employee_id=$this->Allmodel->decrypt($_POST['otp'],KEY);
            $select=$this->Allmodel->selectrow('guard_master',array('id'=>$employee_id));
            if(!empty($select)){
                $select1=$this->Allmodel->selectrow('lt_otp',array('employee_id'=>$select->id));
                if(!empty($select1)){
                    $this->Allmodel->update('lt_otp',array('otp'=>$otp),array('employee_id'=>$select->id));
                }else{
                    $this->Allmodel->insert('lt_otp',array('employee_id'=>$select->id,'otp'=>$otp));
                }
                $msg=urlencode("$otp is your verification OTP for Login. TENON / PEREGRINE");
                // $responce=$this->get_content("http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text=$msg&mobile=$select->phone_number");
                $responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$select->phone_number&country=91&DLT_TE_ID=1107161587217934328&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");
            }
        }
    }
    function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
  	}
    public function match_otp()
    {
        if(!empty($_POST)){
            $employee_id=$this->Allmodel->decrypt($_POST['employee_id'],KEY);
            $select1=$this->Allmodel->selectrow('lt_otp',array('employee_id'=>$employee_id,'otp'=>$_POST['input_otp']));
            if(!empty($select1)){
                $data['confirmation_status']=1;
                $data['approved_date']=date('Y-m-d H:i:s');
                $data['message']='url';
                 $data['verify_by']='Verified By OTP'.date('Y-m-d H:i:s');
                $select=$this->Allmodel->update('lt_track_letters',$data,array('employee_id'=>$employee_id));
                if($select){
					$this->session->set_flashdata('success','Apointment applied');
				}else{
					$this->session->set_flashdata('error','Apointment not applied');
				}
            }else{
                $this->session->set_flashdata('error','Wrong OTP');
            }
        }else{
            $this->session->set_flashdata('error','Please inter otp');
        }
    }




     /**********************Start Create short link ***************************************/
     public function cheak_number()
     {
         if(!empty($_POST)){
             $my_number=$_POST['my_number'];
             $select=$this->Allmodel->join_row("SELECT b.phone_number,a.employee_id FROM `lt_track_letters` as a INNER JOIN guard_master as b on a.employee_id=b.id WHERE a.confirmation_status=0 AND b.phone_number='$my_number'");
             $otp=sprintf("%04d",rand(1000,9999));
             if(!empty($select)){
                 $select1=$this->Allmodel->selectrow('lt_otp',array('employee_id'=>$select->employee_id));
                 if(!empty($select1)){
                     $this->Allmodel->update('lt_otp',array('otp'=>$otp),array('employee_id'=>$select->employee_id));
                 }else{
                     $this->Allmodel->insert('lt_otp',array('employee_id'=>$select->employee_id,'otp'=>$otp));
                 }
                 $msg=urlencode("$otp is your verification OTP for Login. TENON / PEREGRINE");
                 //$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$select->phone_number&country=91&DLT_TE_ID=1107161587217934328&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");
                 $employee_id=$this->Allmodel->encrypt($select->employee_id,KEY);
                 $data=['status'=>200,'msg'=>'Sucessfully Sent Otp','mobile'=>$select->phone_number,'employee_id'=>$employee_id];
             }else{
                 $data=['status'=>404,'msg'=>'No pending Letter available for this mobile number '.$my_number.'','mobile'=>'','employee_id'=>''];
             }
         }
         header("Content-type: application/json");
         echo json_encode($data);
     }
     public function match_chkltr_otp()
     {
         if(!empty($_POST)){
             $employee_id=$this->Allmodel->decrypt($_POST['employee_id'],KEY);
                
             $select1=$this->Allmodel->selectrow('lt_otp',array('employee_id'=>$employee_id,'otp'=>$_POST['input_otp']));
             
             if(!empty($select1)){
                 $data['is_view']=1;
                 $data['view_date']=date('Y-m-d H:i:s');
                 $select=$this->Allmodel->update('lt_track_letters',$data,array('employee_id'=>$employee_id));
                 if(!empty($select)){
                    $letter_details = $this->Allmodel->join_result("SELECT a.employee_id as id,b.letter_name,a.letter_id,a.letter_template_id FROM `lt_track_letters` as a INNER JOIN  lt_letter_master as b on a.letter_id=b.id WHERE a.employee_id='$employee_id'");
                    $letter_other = $this->Allmodel->join_result("SELECT letter_title,letter  FROM `lt_other_letter` WHERE FIND_IN_SET($employee_id,employee_id);");
                    $this->session->set_flashdata('success','See your letters & perform required operations');
                    $this->session->set_userdata('url',base_url().'apointment-letter/'.$_POST['employee_id'].'');
                    $this->session->set_userdata('letter_name',$letter_details);
                    $this->session->set_userdata('letter_other',$letter_other);
                 }else{
                     $this->session->set_flashdata('error','No letter fount please check mobile app');
                 }
             }else{
                 $this->session->set_flashdata('error','Wrong OTP');
             }
         }else{
             $this->session->set_flashdata('error','Please inter otp');
         }
     }
    /**********************Start Create short link ***************************************/



    /*******************************************
    * Notiication Start
    ********************************************/
     public function notication_type()
     {
        if(!empty($_POST['notication_id'])){
            if($_POST['notication_id']==1){
                $datas=$this->Allmodel->getresult_asc('default_cio_locations',array('type'=>'Office'));
                if(!empty($datas)){
                    foreach($datas as $row){
                        $data['id']=$row->id;
                        $data['name']=$row->name;
                        $dt[]=$data;
                    }
                    $responce['alldata']=$dt;
                    $responce['names']='Select Branch';
                    $responce['multiple']=1;
                    $responce['status']=200;
                }
            }
            if($_POST['notication_id']==2){
                $datas=$this->Allmodel->getresult('customer_master',['active'=>'Y']);
                if(!empty($datas)){
                    foreach($datas as $row){
                        $data['id']=$row->id;
                        $data['name']=$row->name;
                        $dt[]=$data;
                    }
                    $responce['alldata']=$dt;
                    $responce['names']='Select Customer';
                    $responce['multiple']=1;
                    $responce['status']=200;
                }
            }
            if($_POST['notication_id']==3){
                $datas=$this->Allmodel->getresult('lt_tbl_company');
                if(!empty($datas)){
                    foreach($datas as $row){
                        $data['id']=$row->id;
                        $data['name']=$row->company_name;
                        $dt[]=$data;
                    }
                    $responce['alldata']=$dt;
                    $responce['names']='Select Company';
                    $responce['multiple']=0;
                    $responce['status']=200;
                }
            }
            if($_POST['notication_id']==4){
                $responce['names']='Enter Specific Employees';
                $responce['status']=202;
            }
            if($_POST['notication_id']==5){
                $datas=$this->Allmodel->join_result('SELECT `group` FROM `staff_master` GROUP BY `group` ASC');
                if(!empty($datas)){
                    foreach($datas as $row){
                        $data['id']=$row->group;
                        $data['name']=$row->group;
                        $dt[]=$data;
                    }
                    $responce['alldata']=$dt;
                    $responce['names']='Select Group';
                    $responce['multiple']=1;
                    $responce['status']=200;
                }
            }
            if($_POST['notication_id']==6){
                $datas=$this->Allmodel->join_result('SELECT `title` FROM `staff_master` GROUP BY `title` ASC');
                if(!empty($datas)){
                    foreach($datas as $row){
                        $data['id']=$row->title;
                        $data['name']=$row->title;
                        $dt[]=$data;
                    }
                    $responce['alldata']=$dt;
                    $responce['names']='Select Title';
                    $responce['multiple']=1;
                    $responce['status']=200;
                }
            }
            if($_POST['notication_id']==7){
                for($i=0;$i<2;$i++){
                    if($i==0){
                        $value='No';
                    }else{
                        $value='Yes';
                    }
                    $data['id']=$i;
                    $data['name']=$value;
                    $dt[]=$data;
                }
                $responce['alldata']=$dt;
                $responce['names']='Select Client visit allowed';
                $responce['multiple']=0;
                $responce['status']=200;
            }
            echo json_encode($responce);
        }
     }
    public function notification_status()
    {
        $status=$this->input->post('status');
        $id=$this->input->post('id');
        $this->Allmodel->update('lt_notification',array('status'=>$status),array('id'=>$id));
    }
    /*******************************************
    * Notiication Ends
    ********************************************/
    
    /*******************************************
    * Get Employee List
    ********************************************/
    public function getEmployeeName(){
        if(!empty($_POST['id'])){
            $id=$_POST['id'];
            $datas=$this->Allmodel->join_result("SELECT id,name,code FROM `guard_master` WHERE branch='$id' ORDER BY name ASC");
            if(!empty($datas)){
                echo '<option value="">Select Employee</option>';
                foreach($datas as $row){
                    echo '<option value="'.$row->id.'">'.$row->name.' ( '.$row->code.' )</option>';
                }
            }
        }
    }
    public function get_employee_data(){
        if(!empty($_POST['employee_code'])){
            $employee_code=$_POST['employee_code'];
            $datas=$this->Allmodel->join_row("SELECT id,name,doj,branch,address FROM `guard_master` WHERE code='$employee_code'");
            $branch=$this->Allmodel->join_row("SELECT name FROM `default_cio_locations` WHERE id=$datas->branch LIMIT 1;");
            if(!empty($datas)){
                $exit=$this->Allmodel->join_row("SELECT id FROM `lt_exit` WHERE gaurd_id='$datas->id'");
                $responce['status']=200;
                $responce['id']=$datas->id;
                $responce['name']=$datas->name;
                $responce['branch']=$branch->name;
                $responce['address']=$datas->address;
                $responce['exit']=!empty($exit) ? $exit->id:0;
                $responce['doj']=date_format(date_create($datas->doj),"d-M-Y");
                $responce['letters']=$this->get_letter_maped($datas->branch,11);
            }else{
                $responce['status']=400;
            }
        }else{
            $responce['status']=400;
        }
        echo json_encode($responce);
    }
    public function getsite_id(){
        if(!empty($_POST['officer_id'])){
            $officer_id=$_POST['officer_id'];
            $datas=$this->Allmodel->join_result("SELECT c.id,c.name FROM `guard_site_map` as a INNER JOIN guard_master as b on b.id=a.user_id INNER JOIN default_cio_locations as c on a.site_id=c.id WHERE b.id=$officer_id");
            if(!empty($datas)){
                foreach($datas as $row){
                    echo '<option value="'.$row->id.'">'.$row->name.'</option>';
                }
            }
        }
    }
    public function get_letter_maped($branch_id,$letter_mater_id){
        $datas=$this->Allmodel->join_row("SELECT letter_mater_id,letter_template_id FROM `lt_letter_branch_mapping` WHERE letter_mater_id=$letter_mater_id AND branch_id=$branch_id");
        return $datas;
    }
}