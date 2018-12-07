<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author : Joyonto Roy
 *	date	: 4 August, 2014
 *	Ekattor School  Management System
 *	http://codecanyon.net/user/Creativeitem
 */

class Teacher extends MY_Controller
{
    
    
    function __construct()
    {
        parent::__construct();
        /*
		$this->load->database();
        $this->load->library('session');
     
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");*/
    }
    
    /***default functin, redirects to login page if no teacher logged in yet***/
    public function index()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('teacher_login') == 1)
            redirect(base_url() . 'index.php?teacher/dashboard', 'refresh');
    }
    
    /***TEACHER DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('teacher_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /*ENTRY OF A NEW STUDENT*/
    
    
    /****MANAGE STUDENTS CLASSWISE*****/
    function student_add()
	{
		if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
			
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_information($class_id = '')
	{
		if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information'). " - ".get_phrase('class')." : ".
											$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
	function student_marksheet($student_id = '') {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        $class_id     = $this->db->get_where('enroll' , array(
            'student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->row()->class_id;
        $student_name = $this->db->get_where('student' , array('student_id' => $student_id))->row()->name;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
        $page_data['page_name']  =   'student_marksheet';
        $page_data['page_title'] =   get_phrase('marksheet_for') . ' ' . $student_name . ' (' . get_phrase('class') . ' ' . $class_name . ')';
        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function student_marksheet_print_view($student_id , $exam_id) {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        $class_id     = $this->db->get_where('enroll' , array(
            'student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->row()->class_id;
        $class_name   = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

        $page_data['student_id'] =   $student_id;
        $page_data['class_id']   =   $class_id;
        $page_data['exam_id']    =   $exam_id;
        $this->load->view('backend/teacher/student_marksheet_print_view', $page_data);
    }
	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
            $data['password']       = sha1($this->input->post('password'));
            $data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
            $this->db->insert('student', $data);
            $student_id = $this->db->insert_id();

            $data2['student_id']     = $student_id;
            $data2['enroll_code']    = substr(md5(rand(0, 1000000)), 0, 7);
            $data2['class_id']       = $this->input->post('class_id');
            if ($this->input->post('section_id') != '') {
                $data2['section_id'] = $this->input->post('section_id');
            }
            
            $data2['roll']           = $this->input->post('roll');
            $data2['date_added']     = strtotime(date("Y-m-d H:i:s"));
            $data2['year']           = $running_year;
            $this->db->insert('enroll', $data2);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?teacher/student_add/', 'refresh');
        }
        if ($param2 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
            $data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
            
            $this->db->where('student_id', $param2);
            $this->db->update('student', $data);

            $data2['section_id']    =   $this->input->post('section_id');
            $data2['roll']          =   $this->input->post('roll');
            $running_year = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
            $this->db->where('student_id' , $param2);
            $this->db->where('year' , $running_year);
            $this->db->update('enroll' , array(
                'section_id' => $data2['section_id'] , 'roll' => $data2['roll']
            ));

            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param3 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?teacher/student_information/' . $param3, 'refresh');
        } 
		
        if ($param2 == 'delete') {
            $this->db->where('student_id', $param3);
            $this->db->delete('student');
            redirect(base_url() . 'index.php?teacher/student_information/' . $param1, 'refresh');
        }
    }

    function get_class_section($class_id)
    {
        $sections = $this->db->get_where('section' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }
    
    /****MANAGE TEACHERS*****/
    function teacher_list($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('teacher_list');
        $this->load->view('backend/index', $page_data);
    }

    /*Fee collection*/
    function feecollection($campus_id = '')
    {
        

            if ($this->session->userdata('teacher_login') != 1)
                redirect('login', 'refresh');

            $group_id = -1;
            $class_id = -1;
            if($campus_id == NULL){
                $campus_id = $this->input->post('campus');
                $group_id = $this->input->post('group');
                $class_id = $this->input->post('class');
            }

            $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
            //echo $running_year;

            
            if($group_id > 0 && $class_id > 0){
                $query = "SELECT e.*, st.name from enroll e
                            INNER JOIN student st ON st.student_id = e.student_id
                            INNER JOIN section s ON (e.section_id = s.section_id AND s.group_id = $group_id)
                            WHERE e.class_id = $class_id AND e.session_id = $running_year";
                $students = $this->db->query($query)->result_array();
            }
                $page_data['campus_id'] = $campus_id;
                $page_data['group_id'] = $group_id;
                $page_data['class_id']  = $class_id;
                $page_data['students'] = $students;

                $page_data['groupClass'] = $this->getGroupClass();


                $teacher_id = $this->session->userdata('teacher_id');
                if($campus_id != null){
                    $classInfo = "SELECT 
                                * 
                                FROM
                                CLASS cl
                                INNER JOIN courseteacherassignment ct ON cl.class_id = ct.class_id
                                WHERE ct.teacher_id = $teacher_id AND cl.campus_id = $campus_id
                                GROUP BY cl.class_id";
                    $page_data['classInfo'] = $this->db->query($classInfo)->result_array();
                }
                

                $page_data['page_name']     = 'fee_collection';
                $page_data['page_title']    = get_phrase('fee_collection'). " - ".get_phrase('class')." : ".$this->crud_model->get_class_name($class_id);
                
                $this->load->view('backend/index', $page_data);
                
        }
        
        function save_student_fee($param='')
        {
            if ($this->session->userdata('teacher_login') != 1)
                redirect('login', 'refresh');
                    
            if($param == 'create') {

                $trdata['description']      = 'Student Fee';



                $trdata['tdate']            = date('Y-m-d', strtotime($this->input->post('timestamp')));



                $trdata['uniqueCode']       = Applicationconst::TRANSACTION_TYPE_FEE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_FEE);

                $trdata['type']     = Applicationconst::TRANSACTION_TYPE_FEE;

                $this->db->insert('transaction', $trdata);
                $transaction_id = $this->db->insert_id();
            
                $student_id = $this->input->post('student_id');
                $student_code = $this->db->get_where('student', array('student_id' => $student_id))->row()->student_code;

                $items = $this->input->post('fee');

                foreach ($items as $i) {
                            $amt = 0.0;
                            $detaildata = array();
                            $detaildata['transactionId']        =  $transaction_id;
                            $detaildata['itemId']               =  $this->input->post('item_'.$i);
                            $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_RECEIVABLE;
                            $detaildata['userId']               =  $this->db->get_where('user', array('reference_id' => $student_id))->row()->user_id;
                            $detaildata['type']                 =  -1;
                            $detaildata['month']                =  date('n',strtotime($this->input->post('month_'.$i)));
                            $detaildata['year']                 =  $this->input->post('year_'.$i);
                            $detaildata['quantity']             =  1;
                            $detaildata['unitPrice']            =  $this->input->post('amount_'.$i);
                            
                            $amt +=  $this->input->post('amount_'.$i);
                    
                            $this->db->insert('transaction_detail' , $detaildata);
                
                if($amt > 0 ){
                    $detaildata = array();
                    $detaildata['transactionId']        =  $transaction_id;
                    $detaildata['itemId']               =  Applicationconst::ITEM_CASH;
                    $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
                    $detaildata['userId']               =  Applicationconst::USER_COMPANY;
                    $detaildata['type']                 =  1;
                    $detaildata['month']                =  date('n',strtotime($this->input->post('month_'.$i)));
                    $detaildata['year']                 =  $this->input->post('year_'.$i);
                    $detaildata['quantity']             =   1;
                    $detaildata['unitPrice']            =  $amt;
                    $this->db->insert('transaction_detail' , $detaildata);
                }
            }
            
            $feedata['student_id'] = $student_id;
            $feedata['transaction_id'] = $transaction_id;
            $feedata['session_id'] = $this->input->post('session_id');
            $feedata['receipt_no'] = $trdata['uniqueCode'] = Applicationconst::TRANSACTION_TYPE_FEE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_FEE);
            $this->db->insert('fee_record' , $feedata);


            $msms = $this->input->post('msms');
                    if($msms == 'on'){
            $message  = "Received Taka : ".$amt;
            $timestamp = strtotime($this->input->post('timestamp'));

            $time = date("Y-m-d", $timestamp);

            $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.fee.title'))->row()->value;

            $reciever = $this->crud_model->send_mobile_sms_fee($student_id);
            $this->sms_model->send_sms($message , $reciever, $time,  $msgTittle);
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
              }
        }

            $this->fee_invoice($student_code);
            //redirect(base_url() . 'index.php?admin/fee_invoice');
            
        }
        
        public function fee_invoice($param1)
        {
            $student_code = $param1;
            if($student_code == NULL){
                $student_code = $this->input->post('student_code');
            }
            //$student_code = -1;
            if($student_code != null) {
                //$student_code = $this->input->post('student_code');
                $this->db->select('student.name, student.student_code,student.student_id, fee_record.*, transaction.*');
                $this->db->from('student');
                $this->db->join('fee_record', 'fee_record.student_id = student.student_id');
                $this->db->join('transaction', 'fee_record.transaction_id = transaction.componentId');
                $this->db->where('student.student_code', $student_code);
                $this->db->order_by('transaction.tdate', 'DESC');
                $this->db->distinct();
                //$myQuery = $this->db->get_compiled_select();
                //echo $myQuery;
                $transaction_info = $this->db->get()->result_array();

            }
            $page_data['transaction_info'] = $transaction_info;
            $page_data['page_name'] = 'student_all_payments';
            $page_data['page_title'] = get_phrase('student_fee_record');
            $this->load->view('backend/index', $page_data);
        }

        public function getSequence($seqName){
        
            $currentValue = 1;
       
            $query = $this->db->query("CALL getsequence('".$seqName."');");
            $res = $query->result_array();
            mysqli_next_result( $this->db->conn_id );
             
            $currentValue = 0;
            foreach ($res as $row)
            {
                $currentValue = $row['currentValue'];
            }
             
            return $currentValue;
        }

    
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $data['year']       = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?teacher/subject/'.$data['class_id'], 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $data['year']       = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?teacher/subject/'.$data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('subject_id', $param2);
            $this->db->delete('subject');
            redirect(base_url() . 'index.php?teacher/subject/'.$param3, 'refresh');
        }
		 $page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array(
            'class_id' => $param1,
            'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE EXAMMARKS*****/
function examtype_marks()
    {
    
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
    
            $termId = -1;
            $groupId = -1;
            $classId = -1;
            $sectionId = -1;
            $courseId = -1;
            $examtypeId = -1;
            if($this->input->post('termId') !=null)
                $termId = $this->input->post('termId');
            if($this->input->post('groupId') !=null)
                    $groupId = $this->input->post('groupId');
                if($this->input->post('classId') !=null)
                    $classId = $this->input->post('classId');
                    if($this->input->post('sectionId') !=null)
                        $sectionId = $this->input->post('sectionId');
                        if($this->input->post('courseId') !=null)
                            $courseId = $this->input->post('courseId');
                            if($this->input->post('examtypeId') !=null)
                                $examtypeId = $this->input->post('examtypeId');
                            $operation = '';
                            if($this->input->post('operation') !=null)
                                $operation = $this->input->post('operation');
                                    
                                if($operation == "PROCESS"){
                                    $examTypeId = $this->input->post('examtype_id');
                                    //echo "CALL processResult ($examTypeId, $termId, $courseId)";
                                    $qry = $this->db->query("CALL processResult ($examTypeId, $termId, $courseId)");

                                }else  if($operation == "PUBLISH"){
                                    $examTypeId = $this->input->post('examtype_id');
                                    $running_session = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
                                    $running_term = $this->db->get_where('settings', array('type' => 'running_term'))->row()->description;

                                    $qry = $this->db->query("CALL publishResult ($examTypeId, $termId, $courseId, $running_session, $running_term)");
                                }
    
                                    $exams = $this->db->get('exam')->result_array();
    
                                    $page_data['terms'] = array(''=>'Select one');
                                    foreach($exams as $row):
                                    $page_data['terms'][$row['exam_id']] = $row['name'];
                                    endforeach;
                                    $page_data['termId'] = $termId;

                                    $groups = $this->db->get('class_group')->result_array();
                                    $page_data['allgroups'] = array(''=>'Select one');

                                    foreach($groups as $row):
                                    $page_data['allgroups'][$row['id']] = $row['group_name'];
                                    endforeach;
                                    $page_data['groupId'] = $groupId;
    
    
                                    $classes = $this->db->get('class')->result_array();
                                    $page_data['allclasses'] = array(''=>'Select one');
                                        
                                    foreach($classes as $row):
                                    $page_data['allclasses'][$row['class_id']] = $row['name'];
                                    endforeach;
                                    $page_data['classId'] = $classId;
                                        
                                    $sections = $this->db->get_where('section', array('class_id' => $classId, 'group_id' => $groupId))->result_array();
                                    $page_data['allsections'] = array(''=>'Select one');
    
                                    foreach($sections as $row):
                                    $page_data['allsections'][$row['section_id']] = $row['name'];
                                    endforeach;
                                    $page_data['sectionId'] = $sectionId;
                                        
                                    $courses = $this->db->get_where('course', array('class_id' => $classId, 'group_id' => $groupId))->result_array();
                                    $page_data['courses'] = array(''=>'Select one');
                                    foreach($courses as $row):
                                    $page_data['courses'][$row['course_id']] = $row['tittle'];
                                    endforeach;
                                    $page_data['courseId'] = $courseId;
                                    
                                    $examtypes = $this->db->get_where('vexamcourse', array('course_id' => $courseId,'type' => 'single'))->result_array();
                                    $page_data['examtypess'] = array(''=>'Select one');
                                    foreach($examtypes as $row):
                                    $page_data['examtypess'][$row['examtype_id']] = $row['name'];
                                    endforeach;
                                    $page_data['examtypeId'] = $examtypeId;
                                    
                                    
    
                                    $students = $this->db->get_where('v_student_class', array('class_id' => $classId, 'section_id' => $sectionId))->result_array();
    
                                    $page_data['students'] = $students;
                                    
                                    $examtypes = $this->db->get_where('vexamcourse', array('course_id' => $courseId, 'exam_id' => $termId,'examtype_id' => $examtypeId))->result_array();
                                    
                                    $page_data['examtypes'] = $examtypes;
    
                                    $exammarks = $this->db->get_where('exammark', array('course_id' => $courseId, 'exam_id' => $termId))->result_array();
    
                                    $marks = array();
                                    foreach($exammarks as $row):
                                    $marks[$row['exam_id']][$row['course_id']][$row['examtype_id']][$row['student_id']] = $row['mark_obtained'];
                                    endforeach;
                                    $page_data['marks'] = $marks;
                                    $page_data['page_info'] = 'Exam marks';
    
                                    // print_r($marks);
                                    
                                    $page_data['groupClass']  = $this->getGroupClass();
                                    $page_data['page_name']  = 'examtype_marks';
                                    $page_data['page_title'] = get_phrase('manage_exam_marks');
                                    $this->load->view('backend/index', $page_data);
    }

    function exam_marks()
    {
         
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
    
            $termId = -1;
            $classId = -1;
            $sectionId = -1;
            $courseId = -1;
            if($this->input->post('termId') !=null)
                $termId = $this->input->post('termId');
            if($this->input->post('groupId') !=null)
                $groupId = $this->input->post('groupId');
                if($this->input->post('classId') !=null)
                    $classId = $this->input->post('classId');
                    if($this->input->post('sectionId') !=null)
                        $sectionId = $this->input->post('sectionId');
                        if($this->input->post('courseId') !=null)
                            $courseId = $this->input->post('courseId');
                        $operation = '';
                        if($this->input->post('operation') !=null)
                            $operation = $this->input->post('operation');
                             
                            if($operation == "PROCESS"){
                                $examTypeId = $this->input->post('examtype_id');
                                //echo "CALL processResult ($examTypeId, $termId, $courseId)";
                              $qry = $this->db->query("CALL processResult ($examTypeId, $termId, $courseId)");

                                    $condition = array(
                                        'examtype_id' => $examTypeId, 
                                        'exam_id'     => $termId, 
                                        'course_id'   => $courseId
                                    );

                                    $this->db->where($condition);
                                    $this->db->from('examcourse');
                                    $reportcard = $this->db->get()->row()->report_card;
                                    $combined   = $this->db->get_where('course', array('course_id' => $courseId))->row()->combined;

                                    $passing_check_examtypes_condition = array(
                                        'ec.examtype_id !=' => $examTypeId,
                                        'ec.course_id'      => $courseId,
                                        'ec.exam_id'        => $termId,
                                        'et.passing_check'  => 1
                                    );
                                    $this->db->select('et.examtype_id');
                                    $this->db->from('examcourse ec');
                                    $this->db->join('examtype et', 'ec.examtype_id = et.examtype_id', 'inner');
                                    $this->db->where($passing_check_examtypes_condition);
                                    $this->db->group_by('et.examtype_id');
                                    $passing_check_examtypes =  $this->db->get()->result_array();

                                $students = $this->db->get_where('exammark', array('examtype_id' => $examTypeId, 'exam_id' => $termId, 'course_id' => $courseId))->result_array();



                                $exammarkData = array();
                                foreach ($students as $student) {
                                        $student_id = $student['student_id'];



                                        $exammark_id = $this->db->get_where('exammark', array('examtype_id' => $examTypeId, 'exam_id' => $termId, 'course_id' => $courseId, 'student_id' => $student_id))->row()->exammark_id;



                                        $obtained_mark = $this->db->get_where('exammark', array('examtype_id' => $examTypeId, 'exam_id' => $termId, 'course_id' => $courseId, 'student_id' => $student_id))->row()->mark_obtained;

                                        
                                    $failed = 0;
                                    if($combined){
                                            if($reportcard){
                                                foreach ($passing_check_examtypes as  $pctype) {

                                                    $failed_condition = array(
                                                        'student_id' => $student_id,
                                                        'exam_id'    => $termId,
                                                        'course_id'  => $courseId,
                                                        'examtype_id'=> $pctype['examtype_id']
                                                    );
                                                    $gp = $this->db->get_where('exammark', $failed_condition)->row()->gp;
                                                    if($gp == 0){
                                                        $failed = 1;
                                                    }
                                                }
                                                
                                            }
                                            $display_name = $this->db->get_where('examtype', array('examtype_id' => $examTypeId))->row()->displayname;

                                                $courseGroupId = $this->db->get_where('course_group', array('course_id' => $courseId))->row()->group_id;




                                                $combined_courseId = $this->db->get_where('course_group', array('group_id' => $courseGroupId, 'course_id !=' => $courseId))->row()->course_id;




                                                $this->db->select('e.examtype_id, e.total_mark');
                                                $this->db->from('examtype e');
                                                $this->db->join('examcourse ec', "e.examtype_id = ec.examtype_id AND ec.course_id = '$combined_courseId'", 'inner');
                                                $this->db->where('e.displayname', "$display_name");
                                                $this->db->group_by('e.examtype_id');
                                                $combined_info = $this->db->get()->result_array();

                                                $combined_examtypeId = $combined_info[0]['examtype_id'];
                                                $combined_total_mark = $combined_info[0]['total_mark'];



                                                $combined_exammark_id = $this->db->get_where('exammark', array('examtype_id' => $combined_examtypeId, 'exam_id' => $termId, 'course_id' => $combined_courseId, 'student_id' => $student_id))->row()->exammark_id;

                                                

                                                $this_type_total_mark = $this->db->get_where('examtype', array('examtype_id' => $examTypeId))->row()->total_mark;



                                                $combined_total_mark = $combined_total_mark + $this_type_total_mark;



                                                $combined_mark_obtained_condition = array(
                                                        'exam_id' => $termId,
                                                        'course_id' => $combined_courseId,
                                                        'examtype_id' => $combined_examtypeId,
                                                        'student_id' => $student_id
                                                );
                                                $this->db->where($combined_mark_obtained_condition);
                                                $this->db->from('exammark');
                                                $combined_markId_mark = $this->db->get()->result_array();

                                                $combined_obtained_mark = $combined_markId_mark[0]['mark_obtained'];

                                                $combined_markId        = $combined_markId_mark[0]['exammark_id'];

                                                $total_obtained_marks = $obtained_mark + $combined_obtained_mark;
                                                $average = round($total_obtained_marks*100/$combined_total_mark,2);

                                                if($failed == 1){
                                                    $exammarkData['lg'] = 'F';
                                                    $exammarkData['gp'] = 0;
                                                }else{
                                                    $exammarkData['lg'] = $this->crud_model->get_grade_with_everage($average, 'lg');
                                                    $exammarkData['gp'] = $this->crud_model->get_grade_with_everage($average, 'gp');
                                                }

                                                $condition = array($combined_markId, $exammark_id);
                                                $this->db->where_in('exammark_id', $condition);
                                                $this->db->update('exammark', $exammarkData);

                                                
                                        }else{
                                            $letterGrade = "SELECT calcGrade($obtained_mark, $examTypeId) as lg
                                                            FROM exammark
                                                            WHERE exammark_id = $exammark_id";
                                            $gradePoint = "SELECT calcGradePoint($obtained_mark, $examTypeId) as gp
                                                            FROM exammark
                                                            WHERE exammark_id = $exammark_id";
                                            $letterGrade = $this->db->query($letterGrade)->row()->lg;
                                            $gradePoint = $this->db->query($gradePoint)->row()->gp;

                                            $exammarkData['lg'] = $letterGrade;
                                            $exammarkData['gp'] = $gradePoint;

                                            $this->db->where('exammark_id', $exammark_id);
                                            $update = $this->db->update('exammark', $exammarkData);
                                        }

                                }
                            }else  if($operation == "PUBLISH"){
                                $examTypeId = $this->input->post('examtype_id');
                                $running_session = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
                                $running_term = $this->db->get_where('settings', array('type' => 'running_term'))->row()->description;

                                $qry = $this->db->query("CALL publishResult ($examTypeId, $termId, $courseId, $running_session, $running_term)");
                            }
    
                                $exams = $this->db->get('exam')->result_array();
    
                                $page_data['terms'] = array(''=>'Select one');
                                foreach($exams as $row):
                                $page_data['terms'][$row['exam_id']] = $row['name'];
                                endforeach;
                                $page_data['termId'] = $termId;

                                $groups = $this->db->get('class_group')->result_array();
                                $page_data['groups'] = array(''=>'Select one');
                                foreach($groups as $row):
                                $page_data['groups'][$row['id']] = $row['group_name'];
                                endforeach;
                                $page_data['groupId'] = $groupId;
    
    
                                $classes = $this->db->get('class')->result_array();
                                $page_data['allclasses'] = array(''=>'Select one');
                                
                                foreach($classes as $row):
                                $page_data['allclasses'][$row['class_id']] = $row['name'];
                                endforeach;
                                $page_data['classId'] = $classId;
                                
                                $sections = $this->db->get_where('section', array('class_id' => $classId, 'group_id' => $groupId))->result_array();
                                $page_data['allsections'] = array(''=>'Select one');
                                    
                                foreach($sections as $row):
                                $page_data['allsections'][$row['section_id']] = $row['name'];
                                endforeach;
                                $page_data['sectionId'] = $sectionId;


                                $teacher_id = $this->session->userdata('teacher_id');
                                $this->db->select('*');
                                $this->db->from('course c');
                                $this->db->join('courseteacherassignment ct', 'c.course_id = ct.course_id', 'inner');
                                $this->db->where('c.class_id', $classId);
                                $this->db->where('c.group_id', $groupId);
                                $this->db->where('ct.teacher_id', $teacher_id);
                                $courses = $this->db->get()->result_array();

                                
                                // $courses = $this->db->get_where('course', array('class_id' => $classId, 'group_id' => $groupId))->result_array();

                                $page_data['courses'] = array(''=>'Select one');
                                foreach($courses as $row):
                                $page_data['courses'][$row['course_id']] = $row['tittle'];
                                endforeach;
                                $page_data['courseId'] = $courseId;


                                $students = $this->db->select('*')->from('v_student_class')->where(array('class_id' => $classId, 'section_id' => $sectionId))->order_by('roll', ASC)->get()->result_array();
    
                                $page_data['students'] = $students;
    
                                $examtypes = $this->db->order_by('examtype_id', 'ASC')->get_where('vexamcourse', array('course_id' => $courseId, 'exam_id' => $termId))->result_array();
    
                                $page_data['examtypes'] = $examtypes;
    
                                $exammarks = $this->db->get_where('exammark', array('course_id' => $courseId, 'exam_id' => $termId))->result_array();
    
                                $marks = array();
                                foreach($exammarks as $row):
                                $marks[$row['exam_id']][$row['course_id']][$row['examtype_id']][$row['student_id']] = $row['mark_obtained'];
                                endforeach;
                                $page_data['marks'] = $marks;
                                $page_data['page_info'] = 'Exam marks';

                                $page_data['groupClass']  = $this->getGroupClass();

                                $page_data['page_name']  = 'exam_marks';
                                $page_data['page_title'] = get_phrase('manage_exam_marks');
                                $this->load->view('backend/index', $page_data);
    }

    function markupdate($termId=-1, $courseId = -1, $examTypeId = -1, $studentId = -1, $marks = 0){
    	$whclause = array('exam_id' => $termId,'course_id' => $courseId,'examtype_id' => $examTypeId,'student_id' => $studentId);
    	$row = $this->db->get_where('exammark',  $whclause)->result_array();
    	 
    	if(count($row)>0){
    		$this->db->where('exam_id', $termId);
    		$this->db->where('course_id', $courseId);
    		$this->db->where('examtype_id', $examTypeId);
    		$this->db->where('student_id', $studentId);
    
    		echo $this->db->update('exammark', ["mark_obtained"=>$marks]);
    	}else{
    		$whclause['mark_obtained'] = $marks;
    		echo $this->db->insert('exammark', $whclause);
    	}
    	 
    }
	
    
    /****MANAGE EXAM MARKS*****/
    function marks_manage()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  =   'marks_manage';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_manage_view($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['exam_id']    =   $exam_id;
        $page_data['class_id']   =   $class_id;
        $page_data['subject_id'] =   $subject_id;
        $page_data['section_id'] =   $section_id;
        $page_data['page_name']  =   'marks_manage_view';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_selector()
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');

        $data['exam_id']    = $this->input->post('exam_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['subject_id'] = $this->input->post('subject_id');
        $data['year']       = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
        $query = $this->db->get_where('mark' , array(
                    'exam_id' => $data['exam_id'],
                        'class_id' => $data['class_id'],
                            'section_id' => $data['section_id'],
                                'subject_id' => $data['subject_id'],
                                    'year' => $data['year']
                ));
        if($query->num_rows() < 1) {
            $students = $this->db->get_where('enroll' , array(
                'class_id' => $data['class_id'] , 'section_id' => $data['section_id'] , 'year' => $data['year']
            ))->result_array();
            foreach($students as $row) {
                $data['student_id'] = $row['student_id'];
                $this->db->insert('mark' , $data);
            }
        }
        redirect(base_url() . 'index.php?teacher/marks_manage_view/' . $data['exam_id'] . '/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['subject_id'] , 'refresh');
        
    }

    function marks_update($exam_id = '', $class_id = '' , $section_id = '' , $subject_id = '')
    {
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        $marks_of_students = $this->db->get_where('mark' , array(
            'exam_id' => $exam_id,
                'class_id' => $class_id,
                    'section_id' => $section_id,
                        'year' => $running_year,
                            'subject_id' => $subject_id
        ))->result_array();
        foreach($marks_of_students as $row) {
            $obtained_marks = $this->input->post('marks_obtained_'.$row['mark_id']);
            $comment = $this->input->post('comment_'.$row['mark_id']);
            $this->db->where('mark_id' , $row['mark_id']);
            $this->db->update('mark' , array('mark_obtained' => $obtained_marks , 'comment' => $comment));
        }
        $this->session->set_flashdata('flash_message' , get_phrase('marks_updated'));
        redirect(base_url().'index.php?teacher/marks_manage_view/'.$exam_id.'/'.$class_id.'/'.$section_id.'/'.$subject_id , 'refresh');
    }

    function marks_get_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/teacher/marks_get_subject' , $page_data);
    }

    /**********ACADEMIC CALENDAR******************/
    function academic_calendar() {
    	$page_data['page_name']  = 'view_academic_calendar';
    	$page_data['page_title'] = get_phrase('academic_calendar');
    	$page_data['events']    = $this->db->get('academic_calendar')->result_array();
    	$this->load->view('backend/index', $page_data);
    }
    // ACADEMIC SYLLABUS
    function academic_syllabus($class_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $page_data['page_name']  = 'academic_syllabus';
        $page_data['page_title'] = get_phrase('academic_syllabus');
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);
    }

    function upload_academic_syllabus()
    {
        $data['academic_syllabus_code'] =   substr(md5(rand(0, 1000000)), 0, 7);
        $data['title']                  =   $this->input->post('title');
        $data['description']            =   $this->input->post('description');
        $data['class_id']               =   $this->input->post('class_id');
        $data['uploader_type']          =   $this->session->userdata('login_type');
        $data['uploader_id']            =   $this->session->userdata('login_user_id');
        $data['year']                   =   $this->db->get_where('settings',array('type'=>'running_year'))->row()->description;
        $data['timestamp']              =   strtotime(date("Y-m-d H:i:s"));
        //uploading file using codeigniter upload library
        $files = $_FILES['file_name'];
        $this->load->library('upload');
        $config['upload_path']   =  'uploads/syllabus/';
        $config['allowed_types'] =  '*';
        $_FILES['file_name']['name']     = $files['name'];
        $_FILES['file_name']['type']     = $files['type'];
        $_FILES['file_name']['tmp_name'] = $files['tmp_name'];
        $_FILES['file_name']['size']     = $files['size'];
        $this->upload->initialize($config);
        $this->upload->do_upload('file_name');

        $data['file_name'] = $_FILES['file_name']['name'];

        $this->db->insert('academic_syllabus', $data);
        $this->session->set_flashdata('flash_message' , get_phrase('syllabus_uploaded'));
        redirect(base_url() . 'index.php?teacher/academic_syllabus/' . $data['class_id'] , 'refresh');

    }

    function download_academic_syllabus($academic_syllabus_code)
    {
        $file_name = $this->db->get_where('academic_syllabus', array(
            'academic_syllabus_code' => $academic_syllabus_code
        ))->row()->file_name;
        $this->load->helper('download');
        $data = file_get_contents("uploads/syllabus/" . $file_name);
        $name = $file_name;

        force_download($name, $data);
    }
    
    /*****BACKUP / RESTORE / DELETE DATA PAGE**********/
    function backup_restore($operation = '', $type = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'index.php?teacher/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'index.php?teacher/backup_restore/', 'refresh');
        }
        
        $page_data['page_info']  = 'Create backup / restore from backup';
        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $data['name']        = $this->input->post('name');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $this->session->userdata('teacher_id'));
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $this->session->userdata('teacher_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'index.php?teacher/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = sha1($this->input->post('password'));
            $data['new_password']         = sha1($this->input->post('new_password'));
            $data['confirm_new_password'] = sha1($this->input->post('confirm_new_password'));
            
            $current_password = $this->db->get_where('teacher', array(
                'teacher_id' => $this->session->userdata('teacher_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('teacher_id', $this->session->userdata('teacher_id'));
                $this->db->update('teacher', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'index.php?teacher/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('teacher', array(
            'teacher_id' => $this->session->userdata('teacher_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($class_id)
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'class_routine';
        $page_data['class_id']  =   $class_id;
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_print_view($class_id , $section_id)
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        $page_data['class_id']   =   $class_id;
        $page_data['section_id'] =   $section_id;
        $this->load->view('backend/teacher/class_routine_print_view' , $page_data);
    }

    private function getGroupClass(){
        return $this->db->query("select * from codes where key_name='class' or key_name = 'group' order by key_name")->result_array();
    }
	
	/****** DAILY ATTENDANCE *****************/
    function manage_attendance($class_id)
    {
        if($this->session->userdata('teacher_login')!=1)
            redirect(base_url() , 'refresh');


        $page_data['classes'] = $this->db->get('class')->result_array();
        $page_data['groupClass'] = $this->getGroupClass();
        
        $page_data['page_name']  =  'manage_attendance';
        $page_data['page_title'] =  get_phrase('manage_attendance_of_class');

        $this->load->view('backend/index', $page_data);
    }

    function manage_attendance_view($group_id = '', $class_id = '' , $section_id = '', $course_id = '', $timestamp = '')
    {
        if($this->session->userdata('teacher_login')!=1)
            redirect(base_url() , 'refresh');

        $timestamp = date("Y-m-d", $timestamp);

        $query = "SELECT 
                   e.student_id, s.name AS student_name, e.roll,
                   ifnull(a.`status`, 1) as status 
                   
                FROM enroll e
                INNER JOIN student s ON e.student_id = s.student_id
                LEFT JOIN attendance a ON (a.student_id = s.student_id and a.timestamp = '$timestamp' AND course_id = $course_id)
                LEFT JOIN section sec ON sec.section_id = e.section_id
                where sec.group_id = $group_id AND e.class_id = $class_id AND sec.section_id = $section_id
                ORDER BY e.roll";

        $students = $this->db->query($query)->result_array();
        $page_data['students'] = $students;

        $group_name = $this->db->get_where('class_group', array('id' => $group_id))->row()->group_name;
        $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
        $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;


        $page_data['group_id'] = $group_id;
        $page_data['class_id'] = $class_id;
        $page_data['section_id'] = $section_id;
        $page_data['course_id'] = $course_id;
        $timestamp = strtotime(date($timestamp));
        $page_data['timestamp'] = $timestamp;

        $page_data['groupClass'] = $this->getGroupClass();

        $page_data['page_name'] = 'manage_attendance_view';
        $page_data['page_title'] = get_phrase('manage_attendance_of') .' '.$group_name. ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
        $this->load->view('backend/index', $page_data);



        // if($this->session->userdata('teacher_login')!=1)
        //     redirect(base_url() , 'refresh');
        // $class_name = $this->db->get_where('class' , array(
        //     'class_id' => $class_id
        // ))->row()->name;
        // $page_data['class_id'] = $class_id;
        // $page_data['timestamp'] = $timestamp;
        // $page_data['page_name'] = 'manage_attendance_view';
        // $section_name = $this->db->get_where('section' , array(
        //     'section_id' => $section_id
        // ))->row()->name;
        // $page_data['section_id'] = $section_id;
        // $page_data['page_title'] = get_phrase('manage_attendance_of_class') . ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
        // $this->load->view('backend/index', $page_data);
    }

    function attendance_selector()
    {
        $data['group_id']   = $this->input->post('group_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['year']       = $this->input->post('year');
        $data['timestamp']  = strtotime($this->input->post('timestamp'));


        $this->manage_attendance_view($data['group_id'], $data['class_id'],$data['section_id'], $data['course_id'], $data['timestamp']);

        
    }

    function attendance_report($timest = '', $group_id = '', $class_id = '', $section_id = '', $course_id = '', $absent_sms = '') {

            $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
            $timestamp    = strtotime(date("d-m-Y"));
            if($timest != ''){
                $timestamp = $timest;
            }

            if($absent_sms == 'absent_sms' && $absent_sms != ''){
            $time = date("Y-m-d", $timestamp);

            $query = "SELECT st.name, e.roll, p.phone FROM enroll e
                        JOIN student st ON (st.student_id = e.student_id and e.class_id = $class_id AND e.section_id = $section_id)
                        JOIN section sec ON (e.section_id = sec.section_id and sec.group_id = $group_id)
                        JOIN attendance a ON (a.student_id = e.student_id and a.course_id = $course_id AND a.timestamp = '$time' AND a.`status` = 0)
                        JOIN parent p ON (st.parent_id = p.parent_id)";
            $details = $this->db->query($query)->result_array();
            $reciever = array_column($details, 'phone');

            $message = $this->db->get_where('codes', array('key_name' => 'notification.sms.absent.content'))->row()->value;

            $this->sms_model->send_sms($message, $reciever, $time);
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));

            $timestamp = strtotime($time);
            redirect(base_url().'index.php?teacher/attendance_report/'.$timestamp,'refresh');
        }

            $timestamp = date("Y-m-d", $timestamp);

            $query = "SELECT a.`timestamp`, s.group_id, g.group_name, e.class_id, c.name AS class_name,
                    e.section_id, s.name AS section_name,
                    COUNT(*) AS total,
                    SUM(CASE a.status WHEN 1 THEN 1 ELSE 0 END) AS present,
                    SUM(CASE a.`status` WHEN 0 THEN 1 ELSE 0 END) AS absent,
                    a.course_id
                    FROM enroll e
                    LEFT JOIN attendance a on (e.student_id = a.student_id and a.timestamp = '$timestamp')
                    LEFT JOIN class c ON c.class_id = e.class_id
                    LEFT JOIN section s ON s.section_id = e.section_id
                    LEFT JOIN class_group g ON g.id = s.group_id
                    where e.`year` = $running_year
                    GROUP BY s.group_id, g.group_name, e.class_id, c.name, 
                    e.section_id, s.name";

            $attendance = $this->db->query($query)->result_array();

            $page_data['attendance'] = $attendance;

            $timestamp = strtotime($timestamp);

            $page_data['group_id'] = $group_id;
            $page_data['class_id'] = $class_id;
            $page_data['section_id'] = $section_id;
            $page_data['select_time'] = $timestamp;

            $page_data['groupClass'] = $this->getGroupClass();
            $page_data['page_name']    = 'attendance_report_view';
            $page_data['page_title']   = get_phrase('attendance_report_view');
            $this->load->view('backend/index',$page_data);
     }


     function groupwise_attendance_selector()
    {

        $page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
        if ($this->input->post ( 'start_date' ) != null) {
            $page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
        }
            
        $page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
        if ($this->input->post ( 'end_date' ) != null) {
            $page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
        }

        $page_data['group_id']   = $this->input->post('group_id');
        $page_data['course_id'] = $this->input->post('course_id');
        

        //$this->manage_attendance_view($data['group_id'], $data['class_id'],$data['section_id'], $data['timestamp']);
        redirect(base_url().'index.php?teacher/coursewise_attendance_report_view/'.$page_data ['start_date'].'/'.$page_data ['end_date'].'/'.$page_data['group_id'].'/'.$page_data['course_id'],'refresh');

    }

    function coursewise_attendance_report_view($start_date = '', $end_date = '', $group_id = '', $course_id = '') {

            $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

            $query = "SELECT e.student_id, st.name,
                    COUNT(*) total,
                    SUM(CASE WHEN a.`status`=1 THEN 1 ELSE 0 END) present,
                    SUM(CASE WHEN a.`status`=0 THEN 1 ELSE 0 END) absent,
                    ROUND((SUM(CASE WHEN a.`status`=1 THEN 1 ELSE 0 END)/COUNT(*)),2) * 100 percentage
                    FROM enroll e
                    INNER JOIN student st ON st.student_id = e.student_id
                    INNER JOIN section s ON (s.section_id = e.section_id AND group_id = $group_id)
                    INNER JOIN attendance a ON (a.student_id = e.student_id and a.course_id = $course_id AND a.timestamp BETWEEN '$start_date' AND '$end_date')
                    GROUP BY e.student_id
                    ORDER BY e.roll";
            $students = $this->db->query($query)->result_array();

            $start_date = strtotime($start_date);
            $end_date   = strtotime($end_date);

            
            $page_data['students']     = $students;
            $page_data['start_date']   = $start_date;
            $page_data['end_date']     = $end_date;
            $page_data['group_id'] = $group_id;

            $page_data['groupClass'] = $this->getGroupClass();

            $page_data['page_name']    = 'coursewise_attendance_report_view';
            $page_data['page_title']   = get_phrase('coursewise_attendance_report_view');
            $this->load->view('backend/index',$page_data);
     }


     function coursewise_attendance_report()
    {
        if($this->session->userdata('teacher_login')!=1)
            redirect(base_url() , 'refresh');


        $page_data['classes'] = $this->db->get('class')->result_array();

        $page_data['groupClass'] = $this->getGroupClass();
        
        $page_data['page_name']  =  'coursewise_attendance_report';
        $page_data['page_title'] =  get_phrase('manage_attendance_of_class');
        $this->load->view('backend/index', $page_data);
    }

     function attendance_report_selector()
    {

        $data['group_id']   = $this->input->post('group_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['year']       = $this->input->post('year');
        $data['timestamp'] = strtotime($this->input->post('timestamp'));
        
        redirect(base_url().'index.php?teacher/attendance_report/'.$data['timestamp'],'refresh');
    }


    function attendance_update($group_id = '' , $class_id = '' , $section_id = '', $course_id = '', $timestamp = '')
    {
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
        //$active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;

        $student_id = $this->input->post('student_id');


        $timestamp = date("Y-m-d", $timestamp);

        foreach ($student_id as $studentId) {
            $attn_data['timestamp'] = $timestamp;
            $attn_data['student_id'] = $studentId;

            $attn_data['status']      = $this->input->post('status_'.$studentId);

            if($attn_data['status'] == ''){
                $attn_data['status'] = 0;
            }

            $attn_data['course_id'] = $course_id;

            $existingId = $this->db->get_where('attendance', array('student_id' => $studentId, 'timestamp' => $attn_data['timestamp']))->row()->attendance_id;

            if(count($existingId) > 0){
                $this->db->where('attendance_id', $existingId);
                $this->db->update('attendance', array('status' => $attn_data['status']));
            }else{
                $this->db->insert('attendance', $attn_data); 
            }
        }

        $timestamp = strtotime($timestamp);

        $this->session->set_flashdata('flash_message' , get_phrase('attendance_updated'));
        //$this->manage_attendance_view($group_id, $class_id,$section_id, $timestamp);
        redirect(base_url().'index.php?teacher/manage_attendance_view/'.$group_id.'/'.$class_id.'/'.$section_id.'/'.$course_id.'/'.$timestamp , 'refresh');
    }
    
    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert('noticeboard', $data);
            redirect(base_url() . 'index.php?teacher/noticeboard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update('noticeboard', $data);
            $this->session->set_flashdata('flash_message', get_phrase('notice_updated'));
            redirect(base_url() . 'index.php?teacher/noticeboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('noticeboard');
            redirect(base_url() . 'index.php?teacher/noticeboard/', 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    /***** NOTICEBOARD *****/
    function view_noticeboard() {    	  
    	$page_data['page_name']  = 'view_noticeboard';
    	$page_data['page_title'] = get_phrase('noticeboard');
    	$page_data['notices']    = $this->db->get('noticeboard')->result_array();
    	$this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    function document__($do = '', $document_id = '')
    {
        if ($this->session->userdata('teacher_login') != 1)
            redirect('login', 'refresh');
        if ($do == 'upload') {
            move_uploaded_file($_FILES["userfile"]["tmp_name"], "uploads/document/" . $_FILES["userfile"]["name"]);
            $data['document_name'] = $this->input->post('document_name');
            $data['file_name']     = $_FILES["userfile"]["name"];
            $data['file_size']     = $_FILES["userfile"]["size"];
            $this->db->insert('document', $data);
            redirect(base_url() . 'teacher/manage_document', 'refresh');
        }
        if ($do == 'delete') {
            $this->db->where('document_id', $document_id);
            $this->db->delete('document');
            redirect(base_url() . 'teacher/manage_document', 'refresh');
        }
        $page_data['page_name']  = 'manage_document';
        $page_data['page_title'] = get_phrase('manage_documents');
        $page_data['documents']  = $this->db->get('document')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*********MANAGE STUDY MATERIAL************/
    function study_material($task = "", $document_id = "")
    {
        if ($this->session->userdata('teacher_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }      
        if ($task == "create")
        {
            $this->crud_model->save_study_material_info();
            $this->session->set_flashdata('flash_message' , get_phrase('study_material_info_saved_successfuly'));
            redirect(base_url() . 'index.php?teacher/study_material' , 'refresh');
        }
        
        if ($task == "update")
        {
            $this->crud_model->update_study_material_info($document_id);
            $this->session->set_flashdata('flash_message' , get_phrase('study_material_info_updated_successfuly'));
            redirect(base_url() . 'index.php?teacher/study_material' , 'refresh');
        }
        
        if ($task == "delete")
        {
            $this->crud_model->delete_study_material_info($document_id);
            redirect(base_url() . 'index.php?teacher/study_material');
        }
        
        $data['study_material_info']    = $this->crud_model->select_study_material_info();
        $data['page_name']              = 'study_material';
        $data['page_title']             = get_phrase('study_material');
        $this->load->view('backend/index', $data);
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('teacher_login') != 1)
        {
            $this->session->set_userdata('last_page' , current_url());
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?teacher/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?teacher/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGE DOCUMENT / home work FOR A SPECIFIC CLASS or ALL*******************/
    
    function document($do = '', $documentId = '')
    
    {
    
    	
    	 if ($this->session->userdata('teacher_login') != 1)
    
    	 	redirect('login', 'refresh');
    
    	 if ($do == 'upload') {
    	 	
    	 	
    	 	$unitueId = uniqid();
    	 	
    	 	move_uploaded_file($_FILES["userfile"]["tmp_name"], "uploads/document/" . $unitueId);
    
    	 	$data['uniqueCode'] = $unitueId;
  
    	 	$data['name']     = $_FILES["userfile"]["name"];
    
    	 	$data['file_size']     = $_FILES["userfile"]["size"];
    	 	$data['description']     = $this->input->post('description');
    
    	 	$this->db->insert('document', $data);
    
    	 	redirect(base_url() . 'index.php?teacher/document', 'refresh');
    
    	 }
    
    	 	if ($do == 'delete') {
    
    	 	$this->db->where('documentId', $documentId);
    
    	 	$this->db->delete('document');
    
    	 	//redirect(base_url() . 'index.php?teacher/document', 'refresh');
    
    	 	}
    
    	 	$page_data['page_name']  = 'manage_document';
    
    	 	$page_data['page_title'] = get_phrase('manage_documents');
    
    	 	$page_data['documents']  = $this->db->get('document')->result_array();
    
    	 	$this->load->view('backend/index',  $page_data);
    
    }
    
    
    function shareDocument($id, $operation="", $shareId){
    
    	$page_data['page_name']  = 'share_document';
    
    	if($operation == "share"){
    
    		$saveData['type'] = $this->input->post('type');
    		$saveData['documentId'] = $id;
    		$saveData['shareToId'] = $this->input->post('shareToId');
    
    		$this->db->insert('documentpermission', $saveData);
    
    		redirect(base_url() . 'index.php?teacher/shareDocument/'.$id, 'refresh');
    
    	}elseif ($operation == "delete"){
    
    		$this->db->where('permissionId', $shareId);
    		$this->db->delete('documentpermission');
    
    		redirect(base_url() . 'index.php?teacher/shareDocument/'.$id, 'refresh');
    	}
    
    	$page_data['page_title'] = get_phrase('share_document');
    	$page_data['documentId'] = $id;
    	$page_data['documents']  = $this->db->get_where('document', array("documentId"=>$id))->result_array();
    	$page_data['shares']  = $this->db->get_where('vDocShare', array("documentId"=>$id))->result_array();
    
    	$page_data['teachers']  = $this->db->get_where('teacher')->result_array();
    	$page_data['classes']  = $this->db->get_where('class')->result_array();
    
    	$this->load->view('backend/index',  $page_data);
    
    }
    
    
    
    function downloadDocument($id){
    
    
    
    
    	$documents  = $this->db->get_where('document', array("documentId"=>$id))->result_array();
    
    	$fileName = "";
    	$uniqueCode = "";
    
    	foreach ($documents as $row){
    		$fileName = $row['name'];
    		$uniqueCode = $row['uniqueCode'];
    	}
    
    
    
    
    
    	$url = base_url("uploads/document/".$uniqueCode);
    
    
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl, CURLOPT_HEADER, false);
    	$data = curl_exec($curl);
    
    
    	$name = $fileName;
    
    	curl_close($curl);
    
    	force_download($name, $data);
    
    }
    
}