<?php
//include "inc_config.php";

function DateThai($strDate){
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}

if($_POST['action']=='appoint') {

    $_POST['email'] = strtolower($_POST['email']);
    function isValidEmail($email) { 
        if(preg_match("/^([_a-z0-9-]+)(\\.[_a-z0-9-]+)*@([a-z0-9-]+)(\\.[a-z0-9-]+)*(\\.[a-z]{2,4})$/" , $email)) { 
            list($username, $domain) = explode('@', $email);
            if(getmxrr($domain, $mxhosts)) {    //if MX Record exists return the email address
                return $email;
            }
        }
        return false;  
        //return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);  
    }
    if(isValidEmail($_POST['email'])==true) {
        $date = (explode("/",$_POST['datepicker']));
        $date2 = $date[2]."-".$date[1]."-".$date[0];
        $addpoint = array('name'=>$_POST['name'],'lastname'=>$_POST['lastname'],'email'=>$_POST['email'],'phone'=>$_POST['phone'],'date'=>$date2,'time'=>$_POST['timepicker'],'detail'=>$_POST['detail'],'created_date'=>date("Y-m-d H:i:s"));
        $db->AutoExecute("appointments",$addpoint,"INSERT");

        $message = "
            <table width='700' border='0' cellspacing='0' cellpadding='10' style='border:0px solid #000000;'>
                 <tr>
                    <td valign='top'>
                        <fieldset style='margin:0px 0px; padding:0 10px 10px 10px;'>
                            <legend><strong>แจ้งการนัดหมายเข้าชมโครงการล่วงหน้า</strong></legend><br />
                            <span>ผู้เข้าชม คุณ ".$_POST['name']." ".$_POST['lastname']."</span><br /><br />
                            <span>เบอร์โทรติดต่อ ".$_POST['phone']."</span><br /><br />
                            <span>อีเมล์ ".$_POST['email']."</span><br /><br />
                            <span>วันที่เข้าชมโครงการ ".DateThai($date2)." เวลา ".$_POST['timepicker']." น.</span><br /><br />
                            <span>รายละเอียดที่ต้องการเพิ่มเติม ".nl2br($_POST['detail'])."</span><br /><br />
                        </fieldset>
                    </td>
                </tr>
            </table>
        ";
        $from = $_POST['email'];     
        $to = "jerawat@zaxisit.com";
        //$to = "thevictoria.kk@hotmail.com";
        //$to = "suphamas_worakarn@yahoo.com";
        $subject = "Make an appointment";
        $headers = "From: $from\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "X-Mailer: PHP's mail() Function\n"; 
        mail($to, $subject, $message, $headers);

        echo "<script>alert('ส่งข้อมูลแจ้งนัดหมายเข้าชมโครงการของท่านแล้ว');</script>";
        if ($_POST['action2']) {
            echo "<script>window.location.href = 'index.php';</script>";
        }else{
            echo "<script>window.location.href = 'appointments.php';</script>";
        }
    }else{
        echo "<script>alert('รูปแบบอีเมล์ไม่ถูกต้อง');</script>";
    }
    
}

?>

