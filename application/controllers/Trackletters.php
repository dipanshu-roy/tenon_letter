<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trackletters extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Allmodel');
	}
	public function getEmployeeListSendLetter(){
	    
	   // $this->getEmployeeListAppointmentLetter();
        $this->getEmployeeListWarningLetter();
	}
	/**********************************Start Get employee list for appointment Letter list***************************************/
    public function getEmployeeListAppointmentLetter()
	{
		$selsect=$this->Allmodel->join_result("SELECT id, code, On_Board_Emp_code, sync_datetime,phone_number,name,branch,company,approved_by from guard_master where On_Boarded_Through_App = 1 and approval_status ='Verified' and sync_datetime > date_add(sysdate(), interval -60 day) AND `apt_customer_code` IS NOT NULL AND `apt_gross_wages` IS NOT NULL order by id asc");
		
		foreach($selsect as $row){
			if($row){
				$company=$this->Allmodel->selectrow('lt_tbl_company',['company_name'=>$row->company]);
				$letter =$this->Allmodel->selectrow('lt_letter_master',['id'=>1]);
				
				if(!empty($letter)){
					$mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$row->branch,'letter_mater_id'=>$letter->id,'status'=>0]);
					
					if(!empty($mapping)){
						$templates =$this->Allmodel->selectrow('lt_letter_templates',['id'=>$mapping->letter_template_id]);
						
						if(!empty($templates)){
							$letter_veriables = explode(',', $templates->veriables);
							foreach($letter_veriables as $var){
								$var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));
								if(!empty($var_details->sql_query)){
									if($var_details->column_name == 'current_date')
									{
										$new_veriable_array[$var_details->column_name] = date('d-M-Y');
									}
									elseif($var_details->column_name != 'letter_accepted_date_time' && $var_details->column_name != 'employee_signature'){
										$ques = str_replace('$branch_id', $mapping->branch_id , $var_details->sql_query);
										$que1 = str_replace('$letter_template_id', $mapping->letter_template_id , $ques);
										$que2 = str_replace('$letter_id', $mapping->letter_mater_id, $que1);
										$que3 = str_replace('$emp_id', $row->id, $que2);
									
										$var_value = $this->Allmodel->veriable_query($que3);
								
										if(empty($var_value->vals)){
											$datas_ins['complate_status']=0;
										}else{
											$datas_ins['complate_status']=1;
										} 
									}
								}
							}
							
							// start make insert data By suresh
								$datas_ins['employee_id']=$row->id;
								$datas_ins['letter_id']=$mapping->letter_mater_id;
								$datas_ins['letter_template_id']=$mapping->letter_template_id;
								$datas_ins['branch_id']=$mapping->branch_id;
								$datas_ins['company_id']=$company->id;
								
							// end make insert data
								
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
	
	
	   
	/**********************************End Get employee list for appointment list***************************************/
	
	
	/********************************** Start Get employee list for Warning Letter list***************************************/
	
	public function getEmployeeListWarningLetter(){
	    $companys=$this->Allmodel->join_result('SELECT id, company_name FROM lt_tbl_company');  //getting company list   1=>tenon, 2=>peregrine
	    foreach($companys as $company_data){
	        $letter_issueing_days =$this->Allmodel->join_result('SELECT * FROM lt_letter_issuing_days WHERE company_id="'.$company_data->id.'" limit 1');  //getting letter id & issues days
	        if(!empty($letter_issueing_days)){
    	        foreach($letter_issueing_days as $days){
    	            
    	            if(!empty($days->first_warning_days) && !empty($days->first_warning_letter_id)){  // warning letter days
    	                $absent_employee_list_war =$this->Allmodel->join_result('Select company, code, id,branch from guard_master g Where id not in (select distinct user_id from guard_attendance_history where date > date_add(sysdate(), interval -"'.$days->first_warning_days.'" day)) and code in (select empcode from ERP_to_stagging_attendance where ToDate >DATE_ADD(sysdate(),INTERVAL - ("'.$days->first_warning_days.'" + 30) day))');
        	            // now we have letter type, company name, emp branch
        	            
        	            if(!empty($absent_employee_list_war)){
        	               foreach($absent_employee_list_war as $emp_data){
        	                    if($emp_data->company ==$company_data->id || $emp_data->company ==$company_data->company_name){
            	                    $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$emp_data->branch,'letter_mater_id'=>$days->first_warning_letter_id]);
                                
                                    if(!empty($mapping)){
                						$templates =$this->Allmodel->selectrow('lt_letter_templates',['id'=>$mapping->letter_template_id]);
                					    if(!empty($templates)){
                							$letter_veriables = explode(',', $templates->veriables);
                							foreach($letter_veriables as $var){
                								$var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));
                								if(!empty($var_details->sql_query)){
                									if($var_details->column_name == 'current_date')
                									{
                										$new_veriable_array[$var_details->column_name] = date('d-M-Y');
                									}
                									elseif($var_details->column_name != 'letter_accepted_date_time' && $var_details->column_name != 'employee_signature'){
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
                									}
                								}
                							}
                							
                							    // start make insert data By suresh
                							
                								$datas_ins['employee_id']=$emp_data->id;
                								$datas_ins['letter_id']=$mapping->letter_mater_id;
                								$datas_ins['letter_template_id']=$mapping->letter_template_id;
                								$datas_ins['branch_id']=$mapping->branch_id;
                								$datas_ins['company_id']=$company_data->id;
                								$datas_ins['last_letter_days']=$days->first_warning_days;
                								
                							    // end make insert data
                								
                							$track_letters=$this->Allmodel->join_row("SELECT * FROM lt_track_letters WHERE employee_id='".$datas_ins['employee_id']."' AND letter_id='".$datas_ins['letter_id']."' AND branch_id='".$datas_ins['branch_id']."' AND company_id='".$datas_ins['company_id']."'  ORDER BY id DESC LIMIT 1");
                							
                							if(!empty($track_letters)){
                							    $last_warning_letter_send_date = new DateTime($track_letters->letter_issue_date);
                							    $current = date("Y-m-d h:i:s");
        	                                    $current_date = new DateTime($current);
                                                $interval = $last_warning_letter_send_date->diff($current_date);
                                                
                                                if($interval->format('%a') > $days->first_warning_days ){
                                                    
                                                    $select=$this->Allmodel->selectrow('lt_employee_temporary_table',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                    								if(!empty($select)){
                    									$this->Allmodel->update('lt_employee_temporary_table',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
                    								}else{
                    									$this->Allmodel->insert('lt_employee_temporary_table',$datas_ins);
                    								}
                                                    
                                                }
                							}else{
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
    	           /* 
    	            if(!empty($days->second_warning_days) && !empty($days->second_warning_letter_id)){  // show cause letter days
    	                
    	                $absent_employee_list_show_cause =$this->Allmodel->join_result('Select company, code, id,branch from guard_master g Where id not in (select distinct user_id from guard_attendance_history where date > date_add(sysdate(), interval -"'.$days->second_warning_days.'" day)) and code not in (select empcode from ERP_to_stagging_attendance where ToDate >DATE_ADD(sysdate(),INTERVAL - ("'.$days->second_warning_days.'" + 30) day))');
        	            // now we have letter type, company name, emp branch
        	            if(!empty($absent_employee_list_show_cause)){
        	                foreach($absent_employee_list_show_cause as $emp_datas){
        	                    if($emp_datas->company ==$company_data->id || $emp_datas->company ==$company_data->company_name){
        	                        $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$emp_datas->branch,'letter_mater_id'=>$days->second_warning_letter_id]);
                                        if(!empty($mapping)){
                						$templates =$this->Allmodel->selectrow('lt_letter_templates',['id'=>$mapping->letter_template_id]);
                					    if(!empty($templates)){
                							$letter_veriables = explode(',', $templates->veriables);
                							foreach($letter_veriables as $var){
                								$var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));
                								if(!empty($var_details->sql_query)){
                									if($var_details->column_name == 'current_date')
                									{
                										$new_veriable_array[$var_details->column_name] = date('d-M-Y');
                									}
                									elseif($var_details->column_name != 'letter_accepted_date_time' && $var_details->column_name != 'employee_signature'){
                										$ques = str_replace('$branch_id', $mapping->branch_id , $var_details->sql_query);
                										$que1 = str_replace('$letter_template_id', $mapping->letter_template_id , $ques);
                										$que2 = str_replace('$letter_id', $mapping->letter_mater_id, $que1);
                										$que3 = str_replace('$emp_id', $emp_datas->id, $que2);
                									
                										$var_value = $this->Allmodel->veriable_query($que3);
                								
                										if(empty($var_value->vals)){
                											$datas_ins['complate_status']=0;
                										}else{
                											$datas_ins['complate_status']=1;
                										} 
                									}
                								}
                							}
                							
                							// start make insert data By suresh
                							
                								$datas_ins['employee_id']=$emp_datas->id;
                								$datas_ins['letter_id']=$mapping->letter_mater_id;
                								$datas_ins['letter_template_id']=$mapping->letter_template_id;
                								$datas_ins['branch_id']=$mapping->branch_id;
                								$datas_ins['company_id']=$company_data->id;
                								
                							// end make insert data
                								
                							$track_letters=$this->Allmodel->join_result("SELECT * FROM lt_track_letters WHERE employee_id='".$datas_ins['employee_id']."' AND letter_id='".$datas_ins['letter_id']."' AND branch_id='".$datas_ins['branch_id']."' AND company_id='".$datas_ins['company_id']."' ORDER BY id DESC LIMIT 1");
                							
                							if(!empty($track_letters)){
                							    $last_warning_letter_send_date = new DateTime($track_letters->letter_issue_date);
                							    $current = date("Y-m-d h:i:s");
        	                                    $current_date = new DateTime($currect);
                                                $interval = $last_warning_letter_send_date->diff($current_date);
                                                
                                                if($interval->format('%a') > $days->second_warning_letter_id){
                                                    $select=$this->Allmodel->selectrow('lt_employee_temporary_table',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                    								if(!empty($select)){
                    									$this->Allmodel->update('lt_employee_temporary_table',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
                    								}else{
                    									$this->Allmodel->insert('lt_employee_temporary_table',$datas_ins);
                    								}
                                                    
                                                }
                							}else{
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
    	          
    	            if(!empty($days->termination_days) && !empty($days->termination_letter_id)){  // termination letter days
    	                
    	                $absent_employee_list_termination =$this->Allmodel->join_result('Select company, code, id,branch from guard_master g Where id not in (select distinct user_id from guard_attendance_history where date > date_add(sysdate(), interval -"'.$days->termination_days.'" day)) and code not in (select empcode from ERP_to_stagging_attendance where ToDate >DATE_ADD(sysdate(),INTERVAL - ("'.$days->termination_days.'" + 30) day))');
        	            // now we have letter type, company name, emp branch
        	            if(!empty($absent_employee_list_termination)){
                            foreach($absent_employee_list_termination as $termination_data){
                                 if($termination_data->company ==$company_data->id || $termination_data->company ==$company_data->company_name){
                	                $mapping =$this->Allmodel->selectrow('lt_letter_branch_mapping',['branch_id'=>$termination_data->branch,'letter_mater_id'=>$days->termination_letter_id]);
                                    if(!empty($mapping)){
                						$templates =$this->Allmodel->selectrow('lt_letter_templates',['id'=>$mapping->letter_template_id]);
                					    if(!empty($templates)){
                							$letter_veriables = explode(',', $templates->veriables);
                							foreach($letter_veriables as $var){
                								$var_details =$this->Allmodel->selectrow('lt_templates_letter_variables',array('id'=>$var));
                								if(!empty($var_details->sql_query)){
                									if($var_details->column_name == 'current_date')
                									{
                										$new_veriable_array[$var_details->column_name] = date('d-M-Y');
                									}
                									elseif($var_details->column_name != 'letter_accepted_date_time' && $var_details->column_name != 'employee_signature'){
                										$ques = str_replace('$branch_id', $mapping->branch_id , $var_details->sql_query);
                										$que1 = str_replace('$letter_template_id', $mapping->letter_template_id , $ques);
                										$que2 = str_replace('$letter_id', $mapping->letter_mater_id, $que1);
                										$que3 = str_replace('$emp_id', $termination_data->id, $que2);
                									
                										$var_value = $this->Allmodel->veriable_query($que3);
                								
                										if(empty($var_value->vals)){
                											$datas_ins['complate_status']=0;
                										}else{
                											$datas_ins['complate_status']=1;
                										} 
                									}
                								}
                							}
                							
                							// start make insert data By suresh
                							
                								$datas_ins['employee_id']=$termination_data->id;
                								$datas_ins['letter_id']=$mapping->letter_mater_id;
                								$datas_ins['letter_template_id']=$mapping->letter_template_id;
                								$datas_ins['branch_id']=$mapping->branch_id;
                								$datas_ins['company_id']=$company_data->id;
                								$datas_ins['is_approve']=0;  //pending
                								
                							// end make insert data
                								
                							$track_letters=$this->Allmodel->join_result("SELECT * FROM lt_track_letters WHERE employee_id='".$datas_ins['employee_id']."' AND letter_id='".$datas_ins['letter_id']."' AND branch_id='".$datas_ins['branch_id']."' AND company_id='".$datas_ins['company_id']."' ORDER BY id DESC LIMIT 1");
                							
                							if(empty($track_letters)){
                							    $select=$this->Allmodel->selectrow('lt_employee_temporary_termination',['employee_id'=>$datas_ins['employee_id'],'letter_id'=>$datas_ins['letter_id'],'letter_template_id'=>$datas_ins['letter_template_id'],'branch_id'=>$datas_ins['branch_id'],'company_id'=>$datas_ins['company_id']]);
                								if(!empty($select)){
                									$this->Allmodel->update('lt_employee_temporary_termination',['complate_status'=>$datas_ins['complate_status']],$datas_ins);
                								}else{
                									$this->Allmodel->insert('lt_employee_temporary_termination',$datas_ins);
                								}
                							}
                							
                						}
                                    }
                                 }
                            }
        	            }
        	            
    	            }
    	            */
    	        }
	        }
	    }
	    
	    
	 
	}
	/**********************************End Get employee list for Warning Letter list***************************************/
	

	
	
	public function schedule()
	{
	    $all_employee_for_send_letters = $this->Allmodel->join_result("SELECT * FROM `lt_employee_temporary_table` WHERE complate_status!=0");
	  
	    foreach($all_employee_for_send_letters as $row){
	        
	       if($row->letter_id ==1){     //check according to lt_letter_master table id
	            $this->sendAppointmentLetter($row);        // send appointment Letter
	       }
	       
	       if($row->letter_id ==7){      //check according to lt_letter_master table id
	            $this->sendWarningLetter($row);            // send warning Letter
	       }
	       
	       if($row->letter_id ==8){     //check according to lt_letter_master table id
	            $this->sendShowCauseLetter($row);          // send Show cause Letter
	       }
	       
	       if($row->letter_id ==9){    //check according to lt_letter_master table id
	            $this->sendTerminationLetter($row);        // send Termination Letter
	       }
	       
	       if($row->letter_id ==10){    //check according to lt_letter_master table id
	            $this->sendManuallyWarningLetter($row);    // send Mannually warning Letter
	       }
		   
	    //    if($row->letter_id ==11){    //check according to lt_letter_master table id
	    //         $this->sendWarningOtherLetter($row);    // send warning Letter and other letter
	    //    }
	    }
	   
	}
	
	
	
	public function sendAppointmentLetter($row){
	    $count=$this->Allmodel->join_row("SELECT id,employee_id FROM `lt_track_letters` WHERE employee_id=$row->employee_id");
		if(!empty($count)){
			echo "Already send employee list their employee id : ".$count->employee_id;
		}else{
		    $guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
		    $letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));
		      
	    	$link=base_url('chkltr');
			$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'),
Your Appointment Letter has been generated, kindly click on link to view %26 accept.
'.$link.'
Provide your Acceptance for Salary Processing. -Tenon');
	        // echo "https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg";
	        //$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
		     
			$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
            $new_letter_id = @$last_letter_id[0]->id + 1;
            $letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/Apt/(5 digit of running Serial No)		
           
			$data['employee_id']=@$row->employee_id;
			$data['letter_serial_number']=$letter_serial_number;
			$data['letter_issue_date']=date('Y-m-d');
			$data['letter_id']=@$row->letter_id;
			$data['letter_template_id']=@$row->letter_template_id;
			$data['message']=@$link;
			$data['letter_status']=1;
			$data['branch_id']=@$row->branch_id;
			$data['company_id']=@$row->company_id;
			$data['approved_by']='NA';
			$data['sms_report']=@$responce;
			$data['created_date']=date('Y-m-d H:i:s');
			 
			$insert=$this->Allmodel->insert('lt_track_letters',$data);
			$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
		}
	    
	} 
	
	public function sendWarningLetter($row){
	    
		    $guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
		    $letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));
		    
	    	$link=base_url('chkltr');

			$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'), 
You are not reporting to duty since {#var#}, 
A Warning Letter is generated and sent to you. 
'.$link.'
Join your Duty immediately.');

	        //$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
		     
			$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
            $new_letter_id = @$last_letter_id[0]->id + 1;
            $letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/War/(5 digit of running Serial No)		
           
			$data['employee_id']=@$row->employee_id;
			$data['letter_serial_number']=$letter_serial_number;
			$data['letter_issue_date']=date('Y-m-d');
			$data['letter_id']=@$row->letter_id;
			$data['letter_template_id']=@$row->letter_template_id;
			$data['message']=@$link;
			$data['letter_status']=1;
			$data['branch_id']=@$row->branch_id;
			$data['company_id']=@$row->company_id;
			$data['approved_by']='NA';
			$data['sms_report']=@$responce;
			$data['created_date']=date('Y-m-d H:i:s');
			 
			$insert=$this->Allmodel->insert('lt_track_letters',$data);
			$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
	
	} 
	public function sendShowCauseLetter($row){
	    $guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
		 $letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));     
	    	$link=base_url('chkltr');
			$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'), 
You are not reporting to duty since {#var#}, 
Previous Warning Letter was issued to you on {#var#}.
Last  Warning Letter is generated and sent to you. 
'.$link.'
Join your Duty immediately.');

	        //$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
		     
			$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
            $new_letter_id = @$last_letter_id[0]->id + 1;
            $letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/letter_master_letter_code/(5 digit of running Serial No)		
           
			$data['employee_id']=@$row->employee_id;
			$data['letter_serial_number']=$letter_serial_number;
			$data['letter_issue_date']=date('Y-m-d');
			$data['letter_id']=@$row->letter_id;
			$data['letter_template_id']=@$row->letter_template_id;
			$data['message']=@$link;
			$data['letter_status']=1;
			$data['branch_id']=@$row->branch_id;
			$data['company_id']=@$row->company_id;
			$data['approved_by']='NA';
			$data['sms_report']=@$responce;
			$data['created_date']=date('Y-m-d H:i:s');
			 
			$insert=$this->Allmodel->insert('lt_track_letters',$data);
			$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
	} 
	public function sendTerminationLetter($row){
		$guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
		$letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));     
		$link=base_url('chkltr');
		$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'), 
You are not reporting to duty since {#var#}, 
Previous Warning Letter was issued to you on {#var#}.
Last  Warning Letter is generated and sent to you. 
'.$link.'
Join your Duty immediately.');
		//$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
			
		$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
		$new_letter_id = @$last_letter_id[0]->id + 1;
		$letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/letter_master_letter_code/(5 digit of running Serial No)		
		
		$data['employee_id']=@$row->employee_id;
		$data['letter_serial_number']=$letter_serial_number;
		$data['letter_issue_date']=date('Y-m-d');
		$data['letter_id']=@$row->letter_id;
		$data['letter_template_id']=@$row->letter_template_id;
		$data['message']=@$link;
		$data['letter_status']=1;
		$data['branch_id']=@$row->branch_id;
		$data['company_id']=@$row->company_id;
		$data['approved_by']='NA';
		$data['sms_report']=@$responce;
		$data['created_date']=date('Y-m-d H:i:s');
		$insert=$this->Allmodel->insert('lt_track_letters',$data);
		$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
	} 
// 	public function sendWarningOtherLetter($row){
// 		$guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
// 		$letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));     
// 		$link=base_url('chkltr');
// 		$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'), 
// You are not reporting to duty since {#var#}, 
// Previous Warning Letter was issued to you on {#var#}.
// Last  Warning Letter is generated and sent to you. 
// '.$link.'
// Join your Duty immediately.');
// 		//$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
			
// 		$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
// 		$new_letter_id = @$last_letter_id[0]->id + 1;
// 		$letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/letter_master_letter_code/(5 digit of running Serial No)		
		
// 		$data['employee_id']=@$row->employee_id;
// 		$data['letter_serial_number']=$letter_serial_number;
// 		$data['letter_issue_date']=date('Y-m-d');
// 		$data['letter_id']=@$row->letter_id;
// 		$data['letter_template_id']=@$row->letter_template_id;
// 		$data['message']=@$link;
// 		$data['letter_status']=1;
// 		$data['branch_id']=@$row->branch_id;
// 		$data['company_id']=@$row->company_id;
// 		$data['approved_by']='NA';
// 		$data['sms_report']=@$responce;
// 		$data['created_date']=date('Y-m-d H:i:s');
// 		$insert=$this->Allmodel->insert('lt_track_letters',$data);
// 		$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
// 	} 
	public function sendManuallyWarningLetter($row){
	    $guard_details = $this->Allmodel->selectrow('guard_master',array('id'=>$row->employee_id));
		$letter_type = $this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_id));     
		$link=base_url('chkltr');
		$msg=urlencode('Dear '.$guard_details->name.' ('.$guard_details->code.'), 
You are not reporting to duty since {#var#}, 
Previous Warning Letter was issued to you on {#var#}.
Last  Warning Letter is generated and sent to you. 
'.$link.'
Join your Duty immediately.');
		//$responce=$this->get_content("https://api.msg91.com/api/v2/sendsms?route=4&mobiles=$guard_details->phone_number&country=91&DLT_TE_ID=1107164000721467699&authkey=336395A8h02x6jNd1g6084e3d8P1&sender=PERGRN&message=$msg");   
			
		$last_letter_id =$this->Allmodel->join_result("SELECT MAX(ID) as id FROM lt_track_letters LIMIT 1");
		$new_letter_id = @$last_letter_id[0]->id + 1;
		$letter_serial_number =  date("Y").'/'.$row->branch_id.'/'.$letter_type->letter_code.'/'.$new_letter_id;                          // 2021/BranchID/letter_master_letter_code/(5 digit of running Serial No)		
		
		$data['employee_id']=@$row->employee_id;
		$data['letter_serial_number']=$letter_serial_number;
		$data['letter_issue_date']=date('Y-m-d');
		$data['letter_id']=@$row->letter_id;
		$data['letter_template_id']=@$row->letter_template_id;
		$data['message']=@$link;
		$data['letter_status']=1;
		$data['branch_id']=@$row->branch_id;
		$data['company_id']=@$row->company_id;
		$data['approved_by']='NA';
		$data['sms_report']=@$responce;
		$data['created_date']=date('Y-m-d H:i:s');
		$insert=$this->Allmodel->insert('lt_track_letters',$data);
		$this->Allmodel->delete('lt_employee_temporary_table',array('id'=>$row->id));
	} 
	
	
	

	
	function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
  	}
  	
  	
  	/*********************Not in used************************************/
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
	public function my_url($encrypt)
	{
		if($_SERVER['HTTP_HOST']=='localhost')
			return 'https://sspl20.com/tenon/tenon_letter/apointment-letter/'.$encrypt;
		else
			return base_url('chkltr/'.$encrypt.'');
	}
	/*********************Not in used************************************/
}