<?php

/* require_once __DIR__ . '/vendor/autoload.php'; */

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function ($request, $response, $args) {
    echo 'welcome to Hiddenhistory Api.';
    echo '\n';
    print_r(date("Y-m-d"));
});






/* * *-----------------------------------fetch article-----------------------------------------------------* */

$app->get('/fetcharticle', function (Request $request, Response $response, array $args) {
//     $response->header("Content-Type", "application/json");
    $db = new database();
    $allArray = [];
//    $userid = $args['user_id'];
    $featuredArticle = FetchFeaturedArticle($db);
    $FetchArticles_Topic = FetchArticles_Topic($db);
    $FetchFeaturedCollection = FetchFeaturedCollection($db);
    $allArray['featuredArticle'] = $featuredArticle;
    $allArray['FetchArticles_Topic'] = $FetchArticles_Topic;
    $allArray['FetchFeaturedCollection'] = $FetchFeaturedCollection;

//   return SuccessMessageWithData(convert_from_latin1_to_utf8_recursively($featuredArticle), $response, 'Feeds data');
    return SuccessMessageWithData($allArray, $response, 'Article Data');
});
/* * *-----------------------------------fetch article ends---------------------------------------------------------* */

/* * *-----------------------------------fetch article-----------------------------------------------------* */

$app->get('/fetcharticle2', function (Request $request, Response $response, array $args) {
//     $response->header("Content-Type", "application/json");
    $db = new database();
    $allArray = [];
//    $userid = $args['user_id'];
    $featuredArticle = FetchFeaturedArticle($db);
    $FetchArticles_Topic = FetchArticles_Topic($db);
    $FetchFeaturedCollection = FetchFeaturedCollection($db);
    $allArray['featuredArticle'] = $featuredArticle;
    $allArray['FetchArticles_Topic'] = $FetchArticles_Topic;
    $allArray['FetchFeaturedCollection'] = $FetchFeaturedCollection;

//   return SuccessMessageWithData(convert_from_latin1_to_utf8_recursively($featuredArticle), $response, 'Feeds data');
    return SuccessMessageWithData($allArray, $response, 'Article Data');
});
/* * *-----------------------------------fetch article ends---------------------------------------------------------* */

/* * *-----------------------------------fetch Catehory-----------------------------------------------------* */

$app->get('/categories', function (Request $request, Response $response, array $args) {
//     $response->header("Content-Type", "application/json");
    $limit = default_limit(9);
    $db = new database();
    $sql = "SELECT * FROM `categories`  limit 0," . $limit;
    $result = $db->select($sql);
    // print_r($result);
    if (isset($result) && !empty($result)) {
        //    print_r('jj');
        $temp_array = [];
        foreach ($result as $rs) {
            //   print_r('jj');
            // $rs["icon"] = image_url($rs["icon"]);
            $rs["article_count"] = GetCategoryCount($db, $rs["categoryid"]);
            $rs["article_image"] = GetRandomCategoryDp($db, $rs["categoryid"]);
            //            $rs["article"] = htmlspecialchars_decode($rs["article"]);
//            $rs["article"] = mb_convert_encoding($rs["article"], 'UTF-8', 'UTF-8');
            array_push($temp_array, $rs);
        }

        //   return $temp_array;
    } else {
        //  return $temp_array;
    }

    return SuccessMessageWithData($temp_array, $response, 'Category Data');
});
/* * *-----------------------------------fetch Category ends---------------------------------------------------------* */

/* * *-----------------------------------fetch getCategorydetails-----------------------------------------------------* */

$app->get('/getCategorydetails/{cat_id}', function (Request $request, Response $response, array $args) {
    $db = new database();
    $cat_id = $args["cat_id"];
    //print_r($cat_id);
    $sql = "SELECT * FROM `mobile_articles` WHERE `visible` = 1 AND `categoryid` = " . $cat_id . " ORDER BY `categoryid` DESC ";
    $result = $db->select($sql);
    // print_r($result);
    $temp_array = [];
    if (isset($result) && !empty($result)) {
        //    print_r('jj');

        foreach ($result as $rs) {
            //   print_r('jj');
            $rs["Short_discription"] = ReduceText($rs["article"]);
//            $rs["articletitle"] = mb_convert_encoding($rs["articletitle"], 'UTF-8', 'UTF-8');
            $rs["articleimage"] = image_url($rs["articleimage"]);
//             $rs["article"] = mb_convert_encoding($rs["article"], 'UTF-8', 'UTF-8');

            $rs["article_text"] = $rs["article"];
            $rs["article_title"] = $rs["articletitle"];
            $rs["article_image"] = $rs["articleimage"];
            $rs["readUrl"] = FormReadUrl(mobile_articles_type(), $rs["articlesid"]);

            array_push($temp_array, $rs);
        }

        //   return $temp_array;
    } else {
        //  return $temp_array;
    }

    return SuccessMessageWithData($temp_array, $response, 'Category Data');
});
/* * *-----------------------------------fetch getCategorydetails ends---------------------------------------------------------* */

/* * *-----------------------------------fetch getCategorydetails-----------------------------------------------------* */

$app->get('/getsingleArticle/{type}/{article_id}', function (Request $request, Response $response, array $args) {
    $db = new database();
    $article_id = $args["article_id"];
    $type = $args["type"];
    $data = [];
    if ($type == 1) { // mobilearticle
        //echo 'here1';
        $data = GetSingleMobileArticle($db, $article_id);
    } else if ($type == 2) { // Location Article
        //  echo 'here2';
        $data = GetSingleLocationArticles($db, $article_id);
    } else {
        // echo 'here';
        $data = [];
    }
    //print_r($cat_id);


    return SuccessMessageWithData($data, $response, 'Single Data');
});
/* * *-----------------------------------fetch getCategorydetails ends---------------------------------------------------------* */

/* * *-----------------------------------fetch maplocation-----------------------------------------------------* */

$app->get('/maplocation', function (Request $request, Response $response, array $args) {
//     $response->header("Content-Type", "application/json");
    $limit = default_limit(9);
    $db = new database();
    $sql = "SELECT * FROM `categories`  limit 0," . $limit;
    $result = $db->select($sql);
    // print_r($result);
    if (isset($result) && !empty($result)) {
        //    print_r('jj');
        $temp_array = [];
        foreach ($result as $rs) {
            //   print_r('jj');
            // $rs["icon"] = image_url($rs["icon"]);
            $catdatils = GetcategoryDetails($db, $rs["categoryid"]);
            $rs["article_count"] = GetCategoryCount($db, $rs["categoryid"]);
            $rs["catDetails"] = $catdatils;


            array_push($temp_array, $rs);
        }

        //   return $temp_array;
    }
    $generalArray = [];
    $LocationArticles = GetLocationArticles($db);
    $ArticlesWithLocation = GetArticlesWithLocation($db);
    $RandomLocationArticle = GetRandomLocationArticle($db);
    $newArry = array_merge($LocationArticles, $ArticlesWithLocation);
// array_push($generalArray,$newArry);
// array_push($generalArray,$temp_array);
    $generalArray['LocationMap'] = $newArry;
    $generalArray['Cat_segment'] = $temp_array;
    $generalArray['RandomLocationArticle'] = $RandomLocationArticle;
    return SuccessMessageWithData($generalArray, $response, 'Location Deatils');
});
/* * *-----------------------------------fetch maplocation ends---------------------------------------------------------* */

/* * *-----------------------------------Verify Token --------------------------------------------------------* */
$app->post('/savefcmtoken', function (Request $request, Response $response, array $args) {
    $route = 'savefcmtoken';
    $db = new database();
    $input = $request->getParsedBody();
    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('fctoken'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }
        $fctoken = $input['fctoken'];
   
        $tokenstatus = checkToken($db, $fctoken);
        if ($tokenstatus !== false) {

            $dateTime = date('Y-m-d g:i:s A');
            $UpdateTokenParam = array(
                'token' => $fctoken,
                'date_updated' => $dateTime,
                'status' => 1,
                
            );
            $condition = "token='" . $fctoken . "' ";
            $db->update("tokens", $UpdateTokenParam, $condition);
            return SuccessMessage($response, 'Token Updated Successfully');
        } else {
              $dateTime = date('Y-m-d g:i:s A');
            $UpdateTokenParam = array(
                'token' => $fctoken,
                'date_added' => $dateTime,
                'date_updated' => $dateTime,
                'status' => 1,
                
            );
            $condition = "token='" . $fctoken . "' ";
            $db->insert("tokens", $UpdateTokenParam);
            return SuccessMessage($response, 'Token recorded Successfully');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- Verify Token --------------------------------------------------------* */

















/* * *-----------------------------------Authenticate --------------------------------------------------------* */
$app->post('/authenticate', function (Request $request, Response $response, array $args) {
    $route = 'authenticate';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('mobile'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }
        $mobile = $input['mobile'];
        $Resp = FetchPhoneNuberDetails($db, $mobile);
//        print_r($Resp);
//        echo '>>>>>>>>>>>>>';
        if ($Resp === FALSE) {
            return ErrorMessage($response, "You are not an Instructor in any School");
        } else {

            if (isset($Resp) && !empty($Resp)) {
                $temp_array = [];

                foreach ($Resp as $rs) {
//                  $userid=$rs['PositionInFamily'];
                    $dbnamex = $rs['dbnamex'];
                    $dbuserx = $rs['dbuserx'];
                    $dbhostx = $rs['dbhostx'];
                    $dbpassx = $rs['dbpassx'];
                    $mobile = $rs['mobilex'];
                    $schooluniqueidx = $rs['schooluniqueidx'];
                    $subdomain = $rs['subdomain'];
                    $administrator = $rs['administrator'];
                    $student = $rs['student'];
                    $educator = $rs['educator'];
                    $parent = $rs['parent'];
                    $usertype = determinUserType($administrator, $student, $educator, $parent);
                    $username = base64_decode(base64_decode($dbuserx));
                    $password = base64_decode(base64_decode($dbpassx));
                    $dbName = base64_decode(base64_decode($dbnamex));

                    $db = new database($username, $password, $dbName);
                    $status = VerifyIfStaffExistInSchool($db, $mobile);
                    if ($status !== FALSE) {

                        $db = new database();

                        $table = 'general_OTP';
                        $field1 = 'mobile';
                        $field2 = 'token';
                        $value1 = $mobile;
//            $deploymentidx = $Resp[0]['deploymentidx'];
                        $OTP = generatesOTP($table, $field1, $field2, $value1, $db);
                        if ($OTP !== '') {
                            $dateTime = date('Y-m-d g:i:s A');

                            $saveToken = array(
                                'subdomain' => $subdomain,
                                'mobile' => $mobile,
                                'token' => $OTP,
                                'datetimeRequest' => $dateTime,
                                'datetimeUsed' => '',
                                'status' => 1,
                            );
                            //print_r($parentaccountfunding_temp_data);

                            $res = $db->insert('general_OTP', $saveToken);
                            if ($res !== false) {
                                $Message = 'Use this token to complete your sign in process. Please do not disclose   ' . $OTP;
//                                $Receiver = '07065873900';
                                $Receiver = $mobile;
//                                $data = SendSms($Receiver, $Message);
//$data['comment']
                                return SuccessMessageWithData($OTP, $response, 'Sms sent');
                            } else {
                                return ErrorMessage($response, "Error Saving Token");
                            }
                        }
                        break;
                    } else {
//                        print_r('not in school'); 
                    }
                }
                return SuccessMessageWithData($temp_array, $response, 'User Data');
            } else {
                return ErrorMessage($response, "You are not an Instructor in any School ");
            }
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- Authenticate---------------------------------------------------------* */




/* * *-----------------------------------Verify Token --------------------------------------------------------* */
$app->post('/verifyToken', function (Request $request, Response $response, array $args) {
    $route = 'verifyToken';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('mobile', 'token'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }
        $mobile = $input['mobile'];
        $token = $input['token'];
        $tokenstatus = VerifyOTPtoken($db, $mobile, $token);
        if ($tokenstatus !== false) {

            $dateTime = date('Y-m-d g:i:s A');
            $UpdateTokenParam = array(
                'status' => 0,
                'datetimeUsed' => $dateTime,
            );
            $condition = "mobile='" . $mobile . "' AND token='" . $token . "' ";
            $db->update("general_OTP", $UpdateTokenParam, $condition);


            $Resp = FetchPhoneNuberDetails($db, $mobile);
            if ($Resp === FALSE) {
                return ErrorMessage($response, "You are not an Instructor in any School");
            } else {
//            $deploymentidx = $Resp[0]['deploymentidx'];

                if (isset($Resp) && !empty($Resp)) {
                    $temp_array = [];

                    foreach ($Resp as $rs) {
//                  $userid=$rs['PositionInFamily'];

                        $dbnamex = $rs['dbnamex'];
                        $dbuserx = $rs['dbuserx'];
                        $dbhostx = $rs['dbhostx'];
                        $dbpassx = $rs['dbpassx'];
                        $mobile = $rs['mobilex'];
                        $schooluniqueidx = $rs['schooluniqueidx'];
                        $deploymentidx = $rs['deploymentidx'];
                        $subdomain = $rs['subdomain'];
                        $administrator = $rs['administrator'];
                        $student = $rs['student'];
                        $educator = $rs['educator'];
                        $parent = $rs['parent'];
                        $usertype = determinUserType($administrator, $student, $educator, $parent);
                        $username = base64_decode(base64_decode($dbuserx));
                        $password = base64_decode(base64_decode($dbpassx));
                        $dbName = base64_decode(base64_decode($dbnamex));

                        $db = new database($username, $password, $dbName);
                        $status = VerifyIfStaffExistInSchool($db, $mobile);
                        unset($rs['passwrdx']);
                        unset($rs['birthdaygreeting']);
                        unset($rs['birthdayeditor']);
                        unset($rs['paymentstatus']);
                        unset($rs['themeeditor']);
                        unset($rs['deploymentidx']);


                        if ($status !== FALSE) {

                            $db = new database();
//                        print_r($status);
                            $useridx = $status[0]['staffid'];

                            $myclassList = getMyClassList($db, $schooluniqueidx, $useridx, $subdomain);
                            $db = new database($username, $password, $dbName);
                            $myTermList = getTermList($db);
                            $mySessionList = getSessionList($db);
                            $schoolname = GetSchoolName($db, $deploymentidx);

//                       print_r($myclassList);
                            $rs["classList"] = $myclassList;
                            $rs["usertype"] = $usertype;
                            $rs["termList"] = $myTermList;
                            $rs["sessionList"] = $mySessionList;
                            $rs["SchoolName"] = $schoolname[0]['name'];
                            array_push($temp_array, $rs);

                            break;
                        } else {
//                        print_r('not in school'); 
                        }
                    }
                    return SuccessMessageWithData($temp_array, $response, 'User Data');
                } else {
                    return ErrorMessage($response, "You are not an Instructor in any School ");
                }

//             if (isset($Students) && !empty($Students)) {
//                 $temp_array = [];
// 
//                foreach ($Students as $rs) {
//                    unset($rs['PositionInFamily']);
//                    unset($rs['whatkindofformulardoesyourchilddrink']);
//                    unset($rs['howdoesyourchildsleep']);
//                    $rs["Amount2Pay"] = 50000;
//                     array_push($temp_array, $rs);
//                }
//
//                return SuccessMessageWithData($temp_array, $response, 'Student Data', $resp_type);
//            } else {
//                return ErrorMessage($response, "No Student Found For this parent");
//            }
            }
        } else {
            return ErrorMessage($response, 'Incorrect Token Provided');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- Verify Token --------------------------------------------------------* */


/* * *-----------------------------------Verify Token --------------------------------------------------------* */
$app->post('/markAttendance', function (Request $request, Response $response, array $args) {
    $route = 'markAttendance';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('studentUniqueQrcode', 'class_name', 'term_name', 'session_name', 'period', 'personincharge', 'subdomian', 'attendance_status', 'attendance_reason'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }
        $studentUniqueQrcode = $input['studentUniqueQrcode'];
        $class_name = $input['class_name'];
        $term_name = $input['term_name'];
        $session_name = $input['session_name'];
        $period = $input['period']; //morning=1,afternoon=0
        $period_period = $input['period']; //morning=1,afternoon=0
        $personincharge = $input['personincharge'];
        $subdomian = $input['subdomian'];
        $attendance_status = $input['attendance_status']; //present=1 or absent=0
        $attendance_reason = $input['attendance_reason']; //Punctual or Late oR Authorized Absenteeism or UnAuthorized Absenteeism
//         $admissionNumber='';
        $Raw_Unique_identifier = decryptdata($studentUniqueQrcode);
        $f = explode('_', $Raw_Unique_identifier);
        $admissionNumber = str_replace("-", "/", $f[2]);

        $db = new database();
        $status = SchooldetailsFromURl($db, $subdomian);
        if ($status !== false) {
            $school_idx = $status[0]['idx'];
            $school_deploymentidx = $status[0]['deploymentidx'];
            $dbnamex = $status[0]['dbnamex'];
            $dbuserx = $status[0]['dbuserx'];
            $dbhostx = $status[0]['dbhostx'];
            $dbpassx = $status[0]['dbpassx'];
            $username = base64_decode(base64_decode($dbuserx));
            $password = base64_decode(base64_decode($dbpassx));
            $dbName = base64_decode(base64_decode($dbnamex));

            $db = new database($username, $password, $dbName);
            $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionNumber);


            if ($studentProfile !== false) {
                $fullname = $studentProfile[0]['surname'] . ' ' . $studentProfile[0]['firstname'] . ' ' . $studentProfile[0]['middlename'];
                $sex = $studentProfile[0]['sex'];
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
                    $medium = 2;
                    $checkAttandance_status = checkAttandance_status($db, $attendance_code);
                    if ($checkAttandance_status !== false) {
                        $data_insert_status = UpdateMarkedAttendance($db, $attendance_code, $attendance_reason, $school_idx, $sex, $personincharge, $admissionNumber, $date, $dateTime, $class_name, $term_name, $session_name, $period, 4, $medium);

                        if ($data_insert_status !== false) {

                            return SuccessMessageWithData($studentProfile, $response, 'Attendance Updated Successfully');
                        } else {
                            return ErrorMessage($response, "Error Updating Attendance, Try again later");
                        }
                    } else {

                        $data_insert_status = MarkeAttendance($db, $attendance_code, $attendance_reason, $school_idx, $sex, $personincharge, $admissionNumber, $date, $dateTime, $class_name, $term_name, $session_name, $period, 4, $medium);
                        if ($data_insert_status !== false) {

                            return SuccessMessageWithData($studentProfile, $response, 'Attendance marked Successfully');
                        } else {
                            return ErrorMessage($response, "Error Saving Attendance, Try again later");
                        }
                    }
                } else {
                    return ErrorMessage($response, $fullname . ' ' . 'Has not been moved to this class for the term');
                }
            } else {
                return ErrorMessage($response, 'Invalid Admission Number');
//                 return SuccessMessageWithData($f, $response, gettype($f));
            }
        } else {
            return ErrorMessage($response, 'Invalid School URl. Please check and try again');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- Verify Token --------------------------------------------------------* */


/* * *-----------------------------------register Face --------------------------------------------------------* */
$app->post('/registerFace', function (Request $request, Response $response, array $args) {
    $route = 'registerFace';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('admissionnumber', 'imageurl', 'schoolurl'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }

        $imageurl = $input['imageurl'];
        $schoolurl = $input['schoolurl'];
        $admissionnumber = $input['admissionnumber'];
//        $token = $input['token'];
        $url = "https://api.kairos.com/enroll";
        $db = new database();
        $status = SchooldetailsFromURl($db, $schoolurl);
        if ($status !== false) {
            $school_idx = $status[0]['idx'];
            $school_deploymentidx = $status[0]['deploymentidx'];
            $dbnamex = $status[0]['dbnamex'];
            $dbuserx = $status[0]['dbuserx'];
            $dbhostx = $status[0]['dbhostx'];
            $dbpassx = $status[0]['dbpassx'];
            $username = base64_decode(base64_decode($dbuserx));
            $password = base64_decode(base64_decode($dbpassx));
            $dbName = base64_decode(base64_decode($dbnamex));

            $db = new database($username, $password, $dbName);
            $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionnumber);
            if ($studentProfile !== false) {
// $iplode= implode('/', $admissionnumber);
                $formatedAdmissionNumber = str_replace("/", "-", $admissionnumber);
                $uniqueIdentifier = $school_idx . '_' . $school_deploymentidx . '_' . $formatedAdmissionNumber;
                $table = 'general_OTP';
                $field1 = 'mobile';
                $field2 = 'token';
                $value1 = '09099';
//            $deploymentidx = $Resp[0]['deploymentidx'];
//                $OTP = generatesOTP($table, $field1, $field2, $value1, $db);
//                $uniqueIdentifier = $OTP;
                //$gallery = 'EdvesFaceidTest';
                $post_fields = array(
                    'image' => $imageurl,
                    'subject_id' => $uniqueIdentifier,
                    'gallery_name' => GALLERY_name,
                    'multiple_faces' => false
                );
                $payload = json_encode($post_fields);

//$headers = ['Content-Type' => 'application/x-www-form-urlencoded', 'charset' => 'utf-8'];
//$headers = ['app_id' => '7b585406','app_key' => 'b32fa9b848e9e2295b36a04ca68077de','Content-Type' => 'Content-Type:application/json', 'charset' => 'utf-8'];
                $headers = array('Content-Type:application/json', 'app_id:7b585406', 'app_key:b32fa9b848e9e2295b36a04ca68077de');
                $dat = EncrollFaceNow($post_fields);
                $property = 'Errors';
//                array_key_exists ( mixed $key , array $array )
//                 $dat = json_encode($dat);
                $dat = json_decode($dat, TRUE);
                $exists = array_key_exists($property, $dat);
                if ($exists) {
                    return ErrorMessage($response, $dat['Errors'][0]['Message']);
                } else {

                    return SuccessMessageWithData($dat['images'][0]['transaction'], $response, "Face Register Successfully");
//                     return SuccessMessageWithData($dat, $response,gettype($dat));
                }
//                $dat_json = json_encode($dat);
//          return SuccessMessageWithData($encrypt, $response,'Data');
            } else {
                return ErrorMessage($response, 'Invalid Admission Number');
            }
        } else {
            return ErrorMessage($response, 'Invalid School URl. Please check and try again');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- registerFace --------------------------------------------------------* */
/* * *-----------------------------------generateAttendancekey --------------------------------------------------------* */
$app->post('/generateAttendancekey', function (Request $request, Response $response, array $args) {
    $route = 'generateAttendancekey';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);
        $resp = verifyRequiredParams(array('admissionnumber', 'schoolurl'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }

        $schoolurl = $input['schoolurl'];
        $admissionnumber = $input['admissionnumber'];
        $db = new database();
        $status = SchooldetailsFromURl($db, $schoolurl);
        if ($status !== false) {
            $school_idx = $status[0]['idx'];
            $school_deploymentidx = $status[0]['deploymentidx'];
            $dbnamex = $status[0]['dbnamex'];
            $dbuserx = $status[0]['dbuserx'];
            $dbhostx = $status[0]['dbhostx'];
            $dbpassx = $status[0]['dbpassx'];
            $username = base64_decode(base64_decode($dbuserx));
            $password = base64_decode(base64_decode($dbpassx));
            $dbName = base64_decode(base64_decode($dbnamex));

            $db = new database($username, $password, $dbName);
            $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionnumber);
            if ($studentProfile !== false) {
// $iplode= implode('/', $admissionnumber);
                $formatedAdmissionNumber = str_replace("/", "-", $admissionnumber);
                $uniqueIdentifier = $school_idx . '_' . $school_deploymentidx . '_' . $formatedAdmissionNumber;
                $encrypt = encryptdata($uniqueIdentifier);
//  $decr= decryptdata('wRZ639ebFzNA7FRYzN+brP7tpXJFMY0aKSYyJsndYgMYoDP1fUPBt3qvJg\/5Xt5vGYM=');
                return SuccessMessageWithData($encrypt, $response, 'Unique Identification Number as been generated for Admission Number>>' . '  ' . $admissionnumber);
            } else {
                return ErrorMessage($response, 'Invalid Admission Number');
            }
        } else {
            return ErrorMessage($response, 'Invalid School URl. Please check and try again');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- generateAttendancekey --------------------------------------------------------* */

/* * *-----------------------------------recognize Face --------------------------------------------------------* */
$app->post('/recognizeFace', function (Request $request, Response $response, array $args) {
    $route = 'registerFace';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);

        $resp = verifyRequiredParams(array('imageurl', 'schoolurl', 'class_name', 'term_name', 'session_name', 'period',), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }

        $imageurl = $input['imageurl'];
        $schoolurl = $input['schoolurl'];
        $class_name = $input['class_name'];
        $term_name = $input['term_name'];
        $session_name = $input['session_name'];
        $period = $input['period']; //morning=1,afternoon=0
//        $admissionnumber = $input['admissionnumber'];
//        $token = $input['token'];
        $db = new database();
        $status = SchooldetailsFromURl($db, $schoolurl);
        if ($status !== false) {
            $school_idx = $status[0]['idx'];
            $school_deploymentidx = $status[0]['deploymentidx'];
            $dbnamex = $status[0]['dbnamex'];
            $dbuserx = $status[0]['dbuserx'];
            $dbhostx = $status[0]['dbhostx'];
            $dbpassx = $status[0]['dbpassx'];
            $username = base64_decode(base64_decode($dbuserx));
            $password = base64_decode(base64_decode($dbpassx));
            $dbName = base64_decode(base64_decode($dbnamex));

            $db = new database($username, $password, $dbName);

            // $gallery = 'EdvesFaceidTest';
            $post_fields = array(
                "image" => $imageurl,
                "gallery_name" => GALLERY_name
            );

//$headers = ['Content-Type' => 'application/x-www-form-urlencoded', 'charset' => 'utf-8'];
//$headers = ['app_id' => '7b585406','app_key' => 'b32fa9b848e9e2295b36a04ca68077de','Content-Type' => 'Content-Type:application/json', 'charset' => 'utf-8'];
            $dat = RecognizeFaceNow($post_fields);
            $property = 'Errors';
//                array_key_exists ( mixed $key , array $array )
//                 $dat = json_encode($dat);
            $dat = json_decode($dat, TRUE);
            $exists = array_key_exists($property, $dat);
            if ($exists) {
//                return ErrorMessage($response, $dat['Errors'][0]['Message']);
//                return SuccessMessageWithData($dat, $response, gettype($dat));
                return ErrorMessage($response, $dat['Errors'][0]['Message']);
            } else {

                if (isset($dat) && !empty($dat)) {
                    $temp_array = [];
                    $index = 0;
                    foreach ($dat as $rs) {

                        //echo $index . 'lol';
//                  $userid=$rs['PositionInFamily'];
//                        $data=$rs[1];
                        // print_r($data);

                        foreach ($rs as $single) {
                            $data = $single['transaction'];
                            // print_r();
                            $status = $data['status'];
                            if ($status == 'success') {
                                $subject_id = $data['subject_id'];
                                $admissionNumber = SubjectId2AdmissionNumber($subject_id);
                                $db = new database($username, $password, $dbName);
                                $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionNumber);

                                $markingStatus = null;
                                $markingStatus_reason = '';
                                $fullname = null;
                                if ($studentProfile !== false) {
                                    $fullname = $studentProfile[0]['surname'] . ' ' . $studentProfile[0]['firstname'] . ' ' . $studentProfile[0]['middlename'];

                                    $hasStudentBeenMove2Class = CheckIfStudenthasbeenMovedtoclass($db, $school_deploymentidx, $admissionNumber, $class_name, $term_name, $session_name);

                                    if ($hasStudentBeenMove2Class !== false) {
                                        $markingStatus_reason = 'Ready to proceed';
                                        $markingStatus = true;
                                    } else {
                                        $markingStatus_reason = $fullname . ' ' . 'Has not been moved to this class for the term';
                                        $markingStatus = false;
                                    }
                                } else {
                                    $markingStatus_reason = 'Invalid Admission Number';
                                    $markingStatus = false;
                                }
                            }
                            $data['Fullname'] = $fullname;
                            $data['MarkingStatus'] = $markingStatus;
                            $data['MarkingStatusReason'] = $markingStatus_reason;
                            array_push($temp_array, $data);
                        }

//                        $index++;
//                       
                    }
//                return SuccessMessageWithData($dat, $response, 'User Data');
                    return SuccessMessageWithData($temp_array, $response, 'recog');
                } else {
                    return ErrorMessage($response, "noooo ");
                }
                //      return SuccessMessageWithData($dat['images'][0]['transaction'], $response, "Face Register Successfully");
//                     return SuccessMessageWithData($dat, $response,gettype($dat));
            }







//            $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionnumber);
//            if ($studentProfile !== false) {
//// $iplode= implode('/', $admissionnumber);
//                $formatedAdmissionNumber = str_replace("/", "-", $admissionnumber);
//                $uniqueIdentifier = $school_idx . '_' . $school_deploymentidx . '_' . $formatedAdmissionNumber;
//                $table = 'general_OTP';
//                $field1 = 'mobile';
//                $field2 = 'token';
//                $value1 = '09099';
////            $deploymentidx = $Resp[0]['deploymentidx'];
////                $OTP = generatesOTP($table, $field1, $field2, $value1, $db);
////                $uniqueIdentifier = $OTP;
//  
////                $dat_json = json_encode($dat);
//
//               
////          return SuccessMessageWithData($encrypt, $response,'Data');
//            } else {
//                return ErrorMessage($response, 'Invalid Admission Number');
//            }
        } else {
            return ErrorMessage($response, 'Invalid School URl. Please check and try again');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- recognize Face--------------------------------------------------------* */

/* * *-----------------------------------recognize Face --------------------------------------------------------* */
$app->post('/proceedMarkRecFace', function (Request $request, Response $response, array $args) {
    $route = 'proceedMarkRecFace';
    $db = new database();
    $input = $request->getParsedBody();
//    print_r($input);


    $isAuth = authenticateRequest($request, $db, $route);
    if ($isAuth === 1) {
//        $resp_type = getResponseType($request);

        $resp = verifyRequiredParams(array('imageurl', 'schoolurl', 'class_name', 'term_name', 'session_name', 'period', 'personincharge', 'attendance_status', 'attendance_reason'), $response, $this);
        if ($resp['error'] === TRUE) {
            $errormessage = $resp['message'];
            return ErrorMessage($response, $errormessage);
        }

        $imageurl = $input['imageurl'];
        $class_name = $input['class_name'];
        $term_name = $input['term_name'];
        $session_name = $input['session_name'];
        $period = $input['period']; //morning=1,afternoon=0
//        $admissionnumber = $input['admissionnumber'];
//        $token = $input['token'];

        $period_period = $input['period']; //morning=1,afternoon=0
        $personincharge = $input['personincharge'];
        $subdomian = $input['schoolurl'];
        $attendance_status = $input['attendance_status']; //present=1 or absent=0
        $attendance_reason = $input['attendance_reason']; //Punctual or Late oR Authorized Absenteeism or UnAuthorized Absenteeism
//       
        $db = new database();
        $status = SchooldetailsFromURl($db, $subdomian);
        if ($status !== false) {
            $school_idx = $status[0]['idx'];
            $school_deploymentidx = $status[0]['deploymentidx'];
            $dbnamex = $status[0]['dbnamex'];
            $dbuserx = $status[0]['dbuserx'];
            $dbhostx = $status[0]['dbhostx'];
            $dbpassx = $status[0]['dbpassx'];
            $username = base64_decode(base64_decode($dbuserx));
            $password = base64_decode(base64_decode($dbpassx));
            $dbName = base64_decode(base64_decode($dbnamex));

            $db = new database($username, $password, $dbName);

            // $gallery = 'EdvesFaceidTest';
            $post_fields = array(
                "image" => $imageurl,
                "gallery_name" => GALLERY_name
            );

//$headers = ['Content-Type' => 'application/x-www-form-urlencoded', 'charset' => 'utf-8'];
//$headers = ['app_id' => '7b585406','app_key' => 'b32fa9b848e9e2295b36a04ca68077de','Content-Type' => 'Content-Type:application/json', 'charset' => 'utf-8'];
            $dat = RecognizeFaceNow($post_fields);
            $property = 'Errors';
//                array_key_exists ( mixed $key , array $array )
//                 $dat = json_encode($dat);
            $dat = json_decode($dat, TRUE);
            $exists = array_key_exists($property, $dat);
            if ($exists) {
                return ErrorMessage($response, $dat['Errors'][0]['Message']);
            } else {

                if (isset($dat) && !empty($dat)) {
                    $temp_array = [];
                    $index = 0;
                    foreach ($dat as $rs) {

                        //echo $index . 'lol';
//                  $userid=$rs['PositionInFamily'];
//                        $data=$rs[1];
                        // print_r($data);

                        foreach ($rs as $single) {
                            $data = $single['transaction'];
                            // print_r();
                            $status = $data['status'];
                            if ($status == 'success') {
                                $subject_id = $data['subject_id'];
                                $admissionNumber = SubjectId2AdmissionNumber($subject_id);
                                $db = new database($username, $password, $dbName);
                                $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionNumber);

                                $markingStatus = null;
                                $markingStatus_reason = '';
                                $fullname = null;
                                if ($studentProfile !== false) {
                                    $fullname = $studentProfile[0]['surname'] . ' ' . $studentProfile[0]['firstname'] . ' ' . $studentProfile[0]['middlename'];
                                    $sex = $studentProfile[0]['sex'];


                                    $hasStudentBeenMove2Class = CheckIfStudenthasbeenMovedtoclass($db, $school_deploymentidx, $admissionNumber, $class_name, $term_name, $session_name);

                                    if ($hasStudentBeenMove2Class !== false) {
                                        $medium = 3;
                                        $markingStatus_reason = MarkAttendanceNow($db, $attendance_reason, $school_idx, $sex, $personincharge, $admissionNumber, $class_name, $term_name, $session_name, $period, $period_period, $attendance_status, $medium);

                                        $markingStatus = true;
                                    } else {
                                        $markingStatus_reason = $fullname . ' ' . 'Has not been moved to this class for the term';
                                        $markingStatus = false;
                                    }
                                } else {
                                    $markingStatus_reason = 'Invalid Admission Number';
                                    $markingStatus = false;
                                }
                            }
                            $data['Fullname'] = $fullname;
                            $data['MarkingStatus'] = $markingStatus;
                            $data['MarkingStatusReason'] = $markingStatus_reason;
                            array_push($temp_array, $data);
                        }

//                        $index++;
//                       
                    }
//                return SuccessMessageWithData($dat, $response, 'User Data');
                    return SuccessMessageWithData($temp_array, $response, 'recog');
                } else {
                    return ErrorMessage($response, "noooo ");
                }
                //      return SuccessMessageWithData($dat['images'][0]['transaction'], $response, "Face Register Successfully");
//                     return SuccessMessageWithData($dat, $response,gettype($dat));
            }







//            $studentProfile = CheckIfAdmissionNumberExistInSchool($db, $admissionnumber);
//            if ($studentProfile !== false) {
//// $iplode= implode('/', $admissionnumber);
//                $formatedAdmissionNumber = str_replace("/", "-", $admissionnumber);
//                $uniqueIdentifier = $school_idx . '_' . $school_deploymentidx . '_' . $formatedAdmissionNumber;
//                $table = 'general_OTP';
//                $field1 = 'mobile';
//                $field2 = 'token';
//                $value1 = '09099';
////            $deploymentidx = $Resp[0]['deploymentidx'];
////                $OTP = generatesOTP($table, $field1, $field2, $value1, $db);
////                $uniqueIdentifier = $OTP;
//  
////                $dat_json = json_encode($dat);
//
//               
////          return SuccessMessageWithData($encrypt, $response,'Data');
//            } else {
//                return ErrorMessage($response, 'Invalid Admission Number');
//            }
        } else {
            return ErrorMessage($response, 'Invalid School URl. Please check and try again');
        }
    } else if ($isAuth === 2) {

        return ErrorMessage($response, 'Invalid Api Key');
    } else {
        return ErrorMessage($response, 'Please Supply your API token');
    }
});
/* * *---------------------------------- recognize Face--------------------------------------------------------* */



require_once 'utils.php';
