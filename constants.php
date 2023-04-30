<?php

include_once 'config.php';

define("SITE_NAME", $title);
date_default_timezone_set("Asia/Dhaka");
$date = date('D, d-M-Y h:i:s A');;
$date_small = date('d-M-Y');;

$paystack = "sk_test_f0a3ca54c4de394aef3f35b0713ddf27fbbbcb3b"; 
if (!function_exists('connect')) {

    function connect()
    {
        $con = new mysqli("localhost", "root", "", "ticketkatun");
        if (!$con) die("Database is being upgraded!");
        return $con;
    }
}

function genSeat($id, $type, $number)
{
    $conn = connect();
    $type_seat = $conn->query("SELECT train.first_seat as first, train.second_seat as second FROM schedule INNER JOIN train ON train.id = schedule.train_id WHERE schedule.id = '$id'")->fetch_assoc();
    $me = $type_seat[$type];
    $query = $conn->query("SELECT SUM(no) AS no FROM booked WHERE schedule_id = '$id' AND class = '$type'")->fetch_assoc();
    $no = $query['no'];
    if ($no == null) $no = 1;
    else $no++;
    //Multiple Seats or Not
    if ($number == 1) {
        while (strlen($no) != strlen($me)) {
            $no = "0" . $no;
        }
        return strtoupper(substr($type, 0, 1)) . "$no";
    } else {
        $start = $no;
        $end = $no + ($number - 1);
        while (strlen($start) != strlen($me)) {
            $start = "0" . $start;
        }
        while (strlen($end) != strlen($me)) {
            $end = "0" . $end;
        }
        $append = strtoupper(substr($type, 0, 1));

        return $append . $start . " - " . $append . $end;
    }
}


function genCode($id, $user, $class)
{
    $conn = connect();
    $query = $conn->query("SELECT SUM(no) AS no FROM booked WHERE schedule_id = '$id'")->fetch_assoc();
    $no = $query['no'];
    if ($no == null) $no = 1;
    else $no++;
    $number = "";
    switch (strlen($id)) {
        case 1:
            $number = "00";
            break;
        case 2:
            $number = "0";
            break;
        default:
            $number = "0";
            break;
    }
    $code = date('Y') . "/$number" . $id . "/" . $no . mt_rand(1, 882);
    return $code;
}

function login($username, $password)
{
    $password = md5($password);
    $q = connect()->query("SELECT * FROM users WHERE email = '$username' AND password = '$password' AND status = '1' ")->num_rows;
    if ($q == 1) return 1;
    return 0;
}
function adminLogin($username, $password)
{
    $q = connect()->query("SELECT * FROM usersadmin WHERE username = '$username' AND password = '$password' ")->num_rows;
    if ($q == 1) return 1;
    return 0;
}
function getUserName($id, $conn = null)
{
    $conn = connect();
    $q = $conn->query("SELECT * FROM users WHERE id = '$id'")->fetch_assoc();
    return $q['name'];
}

function uploadFile($file)
{

    $loc = genRand() . "." . strtolower(pathinfo(@$_FILES[$file]['name'], PATHINFO_EXTENSION));
    $valid_extension = array("jpg", "png", "jpeg");
    //Check for valid file size
    if (($_FILES[$file]['size'] && !in_array(strtolower(pathinfo(@$_FILES[$file]['name'], PATHINFO_EXTENSION)), $valid_extension)) || ($_FILES[$file]['size'] && $_FILES[$file]['error']) > 0) {
        return -1;
    }
    $upload = move_uploaded_file(@$_FILES[$file]['tmp_name'], "uploads/" . $loc);
    if ($upload) {
        chmod("uploads/" . $loc, 0777);
        return $loc;
    } else {
        return -1;
    }
}

function genRand()
{
    return md5(mt_rand(1, 3456789) . date('dmyhmis'));
}

function getImage($id, $conn)
{
    $row = $conn->query("SELECT loc FROM users WHERE id = '$id'")->fetch_assoc();
    if (strlen($row['loc']) < 10) return "images/admin.jpg";
    else return "uploads/" . $row['loc'];
}

function formatDate($date)
{
    return date('d-m-Y', strtotime($date));
}

function getRoutePath($id)
{
    $val = connect()->query("SELECT * FROM route WHERE id = '$id'")->fetch_assoc();
    return $val['start'] . " to " . $val['stop'];
}

function formatTime($time)
{
    $time = explode(":", $time);
    if ($time[0] > 12) {
        $string = ($time[0] - 12) . ":" . $time[1] . " PM";
    } else {
        $string = ($time[0]) . ":" . $time[1] . " AM";
    }
    return $string;
}
function getToday()
{
    return date('d-m-Y');
}

function getTime()
{
    return date('H:i');
}

function querySchedule($type)
{
    $today = getToday();
    $conn = connect();
    $row = 0;
    if ($type == 'future') {
        $row = $conn->query("SELECT * FROM `schedule` WHERE STR_TO_DATE(`date`,'%d-%m-%Y') >= STR_TO_DATE('$today','%d-%m-%Y')");
    } else {
        $row = $conn->query("SELECT * FROM `schedule` WHERE STR_TO_DATE(`date`,'%d-%m-%Y') <= STR_TO_DATE('$today','%d-%m-%Y')");
    }
    return $row;
}

function getRouteFromSchedule($id)
{
    $q = connect()->query("SELECT route_id as id FROM schedule WHERE id = '$id'")->fetch_assoc();
    return getRoutePath($q['id']);
}

function getFee($id, $type = 'second')
{
    if ($type == 'second') {
        $type = 'second_fee';
    } else {
        $type = 'first_fee';
    }
    $q = connect()->query("SELECT $type FROM schedule WHERE id = '$id'")->fetch_assoc();
    return $q[$type];
}

function getTotalBookByType($id)
{

    $con =  connect()->query("SELECT SUM(no) as no FROM `booked` WHERE schedule_id = '$id' AND class = 'first'")->fetch_assoc();
    $con2 =  connect()->query("SELECT SUM(no) as no FROM `booked` WHERE schedule_id = '$id' AND class = 'second'")->fetch_assoc();
    $no = $con['no'];
    $no2 = $con2['no'];
    $num = $no == null ? 0 :  $con['no'];
    $num2 = $no2 == null ? 0 :  $con2['no'];
    $qu = connect()->query("SELECT train.first_seat as first, train.second_seat as second FROM schedule INNER JOIN train ON train.id = schedule.train_id WHERE schedule.id = '$id'")->fetch_assoc();
    $first = $qu['first'];
    $second = $qu['second'];
    $first = intval($first);
    $second = intval($second);
    $num = intval($num);
    $num2 = intval($num2);
    return array("first" => $first, "second" => $second, "first_booked" => $num, "second_booked" => $num2);
}

function isScheduleActive($id)
{
    $today = getToday();
    $con = connect();
    $conn = $con->query("SELECT * FROM `schedule` WHERE STR_TO_DATE(`date`,'%d-%m-%Y') >= STR_TO_DATE('$today','%d-%m-%Y') AND `id` = '$id'");
    if ($conn->num_rows == 1) {
        $row = $conn->fetch_assoc();
        $time = getTime();
        $schedule_date = $row['date'];
        $schedule_time = $row['time'];
        if ($schedule_date == $today) {
            if (strtotime($schedule_time) <= strtotime($time)) return false;
        }
        return true;
    }
    return false;
}

function getTrainName($id)
{
    $val = connect()->query("SELECT name FROM train WHERE id = '$id'")->fetch_assoc();
    return $val['name'];
}
function alert($msg)
{
    echo "<script>alert('$msg')</script>";
}

function load($link)
{
    echo "<script>window.location = ('$link')</script>";
}

function printClearance($id)
{
    ob_start();
    $con = connect();
    $me = $_SESSION['user_id'];
    $getCount = (connect()->query("SELECT schedule.id as schedule_id, users.name as fullname, users.email as email, users.phn as phone, users.nid as nid, users.loc as loc, payment.amount as amount, payment.ref as ref, payment.date as payment_date, schedule.train_id as train_id, booked.code as code, booked.no as no, booked.class as class, booked.seat as seat, schedule.date as date, schedule.time as time FROM booked INNER JOIN schedule on booked.schedule_id = schedule.id INNER JOIN payment ON payment.id = booked.payment_id INNER JOIN users ON users.id = booked.user_id WHERE booked.id = '$id'"));
    if ($getCount->num_rows != 1) die("Denied");
    $row = $getCount->fetch_assoc();
    $users_name = substr($fullname = ($row['fullname']), 0, 15);
    $name = $fullname;
    $phone = $row['phone'];
    $nid = $row['nid'];
    $email = $row['email'];
    $timeframe = '<tr><th style="text-align:center"><b>Project Phase</b></th><th style="text-align:center"><b>Date Accepted</b></th></tr>';
    $date = $row['date'];
    $time = formatTime($row['time']);
    $uniqueCode = $row['code'];
    $route = getRouteFromSchedule($row['schedule_id']);
    $date = date("D d, M Y", strtotime($date));

    //$barcode = "$fullname Ticket For $route - $date by $time. Ticket ID : $uniqueCode";
    //$barcodeOutput = generateQR($id, $barcode);
    $loc = $row['loc'];
    $seat = $row['seat'];
    $train = getTrainName($row['train_id']);
    $class = $row['class'];
    $payment_date = $row['payment_date'];
    $amount = $row['amount'];
    $file_name = preg_replace('/[^a-z0-9]+/', '-', strtolower($users_name)) . ".pdf";
    require_once 'PDF/tcpdf_config_alt.php';

    // Include the main TCPDF library (search the library on the following directories).
    $tcpdf_include_dirs = array(
        realpath('PDF/tcpdf.php'),
        '/usr/share/php/tcpdf/tcpdf.php',
        '/usr/share/tcpdf/tcpdf.php',
        '/usr/share/php-tcpdf/tcpdf.php',
        '/var/www/tcpdf/tcpdf.php',
        '/var/www/html/tcpdf/tcpdf.php',
        '/usr/local/apache2/htdocs/tcpdf/tcpdf.php',
    );
    foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
        if (@file_exists($tcpdf_include_path)) {
            require_once $tcpdf_include_path;
            break;
        }
    }

    class MYPDF extends TCPDF
    {
        //Page header
        public function Header()
        {
            // get the current page break margin
            $bMargin = $this->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            // disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = K_PATH_IMAGES . "bdrailbg.png";
            // die($img_file);
            $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $this->SetAlpha(0.5);

            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $this->setPageMark();
        }
    }
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    //$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageLayout, true, 'UTF-8', false);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($fullname);
    $pdf->SetTitle($fullname . " Ticket");
    $pdf->SetSubject(SITE_NAME);
    $pdf->SetKeywords("Train Booking System, Rail, Rails, Railway, Booking, Project, System, Website, Portal ");


    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 7, PDF_MARGIN_RIGHT);
    // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(true, 5);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once dirname(__FILE__) . '/lang/eng.php';
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 14, '', true);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
    //$src = $barcodeOutput;
    // set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
    // Set some content to print
    $html = <<<EOD
<style>
table th{font-weight:italic}
</style>
<h1 style="text-align:center"><img src="images/bdrailbg.png" width="100" height="100"/><br/>TicketKatun<br/> Train Tickets</h1> <div style="text-align:right; font-family:courier;font-weight:bold"><font size="+6">Ticket N<u>o</u>: $uniqueCode </font></div>
<table width="100%" border="1">
<tr><th colspan="2" style="text-align:center"><b>Personal Data</b></th></tr>
<tr><th><b>Name:</b></th><td>$fullname</td></tr>
<tr><th><b>Email:</b></th><td>$email</td></tr>
<tr><th><b>Contact:</b></th><td>$phone</td></tr>
<tr><th><b>NID:</b></th><td>$nid</td></tr>
<tr><td colspan="2" style="text-align:center"><b>Trip Details</b></td></tr>
<tr><th><b>Route:</b></th><td>$route</td></tr>
<tr><th><b>Train:</b></th><td>$train</td></tr>
<tr><th><b>Seat Number:</b></th><td>$seat</td></tr>
<tr><th><b>Date:</b></th><td>$date</td></tr>
<tr><th><b>Time:</b></th><td>$time</td></tr>
<tr><th colspan="2"  style="text-align:center"><b>Payment Detailes</b></th></tr>
<tr><th><b>Amount:</b></th><td>$ $amount</td></tr>
<tr><th><b>Payment Date:</b></th><td>$payment_date</td></tr>


</table>
EOD;

    // @unlink($barcodeOutput);
    $html .= <<<EOD
<table width="100%">
<tr><td colspan="2"><p>&nbsp;</p></td></tr>
<tr><td colspan="2" style="text-align:center"><font size="-3"><i><em><strong>CAUTION: </strong></em> After successful payment you can cancel your tickets, before that collect your payment reference for refund. </i></font></td></tr>
<tr><td colspan="2" style="text-align:center"><font size="-3"><i><em><strong>NOTE: </strong></em> Please try to be check in an hour early for all neccessary proceedings! </i></font></td></tr>
    
    </table>
EOD;
    // die($html);
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($file_name, 'D');
    
}
function sum($id, $type = null)
{
    $conn = connect();
    if ($type == null) {
        $row = $conn->query("SELECT SUM(amount) as amount FROM `payment` INNER JOIN booked ON booked.payment_id = payment.id AND booked.schedule_id = payment.schedule_id WHERE payment.schedule_id = '$id'")->fetch_assoc();
    } else {
        $row = $conn->query("SELECT SUM(amount) as amount FROM `payment` INNER JOIN booked ON booked.payment_id = payment.id AND booked.schedule_id = payment.schedule_id WHERE payment.schedule_id = '$id' AND booked.class = '$type'")->fetch_assoc();
    }
    return $row['amount'] == null ? 0 : $row['amount'];
}

function printReport($id)
{
    ob_start();
    $con = connect();
    $getCount = (connect()->query("SELECT schedule.date as date, schedule.time as time, schedule.train_id as train, schedule.route_id as route, booked.seat as seat, users.name as fullname, booked.code as code, booked.class as class FROM booked INNER JOIN schedule ON schedule.id = booked.schedule_id INNER JOIN users ON users.id = booked.user_id WHERE booked.schedule_id = '$id' ORDER BY class "));

    $output = "<style>
    .a {
        text-align:left;
        width: 10%;
    }
    .b{
        width: 20%
    }.c{
    width:30%;
    }
    table {
        border: 1px solid green;
        border-collapse: collapse;
        width: 100%;
        white-space: nowrap;
      }
      
   
          table th.shrink {
        white-space: nowrap
      }
      th{
        font-weight:bolder;

      }
      .shrink {
        white-space: nowrap;
        width: 40%;

      }
      
      table td.expand {
        width: 99%
      }
 
    </style>";
    $sn = 0;
    $schedule = getRouteFromSchedule($id);
    if ($getCount->num_rows < 1) {
        echo "<script>alert('No users yet for this schedule!');window.location='admin.php?page=report'</script>";
        exit;
    }
    while ($row = $getCount->fetch_assoc()) {
        $date = $row['date'];
        $time = $row['time'];
        $train = getTrainName($row['train']);
        $route = getRouteFromSchedule($id);
        $time = formatTime($time);
        $sn++;
        $output .= '<tr><td class="a">' . $sn . '</td><td class="c">' . substr(ucwords(strtolower($row['fullname'])), 0, 15) . '</td><td class="shrink">' . $row['code'] . ' (' . ucwords(strtolower($row['class'])) . ')</td><td class="b">' . (strtoupper($row['seat'])) . '</td></tr>';
    }
    $start = '<table class="table" width="100%" border="1"><tr><th class="a">SN</th><th  class="c">Full Name</th><th  class="shrink">Code/Class</th><th  class="b">Seat No</th></tr>';
    $end = '</table>';
    $result = $start . $output . $end;
    // die($result);
    $file_name = preg_replace('/[^a-z0-9]+/', '-', strtolower('train_booking')) . ".pdf";
    require_once 'PDF/tcpdf_config_alt.php';

    // Include the main TCPDF library (search the library on the following directories).
    $tcpdf_include_dirs = array(
        realpath('PDF/tcpdf.php'),
        '/usr/share/php/tcpdf/tcpdf.php',
        '/usr/share/tcpdf/tcpdf.php',
        '/usr/share/php-tcpdf/tcpdf.php',
        '/var/www/tcpdf/tcpdf.php',
        '/var/www/html/tcpdf/tcpdf.php',
        '/usr/local/apache2/htdocs/tcpdf/tcpdf.php',
    );
    foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
        if (@file_exists($tcpdf_include_path)) {
            require_once $tcpdf_include_path;
            break;
        }
    }

    class MYPDF extends TCPDF
    {
        //Page header
        public function Header()
        {
            // get the current page break margin
            $bMargin = $this->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $this->AutoPageBreak;
            // disable auto-page-break
            $this->SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = K_PATH_IMAGES . "/images/bdrailbg.png";
            // die($img_file);
            $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $this->SetAlpha(0.5);

            // restore auto-page-break status
            $this->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $this->setPageMark();
        }
    }
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $pageLayout, true, 'UTF-8', false);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor("Admin");
    $pdf->SetTitle("Train Bookings " . " Ticket");
    $pdf->SetSubject(SITE_NAME);
    $pdf->SetKeywords("Train Booking System, Rail, Rails, Railway, Booking, Project, System, Website, Portal ");


    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, 7, PDF_MARGIN_RIGHT);
    // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(true, 5);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once dirname(__FILE__) . '/lang/eng.php';
        $pdf->setLanguageArray($l);
    }

    // ---------------------------------------------------------

    // set default font subsetting mode
    $pdf->setFontSubsetting(true);

    // Set font
    // dejavusans is a UTF-8 Unicode font, if you only need to
    // print standard ASCII chars, you can use core fonts like
    // helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 12, '', true);

    // Add a page
    // This method has several options, check the source code documentation for more information.
    $pdf->AddPage();
    // set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
    // Set some content to print
    $html = '<h5 style="text-align:center"><img src="images/bdrailbg.png" width="80" height="80"/><br/>TicketKatun<br/> LIST OF BOOKINGS  FOR ' . $date . ' (' . $time . ')</h5> <div style="text-align:right; font-family:courier;font-weight:bold"><font size="+1">Train ' . $train . ' (' . $sn . ' Passengers) : ' . $schedule . ' </font></div>' . $result;
    // die($html);
    // Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    // ---------------------------------------------------------

    // Close and output PDF document
    // This method has several options, check the source code documentation for more information.
    $pdf->Output($file_name, 'D');
    @unlink($src);
}

function sendFeedback($msg)
{
    $me = $_SESSION['user_id'];
    $msg = connect()->real_escape_string($msg);
    $stmt = connect()->query("INSERT INTO feedback (user_id, message) VALUES ('$me', '$msg')");
    if ($stmt) return 1;
    return 0;
}

function getFeedbacks()
{
    $me = $_SESSION['user_id'];
    return connect()->query("SELECT * FROM feedback WHERE user_id = '$me'");
}

function replyTo($id, $reply)
{
    $con = connect();
    $reply = $con->real_escape_string($reply);
    $sql = $con->query("UPDATE feedback SET response = '$reply' WHERE id = '$id' ");
    if ($sql) return 1;
    return 0;
}