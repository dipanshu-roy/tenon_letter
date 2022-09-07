<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {
    public function __construct()
	{
		parent::__construct();
		$this->load->model('Allmodel');
	}
    public function letters(){
	    $employee_id_encript=$this->uri->segment(2);
		$employee_id=$this->Allmodel->decrypt($employee_id_encript,KEY);
		$employee_id=$this->Allmodel->encrypt($employee_id_encript,KEY);
		print_r($employee_id);die;
	    $employee_details =$this->Allmodel->selectrow('guard_master',array('id'=>$employee_id));
		if(!empty($this->uri->segment(3)) && !empty($this->uri->segment(4))){
			$data['employee_detailsss']=$this->Allmodel->selectrow('lt_track_letters',array('employee_id'=>$employee_id,'letter_id'=>$this->uri->segment(3),'letter_template_id'=>$this->uri->segment(4)));
		}else{
			$data['employee_detailsss']=$this->Allmodel->selectrow('lt_track_letters',array('employee_id'=>$employee_id));
		}
	    $letter_details =$this->Allmodel->selectrow('lt_letter_templates',array('id'=>$data['employee_detailsss']->letter_template_id));
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
	                $que = str_replace('$letter_id', 1 , $ques);
	                $var_value = $this->Allmodel->veriable_query($que);
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

		$this->load->view('appointment-letter',$data);
    }
	public function getContent($details,$template){
	    
		//print_r($details);die;
        foreach ($details as $key => $value) {
            if($value !='' && $value !='space' && $key !='officer_signature' && $key !='employee_joining_date'){
                $template = str_replace('{{' . $key . '}}', '<b>'.$value.'</b>', $template);
            }elseif($value =='space'){
                  $template = str_replace('{{' . $key . '}}', '<b>...............................................</b>', $template);
            }elseif($key =='officer_signature'){
                  $template = str_replace('{{' . $key . '}}', '<img src="'.base_url('assets/media/signature_image/'.$value).'" style="width:234px;height:80px;">', $template);
            }elseif($key =='employee_joining_date'){
                    $date=date_create($value);
                    $doj = date_format($date,"d-M-Y");
                  $template = str_replace('{{' . $key . '}}', '<b>' . $doj . '</b>', $template);

            }else{
                $template = str_replace('{{' . $key . '}}', '<b>...............................................</b>', $template);
            }
        }
        return $template;
    }




	/**********************Start Create short link function ***************************************/
	public function chekLetter(){
		$this->load->view('chkltr');
	}
	/**********************Start Create short link function ***************************************/


    
    
 
}