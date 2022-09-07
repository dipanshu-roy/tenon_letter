<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Allmodel');
	}
    public function get_esi_and_ghi_card(){
		$postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_id'])){
            $tenon_uan=$this->Allmodel->join_row("SELECT uan_no  from tenon_salary where employee_code='".$postData['employee_id']."'");
           
            $pgpl_uan=$this->Allmodel->join_row("SELECT uan_no from  pgpl_salary where employee_code='".$postData['employee_id']."'");
        

            $rows=$this->Allmodel->getresult('lt_upload_documents',array('employee_id'=>$postData['employee_id']));
			$esi ='';
			$ghi ='';
            if(!empty($rows)){
                foreach($rows as $row){
                    $data['id']=$row->id;
                    $data['employee_id']=$row->employee_id;
                    $data['document_type']=$row->document_type;
                    if(!empty($tenon_uan->uan_no)){
                        $uan = $tenon_uan->uan_no;
                    }else{
                        $uan = $pgpl_uan->uan_no;
                    }
					
                    if($row->document_type=='ESI Card'){
                        $folder="esi_card";
						$data['document']=base_url('assets/'.$folder.'/document/').$row->document_image;
						$data['created_at']=$row->created_at;
                        $data['uan_no']=@$uan;
						$esi=$data;
                    }elseif($row->document_type=='GHI Card'){
                        $folder="ghi_card";
						 $data['document']=base_url('assets/'.$folder.'/document/').$row->document_image;
						$data['created_at']=$row->created_at;
                        $data['uan_no']=@$uan;
						$ghi=$data;
                    }
                   
                }
                $print_data=['status_code' => 200,'status'=>'success', 'esi_data' =>$esi,'ghi_data'=>$ghi];
            }else{
                $print_data=['status_code' => 404,'status'=>'failed', 'esi_data' =>$esi,'ghi_data'=>$ghi];
            }
            echo json_encode($print_data);  
        }
    }
    public function employee_letter(){
		$postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_id'])){
            $employee_id=$postData['employee_id'];
            $row=$this->Allmodel->join_row("SELECT a.id,a.letter_serial_number,a.employee_id,a.letter_issue_date,a.confirmation_status,b.name,b.phone_number,b.code,b.company,b.doj,b.address,b.city,b.state,b.role,l.name as branch_name, m.letter_name  FROM `lt_track_letters` as a INNER JOIN guard_master as b on a.employee_id=b.id INNER JOIN default_cio_locations as l on l.id=b.branch INNER JOIN lt_letter_master as m on m.id=a.letter_id WHERE a.employee_id=$employee_id");
            if(!empty($row)){
                $encrypt=$this->Allmodel->encrypt($employee_id,KEY);
                $data['id']=$row->id;
                $data['employee_id']=$row->employee_id;
                $data['letter_serial_number']=$row->letter_serial_number;
                $data['letter_issue_date']=$row->letter_issue_date;
                $data['name']=$row->name;
                $data['phone_number']=$row->phone_number;
                $data['code']=$row->code;
                $data['doj']=$row->doj;
                $data['role']=$row->role;
                $data['branch_name']=$row->branch_name;
                $data['letter_name']=$row->letter_name;
                $data['letter_url']="http://mobility.tenon-fm.com/tenon_letter/apointment-letter/".$encrypt;
                
                $datas[]=$data;
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>$datas];
            }else{
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>[]];
            }
            echo json_encode($print_data);  
        }
    }


     
    /**************************************************************Advnace salary API*****************************************************/
    
    public function getTermsAndCondition(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['language_code'])){ 
            $language_code = $postData['language_code'];
           $row=$this->Allmodel->join_row("SELECT a.col_id,a.col_name,a.tab_name,b.language,b.label  FROM `salary_slip_columns` as a INNER JOIN salary_slip_labels as b on a.col_id=b.col_id WHERE b.language='$language_code' AND a.tab_name ='terms-and-conditions'");
            $data = $row->label;
            $print_data=['status_code' => 200,'status'=>'success', 'data' =>$data];
        }else{
            $data = null;
            $print_data=['status_code' => 404,'status'=>'failed', 'data' =>$data];
        }
        echo json_encode($print_data);  
    }

    public function getAdvanceSalary(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_code'])){ 
            $employee_id = $postData['employee_code'];
            $row=$this->Allmodel->join_row("SELECT advance_salary_applicable,code  FROM `guard_master` WHERE code='$employee_id'");
            if(!empty($row)){
                $data['show_status']=@$row->advance_salary_applicable;
                $data['employee_code']=@$row->code;
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>$data];
            }else{
                 $data = [];
                $print_data=['status_code' => 200,'status'=>'not found', 'data' =>$data];
            }    
        }else{
            $data = [];
            $print_data=['status_code' => 404,'status'=>'failed', 'data' =>$data];
        }
        echo json_encode($print_data);  
    }
    
    public function getWithdrawalLimit(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_code'])){ 
            $employee_id = $postData['employee_code'];
            $row=$this->Allmodel->join_row("SELECT a.*, b.advance_salary_applicable FROM `advance_salary_withdrawal_limits` as a INNER JOIN `guard_master` as b on a.employeeId=b.code WHERE a.employeeId='$employee_id'");
            if(!empty($row)){
                $data['show_status']=@$row->advance_salary_applicable;
                $data['employee_code']=@$row->employeeId;
                $data['eran_amount']=@$row->earnedAmount;
                $data['total_withdrawal_amount']=@$row->maxWithdrawalAmount;  
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>$data];
            }else{
                 $data = [];
                $print_data=['status_code' => 200,'status'=>'not found', 'data' =>$data];
            }    
        }else{
            $data = [];
            $print_data=['status_code' => 404,'status'=>'failed', 'data' =>$data];
        }
        echo json_encode($print_data);  
    }
    public function getTransactionHistory(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_code'])){  
            $employee_id = $postData['employee_code'];
            $rows=$this->Allmodel->join_result("SELECT * FROM advance_salary_transactions WHERE employeeId='$employee_id'");
            if(!empty($rows)){
                foreach($rows as $row){
                    $data['date_time']= @$row->transaction_date;
                    $data['last_withdrawal_amount']=@$row->amount.'.00';
                    $data['account_no']=@$row->to_bank_details?@$row->to_bank_details:'';
                    $data['transaction_id']=@$row->transactionId;
                    $data['status']=@$row->status;
                    $datas[] = $data;  
                }
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>$datas];
            }else{
               $data = [];
                $print_data=['status_code' => 200,'status'=>'not found', 'data' =>$data];  
            }
            
        }else{
            $datas = [];
            $print_data=['status_code' => 404,'status'=>'failed', 'data' =>$datas];
        }
        echo json_encode($print_data);  
    }
    
    /**********************************************************************************************************************/
	public function getLetter(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['user_id'])){
            $user_id=$postData['user_id'];
            $letters=$this->Allmodel->join_result("SELECT id,name,code,supervisor_id FROM `guard_master` where supervisor_id=$user_id");
            if(!empty($letters)){
                foreach($letters as $row){
                    $data['id']=$row->id;
                    $data['name']=$row->name;
                    $data['code']=$row->code;
                    $other_letter=$this->Allmodel->join_result("SELECT letter_title,letter,created_date FROM `lt_other_letter` WHERE find_in_set($row->id,employee_id)");
                    $letter_data=$this->Allmodel->join_result("SELECT a.id as tr_id,a.letter_issue_date,b.letter_name,a.letter_serial_number FROM lt_track_letters as a INNER JOIN lt_letter_master as b on a.letter_id=b.id where a.employee_id=$row->id");
                    $guard=$this->Allmodel->join_row("SELECT b.id,b.name,a.is_approve FROM `lt_employee_temporary_termination` as a inner join staff_master as b on a.approved_by=b.id  where a.employee_id=$row->id");
                    $dates=$this->Allmodel->join_row("SELECT MAX(date) AS MaxDate FROM guard_attendance_history where user_id=$row->id");
                    $ltr=[];
                    if(!empty($letter_data)){
                        foreach($letter_data as $letterdata){
                            $lerdata['tr_id']=$letterdata->tr_id;
                            $lerdata['letter_serial_number']=$letterdata->letter_serial_number;
                            $lerdata['letter_issue_date']=date_format(date_create($letterdata->letter_issue_date),"d-M-Y");
                            $lerdata['letter_name']=$letterdata->letter_name;
                            $ltr[]=$lerdata;
                        }
                    }
                    $data['approved_by']=!empty($guard->name) ? $guard->name:'';
                    $data['emp_id']=$row->supervisor_id;
                    $data['status']=!empty($guard->is_approve) ? 1:0;
                    $data['last_attandace']=date_format(date_create($dates->MaxDate),"d-M-Y");
                    $data['letter_name']=$ltr;
                    $data['other_letter']=[];
                    if(!empty($other_letter)){
                        foreach($other_letter as $other){
                            $other->created_date = date_format(date_create($other->created_date),"d-M-Y");
                            $other->letter = base_url('assets/OtherLetters/').$other->letter;
                            $oth[]=$other;
                        }
                        $data['other_letter']=$oth;
                    }
                    $dataresult[]=$data;
                }
                $print_data=['status_code' => 200,'status'=>'success', 'data' =>$dataresult];
            }
            echo json_encode($print_data);  
        }
    }
    public function getTerminationLetter(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['user_id'])){
            $select=$this->Allmodel->selectrow('staff_master',['id'=>$postData['user_id']]);
            if(!empty($select)){
                $termination=$this->Allmodel->join_result("SELECT a.name,a.company,a.code,b.* FROM `guard_master` as a inner join lt_employee_temporary_termination as b on a.id=b.employee_id where a.supervisor_id=$select->id AND  b.branch_id=$select->branch");
                if(!empty($termination)){
                    foreach($termination as $row){
                        $letters_after_approve=$this->Allmodel->selectrow("lt_track_letters",['employee_id'=>$row->employee_id,'letter_id'=>$row->letter_id,'letter_template_id'=>$row->letter_template_id,'branch_id'=>$row->branch_id,'company_id'=>$row->company_id]);
                         
                        $date=date_create($row->created_at);
                        if($row->is_approve==0){
                            $is_approve='Pending';
                        }elseif($row->is_approve==1){
                            $is_approve='Approved';
                        }
                        $data['termination_id']=$row->id;
                        $data['employee_code']=$row->code;
                        $data['name']=$row->name;
                        $data['company']=$row->company;
                        $data['status']=$is_approve;
                        $data['status_approve']=$row->is_approve;
                        $data['Letter_sent_date']=date_format($date,"d-M-Y");
                        $data['letter_type']='Termination';
                        if(!empty($letters_after_approve)){
                            $date1=date_create($letters_after_approve->letter_issue_date);
                            $data['letter_issue_date']=date_format($date1,"d-M-Y");
                            $data['view_letter']=$letters_after_approve->message;
                        }else{
                            $data['letter_issue_date']='';
                            $data['view_letter']='';
                        }
                        $datas[] = $data;  
                    }
                    $print_data=['status_code' => 200,'status'=>'success', 'data' =>$datas];
                }else{
                    $datas = [];
                    $print_data=['status_code' => 404,'status'=>'not found', 'data' =>$datas]; 
                }
            }else{
                $datas = [];
                $print_data=['status_code' => 404,'status'=>'not found', 'data' =>$datas];  
            }
        }
        echo json_encode($print_data);  
    }
    public function approveTerminationLetter(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['user_id']) && !empty($postData['termination_id'])){
            $user_id=$postData['user_id'];
            $termination_id=$postData['termination_id'];
            //echo  "SELECT a.name,a.company,b.* FROM `guard_master` as a inner join lt_employee_temporary_termination as b on a.id=b.employee_id where a.supervisor_id=$user_id AND b.is_approve=0 AND b.id=$termination_id";die;
            $select=$this->Allmodel->join_row("SELECT a.name,a.company,b.* FROM `guard_master` as a inner join lt_employee_temporary_termination as b on a.id=b.employee_id where a.supervisor_id=$user_id AND b.is_approve=0 AND b.id=$termination_id");
            if(!empty($select)){
                $datas_ins['employee_id']=$select->employee_id;
                $datas_ins['letter_id']=$select->letter_id;
                $datas_ins['letter_template_id']=$select->letter_template_id;
                $datas_ins['branch_id']=$select->branch_id;
                $datas_ins['company_id']=$select->company_id;
                $datas_ins['complate_status']=1;
                $datas_ins['created_at']=date('Y-m-d H:i:s');
                $this->Allmodel->insert('lt_employee_temporary_table',$datas_ins);
                $this->Allmodel->update('lt_employee_temporary_termination',['is_approve'=>1,'approved_by'=>$postData['user_id']],['id'=>$postData['termination_id']]);
                $print_data=['status_code' => 200,'status'=>'Termination Successfully Done', 'data' =>$datas_ins];
            }else{
                $datas = [];
                $print_data=['status_code' => 404,'status'=>'Not Found', 'data' =>$datas];  
            }
        }
        echo json_encode($print_data);  
    }
    public function GetAllAbranch(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['branch'])){
            $dt=[];
            $datas=$this->Allmodel->getresult_asc('default_cio_locations',['id'=>$postData['branch']]);
            if(!empty($datas)){
                foreach($datas as $row){
                    $data['id']=$row->id;
                    $data['name']=$row->name;
                    $dt[]=$data;
                }
                $responce['alldata']=$dt;
                $responce['status']=200;
            }else{
                $responce['alldata']=$dt;
                $responce['status']=404;
            }
            echo json_encode($responce);
        }
    }
    function notification_list(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_id'])){
            $user_id=$postData['employee_id'];
            $result=$this->Allmodel->join_result("SELECT id,msg_title,msg_desc,image FROM `tl_sent_notification` where FIND_IN_SET('$user_id',employe_id) ORDER BY id DESC");
            if(!empty($result)){
                foreach($result as $row){
                    $view=$this->Allmodel->join_row("SELECT is_view FROM `tl_sent_notification` where FIND_IN_SET('$user_id',is_view) AND id=$row->id");
                    if(!empty($view->is_view)){
                        $row->is_view=1;
                    }else{
                        $row->is_view=0;
                    }
                    $row->image=!empty($row->image)? base_url('assets/media/image/'.$row->image):'';
                    $data[]=$row;
                }
                $responce=['status_code' => 200,'status'=>'Successfully', 'data' =>$data];
            }else{
                $responce=['status_code' => 402,'status'=>'Failed', 'data' =>[]];
            }
            echo json_encode($responce);  
        }
    }
    function view_notification(){
        $postData    = json_decode(file_get_contents('php://input'), true);
        if(!empty($postData['employee_id']) && !empty($postData['id'])){
            $user_id=$postData['employee_id'];
            $id=$postData['id'];
            $result=$this->Allmodel->join_row("SELECT is_view FROM `tl_sent_notification` where id=$id");
            if(!empty($result->is_view)){
                $data=explode(',',$result->is_view);
                array_push($data,$user_id);
                $unique=array_unique($data);
                $this->Allmodel->update('tl_sent_notification',['is_view'=>implode(',',$unique)],['id'=>$id]);
                $responce=['status_code' => 200,'status'=>'View Successfully', 'data' =>$unique];
            }else{
                $this->Allmodel->update('tl_sent_notification',['is_view'=>$user_id],['id'=>$id]);
                $responce=['status_code' => 200,'status'=>'View Successfully', 'data' =>$user_id];
            }
            echo json_encode($responce);  
        }
    }
}