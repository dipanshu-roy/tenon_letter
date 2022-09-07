<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RefineNotification extends CI_Controller {
    
    public function __construct(){
		parent::__construct();
		$this->load->model('Allmodel');
		date_default_timezone_set('Asia/Kolkata');
	}
	public function push_notification(){
        $refine = json_decode($this->refine());
        if($refine->status == 200){
            $postData  = json_decode(file_get_contents('php://input'), true);
            if(!empty($postData)){
                if (DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s", strtotime($postData['triggerAt']))) !== FALSE) {
                    $datas['unique_id']=$postData['uniqueId'];
                    $datas['trigger_at']=date('Y-m-d H:i:s',strtotime('+5 hour +30 minutes',strtotime($postData['triggerAt'])));
                    $datas['created_at']=date('Y-m-d H:i:s');
                    $notification = $this->Allmodel->selectrow('lt_refyne_notification',array('unique_id'=>$datas['unique_id']));
                    if(!empty($notification)){
                        if($notification->status==0){
                            $history = $this->Allmodel->selectrow('lt_sent_notification_history',array('msg_id'=>$datas['unique_id']));
                            if(!empty($history)){
                                $data=['status'=>404,'message'=>'Notification already processed'];
                            }else{
                                $trigger = $this->Allmodel->selectrow('lt_refyne_notification_trigger',array('unique_id'=>$datas['unique_id']));
                                if(!empty($trigger)){
                                    $data=['status'=>404,'message'=>'This template already triggered'];
                                }else{
                                    $this->Allmodel->insert('lt_refyne_notification_trigger',$datas);
                                    $data=['status'=>201,'message'=>'Successfull'];
                                }
                            }
                        }else{
                            $data=['status'=>404,'message'=>'Template not approved'];
                        }
                    }else{
                        $data=['status'=>404,'message'=>'No template available'];
                    }
                }else{
                    $data=['status'=>404,'message'=>'Date time format incorrect'];
                }
                echo json_encode($data);
            }
        }else{
            echo $this->refine();
        }
    }
    public function push_notification_stats()
	{
        if(!empty($_GET['uniqueId'])){
            $uniqueId=$_GET['uniqueId'];
            $refine = json_decode($this->refine());
            if($refine->status == 200){
                if(!empty($_GET['to_date_time'])){
                    $query='';
                    $history = $this->Allmodel->join_row("SELECT b.trigger_at FROM `lt_sent_notification_history` as a INNER JOIN lt_refyne_notification_trigger as b on a.msg_id=b.unique_id WHERE a.msg_id='$uniqueId' ORDER BY a.id ASC LIMIT 1");
                    if(!empty($history)){
                        $from_date_time = date('Y-m-d H:i:s', strtotime("-1 minutes", strtotime($history->trigger_at)));
                        $to_date_time = date('Y-m-d H:i:s',strtotime('+5 hour +30 minutes',strtotime($_GET['to_date_time'])));
                        if($from_date_time < $to_date_time){
                            if(date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s'))) >= $to_date_time){
                                $query = "AND b.curent_date BETWEEN '$from_date_time' AND '$to_date_time'";
                                //echo $query;die;
                                $get_sent_count = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$uniqueId' AND msg_status='Success'");
                                if(!empty($get_sent_count)){
                                    $data['status']=true;
                                    $data['status_code']=200;
                                    $data['message']="Stats Fetched Successfully";
                                    $data['deliveryAcknowledgements']=$get_sent_count->id;
                                    $data['eventMetrics']=[
                                        'clicked'               =>$this->getCount($uniqueId),
                                        'withdraw_btn'          =>$this->eventMetrics('1',$uniqueId,$query),
                                        'attendance_enter'      =>$this->eventMetrics('2',$uniqueId,$query),
                                        'attendance_out'        =>$this->eventMetrics('3',$uniqueId,$query),
                                        'menu_employee_loan_btn'=>$this->eventMetrics('4',$uniqueId,$query),
                                    ];
                                }else{
                                    $data['status']=false;
                                    $data['status_code']=404;
                                    $data['message']="Stats Fetched failed";
                                    $data['deliveryAcknowledgements']=0;
                                    $data['eventMetrics']=[
                                        'clicked'               =>0,
                                        'withdraw_btn'          =>0,
                                        'attendance_enter'      =>0,
                                        'attendance_out'        =>0,
                                        'menu_employee_loan_btn'=>0,
                                    ];
                                }
                            }else{
                                $data=['status'=>404,'message'=>'Invalid date & time','curent_date_time'=>date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s'))),'to_date_time'=>$to_date_time];
                            }
                        }else{
                            $data=['status'=>404,'message'=>'Invalid date & time','from_date_time'=>$from_date_time,'to_date_time'=>$to_date_time];
                        }
                    }else{
                        $data=['status'=>404,'message'=>'No notification processed for this unique id'];
                    }
                }else{
                    $data=['status'=>404,'message'=>'to_date_time is missing'];
                }
                echo json_encode($data);
            }else{
                echo $this->refine();
            }
        }else{
            $data=['status'=>404,'message'=>'uniqueId is missing'];
            echo json_encode($data);
        }
    }
    public function getCount($uniqueId)
    {
        $get_sent_count = $this->Allmodel->join_row("SELECT COUNT(id) as id FROM `lt_sent_notification_history` WHERE is_view=1 AND msg_id='$uniqueId' AND msg_status='Success'");
        return $get_sent_count->id;
    }
    public function eventMetrics($action,$uniqueId,$query=null){
        //echo "SELECT COUNT(a.id) as id FROM `lt_sent_notification_history` as a INNER JOIN lt_refyne_stats as b on a.employe_id=b.employee_id WHERE a.is_view=1 AND b.action='$action' AND a.msg_id='$uniqueId' AND a.msg_status='Success' $query";die;
        $query = $this->Allmodel->join_row("SELECT COUNT(a.id) as id FROM `lt_sent_notification_history` as a INNER JOIN lt_refyne_stats as b on a.employe_id=b.employee_id WHERE a.is_view=1 AND b.action='$action' AND a.msg_id='$uniqueId' AND a.msg_status='Success' $query");
        return $query->id;
    }
    public function getDate($TemplateId,$type){
        $select = $this->Allmodel->join_row("SELECT b.sent_date FROM `lt_refyne_notification_trigger` as a INNER JOIN lt_sent_notification_history as b on a.id=b.msg_id WHERE template_id='$TemplateId' ORDER by b.sent_date $type limit 1");
        return $select->sent_date;
    }
    public function refine(){
        $headers = $this->input->request_headers();
        $request_headers=[
            'x-client-id'       =>md5('Refyne'),
            'x-client-secret'   =>md5('Refyne Secret')
        ];
      //  print_r($headers);die;
        if($request_headers['x-client-id']==@$headers['X-Client-Id'] && $request_headers['x-client-secret']==@$headers['X-Client-Secret']){
            $responce=['status'=>200,'msg'=>'success'];
        }else{
            $responce=['status'=>400,'msg'=>'x-client-id and x-client-secret not matched'];
        }
        return json_encode($responce);
    }
    public function save_acknowledgement(){
        $refine = json_decode(json_encode(['status'=>200,'msg'=>'success']));
        if($refine->status == 200){
            $postData  = json_decode(file_get_contents('php://input'), true);
            if(!empty($postData)){
                $this->Allmodel->insert('lt_refyne_stats',['employee_id'=>@$postData['employee_id'],'action'=>@$postData['action'],'curent_date'=>date('Y-m-d H:i:s')]);
                $data['status']=True;
                $data['status_code']=200;
                $data['data']=$postData;
                echo json_encode($data);
            }else{
                $data['status']=false;
                $data['status_code']=404;
                $data['data']=[];
            }
        }else{
            echo $this->refine();
        }
    }
// 	public function crone_url(){
// 	    $todate = date('Y-m-d H:i:s');
// 	    $lessdate = date('Y-m-d H:i:s', strtotime("+5 hour +30 minutes", strtotime($todate)));
// 	    $difference = getDate(strtotime($lessdate)); 
// 	    $year = $difference['year']; 
// 	    $month = $difference['mon']; 
// 	    $day = $difference['mday']; 
// 		$hours = $difference['hours'];
// 	    $minutes = $difference['minutes']; 
// 	    //echo "SELECT a.unique_id,a.template_id,a.msg_title,a.msg_desc,a.image,a.group_id FROM `lt_refyne_notification` as a INNER JOIN lt_refyne_notification_trigger as b on a.unique_id=b.unique_id WHERE b.trigger_at<='$lessdate' AND b.hit_status=0 AND a.status=0";die;
// 	    $results=$this->db->query("SELECT a.unique_id,a.template_id,a.msg_title,a.msg_desc,a.image,a.group_id FROM `lt_refyne_notification` as a INNER JOIN lt_refyne_notification_trigger as b on a.unique_id=b.unique_id WHERE b.trigger_at<='$lessdate' AND b.hit_status=0 AND a.status=0")->result();
// 	    //$results=$this->db->query("SELECT a.unique_id,a.template_id,a.msg_title,a.msg_desc,a.image,a.group_id FROM `lt_refyne_notification` as a INNER JOIN lt_refyne_notification_trigger as b on a.unique_id=b.unique_id WHERE MONTH(b.trigger_at)=$month AND YEAR(b.trigger_at)=$year AND DAY(b.trigger_at)=$day AND HOUR(b.trigger_at)<=$hours AND MINUTE(b.trigger_at) <= $minutes AND b.hit_status=0 AND a.status=0")->result();
// 	    if(!empty($results)){
// 	        foreach($results as $row){
// 	            $this->refineNotificationShoot($row);
// 	        }
// 	    }
// 	}
	public function crone_url(){
	    $results=$this->db->query("SELECT a.unique_id,a.tenon_connect,a.company_id,a.template_id,a.msg_title,a.msg_desc,a.image,a.group_id FROM `lt_refyne_notification` as a INNER JOIN lt_refyne_notification_trigger as b on a.unique_id=b.unique_id WHERE b.hit_status=0 AND a.status=0")->result();
        //print_r($results);die;
	    if(!empty($results)){
	        foreach($results as $row){
                if($row->tenon_connect==1){
                    $this->refineNotificationShootByCompany($row);
                }else{
	                $this->refineNotificationShoot($row);
                }
	        }
	    }
	}
	public function refineNotificationShoot($row){
		if(!empty($row->group_id)){
			$variable=explode(",", $row->group_id);
			$branch=implode("','", $variable);
			$results=$this->db->query("SELECT id,gcm_id FROM `guard_master` WHERE `branch` in ('$branch') AND gcm_id!='' AND advance_salary_applicable ='Y'")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= $row->image;
				}
				$this->Allmodel->update('lt_refyne_notification_trigger',['hit_status'=>1],['unique_id'=>$row->unique_id]);
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if(@$result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'failure';
						}
						$insert['msg_id']=$row->unique_id;
						$insert['employe_id']=$staf->id;
						$insert['msg_status']=@$msg_status;
						$insert['sent_log']=@$responce;
						$insert['sent_date']=date('Y-m-d H:i:s',strtotime('+5 hour +30 minutes',strtotime(date('Y-m-d H:i:s'))));
						$this->Allmodel->insert('lt_sent_notification_history',$insert);
					}
				}
			}
		}
	}
	public function refineNotificationShootByCompany($row){
		if(!empty($row->company_id)){
            if($row->company_id==1){
                $company_name='Tenon';
            }else{
                $company_name='Peregrine';
            }
			$results=$this->db->query("SELECT id,gcm_id FROM `guard_master` WHERE company='$company_name' AND gcm_id!='' AND advance_salary_applicable ='Y';")->result();
			if(!empty($results)){
				$data['title']		=$row->msg_title;
				$data['body']		=$row->msg_desc;
				if(!empty($row->image)){
					$data['image']	= $row->image;
				}
				$this->Allmodel->update('lt_refyne_notification_trigger',['hit_status'=>1],['unique_id'=>$row->unique_id]);
				foreach($results as $staf){
					if(!empty($staf->gcm_id)){
						$responce=$this->send_notification($data,$staf->gcm_id);
						$result = json_decode($responce,true);
						if(@$result['success'] > 0){
							$msg_status = 'Success';
						}else{
							$msg_status = 'failure';
						}
						$insert['msg_id']=$row->unique_id;
						$insert['employe_id']=$staf->id;
						$insert['msg_status']=@$msg_status;
						$insert['sent_log']=@$responce;
						$insert['sent_date']=date('Y-m-d H:i:s',strtotime('+5 hour +30 minutes',strtotime(date('Y-m-d H:i:s'))));
						$this->Allmodel->insert('lt_sent_notification_history',$insert);
					}
				}
			}
		}
	}
    public function TrignodevEmployeesNotification(){
		$required_fields = ['employee_code','title','description','type'];
        $this->validate($required_fields);
		if(!empty($_POST['employee_code'])){
			$code=$_POST['employee_code'];
            if($_POST['type']=='Guard'){
                $table = 'guard_master';
                $tenon_connect = 0;
            }elseif($_POST['type']=='Staff'){
                $tenon_connect = 1;
                $table = 'staff_master';
            }else{
                $dataresponce=['Api_status'=>404,'msg'=>'Use (type) as Guard or Staff'];
				header("Content-Type: application/json");
                echo json_encode($dataresponce);exit;
            }
			$results=$this->db->query("SELECT id,gcm_id FROM $table WHERE gcm_id!='' AND code='$code'")->row();
			if(!empty($results)){
				$data['title']		=$_POST['title'];
				$data['body']		=$_POST['description'];
                $insertdata=[
					'notication_type'	=>4,
					'branch_id'			=>0,
					'tenon_connect'		=>$tenon_connect,
					'msg_title'			=>$_POST['title'],
					'msg_desc'			=>$_POST['description'],
					'company_id'		=>'EMPCODE '.$results->id,
					'user_id'			=>$results->id,
					'image'				=>null,
					'created_at'		=>@date('Y-m-d h:i:s'),
					'shoot_date'		=>@date('Y-m-d h:i:s')
				];
				$ins_id=$this->Allmodel->insert('lt_sent_notification',$insertdata);
                $not_id="TRIGNO".$ins_id;
				$responce=$this->send_notification($data,$results->gcm_id);
				$result = json_decode($responce,true);
				if($result['success'] > 0){
					$msg_status = 'Success';
				}else{
					$msg_status = 'Failed';
				}
                $updatedata=[
                    'msg_id'	        =>@$not_id,
					'totat_sent'		=>@$result['success'],
					'totat_failure'		=>@$result['failure'],
				];
				$this->Allmodel->update('lt_sent_notification',$updatedata,['id'=>$ins_id]);
				$senddata = $this->Allmodel->insert('lt_sent_notification_history',['msg_id'=>$not_id,'employe_id'=>$results->id,'msg_status'=>$msg_status,'sent_log'=>$responce]);
				$dataresponce=['Api_status'=>200,'notificatin_id'=>$senddata,'msg'=> 'Notificatin Sent '.$msg_status,'responce'=>$responce];
				header("Content-Type: application/json");
                echo json_encode($dataresponce);exit;
			}
		}
	}
	public function validate($required_fields){
        foreach ($required_fields as $key => $value) {
            if (empty($_POST[$value])) {
                $nextcode = 1;
                $dataresponce=['Api_status'=>404,'msg'=> $value.' (POST) is missing'];
				header("Content-Type: application/json");
                echo json_encode($dataresponce);exit;
            }
        }
    }
    function send_notification($alldata,$fcm_id){
        $head=array(
            'Authorization: key=' .API_ACCESS_KEY,
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
    // public function delivery_acknowledgement(){
    //     $refine = json_decode($this->refine());
    //     if($refine->status == 200){
    //         $postData  = json_decode(file_get_contents('php://input'), true);
    //         if(!empty($postData)){
    //             $notificationTemplateId = $postData['notificationTemplateId'];
    //             $select = $this->Allmodel->join_result("SELECT b.employe_id,b.msg_status,b.sent_date FROM `lt_refyne_notification_trigger` as a INNER JOIN lt_sent_notification_history as b on a.id=b.msg_id WHERE template_id='$notificationTemplateId'");
    //             if(!empty($select)){
    //                 foreach($select as $row){
    //                     $responce['employeeId']=$row->employe_id;
    //                     $responce['acknowledged']=($row->msg_status=='Success')? 'TRUE':'FALSE';
    //                     $responce['acknowledgedAt']=date_format(date_create($row->sent_date),"Y-m-d G:i:s");
    //                     $datas[]=$responce;
    //                 }
    //                 $data=['notificationTemplateId'=>$notificationTemplateId,'deliveryReports'=>$datas];
    //             }else{
    //                 $data=['notificationTemplateId'=>$notificationTemplateId,'deliveryReports'=>[]];
    //             }
    //             echo json_encode($data);
    //         }
    //     }else{
    //         echo $this->refine();
    //     }
    // }
}