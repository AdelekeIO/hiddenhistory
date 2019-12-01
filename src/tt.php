<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$db = new database($username, $password, $dbName);
$studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionNumber);

$fullname = $studentProfile[0]['surname'] . ' ' . $studentProfile[0]['firstname'] . ' ' . $studentProfile[0]['middlename'];
$sex = $studentProfile[0]['sex'];
if ($studentProfile !== false) {

    $hasStudentBeenMove2Class = CheckIfStudenthasbeenMovedtoclass($db, $school_deploymentidx, $admissionNumber, $class_name, $term_name, $session_name);

    if ($hasStudentBeenMove2Class !== false) {
        $date = date("Y-m-d");
//                    $QUERY_PERIOD = '';
        $dateTime = date('d/m/Y g:i:s A');
//                    if ($period == 1) {
//                        $QUERY_PERIOD = 'Morning';
//                    } elseif ($period == 2) {
//                        $QUERY_PERIOD = 'Afternoon';
//                    }


        if ($period == 1 && $attendance_status == 1) {
            $period = 'Morning Present';
        } elseif ($period == 2 && $attendance_status == 1) {
            $period = 'Afternoon Present';
        } elseif ($period == 1 && $attendance_status == 0) {
            $period = 'Morning Absentee';
        } elseif ($period == 2 && $attendance_status == 0) {
            $period = 'Afternoon Absentee';
        } else {
            $period = '#';
        }
        $db = new database();
        $attendance_code = $school_idx . '/' . $admissionNumber . '/' . $date . '/' . $period_period;
        $checkAttandance_status = checkAttandance_status($db, $attendance_code);
        if ($checkAttandance_status !== false) {
            $data_insert_status = UpdateMarkedAttendance($db, $attendance_code, $attendance_reason, $school_idx, $sex, $personincharge, $admissionNumber, $date, $dateTime, $class_name, $term_name, $session_name, $period, 4);

            if ($data_insert_status !== false) {

                return SuccessMessageWithData($studentProfile, $response, 'Attendance Updated Successfully');
            } else {
                return ErrorMessage($response, "Error Updating Attendance, Try again later");
            }
        } else {

            $data_insert_status = MarkeAttendance($db, $attendance_code, $attendance_reason, $school_idx, $sex, $personincharge, $admissionNumber, $date, $dateTime, $class_name, $term_name, $session_name, $period, 4);
            if ($data_insert_status !== false) {

                return SuccessMessageWithData($studentProfile, $response, 'Attendance marked Successfully');
            } else {
                return ErrorMessage($response, "Error Saving Attendance, Try again later");
            }
        }
    } else {
        return ErrorMessage($response, $fullname . ' ' . 'Has not been moved to this class for the term');
    }  
    
}
else{
      return ErrorMessage($response, 'Invalid Admission Number');
}