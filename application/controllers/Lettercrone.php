<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lettercrone extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Allmodel');
	}
	public function getEmployeeListSendLetter(){
	    
        $this->getEmployeeListWarningLetter();
	}
	/********************************** Start Get employee list for Warning Letter list***************************************/
	public function getEmployeeListWarningLetter(){
	    $companys=$this->Allmodel->join_result('SELECT id, company_name FROM lt_tbl_company'); 
        ################################################ 
        #  getting company list   1=>tenon, 2=>peregrine
        ################################################
	    foreach($companys as $company_data){
	        $issue_days =$this->Allmodel->join_result('SELECT * FROM lt_letter_issuing_days WHERE company_id="'.$company_data->id.'" limit 1');  //getting letter id & issues days
	        if(!empty($issue_days)){
                $employee_list='';
                $intval='';
                $mapping='';
    	        foreach($issue_days as $days){
    	            if(!empty($days->first_warning_days) && !empty($days->first_warning_letter_id)){  
                        #########################
                        # 1st warning letter days
                        #########################
                        $employee_list=$this->cheakcQuerydays($days->first_warning_days);
                        ####################################################
                        # now we have letter type, company name, emp branch
                        ####################################################
        	            if(!empty($employee_list)){
                            foreach($employee_list as $emp_data){
                                ####################################################
                                # guard_attendance_history Got last date difference
                                ####################################################
                                $attendance_history =$this->Allmodel->join_row("SELECT `date` FROM `guard_attendance_history` WHERE user_id=$emp_data->id ORDER BY `date` DESC LIMIT 1");
                                $intval = $this->date_diff($attendance_history->date);
                                
                                if($intval >= $days->first_warning_days && $intval < $days->second_warning_days){
                                    ####################################################
                                    # Matched Company name with branch
                                    ####################################################
                                    if($company_data->company_name==$emp_data->company){
                                        $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$emp_data->branch,'letter_mater_id'=>$days->first_warning_letter_id]);
                                        if(!empty($mapping)){
                                            $issue =$this->Allmodel->join_row("SELECT letter_issue_date FROM `lt_track_letters` WHERE letter_id=$mapping->letter_mater_id AND letter_template_id=$mapping->letter_template_id AND employee_id=$emp_data->id ORDER BY `id` DESC LIMIT 1");
                                            if(!empty($issue)){
                                                $intval_issue=$this->date_diff($issue->letter_issue_date);
                                                if($intval_issue >= $days->first_warning_days && $intval_issue < $days->second_warning_days){
                                                    $reset=TRUE;
                                                    $this->send_letter($mapping,$emp_data,$company_data,$reset);
                                                }
                                            }else{
                                                $reset=FALSE;
                                                $this->send_letter($mapping,$emp_data,$company_data,$reset);
                                            }
                                        }
                                    }
                                }
                            }
    	                }
    	            }
                    if(!empty($days->second_warning_days) && !empty($days->second_warning_letter_id)){ 
                        #########################
                        # 2nd warning letter days
                        #########################
                        $employee_list=$this->cheakcQuerydays($days->second_warning_days);
                        ####################################################
                        # now we have letter type, company name, emp branch
                        ####################################################
        	            if(!empty($employee_list)){
                            foreach($employee_list as $emp_data){
                                ####################################################
                                # guard_attendance_history Got last date difference
                                ####################################################
                                $attendance_history =$this->Allmodel->join_row("SELECT `date` FROM `guard_attendance_history` WHERE user_id=$emp_data->id ORDER BY `date` DESC LIMIT 1");
                                $intval = $this->date_diff($attendance_history->date);
                                if($intval >= $days->second_warning_days && $intval < $days->termination_days){
                                    ####################################################
                                    # Matched Company name with branch
                                    ####################################################
                                    if($company_data->company_name==$emp_data->company){
                                        $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$emp_data->branch,'letter_mater_id'=>$days->second_warning_letter_id]);
                                        if(!empty($mapping)){
                                            $issue =$this->Allmodel->join_row("SELECT letter_issue_date FROM `lt_track_letters` WHERE letter_id=$mapping->letter_mater_id AND letter_template_id=$mapping->letter_template_id AND employee_id=$emp_data->id ORDER BY `id` DESC LIMIT 1");
                                            if(!empty($issue)){
                                                $intval_issue=$this->date_diff($issue->letter_issue_date);
                                                if($intval_issue >= $days->second_warning_days && $intval_issue < $days->termination_days){
                                                    $reset=TRUE;
                                                    $this->send_letter($mapping,$emp_data,$company_data,$reset);
                                                }
                                            }else{
                                                $reset=FALSE;
                                                $this->send_letter($mapping,$emp_data,$company_data,$reset);
                                            }
                                        }
                                    }
                                }
                            }
    	                }
    	            }
                    if(!empty($days->termination_days) && !empty($days->termination_letter_id)){  
                        #########################
                        # 3rd warning letter days
                        #########################
                        $employee_list=$this->cheakcQuerydays($days->termination_days);
                        ####################################################
                        # now we have letter type, company name, emp branch
                        ####################################################
        	            if(!empty($employee_list)){
                            foreach($employee_list as $emp_data){
                                ####################################################
                                # guard_attendance_history Got last date difference
                                ####################################################
                                $attendance_history =$this->Allmodel->join_row("SELECT `date` FROM `guard_attendance_history` WHERE user_id=$emp_data->id ORDER BY `date` DESC LIMIT 1");
                                $intval = $this->date_diff($attendance_history->date);
                                if($intval >= $days->termination_days){
                                    if($company_data->company_name==$emp_data->company){
                                        $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$emp_data->branch,'letter_mater_id'=>$days->termination_letter_id]);
                                        if(!empty($mapping)){
                                            #####################################################################
                                            # lt_letter_branch_mapping Get All previous letter_id and template_id
                                            #####################################################################
                                            $chekletter =$this->Allmodel->join_result("SELECT letter_mater_id,letter_template_id FROM `lt_letter_branch_mapping` WHERE branch_id=$emp_data->branch AND letter_mater_id in ($days->first_warning_letter_id,$days->second_warning_letter_id)");
                                            if(!empty($chekletter)){
                                                foreach($chekletter as $ro){
                                                    $storeletter_id[] = $ro->letter_mater_id;
                                                    $storetemplate_id[] = $ro->letter_template_id;
                                                }
                                            }
                                            if(!empty($storeletter_id) && !empty($storetemplate_id)){
                                                $get_storeletter_id = implode(',',$storeletter_id);
                                                $get_storetemplate_id = implode(',',$storetemplate_id);
                                                ##############################################################################
                                                # letter_template_id Cheack previos warning and absenteeism letter sent or not
                                                ##############################################################################
                                                $chktrminationcount = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_track_letters` WHERE letter_id in ($get_storeletter_id) AND letter_template_id in ($get_storetemplate_id) AND employee_id=$emp_data->id");
                                                if(!empty($chktrminationcount) && $chktrminationcount->id > 0){
                                                    // $dd[]=[
                                                    //     'Status'=>200,
                                                    //     'Terminated Users'=>$emp_data->id
                                                    // ];
                                                    $terminate=True;
                                                    $this->send_letter($mapping,$emp_data,$company_data,$reset,$terminate);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
    	                }
                    }
    	        }
	        }
	    }
        print_r(@$dd);
	}
    public function date_diff($attendance_history){
        $letter_issue_date = new DateTime($attendance_history);
        $current = date("Y-m-d");
        $current_date = new DateTime($current);
        $interval = $letter_issue_date->diff($current_date);
        return $interval->format('%a');
    }
    public function cheakcQuerydays($days){
        return $this->Allmodel->join_result('SELECT company,code,id,branch from guard_master g WHERE id not in (SELECT distinct user_id from guard_attendance_history WHERE date > date_add(sysdate(), interval -"'.$days.'" day) ORDER by id DESC) and code in (SELECT empcode from ERP_to_stagging_attendance WHERE ToDate >DATE_ADD(sysdate(),INTERVAL - ("'.$days.'" + 30) day))');
    }
    public function send_letter($mapping,$emp_data,$company_data,$reset,$terminate=FALSE)
    {
        $templates =$this->Allmodel->join_row("SELECT veriables FROM lt_letter_templates WHERE id=$mapping->letter_template_id");
        if(!empty($templates)){
            $letter_veriables = explode(',', $templates->veriables);
            foreach($letter_veriables as $var){
                $var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));
                if($var_details->column_name == 'current_date'){
                    $new_veriable_array[$var_details->column_name] = date('d-M-Y');
                }elseif($var_details->column_name != 'letter_accepted_date_time' && $var_details->column_name != 'employee_signature'){
                    $ques = str_replace('$branch_id', $mapping->branch_id , $var_details->sql_query);
                    $que1 = str_replace('$letter_template_id', $mapping->letter_template_id , $ques);
                    $que2 = str_replace('$letter_id', $mapping->letter_mater_id, $que1);
                    $que3 = str_replace('$emp_id', $emp_data->id, $que2);
                
                    $var_value = $this->Allmodel->veriable_query($que3);
                    if(empty($var_value->vals)){
                        $datas_ins['complate_status']=0;
                    }else{
                        $datas_ins['complate_status']=1;
                    }

                    $datas_ins['employee_id']=$emp_data->id;
                    $datas_ins['letter_id']=$mapping->letter_mater_id;
                    $datas_ins['letter_template_id']=$mapping->letter_template_id;
                    $datas_ins['branch_id']=$mapping->branch_id;
                    $datas_ins['company_id']=$company_data->id;

                    ####################################################
                    # now we get letter_days 10 15
                    ####################################################
                    if($terminate==TRUE){
                        $this->termination($datas_ins);
                    }else{
                        if($reset==TRUE){
                            $select=$this->Allmodel->selectrow('lt_employee_temporary_table',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                            if(!empty($select)){
                                $this->Allmodel->update('lt_employee_temporary_table',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
                            }else{
                                $this->Allmodel->insert('lt_employee_temporary_table',$datas_ins);
                            }
                        }else{
                            $track_letters=$this->Allmodel->selectrow('lt_track_letters',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                            if(empty($track_letters)){
                                $select=$this->Allmodel->selectrow('lt_employee_temporary_table',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                                if(!empty($select)){
                                    $this->Allmodel->update('lt_employee_temporary_table',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
                                }else{
                                    $this->Allmodel->insert('lt_employee_temporary_table',$datas_ins);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function termination($datas_ins){
        $select=$this->Allmodel->selectrow('lt_employee_temporary_termination',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
        if(!empty($select)){
            $this->Allmodel->update('lt_employee_temporary_termination',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
        }else{
            $this->Allmodel->insert('lt_employee_temporary_termination',$datas_ins);
        }
    }
	/**********************************End Get employee list for Warning Letter list***************************************/
}