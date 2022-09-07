<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allmodel extends CI_Model
{
    protected $l_tbl='tbl_admin';
    protected $system='lt_system_users';
	public function auth($val='')
	{
		$this->db->select('*');
		$this->db->from($this->l_tbl);
		if ($val)
			$this->db->where($val);
		$q = $this->db->get();
		return $q->row();
	}
	public function auth_system($val='')
	{
		$this->db->select('*');
		$this->db->from($this->system);
		if ($val)
			$this->db->where($val);
		$q = $this->db->get();
		return $q->row();
	}
	public function selectrow($tbl,$val='',$val1='',$val2='',$val3='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if ($val)
			$this->db->where($val);
			if ($val1)
				$this->db->where($val1);
				if ($val2)
					$this->db->where($val2);
					if ($val3)
						$this->db->where($val3);
		$q = $this->db->get();
		return $q->row();
	}
	public function countid($tbl,$val='')
	{	
		$this->db->select('count(id) as id');
		$this->db->from($tbl);
		if($val)
			$this->db->where($val);
		$q=$this->db->get();
		return $q->row();
	}
	public function sumamount($tbl,$val='')
	{	
		$this->db->select('sum(amount) as amount');
		$this->db->from($tbl);
		if($val)
			$this->db->where($val);
		$q=$this->db->get();
		return $q->row();
	}
	public function maxid($tbl,$val='')
	{	
		$this->db->select('max(id) as id');
		$this->db->from($tbl);
		if($val)
			$this->db->where($val);
		$q=$this->db->get();
		return $q->row();
	}
	public function getresult($tbl,$val='',$val1='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if($val){
			$this->db->where($val);
		}
		if($val1){
			$this->db->where($val1);
		}
		$this->db->order_by("id", "DESC");
		$q=$this->db->get();
		return $q->result();
	}
	public function getresultOrderByAsc($tbl,$val='',$val1='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if($val){
			$this->db->where($val);
		}
		if($val1){
			$this->db->where($val1);
		}
		$this->db->order_by("id", "ASC");
		$q=$this->db->get();
		return $q->result();
	}
	public function getresultOrderByNameAsc($tbl,$val='',$val1='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if($val){
			$this->db->where($val);
		}
		if($val1){
			$this->db->where($val1);
		}
		$this->db->order_by("name", "ASC");
		$q=$this->db->get();
		return $q->result();
	}
	public function getresult_asc($tbl,$val='',$val1='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if($val){
			$this->db->where($val);
		}
		if($val1){
			$this->db->where($val1);
		}
		$q=$this->db->get();
		return $q->result();
	}
	public function result_group_by($tbl,$val='',$val1='')
	{
		$this->db->select('*');
		$this->db->from($tbl);
		if($val){
			$this->db->where($val);
		}
		if($val1){
			$this->db->where($val1);
		}
		$this->db->group_by('category', 'DESC');
		$q=$this->db->get();
		return $q->result();
	}
	public function selectrowsByIn($tbl,$data){
		return $this->db->query("SELECT `ouinstname` FROM $tbl WHERE `id` IN ($data)")->result();	
	}
	
	public function insert($tbl,$data)
	{
		$this->db->insert($tbl,$data);
		return $this->db->insert_id();
	}
	public function update($tbl,$data,$con='')
	{
		$this->db->where($con);
		$this->db->update($tbl,$data);
		return $this->db->affected_rows();
	}
	public function delete($tbl,$con='')
	{
		$this->db->where($con);
		$this->db->delete($tbl);
		return $this->db->affected_rows();
	}
	public function selectjoin_one($tbl,$tbl1,$on,$on1,$whr='',$whr1='')
	{
		$this->db->select('*');    
		$this->db->from($tbl);
		$this->db->join($tbl1,"$tbl.$on = $tbl1.$on1");
		if ($whr)
			$this->db->where($whr);
			if ($whr1)
				$this->db->where($whr1);
		$query = $this->db->get();
		return $query->result();
	}
	public function selectjoin_two($tbl,$tbl1,$tbl2,$on,$on1,$on2,$whr='',$whr1='')
	{
		$this->db->select('*');    
		$this->db->from($tbl);
		$this->db->join($tbl1,"$tbl.$on = $tbl1.$on1");
		$this->db->join($tbl2,"$tbl1.$on = $tbl2.$on2");
		if ($whr)
			$this->db->where($whr);
			if ($whr1)
				$this->db->where($whr1);
		$query = $this->db->get();
		return $query->result();
	}
	public function join_result($data)
	{
		 return $this->db->query($data)->result();
	}
	
	public function join_row($data)
	{
		 return $this->db->query($data)->row();
	}
	public function encrypt($plainText,$key)
	{
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
	}
	public function decrypt($encryptedText,$key)
	{
	   
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText = $this->hextobin($encryptedText);
		$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}
	public function hextobin($hexString) 
   	{ 
        $length = strlen($hexString); 
        $binString="";   
        $count=0; 
        while($count<$length) {       
        	$subString =substr($hexString,$count,2);           
        	$packedString = pack("H*",$subString); 
        	if ($count==0){
				$binString=$packedString;
		    }else{
				$binString.=$packedString;
		    }    
		    $count+=2; 
        } 
  	    return $binString; 
	}
	public function mailsend($data)
	{
		$usmtp 		= true;			
		$from 		= 'internkro1@gmail.com';
		$toname		= 'Internkro Teams';	
		$fromname	= 'Internkro';	
		include_once ('assets/phpMailer/class.phpmailer.php');		
		try {
			$mail = new PHPMailer(true);
			if($usmtp){
				$mail->IsSMTP(); 
				$mail->SMTPAuth   = true; 
				$mail->Host       = "smtp.gmail.com";
				$mail->Username   = "internkro1@gmail.com";
				$mail->Password   = "fptfeqouartobwes";
				$mail->Port       =  465;
				$mail->SMTPSecure = "ssl";
			}
			$mail->SMTPKeepAlive = true;
			$mail->AddReplyTo($from, $fromname);
			$mail->SetFrom($from, $fromname);
			$mail->Subject = $data['subject'];
			$mail->AltBody = '';
			$mail->MsgHTML($data['msg']);
			$mail->AddAddress($data['email'], $toname);
			if(!$mail->Send())
				return array(FALSE, $mail->ErrorInfo);
			else
		  		return true;
		 	$mail->ClearAddresses();
		}catch (phpmailerException $e) {
			echo $e->errorMessage();
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	

	public function veriable_query($data)
	{
		return $this->db->query($data)->row();
// 		 print_r($this->db->last_query());die;
		
	}

	public function truncate_table($table_name){
	    return $this->db->truncate($table_name);
	}
}
?>