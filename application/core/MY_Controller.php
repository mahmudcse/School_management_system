<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {


    function __construct()
    {

        parent::__construct();

        //Initialization code that affects all controllers
        
        $this->load->database();
        $this->load->library('session');
        
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    
    function finalizer(){
    	
    	$functions = $this->db->get('functions')->result_array();
    	$menu = array(0=>array());
    	//foreach ($menu as $item){
    	//	$menu[]
    	//}
    	 
    	 return $functions;
    }

}

class Member_Controller extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
	}

/***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        
        $page_data['menu'] = $this->finalizer();
        $this->load->view('backend/index', $page_data);
      
    }
  
    /****MANAGE Campus by Nishan*****/
    
    function add_campus()
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	$systeminfo = $this->db->get('settings')->row();
    	$page_data['institute'] = $systeminfo->description;
    	$page_data['page_name']  = 'campus_add';
    	$page_data['page_title'] = get_phrase('add_campus');
    	$this->load->view('backend/index', $page_data);
    }
    function manage_campus($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	
    	if($param1 == 'create')
    	{
    		$data['campus_name']    = $this->input->post('campus_name');
    		$data['institute_name'] = $this->input->post('institute_name');
    		$this->db->insert('campus' , $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('campus_added'));
    		redirect(base_url() . 'index.php?admin/manage_campus', 'refresh');
    	}
    	if($param1 == 'update')
    	{
    		$data['campus_name']    = $this->input->post('campus_name');
    		$data['institute_name'] = $this->input->post('institute_name');
    		$this->db->where('id' , $param2);
    		$this->db->update('campus', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('campus_added'));
    		redirect(base_url() . 'index.php?admin/manage_campus', 'refresh');
    	}
    	if($param1 == 'delete')
    	{    		
    		$this->db->where('id', $param2);
    		$this->db->delete('campus');
    		$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    		redirect(base_url() . 'index.php?admin/manage_campus/', 'refresh');
    	}
    	
    	$page_data['campuslist'] = $this->db->get('campus')->result_array(); 
    	$page_data['page_name']  = 'campus_manage';
    	$page_data['page_title'] = get_phrase('manage_campus');
    	$this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE Group by Nishan*****/
    function add_group()
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		 
    		$classinfo = $this->db->get('class')->result_array();
    		//$page_data['classinfo'] = $classinfo;
            $page_data['groupClass']  = $this->getGroupClass();

    		$page_data['page_name']  = 'group_add';
    		$page_data['page_title'] = get_phrase('add_group');
    		$this->load->view('backend/index', $page_data);
    }

    // public function student_code(){
    //     $query = "SELECT 
    //                 s.student_id,
    //                 s.student_code,
    //                 s.name,
    //                 e.class_id,
    //                 c.name class_name,
    //                 e.section_id,
    //                 sc.name section_name,
    //                 e.roll,
    //                 sc.group_id,
    //                 cg.group_name
    //                 FROM
    //                 STUDENT s
    //                 INNER JOIN enroll e ON e.student_id = s.student_id
    //                 LEFT JOIN section sc ON e.section_id = sc.section_id
    //                 LEFT JOIN class c ON e.class_id = c.class_id
    //                 LEFT JOIN class_group cg ON c.class_id = cg.id
    //                 GROUP BY e.student_id
    //                 ORDER BY e.roll
    //                 ";
    //     $students = $this->db->query($query)->result_array();
        // foreach ($students as $key => $student) {
        //     $code = '';
            
        //     $section_code = '';
        //     $group_code  = '';
        //     $class_code = '';
        //     $roll = '';
        //     $updateData = array();

        //     if($student['section_name'] == 'MORNING SHIFT'){
        //         $section_code = '1';
        //     }else if($student['section_name'] == 'DAY SHIFT'){
        //         $section_code = '2';
        //     }

        //     if($student['group_name'] == 'SCIENCE'){
        //         $group_code = '1';
        //     }else if($student['group_name'] == 'ARTS'){
        //         $group_code = '2';
        //     }else if($student['group_name'] == 'BUISNESS STUDIES'){
        //         $group_code = '3';
        //     }else{
        //         $group_code = '0';
        //     }

        //     if($student['class_name'] == 'NURSERY'){
        //         $class_code = '91';
        //     }else if($student['class_name'] == 'K.G.'){
        //         $class_code = '92';
        //     }else if($student['class_name'] == 'ONE'){
        //         $class_code = '01';
        //     }else if($student['class_name'] == 'TWO'){
        //         $class_code = '02';
        //     }else if($student['class_name'] == 'THREE'){
        //         $class_code = '03';
        //     }else if($student['class_name'] == 'FOUR'){
        //         $class_code = '04';
        //     }else if($student['class_name'] == 'FIVE'){
        //         $class_code = '05';
        //     }else if($student['class_name'] == 'SIX'){
        //         $class_code = '06';
        //     }else if($student['class_name'] == 'SEVEN'){
        //         $class_code = '07';
        //     }else if($student['class_name'] == 'EIGHT'){
        //         $class_code = '08';
        //     }else if($student['class_name'] == 'NINE'){
        //         $class_code = '09';
        //     }else if($student['class_name'] == 'TEN'){
        //         $class_code = '10';
        //     }

        //     $roll = str_pad($student['roll'], 4, '0', STR_PAD_LEFT);

        //     $code = '17'.$section_code.$group_code.$class_code.$roll;

        //     $updateData['student_code'] = $code;

            

        //     $this->db->where('student_id', $student['student_id']);
        //     $this->db->update('student', $updateData);
        // }


    //     $output = '';
    //     $output .= '
    //         <table>
    //             <thead>
    //                 <tr>
    //                     <td>Student_id</td>
    //                     <td>student_code</td>
    //                     <td>name</td>
    //                     <td>class_name</td>
    //                     <td>section_name</td>
    //                     <td>roll</td>
    //                     <td>group_name</td>
    //                 </tr>
    //             </thead>
    //             <tbody>
    //     ';

    //     foreach ($students as $key => $student) {
    //         $output .= '
    //             <tr>
    //                 <td>'.$student['student_id'].'</td>
    //                 <td>'.$student['student_code'].'</td>
    //                 <td>'.$student['name'].'</td>
    //                 <td>'.$student['class_name'].'</td>
    //                 <td>'.$student['section_name'].'</td>
    //                 <td>'.$student['roll'].'</td>
    //                 <td>'.$student['group_name'].'</td>
    //             </tr>

    //             ';
    //     }

    //     $output .= '</tbody>';
    //     header("Content-type: application/xls");
    //     header("Content-Disposition: attachment; filename = d.xls");
    //     echo $output;
        

    // }

    function manage_group($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		 
    		if($param1 == 'create')
    		{
    			$data['group_name']    = $this->input->post('group_name');
    			//$data['class_id'] = $this->input->post('class');
    			$this->db->insert('class_group' , $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('group_added'));
    			redirect(base_url() . 'index.php?admin/manage_group', 'refresh');
    		}
    		if($param1 == 'update')
    		{
    			$data['group_name'] = $this->input->post('group_name');
    			//$data['class_id'] = $this->input->post('class');
    			$this->db->where('id' , $param2);
    			$this->db->update('class_group', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('group_updated'));
    			redirect(base_url() . 'index.php?admin/manage_group', 'refresh');
    		}
    		if($param1 == 'delete')
    		{
    			$this->db->where('id', $param2);
    			$this->db->delete('class_group');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/manage_group/', 'refresh');
    		}
   
    		$groupinfo = $this->db->get('class_group')->result_array();
    		
 		
    		$page_data['groupinfo'] = $groupinfo;
            $page_data['page_name']  = 'group_manage';

    		$page_data['groupClass']  = $this->getGroupClass();
            //$page_data['page_title'] = get_phrase('manage_group');
    		$page_data['page_title'] = "Manage ".$page_data['groupClass'][1][value];
    		$this->load->view('backend/index', $page_data);
    }

    private function getGroupClass(){
        return $this->db->query("select * from codes where key_name='class' or key_name = 'group' order by key_name")->result_array();
    }

    /****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['groupClass'] = $this->getGroupClass();
		$page_data['default_campus'] = $this->db->get_where('settings', array('type' => 'default_campus'))->row()->description;

		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
		
	}
	
	function student_bulk_add($param1 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if($param1 == 'add_bulk_student') {

            $names     = $this->input->post('name');
            $rolls     = $this->input->post('roll');
            $emails    = $this->input->post('email');
            $passwords = $this->input->post('password');
            $phones    = $this->input->post('phone');
            $addresses = $this->input->post('address');
            $genders   = $this->input->post('sex');

            $student_entries = sizeof($names);
            for($i = 0; $i < $student_entries; $i++) {
                $data['name']     =   $names[$i];
                $data['email']    =   $emails[$i];
                $data['password'] =   sha1($passwords[$i]);
                $data['phone']    =   $phones[$i];
                $data['address']  =   $addresses[$i];
                $data['sex']      =   $genders[$i];

                //validate here, if the row(name, email, password) is empty or not
                if($data['name'] == '' || $data['email'] == '' || $data['password'] == '')
                    continue;

                $this->db->insert('student' , $data);
                $student_id = $this->db->insert_id();

                $data2['enroll_code']   =   substr(md5(rand(0, 1000000)), 0, 7);
                $data2['student_id']    =   $student_id;
                $data2['class_id']      =   $this->input->post('class_id');
                if($this->input->post('section_id') != '') {
                    $data2['section_id']    =   $this->input->post('section_id');
                }
                $data2['roll']          =   $rolls[$i];
                $data2['date_added']    =   strtotime(date("Y-m-d H:i:s"));
                $data2['year']          =   $this->db->get_where('settings' , array(
                                                'type' => 'running_year'
                                            ))->row()->description;

                $this->db->insert('enroll' , $data2);
                
                $userData['reference_id'] 	= $student_id;
                $userData['user_name'] 		= $data2['enroll_code'];
                $userData['password'] 		= sha1($passwords[$i]);
                $userData['user_type'] 		= 'STUDENT';
                
                $this->db->insert('user', $userData);

            }
            $this->session->set_flashdata('flash_message' , get_phrase('students_added'));
            redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id') , 'refresh');
        }			

		$page_data['page_name']  = 'student_bulk_add';
		$page_data['page_title'] = get_phrase('add_bulk_student');
		$this->load->view('backend/index', $page_data);
	}

    function get_sections($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/student_bulk_add_sections' , $page_data);
    }
	
	function student_information($campus_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        
		if($campus_id == NULL) {
			$campus_id = $this->input->post('campus');
		}
        $group_id   = $this->input->post('group');    
        $class_id   = $this->input->post('class');    
        $section_id = $this->input->post('section');

        if($group_id != NULL && $class_id != NULL && $section_id != NULL){
            $this->result($class_id, $group_id);
        }

        $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        
              $students = $this->db->get_where('enroll' , array(
            			'class_id' => $class_id , 'section_id' => $section_id, 'session_id' => $running_year
            	))->result_array();

		
		$page_data['campus_id'] 	= $campus_id;
        $page_data['group_id']      = $group_id;
        $page_data['class_id']      = $class_id;
		$page_data['section_id']    = $section_id;

        $page_data['students']      = $students;
		$page_data['groupClass']    = $this->getGroupClass();

        $page_data['groupClass']    = $this->getGroupClass();

        $page_data['page_name']     = 'student_information';
        $page_data['page_title']    = get_phrase('student_information'). " - ".$page_data['groupClass'][1][value]." : ".$this->crud_model->get_group_name($group_id)." - ".$page_data['groupClass'][0][value]." : ".$this->crud_model->get_class_name($class_id)." - ".get_phrase('section')." : ".$this->db->get_where('section', array('section_id' => $section_id))->row()->name;
		$this->load->view('backend/index', $page_data);
	}

    function sectionwise_marksheet_print($group_id, $class_id, $section_id){
        $running_session = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

        $current_term = $this->db->get_where('settings', array('type' => 'running_term'))->row()->description;

        // echo "$group_id $class_id $section_id $running_session";

        $students = "SELECT 
                            st.name,
                            st.student_code,
                            st.fathername,
                            ss.uniqueCode, 
                            e.*, 
                            c.name AS class_name, 
                            s.group_id, 
                            cg.group_name, 
                            s.section_id, 
                            s.name AS section_name
                        FROM enroll e
                        INNER JOIN class c ON c.class_id = e.class_id AND e.class_id =$class_id
                        INNER JOIN section s ON s.class_id = c.class_id
                        INNER JOIN class_group cg ON cg.id = s.group_id AND cg.id = $group_id
                        INNER JOIN student st ON st.student_id = e.student_id
                        INNER JOIN session ss ON ss.componentId = e.session_id
                        WHERE e.session_id = $running_session AND e.section_id = $section_id
                        GROUP BY e.student_id";
        $students = $this->db->query($students)->result_array();

        $exam = $this->db->get_where('exam', array('exam_id' => $current_term))->result_array();
        $page_data['exam']       = $exam;
        $page_data['courses']   =   $this->db->get_where('course' , array('class_id'=>$class_id, 'group_id' => $group_id))->result_array();
        $page_data['grades'] = $this->db->get('grade')->result_array();
        $page_data['group_id']   = $group_id;
        $page_data['class_id']   = $class_id;
        $page_data['section_id'] = $section_id;
        $page_data['running_year'] = $running_session;
        $page_data['students']   = $students;
        $page_data['groupClass'] = $this->getGroupClass();
        $page_data['page_name']  = 'sectionwise_marksheet';
        $page_data['page_title'] = get_phrase('sectionwise_marksheet');
        $this->load->view('backend/index', $page_data);

    }

    function student_marksheet($student_id = '') {
        if ($this->session->userdata('admin_login') != 1)
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


    public function result($paramClassId, $paramGroupId){
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
        $running_term = $this->db->get_where('settings', array('type' => 'running_term'))->row()->description;

        $enrolledStudents = "SELECT 

                            e.student_id, 
                            e.session_id, 
                            e.class_id, 
                            e.section_id, 
                            cg.id AS group_id 

                            FROM enroll e 
                            INNER JOIN section s ON s.section_id = e.section_id 
                            INNER JOIN class_group cg ON cg.id = s.group_id 
                            INNER JOIN exammark em ON (em.student_id = e.student_id AND em.session_id = $running_year)
                            WHERE e.session_id = $running_year AND e.class_id = '$paramClassId'
                            GROUP BY e.student_id, e.session_id";


        $enrolledStudents = $this->db->query($enrolledStudents)->result_array();

        

        foreach ($enrolledStudents as $student) {
            $student_id = $student['student_id'];
            $class_id   = $student['class_id'];
            $group_id   = $student['group_id'];
              
            $courses = "SELECT 
                        c.course_id, 
                        c.tittle, 
                        c.credit, 
                        c.is_optional, 
                        c.combined, 
                        ec.exam_id, 
                        ec.examtype_id, 
                        em.exammark_id,
                        em.mark_obtained, 
                        em.lg, 
                        em.gp
                        FROM course c
                        INNER JOIN examcourse ec ON (ec.course_id = c.course_id AND ec.report_card = 1)
                        INNER JOIN exammark em ON (em.examtype_id = ec.examtype_id AND em.course_id = c.course_id AND em.exam_id = ec.exam_id)
                        WHERE c.class_id = $class_id AND c.group_id = $group_id AND em.student_id = $student_id
                        GROUP BY ec.examtype_id, ec.exam_id, c.course_id";
            $courses = $this->db->query($courses)->result_array();

            $totalPoint  = 0;
            $totalCredit = 0;
            $total_mark  = 0;
            
            $failed = 0;
            foreach ($courses as $course){
                $is_optional  = $course['is_optional'];
                if($is_optional){
                    $optionalPoint = ($course['gp'] - 2) < 0?0:$course['gp'] - 2;
                    $totalPoint   += $optionalPoint; 
                }else{
                    if($course['credit'] != 0 && $course['gp'] == 0){
                        $failed = 1;
                    }
                    $totalPoint  += $course['gp'] * $course['credit']; 
                    $totalCredit += $course['credit'];
                }
                $total_mark  += $course['mark_obtained'];
            }

            if($failed == 1){
                $gpa = 0;
            }else{
                $gpa = round( $totalPoint/$totalCredit , 2);
            }

            $pregpa = "SELECT * FROM result WHERE cgpa != 'NULL' AND student_id = $student_id AND term_id = $running_term";
            
            $pregpa = $this->db->query($pregpa)->result_array();

            $preResultCount = count($pregpa);

            $preTotalGpa = 0;
            foreach ($pregpa as $gpas) {
                $preTotalGpa += $gpas['gpa'];
            }

            $cgpa = round( $preTotalGpa/$preResultCount , 2);
            if($preResultCount < 1){
                $cgpa = $gpa;
            }

            $resultData = array();
            $resultData['student_id'] = $student_id;
            $resultData['session_id'] = $running_year;
            $resultData['term_id'] = $running_term;

            $resultData['gpa'] = is_nan($gpa)?0:$gpa;
            $resultData['cgpa'] = is_nan($cgpa)?0:$cgpa;

            $resultData['total_mark'] = $total_mark;

            $exammarkUpData = array();

            $exammarkUpData['sgpa'] = is_nan($gpa)?0:$gpa;
            $exammarkUpData['cgpa'] = is_nan($cgpa)?0:$cgpa;
            
            
            $exammarkUpData['total_mark'] = $total_mark;

            $entryExist = $this->db->get_where('result', array('student_id' => $student_id, 'session_id' => $running_year, 'term_id' => $running_term))->result_array();
            if(count($entryExist) > 0){
                $result_id = $entryExist[0]['result_id'];

                $condition = array('result_id' => $result_id);
                $this->db->where($condition);
                $this->db->update('result', $resultData);

            }elseif(count($entryExist) == 0){

                $this->db->insert('result', $resultData);
            }

            $exammarkCondition = array('student_id' => $student_id, 'session_id' => $running_year, 'exam_id' => $running_term);
            $this->db->where($exammarkCondition);
            $this->db->update('exammark', $exammarkUpData);

        }

// Position in Group

            $group_id = $paramGroupId;
            $class_id = $paramClassId;

            $position_on_group = "SELECT 
                                 a.*, @rank:=@rank + 1 AS Rank
                             FROM
                                 (SELECT 
                                     r.student_id,
                                      e.session_id,
                                      e.class_id,
                                      cg.id AS group_id,
                                      e.section_id,
                                      r.total_mark,
                                      r.gpa,
                                      r.term_id
                                 FROM
                                     result r
                                 INNER JOIN enroll e ON e.student_id = r.student_id
                                 INNER JOIN section s ON s.section_id = e.section_id AND s.class_id = $class_id AND s.group_id = $group_id
                                 INNER JOIN class_group cg ON s.group_id = cg.id
                                 WHERE
                                     r.session_id = $running_year AND r.term_id = $running_term
                                GROUP BY r.student_id
                                 ORDER BY r.cgpa DESC, r.total_mark DESC) a,
                                 (SELECT @rank:=0) b";

            $position_on_group = $this->db->query($position_on_group)->result_array();

            $resultUpdate = array();
            foreach ($position_on_group as $positionOnGroup) {
                $student_id = $positionOnGroup['student_id'];
                $session_id = $positionOnGroup['session_id'];
                $term_id    = $running_term;
                $resultUpdate['group_position'] = $positionOnGroup['Rank'];

                $exammarkUpData = array();

                $exammarkUpData['group_position'] = $positionOnGroup['Rank'];

                $condition = array('student_id' => $student_id, 'session_id' => $session_id, 'term_id' => $running_term);
                $this->db->where($condition);
                $this->db->update('result', $resultUpdate);

                $grPositionCondition = array('student_id' => $student_id, 'session_id' => $session_id, 'exam_id' => $running_term);
                $this->db->where($grPositionCondition);
                $this->db->update('exammark', $exammarkUpData);
            }
        //}
        // $distinctSections = "SELECT DISTINCT(e.section_id) AS section_id FROM result r
        //                     INNER JOIN enroll e ON r.student_id = e.student_id
        //                     WHERE r.session_id = 1";
        // $distinctSections = $this->db->query($distinctSections)->result_array();

        // foreach ($distinctSections as $sections) {
        //     $section_id = $sections['section_id'];

        //     $position_on_section = "SELECT 
        //                 a.*, @rank:=@rank + 1 AS Rank
        //             FROM
        //                 (SELECT 
        //                     r.student_id,
        //                         e.session_id,
        //                         e.class_id,
        //                         cg.id AS group_id,
        //                         e.section_id,
        //                         r.gpa,
        //                         r.total_mark
        //                 FROM
        //                     result r
        //                 INNER JOIN enroll e ON e.student_id = r.student_id
        //                 INNER JOIN section s ON s.section_id = e.section_id
        //                 INNER JOIN class_group cg ON s.group_id = cg.id
        //                 WHERE
        //                     s.section_id = $section_id AND r.session_id = $running_year AND r.term_id = $running_term
        //                GROUP BY r.student_id
        //                 ORDER BY r.cgpa DESC, r.total_mark DESC) a,
        //                 (SELECT @rank:=0) b";
                        
        //     $position_on_section = $this->db->query($position_on_section)->result_array();

        //     $resultUpdate   = array();
        //     $exammarkUpData = array();
        //     foreach ($position_on_section as $positionOnSection) {
        //         $student_id = $positionOnSection['student_id'];
        //         $session_id = $positionOnSection['session_id'];
        //         $resultUpdate['section_position'] = $positionOnSection['Rank'];

        //         $exammarkUpData['section_position'] = $positionOnSection['Rank'];

        //         $condition = array('student_id' => $student_id, 'session_id' => $session_id, 'term_id' => $term_id);
        //         $this->db->where($condition);
        //         $this->db->update('result', $resultUpdate);

        //         $exammarkCondition = array('student_id' => $student_id, 'session_id' => $session_id, 'exam_id' => $term_id);
        //         $this->db->where($exammarkCondition);
        //         $this->db->update('exammark', $exammarkUpData);
        //     }
        // }  




        //$this->session->set_flashdata('flash_message' , get_phrase('result_created')); 
        //redirect(base_url() . 'index.php?admin/dashboard/', 'refresh');
    } 
    
    function marksheet($student_id = -1){

        $group_id_name = "SELECT 
                            s.group_id,
                            cg.group_name
                            FROM section s
                            INNER JOIN enroll e ON e.section_id = s.section_id AND e.student_id = $student_id
                            inner join class_group cg on s.group_id = cg.id";
        $group_id_name = $this->db->query($group_id_name)->result_array();

        $group_id                = $group_id_name[0]['group_id'];
        $page_data['group_name'] = $group_id_name[0]['group_name'];
    	
    	$students	=	$this->db->get_where('student' , array('student_id'=>$student_id))->result_array();
    	$enroll	=	$this->db->get_where('enroll' , array('student_id'=>$student_id))->result_array();
    	$session_id = $enroll[0]['session_id'];
        $session_info = $this->db->get_where('session', array('componentId' => $session_id))->result_array();
        $section_id = $enroll[0]['section_id'];
        $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;

    	$student_name = $students[0]['name'];
    	$student_roll = $enroll[0]['roll'];

    	$class_id = $enroll[0]['class_id'];

        $condition = array(
            's.class_id'       => $class_id,
            's.group_id'       => $group_id,
            'r.group_position' => 1
        );
        $this->db->select('r.student_id,
                            r.gpa,
                            r.total_mark');
        $this->db->from('result r');
        $this->db->join('enroll e', 'r.student_id = e.student_id', 'inner');
        $this->db->join('section s', 'e.section_id = s.section_id', 'inner');
        $this->db->where($condition);
        $this->db->group_by('r.student_id');
        $highest_total = $this->db->get()->result_array();

        $page_data['highest_total'] = $highest_total[0]['total_mark'];
        $page_data['highest_gpa']   = $highest_total[0]['gpa'];

        $total_students_in_class = $this->db->get_where('enroll', array('session_id' => $session_id, 'class_id' => $class_id))->result_array();
        $page_data['total_students_in_class'] = count($total_students_in_class);
    	
    	$page_data['student'] = $students[0];
    		
    	//$page_data['courses']	=	$this->db->get_where('course' , array('class_id'=>$class_id, 'group_id' => $group_id))->result_array();

        $courseQuery = "SELECT 
                        c.course_id,
                        c.unique_code,
                        c.class_id,
                        c.group_id,
                        c.tittle
                        FROM
                        course c
                        LEFT JOIN course_order eo ON c.course_id = eo.course_id
                        WHERE c.class_id = '$class_id' AND c.group_id = '$group_id'
                        ORDER BY eo.id";
        $page_data['courses'] = $this->db->query($courseQuery)->result_array();



    	$classes = $this->db->get_where('class' , array('class_id'=>$class_id))->result_array();
    	
    	$page_data['class']= $classes[0];

        // echo "<pre>";
        // print_r($page_data['class']);
        // echo "</pre>";
        // exit();
    	
    	$class_name = $classes[0]['name'];
    		
    	$page_data['grades'] = $this->db->get('grade')->result_array();
    		
    	$page_data['exam'] = $this->db->get_where('exam')->result_array();
    	foreach ($page_data['exam'] as $exm):
    		$page_data['header'][$exm['exam_id']] =	$this->db->get_where('vgradesheetheader' , array('exam_id'=>$exm['exam_id'], 'class_id'=>$class_id))->result_array();
    		$marks = $this->db->get_where('vstudentcoursemark' , array('exam_id'=>$exm['exam_id'], 'student_id'=>$student_id))->result_array();
    			
    		foreach ($marks as $mark):
    			$page_data['marks'][$exm['exam_id']][$mark['course_id']][$mark['examtype_id']] = array('mark_obtained'=>$mark['mark_obtained'], 'grade'=>$mark['grade'], 'gradePoint'=>$mark['gradePoint'], 'highestGradePoint'=>$mark['highestGradePoint'],'highestMark'=>$mark['highestMark']);
    		endforeach;
    	endforeach;
    	
        $page_data['groupClass'] = $this->getGroupClass();
        //$page_data['grades'] = $this->db->get('grade')->result_array();

    	
    	$page_data['page_name']  =   'marksheet';
    	$page_data['page_title'] =   get_phrase('marksheet_for') . ' ' . $student_name;
    	$page_data['student_id'] =   $student_id;
        $page_data['student_code'] = $this->db->get_where('enroll', array('student_id' => $student_id))->row()->roll;
    	$page_data['class_id']   =   $class_id;
        $page_data['section_name'] = $section_name;
        $page_data['session_start'] = date('Y', strtotime($session_info[0]['start']));
        $page_data['session_end'] = date('y', strtotime($session_info[0]['end']));
        $page_data['session_name'] = $session_info[0]['uniqueCode'];
        
    	$this->load->view('backend/index', $page_data);
    	
    }

    function student_marksheet_print_view($student_id , $exam_id) {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $group_id_name = "SELECT 
                            s.group_id,
                            cg.group_name
                            FROM section s
                            INNER JOIN enroll e ON e.section_id = s.section_id AND e.student_id = $student_id
                            inner join class_group cg on s.group_id = cg.id";
        $group_id_name = $this->db->query($group_id_name)->result_array();

        $group_id                = $group_id_name[0]['group_id'];
        $page_data['group_name'] = $group_id_name[0]['group_name'];

        $class_id     = $this->db->get_where('enroll' , array(
            'student_id' => $student_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->row()->class_id;
        
        $students   =   $this->db->get_where('student' , array('student_id'=>$student_id))->result_array();
        $enroll =   $this->db->get_where('enroll' , array('student_id'=>$student_id))->result_array();
        $session_id = $enroll[0]['session_id'];
        $session_info = $this->db->get_where('session', array('componentId' => $session_id))->result_array();
        $section_id = $enroll[0]['section_id'];
        $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;

        $student_name = $students[0]['name'];
        $student_roll = $enroll[0]['roll'];

        $class_id = $enroll[0]['class_id'];

        $condition = array(
            's.class_id'       => $class_id,
            's.group_id'       => $group_id,
            'r.group_position' => 1
        );
        $this->db->select('r.student_id,
                            r.gpa,
                            r.total_mark');
        $this->db->from('result r');
        $this->db->join('enroll e', 'r.student_id = e.student_id', 'inner');
        $this->db->join('section s', 'e.section_id = s.section_id', 'inner');
        $this->db->where($condition);
        $this->db->group_by('r.student_id');
        $highest_total = $this->db->get()->result_array();

        $page_data['highest_total'] = $highest_total[0]['total_mark'];
        $page_data['highest_gpa']   = $highest_total[0]['gpa'];

        $total_students_in_class = $this->db->get_where('enroll', array('session_id' => $session_id, 'class_id' => $class_id))->result_array();
        $page_data['total_students_in_class'] = count($total_students_in_class);
        
        $page_data['student'] = $students[0];

        $query = "SELECT s.group_id FROM section s
                    INNER JOIN enroll e ON e.section_id = s.section_id AND e.student_id = $student_id";
        $group_id = $this->db->query($query)->row()->group_id;
            
        //$page_data['courses']   =   $this->db->get_where('course' , array('class_id'=>$class_id, 'group_id' => $group_id))->result_array();

        $courseQuery = "SELECT 
                        c.course_id,
                        c.unique_code,
                        c.class_id,
                        c.group_id,
                        c.tittle
                        FROM
                        course c
                        LEFT JOIN course_order eo ON c.course_id = eo.course_id
                        WHERE c.class_id = '$class_id' AND c.group_id = '$group_id'
                        ORDER BY eo.id";
        $page_data['courses'] = $this->db->query($courseQuery)->result_array();


        $classes = $this->db->get_where('class' , array('class_id'=>$class_id))->result_array();
        
        $page_data['class']= $classes[0];

        
        
        $class_name = $classes[0]['name'];
        $exam_id = $exam_id;
            
        $page_data['grades'] = $this->db->get('grade')->result_array();
            
        $page_data['exam'] = $this->db->get_where('exam', array('exam_id' => $exam_id))->result_array();


        $page_data['header'][$exam_id] = $this->db->get_where('vgradesheetheader' , array('exam_id'=>$exam_id, 'class_id'=>$class_id))->result_array();


        $marks = $this->db->get_where('vstudentcoursemark' , array('exam_id'=>$exam_id, 'student_id'=>$student_id))->result_array();

            foreach ($marks as $mark):
                $page_data['marks'][$exam_id][$mark['course_id']][$mark['examtype_id']] = array('mark_obtained'=>$mark['mark_obtained'], 'grade'=>$mark['grade'], 'gradePoint'=>$mark['gradePoint'], 'highestGradePoint'=>$mark['highestGradePoint'],'highestMark'=>$mark['highestMark']);
            endforeach;

        $page_data['student_code'] = $this->db->get_where('enroll', array('student_id' => $student_id))->row()->roll;

        $page_data['groupClass'] = $this->getGroupClass();

        $page_data['student_id'] =   $student_id;
        $page_data['student_roll'] = $student_roll;
        $page_data['class_id']   =   $class_id;
        $page_data['section_name'] = $section_name;
        $page_data['exam_id']    =   $exam_id;
        $page_data['session_start'] = date('Y', strtotime($session_info[0]['start']));
        $page_data['session_end'] = date('y', strtotime($session_info[0]['end']));
        $page_data['session_name'] = $session_info[0]['uniqueCode'];
        $this->load->view('backend/admin/marksheet_print_view', $page_data);
        //$page_data['page_name']  =   'marksheet_print_view';
        //$page_data['page_title'] =   get_phrase('marksheet_for') . ' ' . $student_name;
        //$this->load->view('backend/index', $page_data);
    }

    private function student_fee_set($session_id = '', $group_id = '', $class_id = '', $student_id = ''){

        $session_info = $this->db->get_where('session', array('componentId' => $session_id))->row_array();
        $start = strtotime($session_info['start']);
        $end = strtotime($session_info['end']);

        $sql = "SELECT fee_conf.class_id,fee_conf.session_id,fee_conf.item_id,fee_conf.amount, item.* 
                FROM fee_conf 
                INNER JOIN item ON item.componentId = fee_conf.item_id
                WHERE fee_conf.session_id = $session_id AND (fee_conf.class_id = $class_id and (fee_conf.group_id = $group_id or fee_conf.group_id = -1) or item.category2 = 'SCHOOL')";
                
            $fee_classwise = $this->db->query($sql)->result_array(); 
        
            foreach ($fee_classwise as $feeInfo) {
                $item_id = $feeInfo['componentId'];
                $itemExist = "select * from student_feeconfig
                            where sessionId = $session_id and studentId = $student_id and itemId = $item_id";
                $itemExist = $this->db->query($itemExist)->result_array();
                if(count($itemExist) == 0){
                        if($feeInfo['category3']=='ONCE') {
                        $dataFee['studentFeeName']  = $feeInfo['itemName'].'-'.date('M', $start).'-'.date('y', $start);
                        $dataFee['studentId']       = $student_id;
                        $dataFee['sessionId']       = $session_id;
                        $dataFee['itemId']          = $feeInfo['componentId'];
                        $dataFee['amount']          = $feeInfo['amount'];
                        $dataFee['month']           = date('F', $start);
                        $dataFee['year']            = date('Y', $end);
                            
                        $this->db->insert('student_feeconfig', $dataFee);
                    }

                        if($feeInfo['category3']=='SESSION') {
                        $session_start = $start;
                        $session_end = $end;

                        if($session_start < $session_end) {
                            $dataFee['studentFeeName']  = $feeInfo['itemName'].'-'.date('M', $session_start).'-'.date('y', $session_start);
                            $dataFee['studentId']       = $student_id;
                            $dataFee['sessionId']       = $session_id;
                            $dataFee['itemId']          = $feeInfo['componentId'];
                            $dataFee['amount']          = $feeInfo['amount'];
                            $dataFee['month']           = date('F', $session_start);
                            $dataFee['year']            = date('Y', $session_start);
                    
                            $this->db->insert('student_feeconfig', $dataFee);
                        }
                    }

                        if($feeInfo['category3']=='MONTHLY') {
                        $month_start = $start;
                        $month_end = $end;
                        while($month_start < $month_end) {
                            $dataFee['studentFeeName']  = $feeInfo['itemName'] .'-'. date('M', $month_start).'-'.date('y', $month_start);
                            $dataFee['studentId']       = $student_id;
                            $dataFee['sessionId']       = $session_id;
                            $dataFee['itemId']          = $feeInfo['componentId'];
                            $dataFee['amount']          = $feeInfo['amount'];
                            $dataFee['month']           = date('F', $month_start);
                            $dataFee['year']            = date('Y', $month_start);
                    
                            $this->db->insert('student_feeconfig', $dataFee);
                            $month_start = strtotime('+1 month', $month_start);
                        }
                    }
                }
                
            }
    }
	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $running_year = $this->db->get_where('settings' , array(
            'type' => 'running_year'
        ))->row()->description;
        if ($param1 == 'create') {
            $section_id = $this->input->post('section_id');
            $group_id   = $this->input->post('group_id');
            $class_id   = $this->input->post('class_id');
            $roll       = $this->input->post('roll');

            $data['student_code']   = generate_student_code($section_id, $group_id, $class_id, $roll);

			$data['name']           = $this->input->post('name');
			
			$data['fathername']     = $this->input->post('fathername');
			$data['fprofession']    = $this->input->post('fprofession');
			$data['fcontactno']     = $this->input->post('fcontactno');
			$data['mothername']     = $this->input->post('mothername');
			$data['mprofession']    = $this->input->post('mprofession');
			$data['mcontactno']     = $this->input->post('mcontactno');
			
			$data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
			$data['paddress']       = $this->input->post('paddress');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
            $data['password']       = sha1($this->input->post('password'));
            //$data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
            
			$pdata['name']     		= $data['fathername'];
			$pdata['profession']    = $data['fprofession'];
			$pdata['phone']    		= $data['fcontactno'];
			$pdata['address']       = $data['address'];
			$pdata['email']         = $data['email'];
			$pdata['password']      = $data['password'];
			
			$this->db->insert('parent', $pdata);
            $data['parent_id'] = $this->db->insert_id();
			
			$this->db->insert('student', $data);
            $student_id = $this->db->insert_id();

            $userData['campus_id'] 		= $this->input->post('campus'); 
            $userData['reference_id'] 	= $student_id;
            $userData['user_name'] 		= $data['student_code'];
            $userData['password'] 		= sha1($this->input->post('password'));
            $userData['user_type'] 		= 'STUDENT';
            
            $this->db->insert('user', $userData);
           
            $data2['student_id']     = $student_id;
            $data2['enroll_code']    = substr(md5(rand(0, 1000000)), 0, 7);
            $data2['class_id']       = $this->input->post('class_id');
            if ($this->input->post('section_id') != '') {
                $data2['section_id'] = $this->input->post('section_id');
                $group_id = $this->db->get_where('section', array('section_id' => $data2['section_id']))->row()->group_id;
            }
            
            $data2['roll']           = $this->input->post('roll');
            $data2['date_added']     = strtotime(date("Y-m-d H:i:s"));
            $data2['session_id']     = $running_year;
            $data2['year']           = $running_year;
			$this->db->insert('enroll', $data2);
			$enroll_id = $this->db->insert_id();
			
			$class_id = $this->input->post('class_id');
            
			$session_id = $this->db->get_where('enroll', array('enroll_id' => $enroll_id))->row()->session_id;


            $this->student_fee_set($session_id, $group_id, $class_id, $student_id);
			
			
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/student_add/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['student_code']   = $this->input->post('student_code');
			$data['name']           = $this->input->post('name');
			
			$data['fathername']     = $this->input->post('fathername');
			$data['fprofession']    = $this->input->post('fprofession');
			$data['fcontactno']     = $this->input->post('fcontactno');
			$data['mothername']     = $this->input->post('mothername');
			$data['mprofession']    = $this->input->post('mprofession');
			$data['mcontactno']     = $this->input->post('mcontactno');
			
			
            $data['birthday']       = $this->input->post('birthday');
            $data['sex']            = $this->input->post('sex');
            $data['address']        = $this->input->post('address');
			$data['paddress']       = $this->input->post('paddress');
            $data['phone']          = $this->input->post('phone');
            $data['email']          = $this->input->post('email');
			
			$password_old           = $this->input->post('password_old');
			$password               = $this->input->post('password');
            
			if ($password_old == $password){
				$data['password'] = $password_old;
				$userData['password'] = $password_old;
			}else{
				$data['password'] = sha1($password);
				$userData['password'] = sha1($password);
			}
			$userData['user_name']  = $this->input->post('student_code');
			
            //$data['parent_id']      = $this->input->post('parent_id');
            $data['dormitory_id']   = $this->input->post('dormitory_id');
            $data['transport_id']   = $this->input->post('transport_id');
			            
            $pdata['name']     		= $data['fathername'];
			$pdata['profession']    = $data['fprofession'];
			$pdata['phone']    		= $data['fcontactno'];
			$pdata['address']       = $data['address'];
			$pdata['email']         = $data['email'];
			$pdata['password']      = $data['password'];
			
			$parent_id = $this->db->get_where('student' , array('student_id'=>$param2))->row()->parent_id;
			
			
			
			$this->db->where('parent_id',$parent_id);
            $this->db->update('parent', $pdata);
			
			$data['parent_id'] 		= $parent_id;
			$this->db->where('student_id', $param2);
            $this->db->update('student', $data);
			
            $this->db->where('reference_id', $param2);
            $this->db->update('user', $userData);
            
            $data2['section_id']    =   $this->input->post('section_id');
            $data2['roll']          =   $this->input->post('roll');
            $running_year = $this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
					
            $this->db->where('student_id' , $param2);
            $this->db->where('year' , $running_year);
            $this->db->update('enroll' , array(
                'section_id' => $data2['section_id'] , 'roll' => $data2['roll']
            ));
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param2 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param3, 'refresh');
        } 
		
        if ($param2 == 'delete') {
            $this->db->where('student_id', $param3);
            $this->db->delete('student');
            $this->db->where('reference_id', $param3);
            $this->db->delete('user');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        }
    }

    // STUDENT PROMOTION
    function student_promotion($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');

        if($param1 == 'promote') {
            

            $running_year  =   $this->input->post('running_year');  
            $from_class_id =   $this->input->post('promotion_from_class_id'); 
            $students_of_promotion_class =   $this->db->get_where('enroll' , array(
                'class_id' => $from_class_id , 'year' => $running_year
            ))->result_array();

            $roll = $this->input->post('roll');
            $roll_array = 0;
            foreach($students_of_promotion_class as $row) {
                $enroll_data['enroll_code']     =   substr(md5(rand(0, 1000000)), 0, 7);
                $enroll_data['student_id']      =   $row['student_id'];

                

                $section_id = $this->db->get_where('enroll', array('student_id' => $row['student_id']))->row()->section_id;
                $group_id = $this->db->get_where('section', array('section_id' => $section_id))->row()->group_id;

                $enroll_data['class_id']        =   $this->input->post('promotion_status_'.$row['student_id']);
                $enroll_data['section_id']      = $this->input->post('section_id_to');
                $enroll_data['roll']            = $roll[$roll_array++];
                
                $enroll_data['session_id']      =   $this->input->post('promotion_year');
                $enroll_data['year']            =   $this->input->post('promotion_year');
                $enroll_data['date_added']      =   strtotime(date("Y-m-d H:i:s"));

                $this->db->insert('enroll' , $enroll_data);
                
                $enroll_id = $this->db->insert_id();
                $class_id = $enroll_data['class_id'];
                $session_id = $enroll_data['session_id'];
                $student_id = $enroll_data['student_id'];

                $this->student_fee_set($session_id, $group_id, $class_id, $student_id);
            } 
            $this->session->set_flashdata('flash_message' , get_phrase('new_enrollment_successfull'));
            redirect(base_url() . 'index.php?admin/student_promotion' , 'refresh');
        }
        $page_data['groupClass'] = $this->getGroupClass();

        $page_data['page_title']    = get_phrase('student_promotion');
        $page_data['page_name']  = 'student_promotion';
        $this->load->view('backend/index', $page_data);
    }

    function get_students_to_promote($from_class_id , $from_section_id , $to_class_id , $to_section_id, $running_year , $promotion_year)
    {
        $page_data['class_id_from']     =   $from_class_id;
        $page_data['section_id_from']   =   $from_section_id;
        $page_data['class_id_to']       =   $to_class_id;
        $page_data['section_id_to']     =   $to_section_id;
        $page_data['running_year']      =   $running_year;
        $page_data['promotion_year']    =   $promotion_year;

        $this->load->view('backend/admin/student_promotion_selector' , $page_data);
    }


     /****MANAGE PARENTS CLASSWISE*****/
    /* Last edited by Nishan*/
    function parent($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        			= $this->input->post('name');
            $data['email']       			= $this->input->post('email');
            $data['password']    			= sha1($this->input->post('password'));
            $data['phone']       			= $this->input->post('phone');
            $data['address']     			= $this->input->post('address');
            $data['profession']  			= $this->input->post('profession');
            $this->db->insert('parent', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {
            $data['name']                   = $this->input->post('name');
            $data['email']                  = $this->input->post('email');
            $data['phone']                  = $this->input->post('phone');
            $data['address']                = $this->input->post('address');
            $data['profession']             = $this->input->post('profession');
            $this->db->where('parent_id' , $param2);
            $this->db->update('parent' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
//        $parents   =   $this->db->get('parent' )->result_array();
        $this->db->select('parent.*, student.name as student_name, class.class_id, class.name as class_name');
        $this->db->from('parent');
        $this->db->join('student', 'parent.parent_id = student.parent_id');
        $this->db->join('enroll', 'student.student_id = enroll.student_id');
        $this->db->join('class', 'class.class_id = enroll.class_id');
        $this->db->order_by('class_id', 'ASC');
        $parents = $this->db->get()->result_array();
        
        $page_data['parentinfo'] = $parents;
        $page_data['classes'] = $class_name;
        $page_data['page_title'] 	= get_phrase('all_parents');
        $page_data['page_name']  = 'parent';
        $this->load->view('backend/index', $page_data);
    }
	
    
    /****MANAGE TEACHERS*****/
    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
        	$data['campus_id']   = $this->input->post('campus');
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = sha1($this->input->post('password'));
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            
            $userData['reference_id'] 	= $teacher_id;
            $userData['user_name'] 		= $this->input->post('email');
            $userData['password'] 		= sha1($this->input->post('password'));
            $userData['user_type'] 		= 'TEACHER';
            
            $this->db->insert('user', $userData);
            $user_id = $this->db->insert_id();

            $salaryData['user_id'] = $user_id;
            $this->db->insert('employee_salary', $salaryData);
            
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        if ($param1 == 'do_update') {
        	$data['campus_id']   = $this->input->post('campus');
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $password_old        = $this->input->post('password_old');
			$password            = $this->input->post('password');
            
			if ($password_old == $password){
				$data['password'] = $password_old;
				$userData['password'] = $password_old;
			}else{
				$data['password'] = sha1($password);
				$userData['password'] = sha1($password);
			}
            $this->db->where('teacher_id', $param2);
            $this->db->update('teacher', $data);
            
            $userData['user_name'] 		= $this->input->post('email');
            $this->db->where('reference_id', $param2);
            $this->db->update('user', $userData);
            
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {

            $user_id = $this->db->get_where('user', array('reference_id' => $param2, 'user_type' => 'TEACHER'))->row()->user_id;
            $this->db->where('user_id', $user_id);
            $this->db->delete('employee_salary');

            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');

            $this->db->where('reference_id', $param2);
            $this->db->delete('user');

            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['title']   = $this->input->post('name');
            $data['unique_code']	= $this->input->post('name').'-1';
            $data['class_id']   	= $this->input->post('class');
            $data['group_id'] 		= $this->input->post('group');
            $data['is_optional']    = $this->input->post('is_optional');
            $this->db->insert('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']       	= $this->input->post('name');
            $data['class_id']   	= $this->input->post('class_id');
            $data['teacher_id'] 	= $this->input->post('group');
            $data['is_optional']    = $this->input->post('is_optional');
            $data['year']      		= $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('subject_id', $param2);
            $this->db->delete('subject');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/subject/'.$param3, 'refresh');
        }
		$page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    function course_assigned($param1 = '', $param2 = '' , $param3 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');   	
    		
    		if ($param1 == 'create') {
    			$data['student_id']	= $this->input->post('student');
    			$data['class_id']   	= $this->input->post('class');
    			$data['course_id'] 		= $this->input->post('course');
    			$data['session_id']    = $this->input->post('session');
    			$this->db->insert('studentcourseassignment', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    			redirect(base_url() . 'index.php?admin/course_assigned/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
    			$data['student_id']	= $this->input->post('student');
    			$data['class_id']   	= $this->input->post('class');
    			$data['course_id'] 		= $this->input->post('course');
    			$data['session_id']    = $this->input->post('session');
    			$this->db->where('sca_id', $param2);
    			$this->db->update('studentcourseassignment', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/course_assigned/', 'refresh');
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('sca_id', $param2);
    			$this->db->delete('studentcourseassignment');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/course_assigned/', 'refresh');
    		}
    	$page_data['classInfo'] = $this->db->get('class')->result_array();	
    	$page_data['courseInfo'] = $this->db->get('course')->result_array();
    	$page_data['sessionInfo'] = $this->db->get('session')->result_array();
    	$page_data['assignedCourse'] = $this->db->get('studentcourseassignment')->result_array();

        $page_data['groupClass']  = $this->getGroupClass();
        $page_data['running_session']  = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

    	$page_data['page_name']  = 'course_assigned';
    	$page_data['page_title'] = get_phrase('course_assigned');
    	$this->load->view('backend/index', $page_data);
    }
   
   /*
    function course_student($param1 = '', $param2 = '' , $param3 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	if ($param1 == 'create') {
    		$data['class_id'] 		= $this->input->post('class_id');
    		
    		$data['course_id'] 		= $this->input->post('course_id');
    		$data['session_id'] 	= $this->input->post('session_id');
    		$data['teacher_id']		= $this->input->post('teacher_id');
    		$this->db->insert('courseteacherassignment', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
    	if ($param1 == 'do_update') {
    		$data['course_id']  = $this->input->post('course');
    		$data['class_id'] 	= $this->input->post('class');
    		$data['session_id'] = $this->input->post('session');
    		$data['teacher_id'] = $this->input->post('teacher');
    		
    		$this->db->where('cta_id', $param2);
    		$this->db->update('courseteacherassignment', $data);
    		
    		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
    	if($param1 == 'delete') {
    		$this->db->where('cta_id', $param2);
    		$this->db->delete('courseteacherassignment');
    		$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
    	$class_id = -1;
    	if($this->input->post('class_id'))
    		$class_id = $this->input->post('class_id');
    		$page_data['class_id'] = $class_id;
    	
			$sectionId=-1;
			if($sectionId = $this->input->post('sectionId'))
			$sectionId = $this->input->post('sectionId');
    		$page_data['sectionId'] = $sectionId;
			
		
    		$session_id = -1;
    		if($this->input->post('session_id'))
    			$session_id = $this->input->post('session_id');
    			$page_data['session_id'] = $session_id;
    	
    			$classes = $this->db->get('class')->result_array();
    			$page_data['allclasses'] = array('-1'=> 'Select One');
    			foreach ($classes as $class)
    				$page_data['allclasses'][$class['class_id']] = $class['name'];
				
				$sections = $this->db->get_where('section', array('class_id' => $class_id))->result_array();
    				$page_data['allsections'] = array(''=>'Select one');
    				foreach($sections as $row):
    							$page_data['allsections'][$row['section_id']] = $row['name'];
    							endforeach;
    							$page_data['sectionId'] = $sectionId;
				
				$studentId = -1;
				$students = $this->db->get_where('v_student_class', array('class_id' => $class_id, 'section_id' => $sectionId))->result_array();
    			$page_data['allstudents'] = array(''=>'Select one');
    				foreach($students as $row):
    							$page_data['allstudents'][$row['student_id']] = $row['name'];
    							endforeach;
    							$page_data['studentId'] = $studentId;
								
				
    				if($this->input->post('operation')){
    					if($this->input->post('operation') == 'update'){
    	
    						$cnt = $this->input->post('coursecount');
    	
    						for($i=1;$i<=$cnt;$i++){
    							$dataToSave = array();
    							$dataToSave['class_id'] 		= $this->input->post('class_id');
    							$dataToSave['course_id'] = $this->input->post('course_'.$i);
    							$dataToSave['session_id'] = $session_id;
    								
    							$this->db->where('course_id', $dataToSave['course_id']);
    							$this->db->where('session_id', $dataToSave['session_id']);
    							$this->db->delete('courseconfig');
    								
    							if($this->input->post('selectedCourse_'.$i)){
    								$dataToSave['teacher_id'] = $this->input->post('teacher_id_'.$i);
    								$this->db->insert('courseteacherassignment', $dataToSave);
    							}
    						}
    					}
    				}
    				
					
								
								
					
					
    				$courses = $this->db->get_where('course', array('class_id'=>$class_id))->result_array();
    	
    				$page_data['courses'] = $courses;
    	
    				$sessions = $this->db->get('session')->result_array();
    				$page_data['sessions'] = array('-1'=> 'Select One');
    				foreach ($sessions as $session){
    					$page_data['sessions'][$session['session_id']] = $session['start'].' - '.$session['end'];
    				}
    				$teachers = $this->db->get('teacher')->result_array();
    				$page_data['teachers'] = array('-1'=> 'Select One');
    				foreach ($teachers as $teacher){
    					$page_data['teachers'][$teacher['teacher_id']] = $teacher['name'];
    				}
    				$courseTeacher = $this->db->get_where('courseteacherassignment', array('session_id'=>$session_id))->result_array();
    				$page_data['courseTeacher'] = array();
    				foreach ($courseTeacher as $ct){
    					$page_data['courseTeacher'][$ct['course_id']] = $ct['teacher_id'];
    				}
    	
    				//$page_data['courseconfigs']   = $this->db->get('courseconfig')->result_array();
    				
    	
    	
    		
    		$page_data['teacherInfo'] = $this->db->get('courseteacherassignment')->result_array();
    		$page_data['page_name']  = 'student_assigned';
    		$page_data['page_title'] = get_phrase('student_assigned');
    		$this->load->view('backend/index', $page_data);
    }
	
	*/
	
    function course_teacher($param1 = '', $param2 = '' , $param3 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	if ($param1 == 'create') {
    		$data['class_id'] 		= $this->input->post('class_id');
    		
    		$data['course_id'] 		= $this->input->post('course_id');
    		$data['session_id'] 	= $this->input->post('session_id');
    		$data['teacher_id']		= $this->input->post('teacher_id');
    		$this->db->insert('courseteacherassignment', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
    	if ($param1 == 'do_update') {
    		$data['course_id']  = $this->input->post('course');
    		$data['class_id'] 	= $this->input->post('class');
    		$data['session_id'] = $this->input->post('session');
    		$data['teacher_id'] = $this->input->post('teacher');
    		
    		$this->db->where('cta_id', $param2);
    		$this->db->update('courseteacherassignment', $data);
    		
    		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
    	if($param1 == 'delete') {
    		$this->db->where('cta_id', $param2);
    		$this->db->delete('courseteacherassignment');
    		$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    		redirect(base_url() . 'index.php?admin/course_teacher/', 'refresh');
    	}
        $class_id = -1;
    	$group_id = -1;
    	if($this->input->post('class_id'))
    		$class_id = $this->input->post('class_id');
    		$page_data['class_id'] = $class_id;
        if($this->input->post('group_id'))
            $group_id = $this->input->post('group_id');
            $page_data['group_id'] = $group_id;
    	
    		$session_id = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
    		if($this->input->post('session_id'))
    			$session_id = $this->input->post('session_id');
    			$page_data['session_id'] = $session_id;
    	
    			$classes = $this->db->get('class')->result_array();
    			$page_data['allclasses'] = array('-1'=> 'Select One');
    			foreach ($classes as $class)
    				$page_data['allclasses'][$class['class_id']] = $class['name'];

                $groups = $this->db->get('class_group')->result_array();
                $page_data['allgroups'] = array('-1'=> 'Select One');
                foreach ($groups as $group)
                    $page_data['allgroups'][$group['id']] = $group['group_name'];
    			
    			$sessions = $this->db->get('session')->result_array();
    			$page_data['sessions'] = array('-1'=> 'Select One');
    			foreach ($sessions as $session){
    					$page_data['sessions'][$session['componentId']] = $session['start'].' - '.$session['end'];
    				}
    				
    	
    				if($this->input->post('operation')){
    					if($this->input->post('operation') == 'update'){
    	
    						$cnt = $this->input->post('coursecount');
    	
    						for($i=1;$i<=$cnt;$i++){
    							$dataToSave = array();
                                $dataToSave['class_id']         = $this->input->post('class_id');
    							$dataToSave['course_id'] = $this->input->post('course_'.$i);
    							$dataToSave['session_id'] = $session_id;
    							
    							$this->db->where('course_id', $dataToSave['course_id']);
    							$this->db->where('session_id', $dataToSave['session_id']);
    							$this->db->delete('courseteacherassignment');
    								
    							if($this->input->post('selectedCourse_'.$i)){
    								$dataToSave['teacher_id'] = $this->input->post('teacher_id_'.$i);
    								$this->db->insert('courseteacherassignment', $dataToSave);
    							}
    						}
    					}
    				}
    	
    				$courses = $this->db->get_where('course', array('class_id'=>$class_id, 'group_id' => $group_id))->result_array();
    	
    				$page_data['courses'] = $courses;
    	
    				
    				$teachers = $this->db->get('teacher')->result_array();
    				$page_data['teachers'] = array('-1'=> 'Select One');
    				foreach ($teachers as $teacher){
    					$page_data['teachers'][$teacher['teacher_id']] = $teacher['name'];
    				}
    				$courseTeacher = $this->db->get_where('courseteacherassignment', array('session_id'=>$session_id))->result_array();
    				$page_data['courseTeacher'] = array();
    				foreach ($courseTeacher as $ct){
    					$page_data['courseTeacher'][$ct['course_id']] = $ct['teacher_id'];
    				}
    	
    		$page_data['teacherInfo'] = $this->db->get('courseteacherassignment')->result_array();

            $page_data['groupClass']  = $this->getGroupClass();
    		$page_data['page_name']  = 'teacher_assigned';
    		$page_data['page_title'] = get_phrase('teacher_assigned');
    		$this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE CLASSES*****/
    /****Added Campus to Class by Nishan*****/
    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') 
        {
        	$data['campus_id'] = $this->input->post('campus');
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $this->db->insert('class', $data);
            $class_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        if ($param1 == 'do_update') 
        {
        	$data['campus_id'] = $this->input->post('campus');      	
        	$data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $this->db->where('class_id', $param2);
            $this->db->update('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class', array(
                'class_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('class');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        $this->db->select('class.*, campus.campus_name');
        $this->db->from('class');
        $this->db->join('campus', 'class.campus_id = campus.id');
        $this->db->order_by('class_id', 'ASC');
        $classinfo = $this->db->get()->result_array(); 
        $campuslist = $this->db->get('campus')->result_array();

        $page_data['classinfo']    = $classinfo;
        $page_data['campuslist'] = $campuslist;

        $page_data['groupClass'] = $this->getGroupClass();
        $page_data['default_campus'] = $this->db->get_where('settings', array('type' => 'default_campus'))->row()->description;

        $page_data['page_name']  = 'class';
        $page_data['page_title'] = "Manage ". $page_data['groupClass'][0][value];
        $this->load->view('backend/index', $page_data);
    }

    // ACADEMIC SYLLABUS
    function academic_syllabus($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;
        $page_data['class_id']   = $class_id;
        $page_data['groupClass']   = $this->getGroupClass();


        $page_data['page_name']  = 'academic_syllabus';
        $page_data['page_title'] = get_phrase('academic_syllabus');
        
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

        $data['file_name'] = str_replace(' ', '_', $_FILES['file_name']['name']);
        $this->db->insert('academic_syllabus', $data);
        $this->session->set_flashdata('flash_message' , get_phrase('syllabus_uploaded'));
        redirect(base_url() . 'index.php?admin/academic_syllabus/' . $data['class_id'] , 'refresh');

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

    /****MANAGE SECTIONS*****/
    function section($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '') {
            $class_id   =   $this->db->get('class')->first_row()->class_id;
        }
        $classes = $this->db->get('class')->result_array();
		$sections = $this->db->get_where('section' , array('class_id' => $class_id))->result_array();
		
        
        $page_data['campus_id']   = $campus_id;
        $page_data['class_id']   = $class_id;
        $page_data['classes']    = $classes;
        $page_data['sections']   = $sections;

        $page_data['groupClass']   = $this->getGroupClass();


        $page_data['page_name']  = 'section';
        $page_data['page_title'] = get_phrase('manage_sections');
        $this->load->view('backend/index', $page_data);    
    }

    function sections($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['group_id']   =   $this->input->post('group_id');
            
            $this->db->insert('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'edit') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['group_id']   =   $this->input->post('group_id');
            $this->db->where('section_id' , $param2);
            $this->db->update('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('section_id' , $param2);
            $this->db->delete('section');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/section' , 'refresh');
        }
    }
   
    /*Get Class, Section, Student for Private Message*/
    
    function get_class()   
    {  	
    	$param_id = $this->input->post(nodes);
    	
    	$type = substr($param_id, 0,3);
    	$id = substr($param_id, 3);
		
		
    	$result=array();
    	/*CAM for campus*/
    	if($type == 'CAM') {
    		$result = array( 
	    				array(
		    				'id' => 'TEA'.$id,
		    				'title' => 'TEACHER',
		    				'hasChildren' => 'true'    			
	    					),
						array(
							'id' => 'PAR'.$id,
							'title' => 'PARENTS',
							'hasChildren' => 'true'
						),
	    				array(
	    						'id' => 'STU'.$id,
	    						'title' => 'STUDENT',
	    						'hasChildren' => 'true'
    				));
    	}

    	/*TEA for type teacher*/
    	if($type == 'TEA') {
    		$this->db->select("concat('TES', teacher.teacher_id) as id, concat(teacher.name,' - ',teacher.phone) as title", FALSE);
    		$this->db->from('teacher');
    		$this->db->where('teacher.campus_id', $id);
    		$result = $this->db->get()->result_array();
    	}
        
    	/*PAR for type parents*/
    	if($type == 'PAR') {
    		$this->db->select("concat('PCL',class.class_id) as id, class.name as title,'true' as hasChildren", FALSE);
    		$this->db->from('class');
    		$this->db->where('campus_id', $id);
    		$result = $this->db->get()->result_array();
    	}
    	/*PCL for type class wise parents*/
    	if($type == 'PCL') {
    		$this->db->select("concat('PSE',section.section_id) as id, section.name as title,'true' as hasChildren", FALSE);
    		$this->db->from('section');
    		$this->db->where('class_id', $id);
    		$result = $this->db->get()->result_array();
    	}
    	/*PSE for section wise parent*/
    	if($type == 'PSE') {
    		$this->db->select("concat('PAS',parent.parent_id) as id, concat(parent.name,' - ',parent.phone) as title, 'false' as hasChildren", FALSE);
    		$this->db->from('enroll');
    		$this->db->join('student', 'enroll.student_id=student.student_id');
    		$this->db->join('parent', 'student.parent_id=parent.parent_id');
    		$this->db->where('enroll.section_id', $id);
    		$result = $this->db->get()->result_array();
    	}
    	/*STU for type student*/
    	if($type == 'STU') {
    		$this->db->select("concat('CLS',class.class_id) as id, class.name as title,'true' as hasChildren", FALSE);
    		$this->db->from('class');
    		$this->db->where('campus_id', $id);
    		$result = $this->db->get()->result_array();	    	
    	}
    	/*CLS for class wise student*/
    	if($type == 'CLS') {
    		$this->db->select("concat('SEC',section.section_id) as id, section.name as title,'true' as hasChildren", FALSE);
    		$this->db->from('section');
    		$this->db->where('class_id', $id);
    		$result = $this->db->get()->result_array();
    	}
    	/*STS is for a specific student under a section*/
    	if($type == 'SEC') {
    		$this->db->select("concat('STS',student.student_id) as id, concat(student.name,' - ',student.phone) as title, 'false' as hasChildren", FALSE);
    		$this->db->from('enroll');
    		$this->db->join('student', 'enroll.student_id=student.student_id');
    		$this->db->where('enroll.section_id', $id);
    		$result = $this->db->get()->result_array();
    	}
    	$hierarchy = array();    	
    	$hierarchy[$param_id]['nodes']=$result;    	
    	echo json_encode($hierarchy, JSON_PRETTY_PRINT);  
    	
    }


    function get_section_group($class_id)
    {
    	$page_data['class_id'] = $class_id;
    	$this->load->view('backend/admin/section_group_selector' , $page_data);
    }
    
    function get_campus_class($campus_id)
    {
    	$classes = $this->db->get_where('class' , array(
    			'campus_id' => $campus_id
    	))->result_array();
    	echo '<option value="">' . 'Select' . '</option>';
    	foreach ($classes as $row) {
    		
    		echo '<option value="' . $row['class_id'] . '">' . $row['name'] . '</option>';
    	}
    }
    function get_campus_class_for_message($campus_id)
    {
    	$classes = $this->db->get_where('class' , array(
    			'campus_id' => $campus_id
    	))->result_array();
    	foreach ($classes as $row) {
    		echo '<input type="'.'checkbox"'. 'value="' . $row['class_id'] . '">' . $row['name'].'<br>' ;
    	}
    }

    function get_class_sections($class_id)
    {
        $sections = $this->db->get_where('section' , array(
            'class_id' => $class_id
        ))->result_array();
        echo '<option value="">' . 'select'. '</option>';
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name']. '</option>';
        }
    }

    function get_section_with_cls_group($class_id, $group_id)
    {
        $sections = $this->db->get_where('section' , array(
            'class_id' => $class_id,
            'group_id' => $group_id
        ))->result_array();
        echo '<option value="">' . 'select'. '</option>';
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name']. '</option>';
        }
    }
    
    function get_class_course($class_id)
    {
    	$course = $this->db->get_where('course' , array(
    			'class_id' => $class_id,'is_optional' =>'1'
    	))->result_array();
    	foreach ($course as $row) {
    		echo '<option value="' . $row['course_id'] . '">' . $row['tittle']. '</option>';
    	}
    }

    function get_group_course($group_id)
    {
        $course = $this->db->get_where('course' , array(
                'group_id' => $group_id))->result_array();
        foreach ($course as $row) {
            echo '<option value="' . $row['course_id'] . '">' . $row['tittle']. '</option>';
        }
    }

    function get_course_with_group_class($class_id, $group_id)
    {
        $course = $this->db->get_where('course' , array(
                'class_id' => $class_id,'group_id' =>$group_id
        ))->result_array();
        foreach ($course as $row) {
            echo '<option value="' . $row['course_id'] . '">' . $row['tittle']. '</option>';
        }
    }
    
    function get_class_group() 
    {
    	$groups = $this->db->get_where('class_group')->result_array();
        echo '<option value="">'.'select'.'</option>';
        foreach ($groups as $group) {
            echo '<option value="'. $group['id'] .'">'. $group['group_name'] .'</option>';
        }
    }
    
    function get_group_sections($group_id, $class_id)
    {
        $iscommon = $this->db->get_where('class_group', array('id' => $group_id))->row()->group_name;
        if($iscommon == 'Common'){
                $sections = $this->db->get_where('section' , array('class_id' => $class_id
            ))->result_array();
        }else{
                $sections = $this->db->get_where('section' , array(
                    'group_id' => $group_id, 'class_id' => $class_id
            ))->result_array();
        }

    	
    	echo '<option value="">' . 'Select' . '</option>';
    	foreach ($sections as $row) {
    		echo '<option value="' . $row['section_id'] . '">' . $row['name'].'</option>';
    	}
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
        	echo '<option value="">'.'select item'.'</option>';
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_class_students($class_id)
    {
        $students = $this->db->get_where('enroll' , array(
            'class_id' => $class_id , 'session_id' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->result_array();
        foreach ($students as $row) {
            $name = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
            echo '<option value="' . $row['student_id'] . '">' . $name . '</option>';
        }
    }

    function get_class_students_mass($class_id)
    {
        $students = $this->db->get_where('enroll' , array(
            'class_id' => $class_id , 'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ))->result_array();
        echo '<div class="form-group">
                <label class="col-sm-3 control-label">' . get_phrase('students') . '</label>
                <div class="col-sm-9">';
        foreach ($students as $row) {
             $name = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
            echo '<div class="checkbox">
                    <label><input type="checkbox" class="check" name="student_id[]" value="' . $row['student_id'] . '">' . $name .'</label>
                </div>';
        }
        echo '<br><button type="button" class="btn btn-default" onClick="select()">'.get_phrase('select_all').'</button>';
        echo '<button style="margin-left: 5px;" type="button" class="btn btn-default" onClick="unselect()"> '.get_phrase('select_none').' </button>';
        echo '</div></div>';
    }



    /****MANAGE EXAMS*****/
    function exam($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            $data['year']    = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        if ($param1 == 'edit' && $param2 == 'do_update') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            $data['year']    = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->where('exam_id', $param3);
            $this->db->update('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('exam', array(
                'exam_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exam');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        $page_data['exams']      = $this->db->get('exam')->result_array();
        $page_data['page_name']  = 'exam';
        $page_data['page_title'] = get_phrase('manage_exam');
        $this->load->view('backend/index', $page_data);
    }
	
	

    /****** SEND EXAM MARKS VIA SMS ********/
    function exam_marks_sms($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_sms') {

            $exam_id    =   $this->input->post('exam_id');
            $class_id   =   $this->input->post('class_id');
            $receiver   =   $this->input->post('receiver');

            // get all the students of the selected class
            $students = $this->db->get_where('enroll' , array(
                'class_id' => $class_id,
                    'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
            ))->result_array();
            // get the marks of the student for selected exam
            foreach ($students as $row) {
                if ($receiver == 'student')
                    $receiver_phone = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->phone;
                if ($receiver == 'parent') {
                    $parent_id =  $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->parent_id;
                    if($parent_id != '') {
                        $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $row['parent_id']))->row()->phone;
                    }
                }
                

                $this->db->where('exam_id' , $exam_id);
                $this->db->where('student_id' , $row['student_id']);
                $marks = $this->db->get_where('mark' , array('year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description))->result_array();
                $message = '';
                foreach ($marks as $row2) {
                    $subject       = $this->db->get_where('subject' , array('subject_id' => $row2['subject_id']))->row()->name;
                    $mark_obtained = $row2['mark_obtained'];  
                    $message      .= $row2['student_id'] . $subject . ' : ' . $mark_obtained . ' , ';
                    
                }
                // send sms

                $time = date("Y-m-d");

                $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.exammark.title'))->row()->value;

                $this->sms_model->send_sms( $message , $receiver_phone, $time,  $msgTittle);
            }
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'index.php?admin/exam_marks_sms' , 'refresh');
        }
                
        $page_data['page_name']  = 'exam_marks_sms';
        $page_data['page_title'] = get_phrase('send_marks_by_sms');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE EXAM MARKS*****/
    function marks2($exam_id = '', $class_id = '', $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
                redirect(base_url() . 'index.php?admin/marks2/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id'], 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
                redirect(base_url() . 'index.php?admin/marks2/', 'refresh');
            }
        }
        if ($this->input->post('operation') == 'update') {
            $students = $this->db->get_where('enroll' , array('class_id' => $class_id , 'year' => $running_year))->result_array();
            foreach($students as $row) {
                $data['mark_obtained'] = $this->input->post('mark_obtained_' . $row['student_id']);
                $data['comment']       = $this->input->post('comment_' . $row['student_id']);
                
                $this->db->where('mark_id', $this->input->post('mark_id_' . $row['student_id']));
                $this->db->update('mark', array('mark_obtained' => $data['mark_obtained'] , 'comment' => $data['comment']));
            }
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/marks2/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'), 'refresh');
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['subject_id'] = $subject_id;
        
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks2';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_manage()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  =   'marks_manage';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }

    function marks_manage_view($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
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
        if ($this->session->userdata('admin_login') != 1)
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
        redirect(base_url() . 'index.php?admin/marks_manage_view/' . $data['exam_id'] . '/' . $data['class_id'] . '/' . $data['section_id'] . '/' . $data['subject_id'] , 'refresh');
        
    }

    function marks_update($exam_id = '' , $class_id = '' , $section_id = '' , $subject_id = '')
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
        redirect(base_url().'index.php?admin/marks_manage_view/'.$exam_id.'/'.$class_id.'/'.$section_id.'/'.$subject_id , 'refresh');
    }

    function marks_get_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/marks_get_subject' , $page_data);
    }

    // TABULATION SHEET
    function tabulation_sheet($exam_id = '' , $class_id = '', $group_id = '', $section_id = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['group_id']   = $this->input->post('group_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['section_id'] = $this->input->post('section_id');



            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['group_id'] > 0 && $page_data['section_id'] > 0) {
                redirect(base_url() . 'index.php?admin/tabulation_sheet/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['group_id'] . '/' . $page_data['section_id'], 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose exam, group, class, section');
                redirect(base_url() . 'index.php?admin/tabulation_sheet/', 'refresh');
            }
        }
		$termId = -1;
		$exams = $this->db->get('exam')->result_array();
    	$page_data['terms'] = array(''=>'Select one');
    	foreach($exams as $row):
    			$page_data['terms'][$row['exam_id']] = $row['name'];
    	endforeach;
    	$page_data['termId'] = $termId;
		
		
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['group_id']   = $group_id;
        $page_data['section_id']   = $section_id;
        
        $page_data['page_info'] = 'Exam marks';

        $page_data['groupClass'] = $this->getGroupClass();
        
        $page_data['page_name']  = 'tabulation_sheet';
        $page_data['page_title'] = get_phrase('tabulation_sheet');
        $this->load->view('backend/index', $page_data);
    
    }

    function tabulation_sheet_print_view($exam_id , $class_id , $group_id , $section_id ) {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['class_id'] = $class_id;
        $page_data['exam_id']  = $exam_id;
        $page_data['group_id']  = $group_id;
        $page_data['section_id']  = $section_id;
        $this->load->view('backend/admin/tabulation_sheet_print_view' , $page_data);
    }
    
    
    /****MANAGE GRADES*****/
    function grade($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            $this->db->insert('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            
            $this->db->where('grade_id', $param2);
            $this->db->update('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('grade', array(
                'grade_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('grade_id', $param2);
            $this->db->delete('grade');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        $page_data['grades']     = $this->db->get('grade')->result_array();
        $page_data['page_name']  = 'grade';
        $page_data['page_title'] = get_phrase('manage_grade');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['class_id']       = $this->input->post('class_id');
            if($this->input->post('section_id') != '') {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/class_routine_add/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['class_id']       = $this->input->post('class_id');
            if($this->input->post('section_id') != '') {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['subject_id']     = $this->input->post('subject_id');
            $data['time_start']     = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']       = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['time_start_min'] = $this->input->post('time_start_min');
            $data['time_end_min']   = $this->input->post('time_end_min');
            $data['day']            = $this->input->post('day');
            $data['year']           = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->where('class_routine_id', $param2);
            $this->db->update('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/class_routine_view/' . $data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                'class_routine_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $class_id = $this->db->get_where('class_routine' , array('class_routine_id' => $param2))->row()->class_id;
            $this->db->where('class_routine_id', $param2);
            $this->db->delete('class_routine');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/class_routine_view/' . $class_id, 'refresh');
        }
        
    }

    function class_routine_add()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['groupClass'] = $this->getGroupClass();
        $page_data['page_name']  = 'class_routine_add';
        $page_data['page_title'] = get_phrase('add_class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_view($class_id)
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'class_routine_view';
        $page_data['class_id']  =   $class_id;
        $page_data['page_title'] = get_phrase('class_routine');
        $this->load->view('backend/index', $page_data);
    }

    function class_routine_print_view($class_id , $section_id)
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['class_id']   =   $class_id;
        $page_data['section_id'] =   $section_id;
        $this->load->view('backend/admin/class_routine_print_view' , $page_data);
    }

    function get_class_section_subject($class_id)
    {
        $page_data['class_id'] = $class_id;
        $this->load->view('backend/admin/class_routine_section_subject_selector' , $page_data);
    }

    function section_subject_edit($class_id , $class_routine_id)
    {
        $page_data['class_id']          =   $class_id;
        $page_data['class_routine_id']  =   $class_routine_id;
        $this->load->view('backend/admin/class_routine_section_subject_edit' , $page_data);
    }

    function manage_attendance()
    {

        if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');


        $page_data['classes'] = $this->db->get('class')->result_array();
        $page_data['groupClass'] = $this->getGroupClass();
        
        $page_data['page_name']  =  'manage_attendance';
        $page_data['page_title'] =  get_phrase('manage_attendance_of_class');

        $this->load->view('backend/index', $page_data);
    }

    function manage_attendance_view($group_id = '', $class_id = '' , $section_id = '', $course_id = '', $timestamp = '')
    {
        if($this->session->userdata('admin_login')!=1)
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
        $page_data['$groupClass'] = $this->getGroupClass();

        $page_data['page_name'] = 'manage_attendance_view';
        $page_data['page_title'] = get_phrase('manage_attendance_of') .' '.$group_name. ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
        $this->load->view('backend/index', $page_data);
    }
    function get_section($class_id) {
          $page_data['class_id'] = $class_id; 
          $this->load->view('backend/admin/manage_attendance_section_holder' , $page_data);
    }
    function attendance_selector()
    {


        $data['group_id']   = $this->input->post('group_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['year']       = $this->input->post('year');
        $data['timestamp']  = strtotime($this->input->post('timestamp'));
        

        // $this->manage_attendance_view($data['group_id'], $data['class_id'],$data['section_id'], $data['course_id'], $data['timestamp']);
        redirect(base_url().'index.php?admin/manage_attendance_view/'.$data['group_id'].'/'.$data['class_id'].'/'.$data['section_id'].'/'.$data['course_id'].'/'.$data['timestamp'],'refresh');

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
        redirect(base_url().'index.php?admin/coursewise_attendance_report_view/'.$page_data ['start_date'].'/'.$page_data ['end_date'].'/'.$page_data['group_id'].'/'.$page_data['course_id'],'refresh');

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
        redirect(base_url().'index.php?admin/manage_attendance_view/'.$group_id.'/'.$class_id.'/'.$section_id.'/'.$course_id.'/'.$timestamp , 'refresh');
    }
	
	/****** DAILY ATTENDANCE *****************/
	function manage_attendance2($date='',$month='',$year='',$class_id='' , $section_id = '' , $session = '')
	{
		if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');

        $active_sms_service = $this->db->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;
        $running_year = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;

		
		if($_POST)
		{
			// Loop all the students of $class_id
            $this->db->where('class_id' , $class_id);
            if($section_id != '') {
                $this->db->where('section_id' , $section_id);
            }
            //$session = base64_decode( urldecode( $session ) );
            $this->db->where('year' , $session);
            $students = $this->db->get('enroll')->result_array();
            foreach ($students as $row)
            {
                $attendance_status  =   $this->input->post('status_' . $row['student_id']);

                $this->db->where('student_id' , $row['student_id']);
                $this->db->where('date' , $date);
                $this->db->where('year' , $year);
                $this->db->where('class_id' , $row['class_id']);
                if($row['section_id'] != '' && $row['section_id'] != 0) {
                    $this->db->where('section_id' , $row['section_id']);
                }
                $this->db->where('session' , $session);

                $this->db->update('attendance' , array('status' => $attendance_status));

                if ($attendance_status == 2) {

                    if ($active_sms_service != '' || $active_sms_service != 'disabled') {
                        $student_name   = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
                        $parent_id      = $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->parent_id;
                        $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $parent_id))->row()->phone;
                        $message        = 'Your child' . ' ' . $student_name . 'is absent today.';

                        $time = date("Y-m-d");

                        $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.absent.title'))->row()->value;

                        $this->sms_model->send_sms($message,$receiver_phone, $time, $msgTittle);
                    }
                }

            }

			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
			redirect(base_url() . 'index.php?admin/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id.'/'.$section_id.'/'.$session , 'refresh');
		}
        $page_data['date']       =	$date;
        $page_data['month']      =	$month;
        $page_data['year']       =	$year;
        $page_data['class_id']   =  $class_id;
        $page_data['section_id'] =  $section_id;
        $page_data['session']    =  $session;
		
        $page_data['page_name']  =	'manage_attendance';
        $page_data['page_title'] =	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	function attendance_selector2()
	{
        //$session = $this->input->post('session');
        //$encoded_session = urlencode( base64_encode( $session ) );
		redirect(base_url() . 'index.php?admin/manage_attendance/'.$this->input->post('date').'/'.
					$this->input->post('month').'/'.
						$this->input->post('year').'/'.
							$this->input->post('class_id').'/'.
                                $this->input->post('section_id').'/'.
                                    $this->input->post('session') , 'refresh');
	}
        ///////ATTENDANCE REPORT /////
     function attendance_report($timest = '', $group_id = '', $class_id = '', $section_id = '', $course_id = '', $absent_sms = '') {

            $running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;
            $timestamp    = strtotime(date("d-m-Y"));
            if($timest != ''){
                $timestamp = $timest;
            }

            if($absent_sms == 'absent_sms' && $absent_sms != ''){
            $time = date("Y-m-d", $timestamp);

            $query = "SELECT st.name, e.roll, p.phone FROM enroll e
                        INNER JOIN student st ON (st.student_id = e.student_id and e.class_id = $class_id AND e.section_id = $section_id)
                        INNER JOIN section sec ON (e.section_id = sec.section_id and sec.group_id = $group_id)
                        INNER JOIN attendance a ON (a.student_id = e.student_id and a.course_id = $course_id AND a.timestamp = '$time' AND a.`status` = 0)
                        INNER JOIN parent p ON (st.parent_id = p.parent_id)";
            $details = $this->db->query($query)->result_array();
            $reciever = array_column($details, 'phone');

            $message = $this->db->get_where('codes', array('key_name' => 'notification.sms.absent.content'))->row()->value;

            $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.absent.title'))->row()->value;


            $this->sms_model->send_sms($message, $reciever, $time, $msgTittle);
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));

            $timestamp = strtotime($time);
            redirect(base_url().'index.php?admin/attendance_report/'.$timestamp,'refresh');
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
                    WHERE e.`year` = $running_year
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

    function coursewise_attendance_report()
    {
        if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');


        $page_data['classes'] = $this->db->get('class')->result_array();

        $page_data['groupClass'] = $this->getGroupClass();
        
        $page_data['page_name']  =  'coursewise_attendance_report';
        $page_data['page_title'] =  get_phrase('manage_attendance_of_class');
        $this->load->view('backend/index', $page_data);
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


     function attendance_report_view($group_id = '' ,$class_id = '' , $section_id = '', $course_id = '', $timestamp = '', $absent_sms = '') {
         if($this->session->userdata('admin_login')!=1)
            redirect(base_url() , 'refresh');
        $class_name = $this->db->get_where('class' , array(
            'class_id' => $class_id
        ))->row()->name;
        $page_data['group_id'] = $group_id;
        $page_data['class_id'] = $class_id;
        $page_data['section_id'] = $section_id;
        $page_data['course_id'] = $course_id;
        $page_data['timestamp']    = $timestamp;

        if($absent_sms == 'absent_sms'){
            $timestamp = date("Y-m-d", $timestamp);

            $query = "SELECT st.name, e.roll, p.phone FROM enroll e
                        JOIN student st ON (st.student_id = e.student_id AND e.class_id = $class_id AND e.section_id = $section_id)
                        JOIN section sec ON (e.section_id = sec.section_id AND sec.group_id = $group_id)
                        JOIN attendance a ON (a.student_id = e.student_id AND a.course_id = $course_id AND a.timestamp = '$timestamp' AND a.`status` = 0)
                        JOIN parent p ON (st.parent_id = p.parent_id)";
            $details = $this->db->query($query)->result_array();
            //$reciever = array_column($details, 'phone');
            //exit();

            foreach ($details as $row) {
                $message = "Roll: ".$row['roll'].", ".$row['name']." is absent today.";

                $time = date("Y-m-d");

                $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.absent.title'))->row()->value;

                $this->sms_model->send_sms($message, $row['phone'], $time, $msgTittle);
                echo $row['phone'];
            }
            
            //$message  = "Your Taka : ".$amt;
                        //$reciever = $this->crud_model->send_mobile_sms_fee($student_id);
                        //$this->sms_model->send_sms($message , $reciever);
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));

        }
        


        
        $section_name = $this->db->get_where('section' , array(
            'section_id' => $section_id
        ))->row()->name;
        
        $page_data['page_name'] = 'attendance_report_view';
        $page_data['page_title'] = get_phrase('attendance_report_of_class') . ' ' . $class_name . ' : ' . get_phrase('section') . ' ' . $section_name;
        $this->load->view('backend/index', $page_data);
     }


     function attendance_report_print_view($class_id ='' , $section_id = '' , $month = '') {
          if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['class_id'] = $class_id;
        $page_data['section_id']  = $section_id;
        $page_data['month'] = $month;
        $this->load->view('backend/admin/attendance_report_print_view' , $page_data);
    }
     
    function attendance_report_selector()
    {

        $data['group_id']   = $this->input->post('group_id');
        $data['class_id']   = $this->input->post('class_id');
        $data['section_id'] = $this->input->post('section_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['year']       = $this->input->post('year');
        $data['timestamp'] = strtotime($this->input->post('timestamp'));
        
        redirect(base_url().'index.php?admin/attendance_report/'.$data['timestamp'],'refresh');
    }

    function student_payable(){
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');


        $cr_month = date("F");

        $payable_entries = $this->db->get_where('student_feeconfig', array('month' => $cr_month))->result_array();

        foreach ($payable_entries as $entry){
            $trdata['description']      = 'Student Fee';
            $trdata['tdate']            = date('Y-m-d');
            $trdata['uniqueCode']       = Applicationconst::TRANSACTION_TYPE_FEE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_FEE);
            $trdata['type']     = Applicationconst::TRANSACTION_TYPE_FEE;

            $this->db->insert('transaction', $trdata);
            $transaction_id = $this->db->insert_id();

            $student_id = $entry['studentId'];
            
            
                $detaildata = array();
                $detaildata['transactionId']        =  $transaction_id;
                $detaildata['itemId']               =  $entry['itemId'];
                $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_STUDENT_FEE;
                $detaildata['userId']               =  $this->db->get_where('user', array('reference_id' => $student_id))->row()->user_id;
                $detaildata['type']                 =  -1;
                $detaildata['month']                =  date('n',strtotime($entry['month']));
                $detaildata['year']                 =  $entry['year'];
                $detaildata['quantity']             =  1;
                $detaildata['unitPrice']            =  $entry['amount'];
                $this->db->insert('transaction_detail',$detaildata);
                

                $detaildata = array();
                $detaildata['transactionId']        =  $transaction_id;
                $detaildata['itemId']               =  $entry['itemId'];
                $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_RECEIVABLE;
                $detaildata['userId']               =  Applicationconst::USER_COMPANY;
                $detaildata['type']                 =  1;
                $detaildata['month']                =  date('n',strtotime($entry['month']));
                $detaildata['year']                 =  $entry['year'];
                $detaildata['quantity']             =   1;
                $detaildata['unitPrice']            =  $entry['amount'];

                $this->db->insert('transaction_detail' , $detaildata);
        }

        $this->session->set_flashdata('flash_message' , get_phrase('Payable_successfully_created'));
        redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }

    function salary_payable(){

        $cr_month = date("F");
        $currentTime = date("Y-m-d");

        $payable_entries = "select * from employee_salary es
                             where '$currentTime' 
                             between es.applicableFrom and es.applicableTill";
        $payable_entries = $this->db->query($payable_entries)->result_array();

        foreach ($payable_entries as $entry){
            $trdata['description']      = 'Salary of '.date("F Y");
            $trdata['tdate']            = date('Y-m-d');
            $trdata['uniqueCode']       = Applicationconst::TRANSACTION_TYPE_SALARY_PAYABLE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_SALARY_PAYABLE);
            $trdata['type']     = Applicationconst::TRANSACTION_TYPE_SALARY_PAYABLE;


            $this->db->insert('transaction', $trdata);
            $transaction_id = $this->db->insert_id();
            
                $detaildata = array();
                $detaildata['transactionId']        =  $transaction_id;
                $detaildata['itemId']               =  Applicationconst::ITEM_TAKA;
                $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_PAYABLE;
                $detaildata['userId']               =  $entry['user_id'];
                $detaildata['type']                 =  -1;
                $detaildata['month']                =  date('n');
                $detaildata['year']                 =  date('Y');
                $detaildata['quantity']             =  1;
                $detaildata['unitPrice']            =  $entry['salary'];
                $this->db->insert('transaction_detail',$detaildata);
                

                $detaildata = array();
                $detaildata['transactionId']        =  $transaction_id;
                $detaildata['itemId']               =  Applicationconst::ITEM_TAKA;
                $detaildata['accountId']            =  Applicationconst::ACCOUNT_HEAD_SALARY;
                $detaildata['userId']               =  Applicationconst::USER_COMPANY;
                $detaildata['type']                 =  1;
                $detaildata['month']                =  date('n');
                $detaildata['year']                 =  date('Y');
                $detaildata['quantity']             =   1;
                $detaildata['unitPrice']            =  $entry['salary'];
                $this->db->insert('transaction_detail' , $detaildata);

                
            
        }

        $this->session->set_flashdata('flash_message' , get_phrase('salary_payable_successfully_created'));
        redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }

    function employee_salary_view($param1='', $param2=''){
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $query = "SELECT 
                    id,
                    user_id,
                    salary,
                    DATE_FORMAT(applicableFrom,'%d-%b-%Y')  applicableFrom,
                    DATE_FORMAT(applicableTill,'%d-%b-%Y')  applicableTill,
                    description
                    FROM employee_salary WHERE user_id = $param2";
        $page_data['salary_data'] = $this->db->query($query)->result_array();



        $page_data['page_name'] = 'employee_salary_view';
        $page_data['page_title'] = get_phrase('employee_salary_view');

        $this->load->view('backend/index', $page_data);
    }

    function employee_salary($param1 = '', $param2 = '', $param3 = ''){
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if($param1 == 'do_update'){
            $salaryIds       = $this->input->post('salary_id');
            $user_id         = $this->input->post('user_id');
            $salaries        = $this->input->post('salary');
            $applicableFroms = $this->input->post('applicableFrom');
            $applicableTills = $this->input->post('applicableTill');
            $descriptions    = $this->input->post('description');

            $salaryData = array();

            for ($i=0; $i < count($salaries); $i++) { 
                if($salaries[$i] == ''){
                    continue;
                }
                $salary_id                    = $salaryIds[$i];
                $salaryData['user_id']        = $user_id;
                $salaryData['salary']         = $salaries[$i];
                $salaryData['applicableFrom'] = date("Y-m-d",strtotime($applicableFroms[$i]));
                $salaryData['applicableTill'] = date("Y-m-d",strtotime($applicableTills[$i]));;
                $salaryData['description']    = $descriptions[$i];

                if($salary_id != ''){
                    $this->db->where('id', $salary_id);
                    $this->db->update('employee_salary', $salaryData);
                }elseif($salary_id == ''){
                    $this->db->insert('employee_salary', $salaryData);
                }
            }

        }elseif($param1 == 'delete'){
            $this->db->where('user_id', $param2);
            $this->db->delete('employee_salary');
        }
        //$page_data['teachers']   = $this->db->get('employee_salary')->result_array();

        $currentTime = date("Y-m-d");

        $query = "SELECT 
                      es.id AS salary_id,
                      es.user_id AS user_id,
                      u.reference_id AS teacher_id,
                      t.name,
                      u.user_name AS email,
                      ifnull(es.salary, 0) AS salary,
                      u.user_type AS employee_type,
                      date_format(es.applicableFrom, '%d-%b-%Y') applicableFrom,
                      date_format(es.applicableTill, '%d-%b-%Y') applicableTill
                      FROM employee_salary es
                      INNER JOIN user u ON es.user_id = u.user_id
                      INNER JOIN teacher t ON t.teacher_id = u.reference_id
                      WHERE '$currentTime' BETWEEN es.applicableFrom AND es.applicableTill OR ISNULL(es.applicableFrom) ORDER BY t.name";

        $employees = $this->db->query($query)->result_array();

        $page_data['employees'] = $employees;


        $page_data['page_name']  = 'employee_salary';
        $page_data['page_title'] = get_phrase('employee_salary');

        $this->load->view('backend/index', $page_data);
        
    }
    
    /****** MANAGE BILLING / INVOICES WITH STATUS *****/
    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['amount_paid']        = $this->input->post('amount_paid');
            $data['due']                = $data['amount'] - $data['amount_paid'];
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            $data['year']               = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            
            $this->db->insert('invoice', $data);
            $invoice_id = $this->db->insert_id();

            $data2['invoice_id']        =   $invoice_id;
            $data2['student_id']        =   $this->input->post('student_id');
            $data2['title']             =   $this->input->post('title');
            $data2['description']       =   $this->input->post('description');
            $data2['payment_type']      =  'income';
            $data2['method']            =   $this->input->post('method');
            $data2['amount']            =   $this->input->post('amount_paid');
            $data2['timestamp']         =   strtotime($this->input->post('date'));
            $data2['year']              =  $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;

            $this->db->insert('payment' , $data2);

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
        }

        if ($param1 == 'create_mass_invoice') {
            foreach ($this->input->post('student_id') as $id) {

                $data['student_id']         = $id;
                $data['title']              = $this->input->post('title');
                $data['description']        = $this->input->post('description');
                $data['amount']             = $this->input->post('amount');
                $data['amount_paid']        = $this->input->post('amount_paid');
                $data['due']                = $data['amount'] - $data['amount_paid'];
                $data['status']             = $this->input->post('status');
                $data['creation_timestamp'] = strtotime($this->input->post('date'));
                $data['year']               = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
                
                $this->db->insert('invoice', $data);
                $invoice_id = $this->db->insert_id();

                $data2['invoice_id']        =   $invoice_id;
                $data2['student_id']        =   $id;
                $data2['title']             =   $this->input->post('title');
                $data2['description']       =   $this->input->post('description');
                $data2['payment_type']      =  'income';
                $data2['method']            =   $this->input->post('method');
                $data2['amount']            =   $this->input->post('amount_paid');
                $data2['timestamp']         =   strtotime($this->input->post('date'));
                $data2['year']               =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;

                $this->db->insert('payment' , $data2);
            }
            
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
        }

        if ($param1 == 'do_update') {
            $data['student_id']         = $this->input->post('student_id');
            $data['title']              = $this->input->post('title');
            $data['description']        = $this->input->post('description');
            $data['amount']             = $this->input->post('amount');
            $data['status']             = $this->input->post('status');
            $data['creation_timestamp'] = strtotime($this->input->post('date'));
            
            $this->db->where('invoice_id', $param2);
            $this->db->update('invoice', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'take_payment') {
            $data['invoice_id']   =   $this->input->post('invoice_id');
            $data['student_id']   =   $this->input->post('student_id');
            $data['title']        =   $this->input->post('title');
            $data['description']  =   $this->input->post('description');
            $data['payment_type'] =   'income';
            $data['method']       =   $this->input->post('method');
            $data['amount']       =   $this->input->post('amount');
            $data['timestamp']    =   strtotime($this->input->post('timestamp'));
            $data['year']         =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->insert('payment' , $data);

            $status['status']   =   $this->input->post('status');
            $this->db->where('invoice_id' , $param2);
            $this->db->update('invoice' , array('status' => $status['status']));

            $data2['amount_paid']   =   $this->input->post('amount');
            $data2['status']        =   $this->input->post('status');
            $this->db->where('invoice_id' , $param2);
            $this->db->set('amount_paid', 'amount_paid + ' . $data2['amount_paid'], FALSE);
            $this->db->set('due', 'due - ' . $data2['amount_paid'], FALSE);
            $this->db->update('invoice');

            $this->session->set_flashdata('flash_message' , get_phrase('payment_successfull'));
            redirect(base_url() . 'index.php?admin/income/', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('invoice_id', $param2);
            $this->db->delete('invoice');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/income', 'refresh');
        }
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function payment($param1 = '', $param2 = '', $param3 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	
    		$operation = "";
    		
    		if($this->input->post('operation')){
    			$operation = $this->input->post('operation');
    		}
    		
    		$class_id = -1;
    		$student_id = -1;
    		
    		if($this->input->post('student_id')){
    			$student_id = $this->input->post('student_id');
    		}
    		
    		if($this->input->post('class_id')){
    			$class_id = $this->input->post('class_id');
    		}
    		
    		$page_data['class_id'] = $class_id;
    		$page_data['student_id'] = $student_id;
    		
    		$session_id = $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
    		
    
    		if ($operation == 'save_payment') {
    			
    			$trdata['description']     	= $this->input->post('description');
    			$trdata['tdate']             = Applicationconst::convertDate ($this->input->post('date'));
    		
    			$trdata['uniqueCode']		= Applicationconst::TRANSACTION_TYPE_FEE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_FEE);
  				$trdata['type']		= Applicationconst::TRANSACTION_TYPE_FEE;
     			$this->db->insert('transaction', $trdata);
     			$transaction_id = $this->db->insert_id(); 
     			
     			$amt = 0.0;
     			$feecnt =  $this->input->post('feecnt');
     			for($i = 1;$i<=$feecnt;$i++){
     				if($this->input->post('item_'.$i)){
		     			$detaildata = array();
		     			$detaildata['transactionId']        =  $transaction_id;
		     			$detaildata['itemId']        		=  $this->input->post('item_'.$i);
		     			$detaildata['accountId']        	=  Applicationconst::ACCOUNT_HEAD_STUDENT_FEE;
		     			$detaildata['userId']             	=  $student_id;
		     			$detaildata['type']       			=  -1;
		     			$detaildata['month']      			=  $this->input->post('month_'.$i);
		     			$detaildata['year']      			=  $this->input->post('year_'.$i);
		     			$detaildata['quantity']            	=   1;
		     			$detaildata['unitPrice']            =   $this->input->post('amount_'.$i);
						
		     			$amt +=  $this->input->post('amount_'.$i);
		     			
		     			$this->db->insert('transaction_detail' , $detaildata);
     				}
     			}
     			
     			if($amt > 0 ){
	     			$detaildata = array();
	     			$detaildata['transactionId']        =  $transaction_id;
	     			$detaildata['itemId']        		=  Applicationconst::ITEM_CASH;
	     			$detaildata['accountId']        	=  Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
	     			$detaildata['userId']             	=  $student_id;
	     			$detaildata['type']       			=  1;
	     			$detaildata['month']      			=  date('m');
	     			$detaildata['year']      			=  date('Y');
	     			$detaildata['quantity']            	=   1;
	     			$detaildata['unitPrice']            =   $amt;
	     			
	     			$this->db->insert('transaction_detail' , $detaildata);
     			}
    			
//     			$this->db->insert('payment' , $data2);
    
     			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
//     			redirect(base_url() . 'index.php?admin/student_payment', 'refresh');
    		}
    
    		if ($param1 == 'do_update') {
    			$data['student_id']         = $this->input->post('student_id');
    			$data['title']              = $this->input->post('title');
    			$data['description']        = $this->input->post('description');
    			$data['amount']             = $this->input->post('amount');
    			$data['status']             = $this->input->post('status');
    			$data['creation_timestamp'] = strtotime($this->input->post('date'));
    
    			$this->db->where('invoice_id', $param2);
    			$this->db->update('invoice', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/invoice', 'refresh');
    		} else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('invoice', array(
    					'invoice_id' => $param2
    			))->result_array();
    		}else if ($param1 == 'delete') {
    			$this->db->where('invoice_id', $param2);
    			$this->db->delete('invoice');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/income', 'refresh');
    		}
    		
    		$classes = $this->db->get('class')->result_array();
    		$page_data['classes'] = $classes;
    		
    		$payments = array();
    		if($class_id > 0 && $student_id > 0){
    		//	echo "CALL getpayments($student_id)";
    			$payments = $this->db->query("CALL getpayments($student_id)")->result_array();
    		}

    		mysqli_next_result( $this->db->conn_id );
            

    		//$this->db->freeDBResource($this->db->conn_id);
    		$page_data['payments'] = $payments;

            // echo "<pre>";
            // print_r($payments);
            // echo "</pre>";
    		
    		$students = array();
    		if($class_id > 0 ){
    			$students = $this->db->get_where('v_student_class',['class_id'=>$class_id, 'session_id'=>$session_id])->result_array();
    		}
    		$page_data['students'] = $students;
    		
    		$page_data['page_name']  = 'student_payment';
    		$page_data['page_title'] = get_phrase('manage_invoice/payment');
    		//$this->db->order_by('creation_timestamp', 'desc');
    		//$page_data['invoices'] = $this->db->get('invoice')->result_array();
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
    
    

    /**********ACCOUNTING********************/
    function income($param1 = '' , $param2 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['page_name']  = 'income';
        $page_data['page_title'] = get_phrase('student_payments');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data); 
    }

    function student_payment($param1 = '' , $param2 = '' , $param3 = '') {

        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['page_name']  = 'student_payment';
        $page_data['page_title'] = get_phrase('create_student_payment');
        $this->load->view('backend/index', $page_data); 
    }

    function expense($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
            $page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
            if ($this->input->post ( 'start_date' ) != null) {
            	$page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
            }
             
            $page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
            if ($this->input->post ( 'end_date' ) != null) {
            	$page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
            }
            
        if ($param1 == 'create') {
        	$items = $this->input->post('item');
        	$amount = $this->input->post('amount');
        	
        	$trdata['description']     	= $this->input->post('particulars');
        	$trdata['tdate']            = date('Y-m-d',strtotime($this->input->post('timestamp')));
        	$trdata['uniqueCode']		= Applicationconst::TRANSACTION_TYPE_EXPENSE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_EXPENSE);
        	$trdata['type']				= Applicationconst::TRANSACTION_TYPE_EXPENSE;
        	$this->db->insert('transaction', $trdata);
        	$transaction_id = $this->db->insert_id();
        	for ($i = 0; $i < count($items); $i++) {
	        	
        	   
        			$amt = 0.0;
        			$detaildata = array();
        			$detaildata['transactionId']        =  $transaction_id;
        			$detaildata['itemId']        		=  $items[$i];
        			$detaildata['accountId']        	=  Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
        			$detaildata['userId']             	=  Applicationconst::USER_COMPANY;
        			$detaildata['type']       			=  -1;
        			$detaildata['month']      			=  date('m', strtotime($this->input->post('timestamp')));
        			$detaildata['year']      			=  date('Y', strtotime($this->input->post('timestamp')));
        			$detaildata['quantity']            	=  1;
        			$detaildata['unitPrice']            =  $amount[$i];
        			$amt +=  $amount[$i];
        			$this->db->insert('transaction_detail' , $detaildata);

        	
        	if($amt > 0 ){
        		$detaildata = array();
        		$detaildata['transactionId']        =  $transaction_id;
        		$detaildata['itemId']        		=  $items[$i];
        		$detaildata['accountId']        	=  $this->input->post('account');
        		$detaildata['userId']             	=  Applicationconst::USER_ANNONYMUS;
        		$detaildata['type']       			=  1;
        		$detaildata['month']      			=  date('m', strtotime($this->input->post('timestamp')));
        		$detaildata['year']      			=  date('Y', strtotime($this->input->post('timestamp')));
        		$detaildata['quantity']            	=   1;
        		$detaildata['unitPrice']            =   $amt;
        		$this->db->insert('transaction_detail' , $detaildata);
        	}		
        	}
        	
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'edit') {
            $data['account_id']          =   $this->input->post('account');
            $data['item']         		 =   $this->input->post('item');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   $this->input->post('timestamp');
            $data['year']                =   $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description;
            $this->db->where('expense_id' , $param2);
            $this->db->update('expense' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('expense_id' , $param2);
            $this->db->delete('expense');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }
        
        $expenseSql = "SELECT componentId, description, uniqueCode, tdate, type,  amount
        FROM vtransactions
        WHERE type = 'EXPENSE' and tdate BETWEEN '".$page_data['start_date']."' AND '".$page_data['end_date']."'  ORDER BY tdate DESC";
        
        
        $expensequery = $this->db->query ( $expenseSql ); 
        $page_data['expenseinfo']  = $expensequery->result ();
        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expenses');
        $this->load->view('backend/index', $page_data);
    }

    function expense_category($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']   =   $this->input->post('name');
            $this->db->insert('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'edit') {
            $data['name']   =   $this->input->post('name');
            $this->db->where('expense_category_id' , $param2);
            $this->db->update('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'delete') {
            $this->db->where('expense_category_id' , $param2);
            $this->db->delete('expense_category');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }

        $page_data['page_name']  = 'expense_category';
        $page_data['page_title'] = get_phrase('expense_category');
        $this->load->view('backend/index', $page_data);
    }
    
    function code_element() {
    	$elementInfo = $this->db->get('codes')->result_array();
    	$page_data['elementInfo'] = $elementInfo;
    	$page_data['page_name']  = 'code_element';
    	$page_data['page_title'] = get_phrase('code_element');
    	$this->load->view('backend/index', $page_data);
    }
    function manage_code_element($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		 
    		if($param1 == 'create')
    		{
    			$dataToSave['key_name'] = $this->input->post('key_name');
    			$dataToSave['value'] = $this->input->post('value');
    			$this->db->insert('codes', $dataToSave);
    			$this->session->set_flashdata('flash_message' , get_phrase('element_added'));
    			redirect(base_url() . 'index.php?admin/code_element', 'refresh');
    		}
    		if($param1 == 'update')
    		{
    			$dataToSave['key_name'] = $this->input->post('key_name');
    			$dataToSave['value'] = $this->input->post('value');
    			$this->db->where('id' , $param2);
    			$this->db->update('codes', $dataToSave);
    			$this->session->set_flashdata('flash_message' , get_phrase('element_updated'));
    			redirect(base_url() . 'index.php?admin/code_element', 'refresh');
    		}
    		if($param1 == 'delete')
    		{
    			$this->db->where('id', $param2);
    			$this->db->delete('codes');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/code_element/', 'refresh');
    		}

    		$elementInfo = $this->db->get('codes')->result_array();
    
    		$page_data['elementInfo'] = $elementInfo;
    		$page_data['page_name']  = 'code_management';
    		$page_data['page_title'] = get_phrase('manage_code_element');
    		$this->load->view('backend/index', $page_data);
    }

    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            $this->db->insert('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->insert('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            
            $this->db->where('transport_id', $param2);
            $this->db->update('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('transport', array(
                'transport_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('transport_id', $param2);
            $this->db->delete('transport');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            $this->db->insert('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            
            $this->db->where('dormitory_id', $param2);
            $this->db->update('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('dormitory', array(
                'dormitory_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('dormitory_id', $param2);
            $this->db->delete('dormitory');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['file_name'] 	  	  = $_FILES["file_name"]["name"];
            $data['file_type']     	  = $this->input->post('file_type');
            $data['create_timestamp'] = date('Y-m-d', strtotime($this->input->post('create_timestamp')));
            $this->db->insert('noticeboard', $data);
            move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/notice/" . $_FILES["file_name"]["name"]);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];

                    $time = date("Y-m-d");

                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;


                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];

                    $time = date("Y-m-d");

                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;

                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];

                    $time = date("Y-m-d");

                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;

                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = date('Y-m-d', strtotime($this->input->post('create_timestamp')));
            $this->db->where('notice_id', $param2);
            $this->db->update('noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];

                    $time = date("Y-m-d");
                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;

                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];

                    $time = date("Y-m-d");
                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;


                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $time = date("Y-m-d");
                    $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.noticeboard.title'))->row()->value;

                    $this->sms_model->send_sms($message , $reciever_phone, $time, $msgTittle);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('noticeboard');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        
        
        $this->load->view('backend/index', $page_data);
    }
    
    function view_noticeboard() {
    	   	
    	$page_data['page_name']  = 'view_noticeboard';
    	$page_data['page_title'] = get_phrase('noticeboard');
    	$page_data['notices']    = $this->db->get('noticeboard')->result_array();
    	$this->load->view('backend/index', $page_data);	
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $param2, 'refresh');
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
	
	function notification($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
			
			$message    = $this->input->post('message');
            $reciever = $this->crud_model->send_mobile_sms();

            $time = date("Y-m-d");
            $msgTittle = $this->db->get_where('codes', array('key_name' => 'notification.sms.notification.title'))->row()->value;
            
			$this->sms_model->send_sms($message , $reciever, $time, $msgTittle);
			$this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/notification/' , 'refresh');
        }

       
        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'notification';
        $page_data['page_title']                = get_phrase('Notification');
        $this->load->view('backend/index', $page_data);
    }

    // function absent_student_notification($param1 = 'message_home', $param2 = '', $param3 = '') {
    //     if ($this->session->userdata('admin_login') != 1)
    //         redirect(base_url(), 'refresh');

    //     if ($param1 == 'send_new') {
    //         $reciever = $this->input->post('phones');
    //         $message = $param3;
    //         //$reciever = $param2;
    //         echo '<pre>';
    //         print_r($reciever);
    //         echo '</pre>';
    //         exit();
    //         $this->sms_model->send_sms($message , $reciever);
    //         $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
    //         redirect(base_url() . 'index.php?admin/notification/' , 'refresh');
    //     }

       
    //     $page_data['message_inner_page_name']   = $param1;
    //     $page_data['page_name']                 = 'notification';
    //     $page_data['page_title']                = get_phrase('Notification');
    //     $this->load->view('backend/index', $page_data);
    // }
	
    
	
    /*****SITE/SYSTEM SETTINGS*********/
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('text_align');
            $this->db->where('type' , 'text_align');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('default_campus');
            $this->db->where('type' , 'default_campus');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('running_year');
            $this->db->where('type' , 'running_year');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('running_term');
            $this->db->where('type' , 'running_term');
            $this->db->update('settings' , $data);
			
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        if ($param1 == 'change_skin') {
            $data['description'] = $param2;
            $this->db->where('type' , 'skin_colour');
            $this->db->update('settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('theme_selected')); 
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh'); 
        }
        
        $page_data['campuses'] = $this->db->get('campus')->result_array();
        $page_data['sessions'] = $this->db->get('session')->result_array();
        $page_data['terms'] = $this->db->get('exam')->result_array();

        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings'] = array();


        
        $settings = $this->db->get('settings')->result_array();
        
        foreach ($settings as $stng){
        	$page_data['settings'][$stng['type']] = $stng['description'];
        }
        //$page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function get_session_changer()
    {
        $this->load->view('backend/admin/change_session');
    }

    function change_session()
    {
        $data['description'] = $this->input->post('running_year');
        $this->db->where('type' , 'running_year');
        $this->db->update('settings' , $data);
        $this->session->set_flashdata('flash_message' , get_phrase('session_changed')); 
        redirect(base_url() . 'index.php?admin/dashboard/', 'refresh'); 
    }
	
	/***** UPDATE PRODUCT *****/
	
	function update( $task = '', $purchase_code = '' ) {
        
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
            
        // Create update directory.
        $dir    = 'update';
        if ( !is_dir($dir) )
            mkdir($dir, 0777, true);
        
        $zipped_file_name   = $_FILES["file_name"]["name"];
        $path               = 'update/' . $zipped_file_name;
        
        move_uploaded_file($_FILES["file_name"]["tmp_name"], $path);
        
        // Unzip uploaded update file and remove zip file.
        $zip = new ZipArchive;
        $res = $zip->open($path);
        if ($res === TRUE) {
            $zip->extractTo('update');
            $zip->close();
            unlink($path);
        }
        
        $unzipped_file_name = substr($zipped_file_name, 0, -4);
        $str                = file_get_contents('./update/' . $unzipped_file_name . '/update_config.json');
        $json               = json_decode($str, true);
        

			
		// Run php modifications
		require './update/' . $unzipped_file_name . '/update_script.php';
        
        // Create new directories.
        if(!empty($json['directory'])) {
            foreach($json['directory'] as $directory) {
                if ( !is_dir( $directory['name']) )
                    mkdir( $directory['name'], 0777, true );
            }
        }
        
        // Create/Replace new files.
        if(!empty($json['files'])) {
            foreach($json['files'] as $file)
                copy($file['root_directory'], $file['update_directory']);
        }
        
        $this->session->set_flashdata('flash_message' , get_phrase('product_updated_successfully'));
        redirect(base_url() . 'index.php?admin/system_settings');
    }

    /*****SMS SETTINGS*********/
    function sms_settings($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'clickatell') {

            $data['description'] = $this->input->post('clickatell_user');
            $this->db->where('type' , 'clickatell_user');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_api_id');
            $this->db->where('type' , 'clickatell_api_id');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'twilio') {

            $data['description'] = $this->input->post('twilio_account_sid');
            $this->db->where('type' , 'twilio_account_sid');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_auth_token');
            $this->db->where('type' , 'twilio_auth_token');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_sender_phone_number');
            $this->db->where('type' , 'twilio_sender_phone_number');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'active_service') {

            $data['description'] = $this->input->post('active_sms_service');
            $this->db->where('type' , 'active_sms_service');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        $page_data['page_name']  = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*****LANGUAGE SETTINGS*********/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		if ($param1 == 'update_phrase') {
			$language	=	$param2;
			$total_phrase	=	$this->input->post('total_phrase');
			for($i = 1 ; $i < $total_phrase ; $i++)
			{
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id' , $i);
				$this->db->update('language' , array($language => $this->input->post('phrase'.$i)));
			}
			redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/'.$language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language        = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);
			
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		$page_data['page_name']        = 'manage_language';
		$page_data['page_title']       = get_phrase('manage_language');
		//$page_data['language_phrases'] = $this->db->get('language')->result_array();
		$this->load->view('backend/index', $page_data);	
    }
    
    /*****BACKUP / RESTORE / DELETE DATA PAGE**********/
    function backup_restore($operation = '', $type = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        
        $page_data['page_info']  = 'Create backup / restore from backup';
        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = sha1($this->input->post('password'));
            $data['new_password']         = sha1($this->input->post('new_password'));
            $data['confirm_new_password'] = sha1($this->input->post('confirm_new_password'));
            
            $current_password = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('admin_id', $this->session->userdata('admin_id'));
                $this->db->update('admin', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('admin', array(
            'admin_id' => $this->session->userdata('admin_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    
    
    /****MANAGE EXAMMARKS*****/
    function exam_marks()
    {
    	 
    	if ($this->session->userdata('admin_login') != 1)
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
                                $condition = array(
                                    'examtype_id' => $examTypeId, 
                                    'exam_id'     => $termId, 
                                    'course_id'   => $courseId
                                );

                                $this->db->where($condition);
                                $this->db->delete('exammark');

    						  $qry = $this->db->query("CALL processResult ($examTypeId, $termId, $courseId)");

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
    							
    							$courses = $this->db->get_where('course', array('class_id' => $classId, 'group_id' => $groupId))->result_array();

    							$page_data['courses'] = array(''=>'Select one');
    							foreach($courses as $row):
    							$page_data['courses'][$row['course_id']] = $row['tittle'];
    							endforeach;
    							$page_data['courseId'] = $courseId;
    
    							// $students = $this->db->get_where('v_student_class', array('class_id' => $classId, 'section_id' => $sectionId))->result_array();

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
    
    							// print_r($marks);

                                $page_data['groupClass']  = $this->getGroupClass();

    							$page_data['page_name']  = 'exam_marks';
    							$page_data['page_title'] = get_phrase('manage_exam_marks');
    							$this->load->view('backend/index', $page_data);
    }
    
    function examtype_marks()
    {
    
    	if ($this->session->userdata('admin_login') != 1)
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

                                    $this->db->where('exam_id', $termId);
                                    $this->db->where('examtype_id', $examTypeId);
                                    $this->db->where('course_id', $courseId);
                                    $this->db->delete('exammark');
                                    exit();

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
    
    
    function markupdate($termId=-1, $courseId = -1, $examTypeId = -1, $studentId = -1, $marks = 0){
    	$whclause = array('exam_id' => $termId,'course_id' => $courseId,'examtype_id' => $examTypeId,'student_id' => $studentId);

        $session_id = $this->db->get_where('enroll', array('student_id' => $studentId))->row()->session_id;

    	$row = $this->db->get_where('exammark',  $whclause)->result_array();
        $exammark_id = $row[0]['exammark_id'];
    	 
    	if(count($row)>0){
            $this->db->where('exammark_id', $exammark_id);
    		echo $this->db->update('exammark', ["mark_obtained"=>$marks, "session_id" => $session_id]);
    	}else{
            $whclause['mark_obtained'] = $marks;
    		$whclause['session_id']    = $session_id;
    		echo $this->db->insert('exammark', $whclause);
            $exammark_id = $this->db->insert_id();
    	}

#Calculate gradepoint for every examtype
        //echo "<br>".$courseId." ".$examTypeId;
        //exit();
        $is_combined = $this->db->get_where('course', array('course_id' => $courseId))->row()->combined;

        if($is_combined){
            $display_name = $this->db->get_where('examtype', array('examtype_id' => $examTypeId))->row()->displayname;
            $groupId = $this->db->get_where('course_group', array('course_id' => $courseId))->row()->group_id;
            $combined_courseId = $this->db->get_where('course_group', array('group_id' => $groupId, 'course_id !=' => $courseId))->row()->course_id;

            $this->db->select('e.examtype_id, e.total_mark');
            $this->db->from('examtype e');
            $this->db->join('examcourse ec', "e.examtype_id = ec.examtype_id AND ec.course_id = '$combined_courseId'", 'inner');
            $this->db->where('e.displayname', "$display_name");
            $this->db->group_by('e.examtype_id');
            $combined_info = $this->db->get()->result_array();

            $combined_examtypeId = $combined_info[0]['examtype_id'];
            $combined_total_mark = $combined_info[0]['total_mark'];

            $this_type_total_mark = $this->db->get_where('examtype', array('examtype_id' => $examTypeId))->row()->total_mark;
            $combined_total_mark = $combined_total_mark + $this_type_total_mark;
            
            $this_obtained_mark = $marks;

            $combined_mark_obtained_condition = array(
                    'exam_id' => $termId,
                    'session_id' => $session_id,
                    'course_id' => $combined_courseId,
                    'examtype_id' => $combined_examtypeId,
                    'student_id' => $studentId
            );
            $this->db->where($combined_mark_obtained_condition);
            $this->db->from('exammark');
            $combined_markId_mark = $this->db->get()->result_array();

            $combined_obtained_mark = $combined_markId_mark[0]['mark_obtained'];
            $combined_markId        = $combined_markId_mark[0]['exammark_id'];

            $total_obtained_marks = $this_obtained_mark + $combined_obtained_mark;
            $average = round($total_obtained_marks*100/$combined_total_mark,2);

            $lg = $this->crud_model->get_grade_with_everage($average, 'lg');
            $gp = $this->crud_model->get_grade_with_everage($average, 'gp');

            //echo "<br>$exammark_id  $combined_markId";

            $resData = array();
            $resData['lg'] = $lg;
            $resData['gp'] = $gp;

            $condition = array($exammark_id, $combined_markId);
            $this->db->where_in('exammark_id', $condition);
            $this->db->update('exammark', $resData);
            exit();

        }

        $letterGrade = "SELECT calcGrade($marks, $examTypeId) as lg
                                        FROM exammark
                                        WHERE exammark_id = $exammark_id";
        $gradePoint = "SELECT calcGradePoint($marks, $examTypeId) as gp
                                        FROM exammark
                                        WHERE exammark_id = $exammark_id";
        $letterGrade = $this->db->query($letterGrade)->row()->lg;
        $gradePoint = $this->db->query($gradePoint)->row()->gp;

                        //echo "$letterGrade $gradePoint";

        $exammarkData['lg'] = $letterGrade;
        $exammarkData['gp'] = $gradePoint;

                        // echo "$exammark_id $student_id $examTypeId $termId $courseId";

        $this->db->where('exammark_id', $exammark_id);
        $update = $this->db->update('exammark', $exammarkData);
    }
    
    
    /****MANAGE courses*****/
    function course($param1 = '', $param2 = '',$param3 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			//$data['CourseId']       = $this->input->post('name');
    			
                $data['unique_code']   = $this->input->post('course_code');
                $data['tittle']   = $this->input->post('name');
    			$data['credit']   = $this->input->post('credit');
    			//$data['unique_code']	= $this->input->post('name').'-1';
    			$data['class_id']   	= $this->input->post('class');
    			$data['group_id'] 		= $this->input->post('group');
                $data['is_optional']    = $this->input->post('is_optional');
    			$data['combined']       = ($this->input->post('combined_with') == 0)?0:1;
    			$this->db->insert('course', $data);

                if($data['combined']){
                    $course_id = $this->db->insert_id();
                    
                    $withId = $this->input->post('combined_with');
                    
                    $groupData['group_id']  = ($withId == -1)?$course_id:$withId;
                    $groupData['course_id'] = $course_id;
                    $groupData['class_id']  = $data['class_id'];

                    $this->db->insert('course_group', $groupData);
                }

                

    			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    			redirect(base_url() . 'index.php?admin/course/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
                #course_id = param2
    			$data['tittle']         = $this->input->post('name');
    			$data['class_id']   	= $this->input->post('class');
    			$data['group_id'] 		= $this->input->post('group');
    			$data['is_optional']    = $this->input->post('is_optional');
                $data['combined']       = ($this->input->post('combined_with') == 0)?0:1;

    			$this->db->where('course_id', $param2);
    			$this->db->update('course', $data);

                if($data['combined']){
                    
                    $withId = $this->input->post('combined_with');
                    
                    $groupData['group_id']  = ($withId == -1)?$param2:$withId;
                    $groupData['course_id'] = $param2;
                    $groupData['class_id']  = $data['class_id'];

                    if($withId == 0){
                        $this->db->where('course_id', $param2);
                        $this->db->delete('course_group');
                    }else if($withId == -1){
                         $is_entry_exists = $this->db->get_where('course_group', array('course_id'=> $param2))->result_array();

                         if(count($is_entry_exists) == 1){
                            $this->db->where('course_id', $param2);
                            $this->db->update('course_group', $groupData);
                         }else if(count($is_entry_exists) == 0){
                            $this->db->insert('course_group', $groupData);
                         }
                    }else{
                        $this->db->insert('course_group', $groupData);
                    }
                }else{
                    $is_entry_exists = $this->db->get_where('course_group', array('course_id'=> $param2))->result_array();

                         if(count($is_entry_exists) == 1){
                            $this->db->where('course_id', $param2);
                            $this->db->delete('course_group');
                         }
                }


    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/course/', 'refresh');
    		} 
    		if ($param1 == 'delete') {
    			$this->db->where('course_id', $param2);
    			$this->db->delete('course');

                $this->db->where('course_id', $param2);
                $this->db->delete('course_group');

    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/course/', 'refresh');
    		}
    		//$page_data['courses']   = $this->db->get('course')->result_array();

            $courses = "SELECT cr.course_id, cr.class_id, cr.group_id, cr.tittle, cr.is_optional
                FROM course cr
                INNER JOIN class cl ON cr.class_id = cl.class_id
                INNER JOIN class_group cg ON cr.group_id = cg.id
                ORDER BY cl.name, cg.group_name";
            $page_data['courses'] = $this->db->query($courses)->result_array();

            $page_data['campus'] = $this->db->get('campus')->result_array();
            $page_data['default_campus'] = $this->db->get_where('settings', array('type' => 'default_campus'))->row()->description;

            $page_data['groupClass']  = $this->getGroupClass();
    		$page_data['page_name']  = 'course';
    		$page_data['page_title'] = get_phrase('manage_course');
    		$this->load->view('backend/index', $page_data);
    }

    public function get_combined_curses($param1 = ''){
        echo '
            <option value="0">No</option>
            <option value="-1">Create New</option>
        ';
        $this->db->select('cg.group_id, c.tittle');
        $this->db->from('course_group cg');
        $this->db->join('course c', 'cg.course_id = c.course_id', 'inner');
        $this->db->where('cg.class_id', $param1);
        $this->db->group_by('cg.componentId');
        $courses = $this->db->get()->result_array();

        foreach ($courses as $key => $course) {
            echo '
                <option value="'.$course['group_id'].'">'.$course['tittle'].'</option>
            ';
        }
    }
    
    /****MANAGE courseconfigs*****/
    function courseconfig($param1 = '', $param2 = '',$param3 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			//$data['CourseId']       = $this->input->post('name');
    			$data['course_id']   = $this->input->post('course_id');
    			$data['session_id'] = $this->input->post('session_id');
    			$data['teacher_id'] = $this->input->post('teacher_id');
    			$this->db->insert('courseconfig', $data);
    			redirect(base_url() . 'index.php?admin/courseconfig/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
    			$data['course_id']   = $this->input->post('course_id');
    			$data['session_id'] = $this->input->post('session_id');
    			$data['teacher_id'] = $this->input->post('teacher_id');
    
    			$this->db->where('courseconfig_id', $param2);
    			$this->db->update('courseconfig', $data);
    			redirect(base_url() . 'index.php?admin/courseconfig/', 'refresh');
    		} else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('courseconfig', array(
    					'courseconfig_id' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('courseconfig_id', $param2);
    			$this->db->delete('courseconfig');
    			redirect(base_url() . 'index.php?admin/courseconfig/', 'refresh');
    		}
    
    		$class_id = -1;
    		if($this->input->post('class_id'))
    			$class_id = $this->input->post('class_id');
    			$page_data['class_id'] = $class_id;
    
    			$session_id = -1;
    			if($this->input->post('session_id'))
    				$session_id = $this->input->post('session_id');
    				$page_data['session_id'] = $session_id;
    
    				$classes = $this->db->get('class')->result_array();
    				$page_data['allclasses'] = array('-1'=> 'Select One');
    				foreach ($classes as $class)
    					$page_data['allclasses'][$class['class_id']] = $class['name'];
    
    					if($this->input->post('operation')){
    						if($this->input->post('operation') == 'update'){
    
    							$cnt = $this->input->post('coursecount');
    
    							for($i=1;$i<=$cnt;$i++){
    								$dataToSave = array();
    								$dataToSave['course_id'] = $this->input->post('course_'.$i);
    								$dataToSave['session_id'] = $session_id;
    								 
    								$this->db->where('course_id', $dataToSave['course_id']);
    								$this->db->where('session_id', $dataToSave['session_id']);
    								$this->db->delete('courseconfig');
    								 
    								if($this->input->post('selectedCourse_'.$i)){
    									$dataToSave['teacher_id'] = $this->input->post('teacher_id_'.$i);
    									$this->db->insert('courseconfig', $dataToSave);
    								}
    							}
    						}
    					}
    
    					$courses = $this->db->get_where('course', array('class_id'=>$class_id))->result_array();
    
    					$page_data['courses'] = $courses;
    
    					$sessions = $this->db->get('session')->result_array();
    					$page_data['sessions'] = array('-1'=> 'Select One');
    					foreach ($sessions as $session){
    						$page_data['sessions'][$session['session_id']] = $session['start'].' - '.$session['end'];
    					}
    					$teachers = $this->db->get('teacher')->result_array();
    					$page_data['teachers'] = array('-1'=> 'Select One');
    					foreach ($teachers as $teacher){
    						$page_data['teachers'][$teacher['teacher_id']] = $teacher['name'];
    					}
    					$courseTeacher = $this->db->get_where('courseconfig', array('session_id'=>$session_id))->result_array();
    					$page_data['courseTeacher'] = array();
    					foreach ($courseTeacher as $ct){
    						$page_data['courseTeacher'][$ct['course_id']] = $ct['teacher_id'];
    					}
    
    					$page_data['courseconfigs']   = $this->db->get('courseconfig')->result_array();
    					$page_data['page_name']  = 'courseconfig';
    					$page_data['page_title'] = get_phrase('');
    					$this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE course examt ypes*****/
    function courseexamtypes($course_id = '', $examtype_id = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	
    	$class_id = -1;
    	$course_id = -1;
    	$section_id = -1;
    	$term_id = -1;
    
    	if($this->input->post('group_id') !=null)
                $group_id = $this->input->post('group_id');
        $page_data['group_id'] = $group_id;
    	
    	if($this->input->post('class_id')){
    		$class_id = $this->input->post('class_id');
    	}
    	$page_data['class_id'] = $class_id;
    	
    	if($this->input->post('section_id') !=null){
    		$section_id = $this->input->post('section_id');
    	}
    	//$page_data['sectionId'] = $this->input->post('sectionId');

    	if($this->input->post('course_id')){
    		$course_id = $this->input->post('course_id');
    	}  	
    	$page_data['course_id'] = $course_id;
    	
    	if($this->input->post('term_id')){
    		$term_id = $this->input->post('term_id');
    	}
    	$page_data['term_id'] = $term_id;
    	
    	
    	if($this->input->post('operation') == "update"){
    		if($course_id > 0 && $class_id >0 && $term_id > 0 ){
    			$this->db->where(array('course_id'=>$course_id, 'exam_id'=>$term_id));
    			$this->db->delete('examcourse');
    			$examtypes = $this->input->post('examtypes');
    			foreach($examtypes as $type){
    				$oi =  $this->input->post('order_index_'.$type);
    				$tosave = array('examtype_id'=>$type, 'course_id'=>$course_id, 'exam_id'=>$term_id, 'order_index'=>$oi);
    				if($this->input->post('report_card_'.$type)){
    					$tosave['report_card'] = 1;
    				}
    				if($this->db->insert('examcourse', $tosave)){
    					//echo 'Success';
    				}else{
    					//echo 'Failed';
    				}
    			}
    			
    		}
    	}
    	
    	$terms = $this->db->get('exam')->result_array();
    	
    	$page_data['terms'] = array(''=>'Select one');
    	foreach($terms as $row):
    	$page_data['terms'][$row['exam_id']] = $row['name'];
    	endforeach;

        $groups = $this->db->get('class_group')->result_array();
                                $page_data['groups'] = array(''=>'Select one');
                                foreach($groups as $row):
                                $page_data['groups'][$row['id']] = $row['group_name'];
                                endforeach;
    		
    	$classes = $this->db->get('class')->result_array();
    	$page_data['allclasses'] = array('-1'=> 'Select One');
    	
    	foreach ($classes as $class){
    		$page_data['allclasses'][$class['class_id']] = $class['name'];
    	}
    	
    	$sections = $this->db->get_where('section', array('class_id' => $class_id, 'group_id' => $group_id))->result_array();
    	$page_data['allsections'] = array(''=>'Select one');
    	
    	foreach($sections as $row):
    	$page_data['allsections'][$row['section_id']] = $row['name'];
    	endforeach;
    	$page_data['section_id'] = $section_id;
    	
    	$page_data['courses'][-1] = 'Select Course';
    	$courses = $this->db->get_where('course', ["class_id"=>$class_id, "group_id" => $group_id])->result_array();
    	foreach ($courses as $course){
    		$page_data['courses'][$course['course_id']] = $course['tittle'];
    	}
    	
    	$examTypes = $this->db->order_by('examtype_id', 'ASC')->get('examtype')->result_array();
    	$page_data['examtypes'] = $examTypes;
    	
    	$page_data['selectedtypes'] = array();
    	$examcourses = $this->db->get_where('examcourse',array('course_id'=>$course_id, 'exam_id'=>$term_id))->result_array();
    	
    	$page_data['examcourse'] = array();
    	foreach ($examcourses as $course){
    		array_push($page_data['selectedtypes'], $course['examtype_id']);
    		$page_data['examcourse'][$course['examtype_id']] = $course;
    	}

        $page_data['groupClass'] = $this->getGroupClass();
    	
    	$page_data['page_info'] = 'Exam types';
    	$page_data['page_name']  = 'courseexamtypes';
    	$page_data['page_title'] = get_phrase('course_examtypes');
    	$this->load->view('backend/index', $page_data);
    }
    
    function examtypes($param1 = '', $param2 = '', $param3 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			//$data['course_id']         = $this->input->post('course_id');
    			$data['name']            = $this->input->post('name');
    			$data['displayname']     = $this->input->post('displayname');
    			$data['type']            = $this->input->post('type');
    			$data['rule']            = $this->input->post('rule');
                $data['total_mark']      = $this->input->post('total_mark');
    			$data['passing_check']   = $this->input->post('passing_check');
    			$this->db->insert('examtype', $data);
    			redirect(base_url() . 'index.php?admin/examtypes/', 'refresh');
    		}
    
    
    		if ($param1 == 'edit' && $param2 == 'do_update') {
    			//$data['course_id']         = $this->input->post('course_id');
    			$data['name']            = $this->input->post('name');
    			$data['type']            = $this->input->post('type');
    			$data['displayname']     = $this->input->post('displayname');
    			$data['rule']            = $this->input->post('rule');
    			$data['total_mark']      = $this->input->post('total_mark');
                $data['passing_check']   = $this->input->post('passing_check');
    
    			$this->db->where('examtype_id', $param3);
    			$this->db->update('examtype', $data);
    			redirect(base_url() . 'index.php?admin/examtypes/', 'refresh');
    		}
    		else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('examtype', array(
    					'examtype_id' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('examtype_id', $param2);
    			$this->db->delete('examtype');
    			redirect(base_url() . 'index.php?admin/examtypes/', 'refresh');
    		}
    		$page_data['examtypes']    = $this->db->order_by('examtype_id', 'ASC')->get('examtype')->result_array();
    		$page_data['page_name']  = 'examtype';
    		$page_data['page_title'] = get_phrase('manage_examtype');
    		$this->load->view('backend/index', $page_data);
    }
    
    
    /****MANAGE account*****/
    function account($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			$data['uniqueCode']         = $this->input->post('uniqueCode');
    			$data['description'] = $this->input->post('description');
    			$data['category1']   = $this->input->post('category1');
    			$data['category2']   = $this->input->post('category2');
    			$data['category3']   = $this->input->post('category3');
    			$this->db->insert('account', $data);
    			$account_id = $this->db->insert_id();
    
    			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    			redirect(base_url() . 'index.php?admin/account/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
    			$data['uniqueCode']         = $this->input->post('uniqueCode');
    			$data['description'] = $this->input->post('description');
    			$data['category1']   = $this->input->post('category1');
    			$data['category2']   = $this->input->post('category2');
    			$data['category3']   = $this->input->post('category3');
    
    			$this->db->where('componentId', $param2);
    			$this->db->update('account', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/account/', 'refresh');
    		} else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('account', array(
    					'componentId' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('componentId', $param2);
    			$this->db->delete('account');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/account/', 'refresh');
    		}
    		$page_data['accounts']    = $this->db->get('account')->result_array();
    		$page_data['page_name']  = 'account';
    		$page_data['page_title'] = get_phrase('manage_account');
    		$this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE item*****/
    function item($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			$data['itemName']         = $this->input->post('itemName');
    			$data['category1']   = $this->input->post('category1');
    			$data['category2']   = $this->input->post('category2');
    			$data['category3']   = $this->input->post('category3');
    			$data['uniqueCode']  = $data['itemName'].'/'.$data['category1'].'/'.$data['category2'].'/'.$data['category3'];
    			if($this->input->post('salePrice') && $this->input->post('salePrice') !='')
    				$data['salePrice']   = $this->input->post('salePrice');
    			$this->db->insert('item', $data);
    			$account_id = $this->db->insert_id();
    
    			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    			redirect(base_url() . 'index.php?admin/item/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
    			$data['itemName']         = $this->input->post('itemName');
    			$data['category1']   = $this->input->post('category1');
    			$data['category2']   = $this->input->post('category2');
    			$data['category3']   = $this->input->post('category3');
    			$data['uniqueCode']  = $data['itemName'].'/'.$data['category1'].'/'.$data['category2'].'/'.$data['category3'];
    			$data['salePrice']   = $this->input->post('salePrice');
    
    			$this->db->where('componentId', $param2);
    			$this->db->update('item', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/item/', 'refresh');
    		} else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('item', array(
    					'componentId' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('componentId', $param2);
    			$this->db->delete('item');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/item/', 'refresh');
    		}
    		
    		$page_data['category1List'] = array('-Select-',Applicationconst::ITEM_TYPE_FEE,Applicationconst::ITEM_TYPE_INVENTORY,Applicationconst::ITEM_TYPE_OTHERS);
    		$item_category2 = $this->codeElement('item.category2');
    		$item_category3 = $this->codeElement('item.category3');
    		$page_data['item_category2'] = $item_category2;
    		$page_data['item_category3'] = $item_category3;
    		$page_data['items']    = $this->db->get('item')->result_array();
    		$page_data['page_name']  = 'item';
    		$page_data['page_title'] = get_phrase('manage_item');
    		$this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE item*****/
    function feeconf($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {
    			$data['itemName']    = $this->input->post('itemName');
    			$data['category1']   = $this->input->post('category1');
    			$data['category2']   = $this->input->post('category2');
    			$data['category3']   = $this->input->post('category3');
    			$data['uniqueCode']  = $data['itemName'].'/'.$data['category1'].'/'.$data['category2'].'/'.$data['category3'];
    			if($this->input->post('salePrice') && $this->input->post('salePrice') !='')
    				$data['salePrice']   = $this->input->post('salePrice');
    				$this->db->insert('item', $data);
    				$account_id = $this->db->insert_id();
    
    				$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    				redirect(base_url() . 'index.php?admin/feeconf/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
    			
    			$data['category2']   = $this->input->post('category2');
    			$fee_count = $this->input->post('fees_count');
    			$session_id= $this->input->post('session_id');
                $class_id= $this->input->post('class_id');
                $group_id= $this->input->post('group_id');
                
    			
    			for($i = 1; $i <= $fee_count; $i++){

    				$item_id =  $this->input->post('fee_'.$i);
    				$amount =  $this->input->post('amount_'.$i);
    				
    				$dataToSave['amount'] = $amount;
                    $st_feeconfig['amount'] = $amount;
    				$dataToSave['item_id'] = $item_id;
    				$dataToSave['session_id'] = $session_id;
                    $dataToSave['group_id'] = $group_id;


    				if($data['category2'] == 'CLASS'){

    					$dataToSave['class_id'] = $class_id;
    					$this->db->where(['item_id'=>$item_id,'session_id'=>$session_id,'class_id'=>$class_id, 'group_id' => $group_id]);
    					$this->db->delete('fee_conf');
                        $this->db->insert('fee_conf', $dataToSave);

                        if($group_id ==  -1){
                            $students = "SELECT e.student_id, s.class_id, s.group_id FROM enroll e
                                INNER JOIN section s ON s.section_id = e.section_id
                                WHERE e.class_id = $class_id and e.session_id = $session_id";
                            $students = $this->db->query($students)->result_array();

                        }elseif($group_id > 0 && $class_id > 0){
                            $students = "SELECT e.student_id, s.class_id, s.group_id FROM enroll e
                                INNER JOIN section s ON e.section_id = s.section_id and s.group_id = $group_id
                                WHERE e.class_id = $class_id AND e.session_id = $session_id";
                            $students = $this->db->query($students)->result_array();
                        }

                        foreach ($students as $student) {
                            $this->db->where(array('studentId' => $student['student_id'], 'sessionId' => $session_id, 'itemId' => $item_id));
                            $this->db->update('student_feeconfig', $st_feeconfig);

                            $student_id = $student['student_id'];
                            $group_id   = $student['group_id'];
                            $class_id   = $student['class_id'];
                            $this->student_fee_set($session_id, $group_id, $class_id, $student_id);
                        }

    				}elseif ($data['category2']=='SCHOOL'){
    					$this->db->where(['item_id'=>$item_id,'session_id'=>$session_id]);
    					$this->db->delete('fee_conf');
                        $this->db->insert('fee_conf', $dataToSave);

                        $this->db->where(array('sessionId' => $session_id, 'itemId' => $item_id));
                        $this->db->update('student_feeconfig', $st_feeconfig);

                        $entryExist = $this->db->get_where('student_feeconfig', array('sessionId' => $session_id, 'itemId' => $item_id))->result_array();
                        if(count($entryExist) == 0){
                            $students = "SELECT e.student_id, s.class_id, s.group_id 
                                FROM enroll e
                                INNER JOIN section s ON s.section_id = e.section_id
                                WHERE e.session_id = $session_id";
                            $students = $this->db->query($students)->result_array();

                            foreach ($students as $student) {
                                $student_id = $student['student_id'];
                                $group_id   = $student['group_id'];
                                $class_id   = $student['class_id'];
                                
                                $this->student_fee_set($session_id, $group_id, $class_id, $student_id);

                            }
                        }
    				}
    				
    				//$this->db->insert('fee_conf', $dataToSave);
    				
    			}

    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/feeconf/', 'refresh');
    		}elseif($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('item', array(
    					'componentId' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('componentId', $param2);
    			$this->db->delete('item');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/feeconf/', 'refresh');
    		}
    		
    		$session_id = -1;
    		
    		if($this->input->post('session_id')){
    			$session_id = $this->input->post('session_id');
    		}else{
    			$sessionLine  = $this->db->get_where('settings',['type'=>'current_year'])->result_array();
    			$session_id = $sessionLine['description'];
    		}
    		
    		$class_id = -1;
    		
    		if($this->input->post('class_id')){
    			$class_id = $this->input->post('class_id');
    		}

            $group_id = -1;

            if($this->input->post('group_id')){
                $group_id = $this->input->post('group_id');
            }
    		$page_data['class_id'] = $class_id;
    		
    		$category2 = 'SCHOOL';
    		if($this->input->post('category2')){
    			$category2 = $this->input->post('category2');
    		}

            $page_data['groupClass']    = $this->getGroupClass();
    		
    		$cats = array('SCHOOL'=>'School', 'CLASS'=>$page_data['groupClass'][0][value]);
    		
    		$page_data['cats'] = $cats;
    		
    		$page_data['category2'] =$category2;
    		
    		$fees = array();
    		$amts = array();
    		$tmp_amts = array();


    		if($session_id > 0 && ($category2 == 'SCHOOL' || ($category2 == 'CLASS' && $class_id >0 && $group_id < 0) || ($category2 == 'CLASS' && $class_id > 0 && $group_id > 0))){

                $fees = array();

                if($class_id > 0 && $group_id > 0){
                    $fees = $this->db->get_where('item', ['category1'=>'FEE', 'category2' => 'GROUP'])->result_array();
                }elseif($class_id > 0 && $group_id < 0){
                    $fees = $this->db->get_where('item', ['category1'=>'FEE', 'category2' =>$category2])->result_array();
                }
  				if($category2 == 'SCHOOL'){
                    $fees = $this->db->get_where('item', ['category1'=>'FEE', 'category2' => $category2])->result_array();

                    $tmp_amts = "select * from fee_conf
                    where session_id = $session_id and group_id < 0 and isnull(class_id)";

                    $tmp_amts = $this->db->query($tmp_amts)->result_array();


  				}
  				
  				elseif($category2 == 'CLASS' && $class_id > 0){
  					$tmp_amts = $this->db->get_where('fee_conf', ['class_id'=>$class_id,'session_id'=>$session_id, 'group_id' => $group_id])->result_array();

  				}
    		}

    		foreach ($tmp_amts as $amt)
    			$amts[$amt['item_id']] = $amt['amount'];
    		
    		$page_data['fees'] = $fees;
    		$page_data['amts'] = $amts;
    		
    		$page_data['sessions']    = $this->db->get('session')->result_array();
    		
    		
    		$classes =  $this->db->get('class')->result_array();
            $groups  = $this->db->get('class_group')->result_array();
            $page_data['grouplist'] = $groups;
    		$page_data['classlist'] = $classes;
            $page_data['session_id'] = $session_id;
    		$page_data['group_id'] = $group_id;
    		$page_data['items']    = $this->db->get('item')->result_array();
    		$page_data['page_name']  = 'feeconf';
    		$page_data['page_title'] = get_phrase('fee_configuration');
    		$this->load->view('backend/index', $page_data);
    }
    
    
    
    function session($param1 = '', $param2 = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		if ($param1 == 'create') {

                $data['uniqueCode']  = $this->input->post('uniqueCode');
                $code = $data['uniqueCode'];
                $data['start']       = date('Y-m-d', strtotime($this->input->post('start')));
                $data['end']         = date('Y-m-d', strtotime($this->input->post('end')));

    			
    			$this->db->insert('session', $data);
    			$conponentId = $this->db->insert_id();
    
    			$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    			redirect(base_url() . 'index.php?admin/session/', 'refresh');
    		}
    		if ($param1 == 'do_update') {
  
    			$data['uniqueCode']         = $this->input->post('uniqueCode');
    			$data['start']   = date('Y-m-d', strtotime($this->input->post('start')));
    	
    			$data['end']   = date('Y-m-d', strtotime($this->input->post('end')));



    			
    			$this->db->where('componentId', $param2);
    			$this->db->update('session', $data);
    			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    			redirect(base_url() . 'index.php?admin/session/', 'refresh');
    			
    		} else if ($param1 == 'edit') {
    			$page_data['edit_data'] = $this->db->get_where('session', array(
    					'componentId' => $param2
    			))->result_array();
    		}
    		if ($param1 == 'delete') {
    			$this->db->where('componentId', $param2);
    			$this->db->delete('session');
    			$this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
    			redirect(base_url() . 'index.php?admin/session/', 'refresh');
    		}
    		 $page_data['sessions']    = $this->db->get('session')->result_array();
    		 $page_data['page_name']  = 'session';
    		 $page_data['page_title'] = get_phrase('session_management');
    		 $this->load->view('backend/index', $page_data);
    }
	
	 /****MANAGE feedues*****/
    function feedues($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
      
	   $feeDues = "SELECT s.student_id, s.name,s.fathername,s.phone, SUM(sfc.amount) AS dueAmount 
							 FROM student_feeconfig sfc
							 INNER JOIN student s ON (sfc.studentId = s.student_id)
							 INNER JOIN user u ON (u.reference_id = sfc.studentId AND u.user_type = 'STUDENT')
							 LEFT JOIN transaction_detail td ON (u.user_id = td.userId AND sfc.itemId = td.itemId AND month(str_to_date(sfc.month,'%M')) = td.month AND sfc.year = td.year)
							 WHERE DATEDIFF(NOW(), STR_TO_DATE(CONCAT(sfc.year,'/',sfc.month,'/01'),'%Y/%M/%d')) > 30
							  AND td.componentId IS NULL
							 GROUP BY s.student_id
							 ORDER BY dueAmount DESC";
					
		$feeDuesquery = $this->db->query($feeDues);
		$feeDuesData = $feeDuesquery->result ();
		
		
		$page_data['feeDuesData']   = $feeDuesData;
		$page_data['page_name']  = 'feedues';
        $page_data['page_title'] = get_phrase('feeDues');
  
        $this->load->view('backend/index', $page_data);
  
    }
    
	
	
    function feecollection($campus_id = '')
    {
    	if ($this->session->userdata('admin_login') != 1)
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

            $page_data['page_name']     = 'fee_collection';
            $page_data['page_title']    = get_phrase('fee_collection'). " - ".get_phrase('class')." : ".$this->crud_model->get_class_name($class_id);
    		
    		$this->load->view('backend/index', $page_data);
    		
    }
    
    function save_student_fee($param='')
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect('login', 'refresh');
    			
    	if($param == 'create') {
    		$trdata['description']     	= 'Student Fee';
    		$trdata['tdate']            = date('Y-m-d', strtotime($this->input->post('timestamp')));
    		$trdata['uniqueCode']		= Applicationconst::TRANSACTION_TYPE_FEE.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_FEE);
    		$trdata['type']		= Applicationconst::TRANSACTION_TYPE_FEE;
    		$this->db->insert('transaction', $trdata);
    		$transaction_id = $this->db->insert_id();
    	
	    	$student_id = $this->input->post('student_id');
            $student_code = $this->db->get_where('student', array('student_id' => $student_id))->row()->student_code;

	    	$items = $this->input->post('fee');

	    	foreach ($items as $i) {
	    				$amt = 0.0;
	    				$detaildata = array();
	    				$detaildata['transactionId']        =  $transaction_id;
	    				$detaildata['itemId']        		=  $this->input->post('item_'.$i);
	    				$detaildata['accountId']        	=  Applicationconst::ACCOUNT_HEAD_RECEIVABLE;
	    				$detaildata['userId']             	=  $this->db->get_where('user', array('reference_id' => $student_id))->row()->user_id;
	    				$detaildata['type']       			=  -1;
	    				$detaildata['month']      			=  date('n',strtotime($this->input->post('month_'.$i)));
	    				$detaildata['year']      			=  $this->input->post('year_'.$i);
	    				$detaildata['quantity']            	=  1;
	    				$detaildata['unitPrice']            =  $this->input->post('amount_'.$i);
	    				
	    				$amt +=  $this->input->post('amount_'.$i);
	    		
	    				$this->db->insert('transaction_detail' , $detaildata);
    		
    		if($amt > 0 ){
    			$detaildata = array();
    			$detaildata['transactionId']        =  $transaction_id;
    			$detaildata['itemId']        		=  Applicationconst::ITEM_CASH;
    			$detaildata['accountId']        	=  Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
    			$detaildata['userId']             	=  Applicationconst::USER_COMPANY;
    			$detaildata['type']       			=  1;
    			$detaildata['month']      			=  date('n',strtotime($this->input->post('month_'.$i)));
    			$detaildata['year']      			=  $this->input->post('year_'.$i);
    			$detaildata['quantity']            	=   1;
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
    	//$transaction_info = $this->db->get_where('student', array('student_id' => $student_id))->result_array();
  //   	$this->db->select('student.name, student.student_code,student.student_id, fee_record.*, transaction.*');
  //   		$this->db->from('student');
  //   		$this->db->join('fee_record', 'fee_record.student_id = student.student_id');
  //   		$this->db->join('transaction', 'fee_record.transaction_id = transaction.componentId');
  //   		$this->db->where('student.student_code', 555);
  //   		$this->db->distinct();
  //   		$transaction_info = $this->db->get()->result_array();
		// $page_data['transaction_info'] = $transaction_info;

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

    function student_feeConfig()
    {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		 
    		$campus_id = -1;
    		$session_id = -1;
    		$class_id = -1;
    		$group_id = -1;
    		//$student_code = -1;
    		if($this->input->post('campus_id') !=null)
    			$campus_id = $this->input->post('campus_id');
    			if($this->input->post('session_id') !=null)
    				$session_id = $this->input->post('session_id');
    				if($this->input->post('class_id') !=null)
    					$class_id = $this->input->post('class_id');
    					
    					if($this->input->post('group_id') !=null)
    						$group_id = $this->input->post('group_id');
//     						if($this->input->post('section_id') !=null)
//     							$section_id = $this->input->post('section_id');
								if($this->input->post('student_code') !=null);	
								$student_code = $this->input->post('student_code');
    		
    							$campus = $this->db->get('campus')->result_array();    		
    							$page_data['campuslist'] = array(''=>'Select one');
    							foreach($campus as $row):
    							$page_data['campuslist'][$row['id']] = $row['campus_name'];
    							endforeach;
    							$page_data['id'] = $campus_id;
    		
    							$session = $this->db->get('session')->result_array();
    							$page_data['sessions'] = array(''=>'Select one');
    							foreach($session as $row):
    							$page_data['sessions'][$row['id']] = $row['uniqueCode'];
    							endforeach;
    							$page_data['session_id'] = $session_id;
    							
    							$classinfo = $this->db->get_where('class', array('campus_id' => $campus_id))->result_array();  							   			
    							$page_data['classes'] = array(''=>'Select one');		
    							foreach($classinfo as $row): 
    							$page_data['classes'][$row['class_id']] = $row['name'];
    							endforeach;
    							$page_data['allclass']=$page_data['classes'];    							
    							$page_data['class_id'] = $class_id;
    							
    							$groups = $this->db->get_where('class_group')->result_array();
    							$page_data['groups'] = array(''=>'Select one');
    							
    							foreach($groups as $row):
    							$page_data['groups'][$row['id']] = $row['group_name'];
    							endforeach;
    							$page_data['group_id'] = $group_id;
    								    							
    							if($student_code != null) {
    								$this->db->select('student.name, student_feeconfig.*');
    								$this->db->from('student');
    								$this->db->join('student_feeconfig', 'student.student_id = student_feeconfig.studentId');

    								$this->db->where('student.student_code', $student_code);
    								$feeInfo = $this->db->get()->result_array();
    								$page_data['feeInfo'] = $feeInfo;

    							}


    		$page_data['page_name']  = 'student_feeconf';
    		$page_data['page_title'] = get_phrase('fee_management');
    		$this->load->view('backend/index', $page_data);
    }
    
    
   
    function studentfee_manage($param = '', $param2 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	if($param=='update') 
    	{
    		$data ['amount'] = $this->input->post ( 'amount' );
    		$this->db->where('id', $param2);
    		$this->db->update('student_feeConfig', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    		redirect(base_url() . 'index.php?admin/studentfee_manage/', 'refresh');
    	}
    	if($param=='delete')
    	{
    		$this->db->where('id', $param2);
    		$this->db->delete('feeConfig');
    		$this->db->update('student_feeConfig', $data);
    	}
    	$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    	redirect(base_url() . 'index.php?admin/studentfee_manage/', 'refresh');
    }
    function codeElement($key = ''){
    	$this->db->select('*');
    	$this->db->from('codes');
    	$this->db->where('key_name', $key);
    	$query = $this->db->get()->result_array();
    	return $query;
    }
    function receipt_and_payment()
    {

        $page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );      
        if ($this->input->post ( 'start_date' ) != null) {              
            $page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
        }
        
        $page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );     
        if ($this->input->post ( 'end_date' ) != null) {                    
            $page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
        }


        //*** final opening balance sql****//

        $obSql = "SELECT ROUND(IFNULL(SUM(td.`type`*td.quantity*td.unitPrice),0),2) AS ob
                    FROM transaction_detail td
                    INNER JOIN transaction t ON (td.transactionId = t.componentId)
                    INNER JOIN account a ON (td.accountId = a.componentId)
                    WHERE a.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND a.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "' and t.tdate < '".$page_data ['start_date']."' ";

        $obquery = $this->db->query($obSql);
        $page_data ['obData'] = $obquery->row_array();
        
	
            //** receipt part account and amount
        $recBalanceSql = "SELECT uniqueCode, ROUND(SUM(quantity*unitPrice),2) AS balance FROM(
                		SELECT DISTINCT td.componentId, td.accountId, td.itemId, td.transactionId, a.uniqueCode, td.quantity, td.unitPrice
                		FROM transaction_detail td
                		INNER JOIN transaction t ON (td.transactionId = t.componentId)
                		INNER JOIN account a ON (td.accountId = a.componentId)
                		INNER JOIN transaction_detail tdc ON (td.transactionId = tdc.transactionId AND tdc.`type` = 1)
                		INNER JOIN account ac ON (tdc.accountId = ac.componentId)
                		WHERE td.`type` = -1 AND ac.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND ac.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "'
                		AND t.tdate BETWEEN '".$page_data['start_date']."' AND '".$page_data['end_date']."'
                		) a
                		GROUP BY a.uniqueCode";
              //  echo $recBalanceSql;                

                    $recBalancequery = $this->db->query($recBalanceSql);
                    $page_data ['recBalanceData'] = $recBalancequery->result ();


            //** payment part account and amount **//

                $payBalance = "SELECT uniqueCode, ROUND(SUM(quantity*unitPrice),2) AS balance FROM(
                		SELECT DISTINCT td.componentId, td.accountId, td.itemId, td.transactionId, a.uniqueCode, td.quantity, td.unitPrice
                		FROM transaction_detail td
                		INNER JOIN transaction t ON (td.transactionId = t.componentId)
                		INNER JOIN account a ON (td.accountId = a.componentId)
                		INNER JOIN transaction_detail tdc ON (td.transactionId = tdc.transactionId AND tdc.`type` = -1)
                		INNER JOIN account ac ON (tdc.accountId = ac.componentId)
                		WHERE td.`type` = 1 AND ac.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND ac.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "'
                		AND t.tdate BETWEEN '".$page_data ['start_date']."' AND '".$page_data ['end_date']."'
                		) a
                		GROUP BY a.uniqueCode";

        $payBalancequery = $this->db->query($payBalance);
        $page_data ['payBalanceData'] = $payBalancequery->result ();
        
        //*** final opening balance sql****//
        
        $fbSql = "SELECT ROUND(IFNULL(SUM(td.`type`*td.quantity*td.unitPrice),0),2) AS fb
                    FROM transaction_detail td
                    INNER JOIN transaction t ON (td.transactionId = t.componentId)
                    INNER JOIN account a ON (td.accountId = a.componentId)
                    WHERE a.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND a.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "' and t.tdate <= '".$page_data ['end_date']."' ";
        
        $fbquery = $this->db->query($fbSql);
        $page_data ['fbData'] = $fbquery->row_array();

    	//$payquery = $this->db->query ( $paySql );
    	//$page_data ['payData'] = $payquery->result ();
		$page_data['page_name']  = 'dashboard';
    	$page_data['page_name']  = 'receipt_and_payment';
    	$page_data['page_title'] = get_phrase('receipt_and_payment');
    	$this->load->view('backend/index', $page_data);
    } 
   
    /*Academic Calednar added by Nishan*/
    function academic_calendar($param1 = '', $param2 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    	
    	if($param1 == 'create')	{
    		$data['event'] = $this->input->post('event');
    		$data['event_type'] =$this->input->post('event_type');
    		$data['recurring'] =$this->input->post('recurring');
    		$data['start_date'] = $this->input->post('start_date');
    		$data['end_date'] = $this->input->post('end_date');
    		$data['class_off'] =$this->input->post('class_off');
    		$data['school_off'] =$this->input->post('school_off');
    		

    		$this->db->insert('academic_calendar', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    		redirect(base_url() . 'index.php?admin/academic_calendar/', 'refresh');

    	}
    	
    	if ($param1 == 'update') {
    		$data['event'] = $this->input->post('event');
    		$data['event_type'] =$this->input->post('event_type');
    		$data['recurring'] =$this->input->post('recurring');
    		$data['start_date'] = $this->input->post('start_date');
    		$data['end_date'] = $this->input->post('end_date');
    		$data['class_off'] =$this->input->post('class_off');
    		$data['school_off'] =$this->input->post('school_off');
    		$event_id = $this->input->post('event_id');
    		
    		$this->db->where('ac_calendar_id', $event_id);
    		$this->db->update('academic_calendar', $data);
    		$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
    		redirect(base_url() . 'index.php?admin/academic_calendar/', 'refresh');
    	}
    	
    	if ($param1 == 'delete') {
    		$this->db->where('ac_calendar_id', $param2);
    		$this->db->delete('academic_calendar');
    		$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    		redirect(base_url() . 'index.php?admin/academic_calendar/', 'refresh');
    	}
		$eventinfo = $this->db->get('academic_calendar')->result_array();
    	$event_types = $this->codeElement('event.type');
    	$recurrings = $this->codeElement('recurring.type');
    	$page_data['eventinfo'] = $eventinfo;
    	$page_data['event_types'] = $event_types;
    	$page_data['recurrings'] = $recurrings;
    	$page_data['page_name']  = 'manage_academic_calendar';
    	$page_data['page_title'] = get_phrase('academic_caledar');
    	$this->load->view('backend/index', $page_data);
    }
    
    function view_academic_calendar() {
    	$eventinfo = $this->db->get('academic_calendar')->result_array();
    	$page_data['eventinfo'] = $eventinfo;
    	$page_data['page_name']  = 'view_academic_calendar';
    	$page_data['page_title'] = get_phrase('academic_caledar');
    	$this->load->view('backend/index', $page_data);
    }
    
    function recpay() {
    	$page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );   	
    	if ($this->input->post ( 'start_date' ) != null) {   			
    		$page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
    	}
    	
    	$page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );  	
    	if ($this->input->post ( 'end_date' ) != null) {    				
    		$page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
    	}

    	$obSql = "SELECT d.transactionId, d.userId, d.accountId, d.itemId, d.quantity, d.unitPrice, d.`type`, 
					a.uniqueCode, a.category2, i.category1, i.category2, i.category3, t.tdate,
					ROUND(SUM(CASE WHEN t.tdate < '" . $page_data ['start_date'] . "' THEN d.quantity* d.unitPrice*d.type ELSE 0 END),2) AS ob
					FROM transaction_detail d
					INNER JOIN item i ON (d.itemId = i.componentId)
					INNER JOIN  transaction_detail d1 ON (d.transactionId = d1.transactionId AND d1.`type` = 1)
					INNER JOIN account a ON (d1.accountId = a.componentId)
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
					WHERE d.`type` = -1 AND a.category2 = 'CURRENT ASSET'";
    	
                    
                    $recSql = "SELECT d.transactionId, d.userId, d.accountId, d.itemId, d.quantity, d.unitPrice, d.`type`, 
                    a.uniqueCode, a.category1, a.category2, a.category3,
                    i.itemName, i.category1, i.category2, i.category3, t.tdate, 

                    ROUND(SUM(CASE WHEN t.tdate BETWEEN '".$page_data ['start_date']."' AND '".$page_data ['end_date']."' THEN -1*d.`type`*d.quantity*d.unitPrice ELSE 0 END),2) AS amt,

                    ROUND(SUM(CASE WHEN d.type = -1 AND (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as dr
                    FROM transaction_detail d
                    INNER JOIN item i ON (d.itemId = i.componentId)
                    INNER JOIN  transaction_detail d1 ON (d.transactionId = d1.transactionId AND d1.`type` = 1)
                    INNER JOIN account a ON (d1.accountId = a.componentId)
                    INNER JOIN transaction t ON (d.transactionId = t.componentId)
                    WHERE d.`type` = -1 AND a.category2 = 'CURRENT ASSET' AND t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "'
                    GROUP BY i.itemName
                    ORDER BY transactionId";

                     //echo "$recSql";
                     //exit();

// // **** SHARIF SIR SQL ****
    	$paySql = "SELECT d.transactionId, d.userId, d.accountId, d.itemId, d.quantity, d.unitPrice, d.`type`, 
					a.uniqueCode, a.category1, a.category2, a.category3,
					i.itemName, i.category1, i.category2, i.category3, t.tdate,
    				ROUND(SUM(CASE WHEN d.`type` = -1 AND (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "') THEN d.quantity* d.unitPrice ELSE 0 END),2) as amt,
					ROUND(SUM( CASE WHEN t.tdate <= '" . $page_data ['end_date'] . "' THEN -1*d.`type`*d.quantity*d.unitPrice ELSE 0 END),2) cr
					FROM transaction_detail d
					INNER JOIN item i ON (d.itemId = i.componentId)
					INNER JOIN  transaction_detail d1 ON (d.transactionId = d1.transactionId AND d1.`type` = -1)
					INNER JOIN account a ON (d1.accountId = a.componentId)
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
					WHERE d.`type` = 1 AND a.category2 = 'CURRENT ASSET' AND t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "'
    				GROUP BY i.itemName
					ORDER BY transactionId";

    	$obquery = $this->db->query($obSql);
    	$page_data ['obData'] = $obquery->row_array();
    	$recquery = $this->db->query ( $recSql );
    	$page_data ['recData'] = $recquery->result ();
    	$payquery = $this->db->query ( $paySql );
    	$page_data ['payData'] = $payquery->result ();

    	$page_data['page_name']  = 'receipt_and_payment';
    	$page_data['page_title'] = get_phrase('receipt_and_payment');
    	$this->load->view('backend/index', $page_data);
    }
    
    function journal($param = '', $param2 = '') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect('login', 'refresh');  		
    		
    		$page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
    		if ($this->input->post ( 'start_date' ) != null) {
    			$page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
    		}
    			
    		$page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
    		if ($this->input->post ( 'end_date' ) != null) {
    			$page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
    		}
    				 
    		$page_data['inputs']['search'] = ['type'=>'hidden','fielddata'=>['name' => 'search', 'id' => 'search', 'value' => $page_data['search']]];
    		$page_data['inputs']['start_date'] = ['type'=>'textfield','label'=>'Start  date','fielddata'=>['name' => 'start_date', 'id' => 'start_date', 'value' => $page_data['start_date'] ]];
    		$page_data['inputs']['end_date'] = ['type'=>'textfield','label'=>'End  date','fielddata'=>['name' => 'etdate', 'id' => 'end_date', 'value' => $page_data['end_date'] ]];

    			$searchSQL = "SELECT componentId, description, uniqueCode, tdate, type,  amount
				FROM vtransactions
				WHERE (tdate BETWEEN '".$page_data['start_date']."' AND '".$page_data['end_date']."' )  ORDER BY tdate DESC";
    							 
    			//$pageSQL = " LIMIT ".($page_data['pageNo']-1)*$page_data['limit'].",  ".$page_data['limit'];
    			$query = $this->db->query ( $searchSQL );
    			$page_data ['total'] = $query->num_rows ();
    			$query1 = $this->db->query ( $searchSQL);
    			$page_data ['searchData'] = $query1->result ();

    	if($param == 'create') {
    		
    		
    		$trdata['description']     	= $this->input->post('particular');
    		$trdata['tdate']            = date('Y-m-d',strtotime($this->input->post('timestamp')));
    		$trdata['uniqueCode']		= Applicationconst::TRANSACTION_TYPE_JOURNAL.'-'.$this->getSequence(Applicationconst::TRANSACTION_TYPE_JOURNAL);
    		$trdata['type']				= Applicationconst::TRANSACTION_TYPE_JOURNAL;
    		$this->db->insert('transaction', $trdata);
    		$transaction_id = $this->db->insert_id();

            $itemc = $this->input->post('itemc');
            $userc = $this->input->post('userc');
            $accountc = $this->input->post('accountc');
            $amountc = $this->input->post('itemc'); 
            $unitPricec = $this->input->post('unitPricec');
            $quantityc = $this->input->post('quantityc');
    

    		for ($i = 0; $i < count($itemc); $i++) { 

    			$detaildata = array();
    			$detaildata['transactionId']        =  $transaction_id;
    			$detaildata['itemId']        		=  $itemc[$i];
    			$detaildata['accountId']        	=  $accountc[$i];
    			$detaildata['userId']             	=  $userc[$i];;
    			$detaildata['type']       			=  -1;
    			$detaildata['month']      			=  date('m', strtotime($this->input->post('timestamp')));
    			$detaildata['year']      			=  date('Y', strtotime($this->input->post('timestamp')));
    			$detaildata['quantity']            	=  $quantityc[$i];
    			$detaildata['unitPrice']            =  $unitPricec[$i];
    			$this->db->insert('transaction_detail' , $detaildata);
    			
    		}

            $itemd = $this->input->post('itemd');
            $amountd = $this->input->post('amountd');
            $unitPriced = $this->input->post('unitPriced');
            $quantityd = $this->input->post('quantityd');
            $accountd = $this->input->post('accountd');
            $userd = $this->input->post('userd');

            for ($i = 0; $i < count($itemd); $i++) { 

                $detaildata = array();
                $detaildata['transactionId']        =  $transaction_id;
                $detaildata['itemId']               =  $itemd[$i];
                $detaildata['accountId']            =  $accountd[$i];
                $detaildata['userId']               =  $userd[$i];
                $detaildata['type']                 =  1;
                $detaildata['month']                =  date('m', strtotime($this->input->post('timestamp')));
                $detaildata['year']                 =  date('Y', strtotime($this->input->post('timestamp')));
                $detaildata['quantity']             =  $quantityd[$i];
                $detaildata['unitPrice']            =  $unitPriced[$i];
                $this->db->insert('transaction_detail' , $detaildata);

            }

    		$this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
    		redirect(base_url() . 'index.php?admin/journal', 'refresh');
    	}

        if ($param == 'do_update') {

            $data['transaction']['componentId'] = $this->input->post('trComponentId');
            $data['transaction']['description']    = $this->input->post('particular');
            $data['transaction']['tdate']     = $this->input->post('timestamp');

            $transactionId = $this->input->post('trComponentId');
            $existingRows = $this->db->get_where('transaction_detail', array('transactionId' => $transactionId))->result_array();
        

            $userd = $this->input->post('userd');
            $accountd = $this->input->post('accountd');
            $itemd = $this->input->post('itemd');
            $unitPriced = $this->input->post('unitPriced');
            $quantityd = $this->input->post('quantityd');
            $detailComponentIdd = $this->input->post('detailComponentIdd');

            
            $data['preComponentId'] = array();
            foreach ($existingRows as $row) {
                $preComponentId = array();
                $preComponentId['componentId'] = $row['componentId'];

                array_push($data['preComponentId'], $preComponentId);
            }

            $data['details'] = array();
            for ($i=1; $i < count($itemd); $i++) { 
                $detailData = array();

                $detailData['componentId'] = $detailComponentIdd[$i];
                $detailData['userId']      = $userd[$i];
                $detailData['accountId']   = $accountd[$i];
                $detailData['itemId']      = $itemd[$i];
                $detailData['unitPrice']   = $unitPriced[$i];
                $detailData['quantity']    = $quantityd[$i];
                $detailData['type']        = 1;
                $detailData['transactionId'] = $transactionId;
                $detailData['month']       =  date('m', strtotime($this->input->post('timestamp')));
                $detailData['year']        =  date('Y', strtotime($this->input->post('timestamp')));

                array_push($data['details'], $detailData);
            }
            

            $userc = $this->input->post('userc');
            $accountc = $this->input->post('accountc');
            $itemc = $this->input->post('itemc');
            $unitPricec = $this->input->post('unitPricec');
            $quantityc = $this->input->post('quantityc');
            $detailComponentIdc = $this->input->post('detailComponentIdc');

            for ($i=1; $i < count($itemc); $i++) { 

                $detailData['componentId'] = $detailComponentIdc[$i];
                $detailData['userId']      = $userc[$i];
                $detailData['accountId']   = $accountc[$i];
                $detailData['itemId']      = $itemc[$i];
                $detailData['unitPrice']   = $unitPricec[$i];
                $detailData['quantity']    = $quantityc[$i];

                $detailData['type']        = -1;
                $detailData['transactionId'] = $transactionId;
                $detailData['month']       =  date('m', strtotime($this->input->post('timestamp')));
                $detailData['year']        =  date('Y', strtotime($this->input->post('timestamp')));

                array_push($data['details'], $detailData);
            }

            $this->load->model('journaledit');
            $this->journaledit->save($data);
            
        }

         if ($param == 'delete') {
            $this->db->where('transactionId', $param2);
            $this->db->delete('transaction_detail');

            $this->db->where('componentId', $param2);
            $this->db->delete('transaction');

            $this->db->where('transaction_id', $param2);
            $this->db->delete('fee_record');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/journal/', 'refresh');
        }

        $page_data['users'] = $this->db->get('user')->result_array();
        $page_data['accounts'] = $this->db->get('account')->result_array();
        $page_data['items'] = $this->db->get('item')->result_array();

    	
    	$page_data['page_name']  = 'journal';
    	$page_data['page_title'] = get_phrase('journal');
    	$this->load->view('backend/index', $page_data);
    }

    
    function ledger() {
    	
    	$page_data ['search'] = '';
   	   	
    	$page_data ['accountId'] = Applicationconst::ACCOUNT_HEAD_CASH_IN_HAND;
    	
    	if ($this->input->get ( 'accountId' ) != null)
    	
    		$page_data ['accountId'] = $this->input->get ( 'accountId' );
    	
    	if ($this->input->post ( 'accountId' ) != null)
    				
    		$page_data ['accountId'] = $this->input->post ( 'accountId' );
    	
    		$page_data ['userId'] = 1;
    	
    	if ($this->input->get ( 'userId' ) != null)
    	
    		$page_data ['userId'] = $this->input->get ( 'userId' );
    	
    	if ($this->input->post ( 'userId' ) != null)
    						
    		$page_data ['userId'] = $this->input->post ( 'userId' );
    		
    		$page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-3 days" ) );
    		if ($this->input->post ( 'start_date' ) != null) {
    			$page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
    		}
    		 
    		$page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
    		if ($this->input->post ( 'end_date' ) != null) {
    			$page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
    		}
    		$page_data ['account'] = array ();
    		
    		foreach ( $this->db->get( 'account' )->result_array() as $row ) {
    				
    			$page_data ['account'] [$row->componentId] = $row->uniqueCode;
    		}
    		
    		$page_data ['user'] = array (
    				- 1 => 'Select user'
    		);
    		
    		foreach ( $this->db->get( 'user' )->result_array() as $row ) {
    				
    			$page_data ['user'] [$row->user_id] = $row->user_name;
    		}
    		
    		$filter = " AND accountId = " . $page_data ['accountId'] . " ";
    		
    		if ($page_data ['userId'] != null)
    				
    			$filter .= " AND userId = " . $page_data ['userId'] . " ";
    		
    			$transSQL = "SELECT t.componentId, t.tdate, t.description, t.uniqueCode,
    		
						ROUND(CASE WHEN d.type =1 THEN d.quantity* d.unitPrice ELSE NULL END,2) as dr,
    		
	  					ROUND(CASE WHEN d.type =-1 THEN d.quantity* d.unitPrice ELSE NULL END,2) as cr,
    		
						CASE WHEN a.category1='REVENUE' OR a.category1='LIABILITY' THEN -1*d.`type`*d.quantity*d.unitPrice ELSE d.`type`*d.quantity*d.unitPrice END amt
    		
					FROM transaction_detail d
    		
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
					INNER JOIN account a  ON (d.accountId = a.componentId)
    		
					WHERE (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "' ) $filter
    		
    							ORDER BY tdate DESC";
    		
// 				 echo $transSQL;
// 				 exit();
    		
    			$cbSQL = "SELECT CASE WHEN a.category1='REVENUE' OR a.category1='LIABILITY' THEN ROUND(-1*SUM(d.quantity*d.unitPrice*d.type),2) ELSE ROUND(SUM(d.quantity*d.unitPrice*d.type),2) END closingBalance
    		
					FROM transaction_detail d
    		
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
    				INNER JOIN account a  ON (d.accountId = a.componentId)
					WHERE t.tdate <= '" . $page_data ['end_date'] . "' $filter";
    				
    			$obSQL = "SELECT CASE WHEN a.category1='REVENUE' OR a.category1='LIABILITY' THEN ROUND(-1*SUM(d.quantity*d.unitPrice*d.type),2) ELSE ROUND(SUM(d.quantity*d.unitPrice*d.type),2) END openineBalance
    		
					FROM transaction_detail d
    		
					INNER JOIN transaction t ON (d.transactionId = t.componentId)
    				INNER JOIN account a  ON (d.accountId = a.componentId)
					WHERE t.tdate < '" . $page_data ['start_date'] . "' $filter";
    		
    			$query = $this->db->query ( $transSQL );
    		
    			$page_data ['searchData'] = $query->result ();
				
    			$queryOb = $this->db->query ( $obSQL );
    		
    			$page_data ['ob'] = 0.0;
    		
    			$page_data ['cb'] = 0.0;
    			
    			foreach ( $queryOb->result () as $row ) {
    					
    				$page_data ['ob'] = $row->openineBalance;
    				if($page_data ['ob']){
    					$page_data ['ob'] = $row->openineBalance;
    				}
    				else {
    					$page_data ['ob'] = 0.0;
    				}
    			}
    			
    			$queryCb = $this->db->query ( $cbSQL );
    		
    			foreach ( $queryCb->result () as $row ) {
    					
    				$page_data ['cb'] = $row->closingBalance;
    			}
    		
    			$balance = $page_data ['cb'];
    		
    			foreach ( $page_data ['searchData'] as $row ) {
    					
    				$amt = $row->amt;
    					
    				$row->amt = $balance;
    					
    				$balance -= $amt;
    			}
    	
    	$page_data['page_name']  = 'ledger';
    	$page_data['page_title'] = get_phrase('ledger');
    	$this->load->view('backend/index', $page_data);
    }
    
    function profitloss() {
    	$data ['search'] = '';
    	$page_data ['start_date'] = date ( 'Y-m-d', strtotime ( "-1 month" ) );
    	if ($this->input->post ( 'start_date' ) != null) {
    		$page_data ['start_date'] = date('Y-m-d', strtotime($this->input->post ( 'start_date' )));
    	}
    	 
    	$page_data ['end_date'] = date ( 'Y-m-d', strtotime ( "0 days" ) );
    	if ($this->input->post ( 'end_date' ) != null) {
    		$page_data ['end_date'] = date('Y-m-d', strtotime($this->input->post ( 'end_date' )));
    	}
    	
    	$salesSummerySQL = "SELECT itemId, i.itemName,
    	
						ROUND(SUM(CASE WHEN accountId = 14 THEN quantity*unitPrice ELSE 0 END),2) AS sales,
    	
						ROUND(SUM(CASE WHEN accountId = 8 THEN quantity*unitPrice ELSE 0 END),2) AS commission,
    	
    					ROUND(SUM(CASE WHEN a.category2 = '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "' AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " THEN quantity*unitPrice ELSE 0 END),2) AS opexp,
    	
    					ROUND(SUM(CASE WHEN a.category1 = '" . Applicationconst::ACCOUNT_CAT1_EXPENSE . "' AND a.category2 != '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "'  AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " THEN quantity*unitPrice ELSE 0 END),2) AS otherexp,
    	
					ROUND(SUM(getCOGS(itemId)*CASE WHEN accountId = 13 THEN quantity ELSE 0 END),2) AS cogs
    	
					FROM transaction_detail d
    	
    				INNER JOIN transaction t ON (d.transactionId = t.componentId)
    	
					INNER JOIN item i ON (i.componentId = d.itemId)
    	
    				INNER JOIN account a ON (a.componentId = d.accountId)
    	
					WHERE (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "' ) "; 
    	 //echo $salesSummerySQL;
        //cut from 4293 line previous line of from transaction details  ROUND(SUM(getCOGS(itemId)*CASE WHEN accountId = 13 THEN quantity ELSE 0 END),2) AS cogs
    	
    	$page_data ['sales'] = 0.0;
    	
    	$page_data ['commission'] = 0.0;
    	
    	$page_data ['cogs'] = 0.0;
    	
    	$page_data ['otherexp'] = 0.0;
    	
    	$page_data ['opexp'] = 0.0;
    	
    	   	
    	$querySummery = $this->db->query ( $salesSummerySQL );
    	
    	foreach ( $querySummery->result () as $row ) {
    			
    		$page_data ['sales'] = $row->sales;
    			
    		$page_data ['commission'] = $row->commission;
    			
    		$page_data ['cogs'] = $row->cogs;
    			
    		$page_data ['otherexp'] = $row->otherexp;
    			
    		$page_data ['opexp'] = $row->opexp;
    	}
    	
    	$page_data ['revenue'] = $page_data ['sales'] + $page_data ['commission'];
    	
    	$page_data ['grossProfit'] = $page_data ['revenue'] - $page_data ['opexp'];
    	
    	$page_data ['netProfit'] = $page_data ['grossProfit'] - $page_data ['otherexp'];
    	
    	$page_data ['revenue'] = number_format ( ( float ) $page_data ['revenue'], 2 );
    	
    	$page_data ['grossProfit'] = number_format ( ( float ) $page_data ['grossProfit'], 2 );
    	
    	$page_data ['netProfit'] = number_format ( ( float ) $page_data ['netProfit'], 2 );
    	
    	$page_data ['sales'] = number_format ( ( float ) $page_data ['sales'], 2 );
    	
    	$page_data ['cogs'] = number_format ( ( float ) $page_data ['cogs'], 2 );
    	
    	$page_data ['commission'] = number_format ( ( float ) $page_data ['commission'], 2 );
    	
    	$page_data ['otherexp'] = number_format ( ( float ) $page_data ['otherexp'], 2 );
    	
    	$page_data ['opexp'] = number_format ( ( float ) $page_data ['opexp'], 2 );
    	
    	$salesSQL = "SELECT itemId, i.itemName,
    	
						ROUND(SUM(CASE WHEN accountId = 14 THEN quantity*unitPrice ELSE 0 END),2) AS sales,
    	
						ROUND(SUM(CASE WHEN accountId = 8 THEN quantity*unitPrice ELSE 0 END),2) AS commission
    	
						
    	
					FROM transaction_detail d
    	
    				INNER JOIN transaction t ON (d.transactionId = t.componentId)
    	
					INNER JOIN item i ON (i.componentId = d.itemId)
    	
					WHERE (accountId = 8 OR accountId = 14) AND (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "' )
    	
    				GROUP BY i.componentId"; 
    	
    	$salesquery = $this->db->query ( $salesSQL );
    	
    	$page_data ['salesdata'] = $salesquery->result ();
    	
    	$opexpSQL = "SELECT accountId, a.uniqueCode accountName,
   
					ROUND(SUM(quantity*unitPrice),2) opexp,
    	            ROUND(getCOGS(itemId)*SUM(CASE WHEN accountId = 14 THEN quantity ELSE 0 END),2) AS cogs
					FROM transaction_detail d
   
    				INNER JOIN transaction t ON (d.transactionId = t.componentId)
   
					INNER JOIN account a ON (a.componentId = d.accountId)
   
					WHERE a.category2 = '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "' AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " AND (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "' )
   
					GROUP BY accountId ";
    	//cut from 4360 previous line 'from transaction details' ROUND(getCOGS(itemId)*SUM(CASE WHEN accountId = 13 THEN quantity ELSE 0 END),2) AS cogs
    	 
    	$opexpquery = $this->db->query ( $opexpSQL );
    	 
    	$page_data ['opexpdata'] = $opexpquery->result ();
    	
    	$expSQL = "SELECT accountId, a.uniqueCode accountName,
    	
						ROUND(SUM(quantity*unitPrice),2) exp
    	
					FROM transaction_detail d
    	
    				INNER JOIN transaction t ON (d.transactionId = t.componentId)
    	
					INNER JOIN account a ON (a.componentId = d.accountId)
    	
					WHERE a.category1 = '" . Applicationconst::ACCOUNT_CAT1_EXPENSE . "' AND a.category2 != '" . Applicationconst::ACCOUNT_CAT2_OPERATING_EXPENSE . "' AND accountId != " . Applicationconst::ACCOUNT_HEAD_PURCHASE . " AND (t.tdate BETWEEN '" . $page_data ['start_date'] . "' AND '" . $page_data ['end_date'] . "' )
    	
					GROUP BY accountId ";
    	
    	$expquery = $this->db->query ( $expSQL );
    	
    	$page_data ['expdata'] = $expquery->result ();
    	$page_data['page_name']  = 'profitloss';
    	$page_data['page_title'] = get_phrase('profit_&_loss');

        // echo "<pre>";
        // print_r($page_data);
        // echo "</pre>";
    	$this->load->view('backend/index', $page_data);

        
    }
    
    function customer($param1='', $param2='') {
    	if ($this->session->userdata('admin_login') != 1)
    		redirect(base_url(), 'refresh');
    		 
    		if($param1 == 'create')
    		{
    			$data['name']    = $this->input->post('name');
    			$data['address'] = $this->input->post('address');
    			$data['city'] = $this->input->post('city');
    			$data['phone'] = $this->input->post('phone');
    			$data['email'] = $this->input->post('email');
    			$data['uniqueCode'] = $this->input->post('name').uniqid();
    			$this->db->insert('customer' , $data);
    			$customer_id = $this->db->insert_id();
    			
    			$userData['reference_id'] 	= $customer_id;
    			$userData['user_name'] 		= $this->input->post('name');
    			$userData['password'] 		= '';
    			$userData['user_type'] 		= 'CUSTOMER';
    			$this->db->insert('user', $userData);
    			
    			
    			$this->session->set_flashdata('flash_message' , get_phrase('customer_added'));
    			redirect(base_url() . 'index.php?admin/customer', 'refresh');
    		}
    		if($param1 == 'update')
    		{
    			$data['name']    = $this->input->post('name');
    			$data['address'] = $this->input->post('address');
    			$data['city'] = $this->input->post('city');
    			$data['phone'] = $this->input->post('phone');
    			$data['email'] = $this->input->post('email');
    			$this->db->where('componentId' , $param2);
    			$this->db->update('customer', $data);
    			
    			$userData['user_name'] 		= $this->input->post('name');
    			$userData['password'] 		= '';
    			$this->db->where('reference_id' , $param2);
    			$this->db->update('user', $userData);
    			$this->session->set_flashdata('flash_message' , get_phrase('customer_updated'));
    			redirect(base_url() . 'index.php?admin/customer', 'refresh');
    		}
    		if($param1 == 'delete')
    		{
    			$this->db->where('componentId', $param2);
    			$this->db->delete('customer');
    			
    			$this->db->where('reference_id' , $param2);
    			$this->db->delete('user', $userData);
    			$this->session->set_flashdata('flash_message' , get_phrase('customer_added'));
    			redirect(base_url() . 'index.php?admin/customer', 'refresh');
    		}
    		 
    	
    	$page_data ['customerInfo'] = $this->db->get('customer')->result_array();
    	$page_data['page_name']  = 'customer';
    	$page_data['page_title'] = get_phrase('customer_management');
    	$this->load->view('backend/index', $page_data);
    }

}

?>