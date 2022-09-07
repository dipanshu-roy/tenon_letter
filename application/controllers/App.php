<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
	
	public $header = 'header';
	public $footer = 'footer';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Allmodel');
	}
	public function page($mpage,$data='')
	{
		$this->load->view($this->header,$data);
		$this->load->view($mpage,$data);
		$this->load->view($this->footer,$data);
	}
	public function index()
	{
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
		if($this->form_validation->run()==FALSE){
			if(!empty($this->session->userdata('s_admin'))){
				redirect('admin-dashboard');
			}elseif(!empty($this->session->userdata('system_user'))){
				redirect('admin-dashboard');
			}else{
				$this->load->view('login');
			}
		}else{
			$cheak['email']=$this->input->post('email');
			$cheak['password']=$this->Allmodel->encrypt($this->input->post('password'),KEY);
			$query=$this->Allmodel->auth($cheak);
			$query1=$this->Allmodel->auth_system(array('email'=>$this->input->post('email'),'password'=>md5($this->input->post('password'))));
			$qu=$this->Allmodel->auth_system($cheak);
			
			if(!empty($query)){
				if($query->is_admin==1){
					if($query->status==1){
						$this->session->set_userdata('s_admin',$query);
						$this->session->set_flashdata('success','Login Successfully');
						redirect('admin-dashboard');
					}else{
						$this->session->set_flashdata('error','user Blocked');
						redirect();
					}
				}else{
					$this->session->set_flashdata('error','Yoe Have Been Blocked From Admin');
					redirect();
				}
			}elseif(!empty($query1)){
				if($query1->status==1){
					$this->session->set_userdata('system_user',$query1);
					$this->session->set_flashdata('success','Login Successfully');
					redirect('admin-dashboard');
				}else{
					$this->session->set_flashdata('error','Youser Blocked');
					redirect();
				}
			}elseif(!empty($qu)){
			   
    				if($qu->status==1){
					$this->session->set_userdata('system_user',$qu);
					$this->session->set_flashdata('success','Login Successfully');
					redirect('admin-dashboard');
				}else{
					$this->session->set_flashdata('error','Youser Blocked');
					redirect();
				}
				 print_r($qu);die;
			}else{
				$this->session->set_flashdata('error','Sorry ! Email Or Password not Matched');
				redirect();
			}
		}
	}
	public function change_password()
	{
		$emails=$this->Allmodel->decrypt($this->uri->segment(2),KEY);
		if(!empty($emails)){
			$data_page['emails']=$emails;
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
			if($this->form_validation->run()==FALSE){
				$this->load->view('change_password',$data_page);
			}else{
				$password=$this->Allmodel->encrypt($this->input->post('password'),KEY);
				$query=$this->Allmodel->update('tbl_admin',array('password'=>$password),array('email'=>$this->input->post('email')));
				$query1=$this->Allmodel->update('lt_system_users',array('password'=>md5($this->input->post('password'))),array('email'=>$this->input->post('email')));
				if(!empty($query)){
					$this->session->set_flashdata('success','Password updated');
					redirect();
				}elseif(!empty($query1)){
					$this->session->set_flashdata('success','Password updated');
					redirect();
				}else{
					$this->session->set_flashdata('error','Sorry ! Password not updated');
					redirect();
				}
			}
		}else{
			$this->session->set_flashdata('error','No Email Fornd');
			redirect();
		}
	}
	public function recovery_password()
	{
		$this->load->library('email');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		if($this->form_validation->run()==FALSE){
			if(!empty($this->session->userdata('s_admin'))){
				redirect('admin-dashboard');
			}elseif(!empty($this->session->userdata('system_user'))){
				redirect('admin-dashboard');
			}else{
				$this->load->view('recovery_password');
			}
		}else{
			$query=$this->Allmodel->auth(array('email'=>$this->input->post('email')));
			$query1=$this->Allmodel->auth_system(array('email'=>$this->input->post('email')));
			$config['protocol']    	= 'smtp';
			$config['smtp_host']    = 'ssl://smtp.gmail.com';
			$config['smtp_port']    = '465';
			$config['smtp_timeout'] = '7';
			$config['smtp_user']    = 'tenonconnect@peregrine-security.com';
			$config['smtp_pass']    = 'Welcome@123';
			$config['charset']    	= 'utf-8';
			$config['newline']    	= "\r\n";
			$config['mailtype'] 	= 'html'; // or html
			$config['validation'] 	= TRUE; // bool whether to validate email or not      
			$config['wordwrap'] 	= TRUE; // bool whether to validate email or not      
			if(!empty($query)){
				$this->email->initialize($config);
				$this->email->from('tenonconnect@peregrine-security.com', APP_NAME);
				$this->email->to($query->email); 
				$this->email->subject(''.APP_NAME.' Reset Password');
				$datas=$this->msgs($query->email);
				$this->email->message($datas);  
				$this->email->send();
				$this->session->set_flashdata('success','Recovery Mail Sent');
				redirect('recovery-password');
			}elseif(!empty($query1)){
				$this->email->initialize($config);
				$this->email->from('tenonconnect@peregrine-security.com', APP_NAME);
				$this->email->to($query1->email); 
				$this->email->subject(''.APP_NAME.' Reset Password');
				$datas=$this->msgs($query1->email);
				$this->email->message($datas);  
				$this->email->send();
				$this->session->set_flashdata('success','Recovery Mail Sent');
				redirect('recovery-password');
			}else{
				$this->session->set_flashdata('error','Sorry ! Email does not exist');
				redirect('recovery-password');
			}
		}
	}
	public function msgs($email)
	{
		$myemail=$this->Allmodel->encrypt($email,KEY);
		return  '<!doctype html>
		<html lang="en-US">
		<head>
			<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
			<title>'.APP_NAME.' Reset Password</title>
			<meta name="description" content="'.APP_NAME.' Reset Password">
			<style type="text/css">a:hover {text-decoration: underline !important;}</style>
		</head>
		<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
			<!--100% body table-->
			<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: "Open Sans", sans-serif;">
				<tr>
					<td>
						<table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
							align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td style="height:80px;">&nbsp;</td>
							</tr>
							<tr>
								<td>
									<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
										style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
										<tr>
											<td style="height:40px;">&nbsp;</td>
										</tr>
										<tr>
											<td style="padding:0 35px;">
												<h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:"Rubik",sans-serif;">You have requested to reset your password</h1>
												<span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
												<p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
													A unique link to reset your
													password has been generated for you. To reset your password, click the
													following link and follow the instructions.
												</p>
												<a href="'.base_url('change-password/'.$myemail.'').'" style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset Password</a>
											</td>
										</tr>
										<tr>
											<td style="height:40px;">&nbsp;</td>
										</tr>
									</table>
								</td>
							<tr>
								<td style="height:20px;">&nbsp;</td>
							</tr>
							<tr>
								<td style="text-align:center;">
									<p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>'.APP_NAME.'</strong></p>
								</td>
							</tr>
							<tr>
								<td style="height:80px;">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</body>
		</html>';
	}
	public function index1()
	{
		$data_page=array();
		$this->page('login',$data_page);
	}
	/*********************************/
	/*
			Logout Functions
	
	/*********************************/
	public function logout()
	{
		$this->session->sess_destroy();
		$this->session->set_flashdata('success','Logout Successfully');
		redirect();
	}
}
