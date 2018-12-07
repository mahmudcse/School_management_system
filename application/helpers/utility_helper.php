<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('generate_student_code')){
    function generate_student_code($section_id = '', $group_id  = '', $class_id = '', $roll = ''){

            $obj =& get_instance();

            $section_name = $obj->db->get_where('section', array('section_id' => $section_id))->row()->name;
            $group_name = $obj->db->get_where('class_group', array('id' => $group_id))->row()->group_name;
            $class_name = $obj->db->get_where('class', array('class_id' => $class_id))->row()->name;

            $code = '';
            
            $section_code = '';
            $group_code  = '';
            $class_code = '';
            $updateData = array();

                if($section_name == 'MORNING SHIFT'){
                    $section_code = '1';
                }else if($section_name == 'DAY SHIFT'){
                    $section_code = '2';
                }

                if($group_name == 'SCIENCE'){
                    $group_code = '1';
                }else if($group_name == 'ARTS'){
                    $group_code = '2';
                }else if($group_name == 'BUISNESS STUDIES'){
                    $group_code = '3';
                }else{
                    $group_code = '0';
                }

                if($class_name == 'NURSERY'){
                    $class_code = '91';
                }else if($class_name == 'K.G.'){
                    $class_code = '92';
                }else if($class_name == 'ONE'){
                    $class_code = '01';
                }else if($class_name == 'TWO'){
                    $class_code = '02';
                }else if($class_name == 'THREE'){
                    $class_code = '03';
                }else if($class_name == 'FOUR'){
                    $class_code = '04';
                }else if($class_name == 'FIVE'){
                    $class_code = '05';
                }else if($class_name == 'SIX'){
                    $class_code = '06';
                }else if($class_name == 'SEVEN'){
                    $class_code = '07';
                }else if($class_name == 'EIGHT'){
                    $class_code = '08';
                }else if($class_name == 'NINE'){
                    $class_code = '09';
                }else if($class_name == 'TEN'){
                    $class_code = '10';
                }

                $paddedRoll = str_pad($roll, 4, '0', STR_PAD_LEFT);
//2 year + 1 section + 1 group + 2 class + 4 roll
                $code = '17'.$section_code.$group_code.$class_code.$paddedRoll;

                return $code;
    }
}

if( !function_exists('course_id_to_total_mark')){
    function course_id_to_total_mark($course_id){
        $obj =& get_instance();

        $obj->db->select('et.total_mark');
        $obj->db->from('examtype et');
        $obj->db->join('examcourse ec', 'et.examtype_id = ec.examtype_id', 'inner');
        $obj->db->where('ec.course_id', $course_id);
        $obj->db->where('ec.report_card', 1);
        $total_mark = $obj->db->get()->row()->total_mark;
        return $total_mark;
    }
}


if ( ! function_exists('convert_number_to_words'))
{
	function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
}