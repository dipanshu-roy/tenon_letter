<?php 
class Warning_Attendance extends CI_Controller {
    public function __construct(){
		parent::__construct();
		$this->load->model('Allmodel');
	}
	public function send_first()
	{
		$selsect=$this->Allmodel->join_result("SELECT a.user_id,COUNT(a.user_id) as total,b.company,c.id,d.first_warning_days,d.second_warning_days,d.termination_days,d.first_warning_letter_id,d.second_warning_letter_id,d.termination_letter_id FROM `attendance_history` as a INNER JOIN guard_master as b on a.user_id=b.id INNER JOIN lt_tbl_company as c on c.company_name=b.company INNER JOIN lt_letter_issuing_days as d on c.id=d.company_id where `date` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 1 MONTH ) AND DATE_SUB( CURDATE( ) ,INTERVAL 0 MONTH ) GROUP BY user_id ASC");
		if(!empty($selsect)){
			foreach($selsect as $row){
				$first = $row->first_warning_days;
				$second = $row->second_warning_days+$first;
                $third = $row->termination_days+$second;

				$encrypt=$this->Allmodel->encrypt($row->id,KEY);
                $URL=$this->my_url($encrypt);

				$users=$this->Allmodel->join_row("SELECT *,DAY(cdate) as send_date FROM `lt_attendance` WHERE MONTH(cdate) = MONTH(CURRENT_DATE())
                AND YEAR(cdate) = YEAR(CURRENT_DATE()) AND users_id=$row->user_id");
				

				$letter=$this->Allmodel->join_row("SELECT * FROM `lt_letter_master` where id=$row->first_warning_letter_id");

				if(!empty($users)){

					$send_date=$users->send_date+$first;
					$send_date2=$users->send_date+$second;
					$send_date3=$users->send_date+$third;


					echo $send_date2;
					echo $users->cdate;
					$date1=date_create($users->cdate);
					$date2=date_create(date('Y-m-d'));
					$diff=date_diff($date1,$date2);

					echo $diff;

					if($send_date <= date('d')+10){
						$responce="{'msg'=>'Sent'}";
						$this->Allmodel->update("lt_attendance",array('first'=>1),array('users_id'=>$row->user_id));
						if($users->first>0){
							if($row->total < $first){
								$responce="{'msg'=>'Sent'}";
								$this->Allmodel->update("lt_attendance",array('sms_report'=>$responce),array('users_id'=>$row->user_id));
							}
						}	
					}
					if($send_date2 <= date('d')){
						$responce="{'msg'=>'Sent'}";
						$this->Allmodel->update("lt_attendance",array('second'=>1),array('users_id'=>$row->user_id));
						if($users->first>0){
							if($row->total < $second && $row->total > $first){
								$responce="{'msg'=>'Sent'}";
								$this->Allmodel->update("lt_attendance",array('sms_report'=>$responce),array('users_id'=>$row->user_id));
							}
						}	
					}
					if($send_date3 <= date('d')){
						$responce="{'msg'=>'Sent'}";
						$this->Allmodel->update("lt_attendance",array('third'=>1),array('users_id'=>$row->user_id));
						if($users->first>0){
							if($row->total < $third && $row->total > $second && $row->total > $first){
								$responce="{'msg'=>'Sent'}";
								$this->Allmodel->update("lt_attendance",array('sms_report'=>$responce),array('users_id'=>$row->user_id));
							}
						}	
					}
				}else{
					//$responce=$this->msg($URL,$row->name,$row->phone_number,$letter->letter_name);
					$responce="{'msg'=>'Sent'}";
					$insert=$this->Allmodel->insert("lt_attendance",array('users_id'=>$row->user_id,'sms_report'=>$responce));
				}				
			}
		}
	}
    public function schedule()
	{
        //$selsect=$this->Allmodel->join_result("SELECT a.user_id,COUNT(a.user_id) as total,b.company,c.id FROM `attendance_history` as a INNER JOIN guard_master as b on a.user_id=b.id INNER JOIN lt_tbl_company as c on c.company_name=b.company where `date` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 1 MONTH ) AND DATE_SUB( CURDATE( ) ,INTERVAL 0 MONTH ) GROUP BY user_id ASC");

        $selsect=$this->Allmodel->join_result("SELECT a.user_id,COUNT(a.user_id)+26 as total,b.company,c.id,d.first_warning_days,d.second_warning_days,d.termination_days,d.first_warning_letter_id,d.second_warning_letter_id,d.termination_letter_id FROM `attendance_history` as a INNER JOIN guard_master as b on a.user_id=b.id INNER JOIN lt_tbl_company as c on c.company_name=b.company INNER JOIN lt_letter_issuing_days as d on c.id=d.company_id where `date` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 1 MONTH ) AND DATE_SUB( CURDATE( ) ,INTERVAL 0 MONTH ) GROUP BY user_id ASC");
		if(!empty($selsect)){
			foreach($selsect as $row){
                $first = $row->first_warning_days;
                $second = $row->second_warning_days+$first;
                $third = $row->termination_days+$second;
                $encrypt=$this->Allmodel->encrypt($row->id,KEY);
                $URL=$this->my_url($encrypt);

                $users=$this->Allmodel->join_row("SELECT * FROM `lt_attendance` WHERE MONTH(cdate) = MONTH(CURRENT_DATE())
                AND YEAR(cdate) = YEAR(CURRENT_DATE()) AND users_id=$row->user_id");
                $letter=$this->Allmodel->join_row("SELECT * FROM `lt_letter_master` where id=$row->first_warning_letter_id");
                //print_r($letter);die;
                if(!empty($users)){
                    if($users->first==0){
                        if($row->total < $first){
                            $responce=$this->msg($URL,$row->name,$row->phone_number,$letter->letter_name);
                            $this->Allmodel->update("lt_attendance",array('first'=>1,'sms_report'=>$responce),array('users_id'=>$row->user_id));
                        }
                    }elseif($users->second==0){  
                        if($row->total < $second && $row->total>$first){
                            $responce=$this->msg($URL,$row->name,$row->phone_number,$letter->letter_name);
                            $this->Allmodel->update("lt_attendance",array('second'=>1,'sms_report'=>$responce),array('users_id'=>$row->user_id));
                        }
                    }elseif($users->third==0){  
                        if($row->total < $third && $row->total > $second && $row->total > $first){
                            $responce=$this->msg($URL,$row->name,$row->phone_number,$letter->letter_name);
                            $this->Allmodel->update("lt_attendance",array('third'=>1,'sms_report'=>$responce),array('users_id'=>$row->user_id));
                        }
                    }
                }else{
					$responce=$this->msg($URL,$row->name,$row->phone_number,$letter->letter_name);
                    $insert=$this->Allmodel->insert("lt_attendance",array('users_id'=>$row->user_id,'sms_report'=>$responce));
                }
            }
        }
    }
	public function msg($URL,$name,$phone_number,$letter_name)
	{
		$link=$this->createShortLink($URL);
		$msg=urlencode('Dear '.$name.', Your '.$letter_name.', kindly click on link shared below '.$link);
		return $this->get_content("http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text=$msg&mobile=$phone_number");
	}
    public function get_content($URL){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
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
	public function my_url($encrypt)
	{
		if($_SERVER['HTTP_HOST']=='localhost')
			return 'https://sspl20.com/tenon_letter/apointment-letter/'.$encrypt;
		else
			return base_url('apointment-lette/'.$encrypt.'');
	}
	public function pdfgenerates()
	{
		$GLOBALS['imagess']='https://sspl20.com/tenon_letter/assets/media/image/logo.png';
		include('assets/fpdf/fpdf.php');
		include_once('assets/fpdf/tutorial/tuto2.php');
		
		$pdf = new PDF();
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Times','',12);
		for($i=1;$i<=40;$i++)
			$pdf->Cell(0,10,'Printing line number '.$i,0,1);
		$pdf->Output();
	}
}