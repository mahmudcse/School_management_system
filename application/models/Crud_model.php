<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Crud_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    function get_type_name_by_id($type, $type_id = '', $field = 'name') {
        return $this->db->get_where($type, array($type . '_id' => $type_id))->row()->$field;
    }

    ////////STUDENT/////////////
    function get_students($class_id) {
        $query = $this->db->get_where('student', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_student_info($student_id) {
        $query = $this->db->get_where('student', array('student_id' => $student_id));
        return $query->result_array();
    }

    /////////TEACHER/////////////
    function get_teachers() {
        $query = $this->db->get('teacher');
        return $query->result_array();
    }

    function get_teacher_name($teacher_id) {
        $query = $this->db->get_where('teacher', array('teacher_id' => $teacher_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_teacher_info($teacher_id) {
        $query = $this->db->get_where('teacher', array('teacher_id' => $teacher_id));
        return $query->result_array();
    }

    //////////SUBJECT/////////////
    function get_subjects() {
        $query = $this->db->get('subject');
        return $query->result_array();
    }

    function get_subject_info($subject_id) {
        $query = $this->db->get_where('subject', array('subject_id' => $subject_id));
        return $query->result_array();
    }

    function get_subjects_by_class($class_id) {
        $query = $this->db->get_where('subject', array('class_id' => $class_id));
        return $query->result_array();
    }

    function get_subject_name_by_id($subject_id) {
        $query = $this->db->get_where('course', array('course_id' => $subject_id))->row();
        return $query->tittle;
    }

    /////////GROUP/////////
    function get_group_name($group_id) {
        $query = $this->db->get_where('class_group', array('id' => $group_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['group_name'];
    }

    ////////////CLASS///////////
    function get_class_name($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }
	
	////////////section///////////
    function get_section_name($section_id) {
        $query = $this->db->get_where('section', array('section_id' => $section_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name'];
    }

    function get_class_name_numeric($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        $res = $query->result_array();
        foreach ($res as $row)
            return $row['name_numeric'];
    }

    function get_classes() {
        $query = $this->db->get('class');
        return $query->result_array();
    }

    function get_class_info($class_id) {
        $query = $this->db->get_where('class', array('class_id' => $class_id));
        return $query->result_array();
    }

    //////////EXAMS/////////////
    function get_exams() {
        $query = $this->db->get_where('exam' , array(
            'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
        ));
        return $query->result_array();
    }

    function get_exam_info($exam_id) {
        $query = $this->db->get_where('exam', array('exam_id' => $exam_id));
        return $query->result_array();
    }

    //////////GRADES/////////////
    function get_grades() {
        $query = $this->db->get('grade');
        return $query->result_array();
    }

    function get_grade_info($grade_id) {
        $query = $this->db->get_where('grade', array('grade_id' => $grade_id));
        return $query->result_array();
    }

    function get_obtained_marks( $exam_id , $class_id , $subject_id , $student_id) {
        $marks = $this->db->get_where('mark' , array(
                                    'subject_id' => $subject_id,
                                        'exam_id' => $exam_id,
                                            'class_id' => $class_id,
                                                'student_id' => $student_id))->result_array();
                                        
        foreach ($marks as $row) {
            echo $row['mark_obtained'];
        }
    }

    function get_highest_marks( $exam_id , $class_id , $subject_id ) {
        $this->db->where('exam_id' , $exam_id);
        $this->db->where('class_id' , $class_id);
        $this->db->where('subject_id' , $subject_id);
        $this->db->select_max('mark_obtained');
        $highest_marks = $this->db->get('mark')->result_array();
        foreach($highest_marks as $row) {
            echo $row['mark_obtained'];
        }
    }

    function get_grade($mark_obtained) {
        $query = $this->db->get('grade');
        $grades = $query->result_array();
        foreach ($grades as $row) {
            if ($mark_obtained >= $row['mark_from'] && $mark_obtained <= $row['mark_upto'])
                return $row;
        }
    }

    function get_grade_with_point($point) {
        $query = $this->db->get('grade');
        $grades = $query->result_array();
        
        for($i = count($grades)-1; $i >= 0 ; $i--){
            if($point >= $grades[$i][grade_point] and $point < $grades[$i-1][grade_point]){
                return $grades[$i]['name'];
            }elseif($point == $grades[0][grade_point]){
                return "A+";
            }
        }
    }

    function get_grade_with_everage($average, $lg_or_gp){
        $this->db->select('*');
        $this->db->from('grade');
        $this->db->order_by('mark_upto', asc);
        $grades = $this->db->get()->result_array();
        foreach ($grades as $key => $grade) {
            
            if($average <= $grade['mark_upto']){
                if($lg_or_gp == 'lg'){
                    return $grade['name'];
                }else{
                    return $grade['grade_point'];
                }
                
            }
        }
    }

    function create_log($data) {
        $data['timestamp'] = strtotime(date('Y-m-d') . ' ' . date('H:i:s'));
        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        $location = new SimpleXMLElement(file_get_contents('http://freegeoip.net/xml/' . $_SERVER["REMOTE_ADDR"]));
        $data['location'] = $location->City . ' , ' . $location->CountryName;
        $this->db->insert('log', $data);
    }

    function get_system_settings() {
        $query = $this->db->get('settings');
        return $query->result_array();
    }

    ////////BACKUP RESTORE/////////
    function create_backup($type) {
        $this->load->dbutil();


        $options = array(
            'format' => 'txt', // gzip, zip, txt
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );


        if ($type == 'all') {
            $tables = array('');
            $file_name = 'system_backup';
        } else {
            $tables = array('tables' => array($type));
            $file_name = 'backup_' . $type;
        }

        $backup = & $this->dbutil->backup(array_merge($options, $tables));


        $this->load->helper('download');
        force_download($file_name . '.sql', $backup);
    }

    /////////RESTORE TOTAL DB/ DB TABLE FROM UPLOADED BACKUP SQL FILE//////////
    function restore_backup() {
        move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/backup.sql');
        $this->load->dbutil();


        $prefs = array(
            'filepath' => 'uploads/backup.sql',
            'delete_after_upload' => TRUE,
            'delimiter' => ';'
        );
        $restore = & $this->dbutil->restore($prefs);
        unlink($prefs['filepath']);
    }

    /////////DELETE DATA FROM TABLES///////////////
    function truncate($type) {
        if ($type == 'all') {
            $this->db->truncate('student');
            $this->db->truncate('mark');
            $this->db->truncate('teacher');
            $this->db->truncate('subject');
            $this->db->truncate('class');
            $this->db->truncate('exam');
            $this->db->truncate('grade');
        } else {
            $this->db->truncate($type);
        }
    }

    ////////IMAGE URL//////////
    function get_image_url($type = '', $id = '') {
        if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg'))
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.JPG';
        else
            $image_url = base_url() . 'uploads/user.png';

        return $image_url;
    }

    ////////STUDY MATERIAL//////////
    function save_study_material_info()
    {
        $data['timestamp']         = strtotime($this->input->post('timestamp'));
        $data['title'] 		   = $this->input->post('title');
        $data['description']       = $this->input->post('description');
        $data['file_name'] 	   = $_FILES["file_name"]["name"];
        $data['file_type']     	   = $this->input->post('file_type');
        $data['class_id'] 	   = $this->input->post('class_id');
        $data['group_id'] 	   = $this->input->post('group');	
        $data['createdBy'] 	   = $this->session->userdata('teacher_id');
        
        $this->db->insert('document',$data);
        
        $document_id            = $this->db->insert_id();
        move_uploaded_file($_FILES["file_name"]["tmp_name"], "uploads/document/" . $_FILES["file_name"]["name"]);
    }
    
    function select_study_material_info($createdBy)
    {
        $this->db->order_by("timestamp", "desc");
        return $this->db->get_where('document', array('createdBy' => $this->session->userdata('teacher_id')))->result_array(); 
    }
    
    function select_study_material_info_for_student()
    {
        $student_id = $this->session->userdata('student_id');
        $class_id   = $this->db->get_where('enroll', array(
            'student_id' => $student_id,
                'year' => $this->db->get_where('settings' , array('type' => 'running_year'))->row()->description
            ))->row()->class_id;

        $this->db->order_by("timestamp", "desc");
        $query = $this->db->get_where('document', array('class_id' => 1));
        return $query->result_array();
    }
    
    function update_study_material_info($document_id)
    {
        $data['timestamp']      = strtotime($this->input->post('timestamp'));
        $data['title'] 		= $this->input->post('title');
        $data['description']    = $this->input->post('description');
        $data['class_id'] 	= $this->input->post('class_id');
        $data['group_id'] 	   = $this->input->post('group');
        $this->db->where('documentId',$document_id);
        $this->db->update('document',$data);
    }
    
    function delete_study_material_info($document_id)
    {
        $this->db->where('documentId',$document_id);
        $this->db->delete('document');
    }
    
     ////////Mobile sms//////

    function send_mobile_sms_fee($student_id) {

     $reciever = array();
     $fcontactno = $this->db->get_where('student', array('student_id' => $student_id))->row()->fcontactno;
     array_push($reciever, $fcontactno);
     return $reciever;
     }
	 
    function send_mobile_sms() {
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
		$reciever_info   = $this->input->post('receiver_id');

        $reversed = array_reverse($reciever_info);

        $sts = 0;
        $sec = 0;
        $cls = 0;
        $stu = 0;
        $pas = 0;
        $pse = 0;
        $pcl = 0;
        $par = 0;
        $tes = 0;
        $tea = 0;

        $reciever = array();
        foreach ($reversed as $rcvr) {
            $type = substr($rcvr, 0,3);
            $id = substr($rcvr, 3);

//For specific student
            if($type == 'STS'){
                $sts = 1;
                $this->db->select('phone');
                $this->db->from('student');             
                $this->db->where('student_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                }
            }elseif ($type == 'SEC' && $sts != 1) {
                $sec = 1;
//For section
                $this->db->select('student.phone');
                $this->db->from('section');
                $this->db->join('enroll', 'enroll.section_id = section.section_id');
                $this->db->join('student', 'student.student_id = enroll.student_id');
                $this->db->where('section.section_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }


            }elseif ($type == 'CLS' && $sec != 1 && $sts != 1) {
                $cls = 1;
//For class
                $this->db->select('student.phone');
                $this->db->from('student');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'class.class_id = section.class_id');
                $this->db->where('class.class_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }


            }elseif ($type == 'STU' && $cls != 1 && $sec != 1 && $sts != 1) {
                $stu = 1;
//For all students
                $this->db->select('student.phone');
                $this->db->from('student');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'class.class_id = section.class_id');
                $this->db->join('campus', 'campus.id = class.campus_id');
                $this->db->where('campus.id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }

            }elseif($type == 'PAS'){
                $pas = 1;
//For specific parrent
                $this->db->select('phone');
                $this->db->from('parent');              
                $this->db->where('parent_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }
            }elseif ($type == 'PSE' && $pas != 1) {
                $pse = 1;
//For section parent
                $this->db->select('parent.phone');
                $this->db->from('parent');
                $this->db->join('student', 'student.parent_id = parent.parent_id');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->where('section.section_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }

            }elseif ($type == 'PCL' && $pse != 1 && $pas != 1) {
                $pcl = 1;
//For class parent
                $this->db->select('parent.phone');
                $this->db->from('parent');
                $this->db->join('student', 'student.parent_id = parent.parent_id');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'section.class_id = class.class_id');
                $this->db->where('class.class_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }
            }elseif ($type == 'PAR' && $pcl != 1 && $pse != 1 && $pas != 1) {
                $par = 1;
//For all parrent
                $this->db->select('parent.phone');
                $this->db->from('parent');
                $this->db->join('student', 'student.parent_id = parent.parent_id');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'section.class_id = class.class_id');
                $this->db->join('campus', 'campus.id = class.campus_id');
                $this->db->where('campus.id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }
            }elseif($type == 'TES'){
                $tes = 1;
//For specific teacher
                $this->db->select('phone');
                $this->db->from('teacher');             
                $this->db->where('teacher_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }
            }elseif ($type == 'TEA' && $tes != 1) {
                $tea = 1;
//for all teacher
                $this->db->select('phone');
                $this->db->from('teacher');
                $this->db->where('campus_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                }
            }elseif ($type == 'CAM' && $stu != 1 && $cls != 1 && $sec != 1 && $sts != 1 && $par != 1 && $pcl != 1 && $pse != 1 && $pas != 1 && $tea != 1 && $tes != 1) {
//For whole campus

                $this->db->select('phone');
                $this->db->from('teacher');
                $this->db->where('campus_id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                }


                $this->db->select('parent.phone');
                $this->db->from('parent');
                $this->db->join('student', 'student.parent_id = parent.parent_id');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'section.class_id = class.class_id');
                $this->db->join('campus', 'campus.id = class.campus_id');
                $this->db->where('campus.id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }


                $this->db->select('student.phone');
                $this->db->from('student');
                $this->db->join('enroll', 'enroll.student_id = student.student_id');
                $this->db->join('section', 'section.section_id = enroll.section_id');
                $this->db->join('class', 'class.class_id = section.class_id');
                $this->db->join('campus', 'campus.id = class.campus_id');
                $this->db->where('campus.id', $id);
                $result = $this->db->get()->result_array();
                foreach ($result as $row) {
                    array_push($reciever, $row['phone']);
                    
                }

                
            }
        }

        return $reciever;
		
    }

 ////////private message//////
    function send_new_private_message() {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));

        $reciever_info   = $this->input->post('receiver_id');
        $type = substr($reciever_info, 0,3);
        $id = substr($reciever_info, 3);
        /*CAM for campus*/
        if ($type == 'CAM') {
        	$this->db->select('class.class_id, enroll.student_id');
        	$this->db->from('class');
        	$this->db->join('enroll', 'class.class_id = enroll.class_id');
        	$this->db->where('class.campus_id', $id);
        	$result = $this->db->get()->result_array();
        	foreach ($result as $row) {
        		$reciever[] = 'student-'.$row['student_id'];
        	}
        }
        
        /*STU for type student*/
        if($type == 'STU') {
        	$reciever[0] = 'student-'.$id;
        }
        /*PAR for type parents*/
        if($type == 'PAR') {
        	$reciever[0] = 'parent-'.$id;
        }
        /*TEA for type teacher*/
        if($type == 'TEA') {
        	$reciever[0] = 'teacher-'.$id;
        }
        /*CLS for class wise student*/
        if ($type == 'CLS') {
        	$this->db->select('student_id');
        	$this->db->from('enroll');
        	$this->db->where('class_id', $id);
        	$result = $this->db->get()->result_array();
        	foreach ($result as $row) {
        		$reciever[] = 'student-'.$row['student_id'];
        	}
        	//         	echo '<pre>';
        	//         	print_r($reciever);
        	//         	echo '</pre>';
        	//         	exit();
        }
        /*SEC for section wise student*/
        if($type == 'SEC') {
        	$this->db->select('student_id');
        	$this->db->from('enroll');
        	$this->db->where('section_id', $id);
        	$result = $this->db->get()->result_array();
        	foreach ($result as $row) {
        		$reciever[] = 'student-'.$row['student_id'];
        	}
        }
        
        /*STS for specific student*/
        if($type == 'STS') {
        	$reciever[0] = 'student-'.$id;
        }
        /*PCL for type class wise parents*/
        if($type == 'PCL') {
        	$this->db->select('student.student_id, parent.parent_id');
        	$this->db->from('enroll');
        	$this->db->join('student', 'enroll.student_id=student.student_id');
        	$this->db->join('parent', 'student.parent_id=parent.parent_id');
        	$this->db->where('enroll.class_id', $id);
        	$result = $this->db->get()->result_array();
        	foreach ($result as $row) {
        		$reciever[] = 'parent-'.$row['parent_id'];
        	}
        }
        /*PSE for type section wise parents*/
        if($type == 'PSE') {
        	$this->db->select('student.student_id, parent.parent_id');
        	$this->db->from('enroll');
        	$this->db->join('student', 'enroll.student_id=student.student_id');
        	$this->db->join('parent', 'student.parent_id=parent.parent_id');
        	$this->db->where('enroll.section_id', $id);
        	$result = $this->db->get()->result_array();
        	foreach ($result as $row) {
        		$reciever[] = 'parent-'.$row['parent_id'];
        	}
        }
        /*PAS for specfic parents*/
        if($type == 'PAS') {
        	$reciever[0] = 'parent-'.$id;
        }
        /*TES for specfic parents*/
        if($type == 'TES') {
        	$reciever[0] = 'teacher-'.$id;
        }
        $sender = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');

        //check if the thread between those 2 users exists, if not create new thread
    
        for($i=0;$i<count($reciever);$i++) {        	
	       	$num1 = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever[$i]))->num_rows();
        	$num2 = $this->db->get_where('message_thread', array('sender' => $reciever[$i], 'reciever' => $sender))->num_rows();  
        	
        	if ($num1 == 0 && $num2 == 0) {
        		$message_thread_code                        = substr(md5(rand(100000000, 20000000000)), 0, 15);
        		$data_message_thread['message_thread_code'] = $message_thread_code;
        		$data_message_thread['sender']              = $sender;
        		$data_message_thread['reciever']            = $reciever[$i];
        		$this->db->insert('message_thread', $data_message_thread);
        	}
			
        	if ($num1 > 0)
       		$message_thread_code = $this->db->get_where('message_thread', array('sender' => $sender, 'reciever' => $reciever[$i]))->row()->message_thread_code;
        	if ($num2 > 0)
       		$message_thread_code = $this->db->get_where('message_thread', array('sender' => $reciever[$i], 'reciever' => $sender))->row()->message_thread_code;
       		
       		$data_message['message_thread_code']    = $message_thread_code;
       		$data_message['message']                = $message;
       		$data_message['sender']                 = $sender;
       		$data_message['timestamp']              = $timestamp;
       		$this->db->insert('message', $data_message);
        }      
        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());

        return $message_thread_code;
    }

    function send_reply_message($message_thread_code) {
        $message    = $this->input->post('message');
        $timestamp  = strtotime(date("Y-m-d H:i:s"));
        $sender     = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');


        $data_message['message_thread_code']    = $message_thread_code;
        $data_message['message']                = $message;
        $data_message['sender']                 = $sender;
        $data_message['timestamp']              = $timestamp;
        $this->db->insert('message', $data_message);

        // notify email to email reciever
        //$this->email_model->notify_email('new_message_notification', $this->db->insert_id());
    }

    function mark_thread_messages_read($message_thread_code) {
        // mark read only the oponnent messages of this thread, not currently logged in user's sent messages
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $this->db->where('sender !=', $current_user);
        $this->db->where('message_thread_code', $message_thread_code);
        $this->db->update('message', array('read_status' => 1));
    }

    function count_unread_message_of_thread($message_thread_code) {
        $unread_message_counter = 0;
        $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');
        $messages = $this->db->get_where('message', array('message_thread_code' => $message_thread_code))->result_array();
        foreach ($messages as $row) {
            if ($row['sender'] != $current_user && $row['read_status'] == '0')
                $unread_message_counter++;
        }
        return $unread_message_counter;
    }
    
    function get_examtypes_by_course($course_id)
    {
    	$query	=	$this->db->get_where('examtype' , array('course_id' => $course_id));
    	return $query->result_array();
    }
}
