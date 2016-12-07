<?php
namespace Front\Controller;

use COM\Controller\Front;

class AttendanceController extends Front
{
    protected $fadingjiejiari = array(
        '01' => array(1, 2, 3), '02' => array(7, 8, 9, 10, 11, 12, 13),
        '03' => array(), '04' => array(2, 3, 4, 30),
        '05' => array(1, 2), '06' => array(9, 10, 11),
        '07' => array(), '08' => array(),
        '09' => array(15, 16, 17), '10' => array(1, 2, 3, 4, 5, 6, 7),
        '11' => array(), '12' => array(),
    );
    protected $tiaoxiu = array(
        '01' => array(), '02' => array(6, 14),
        '03' => array(), '04' => array(),
        '05' => array(), '06' => array(12),
        '07' => array(), '08' => array(),
        '09' => array(18), '10' => array(8, 9),
        '11' => array(), '12' => array(),
    );

    public function indexAction()
    {
        $this->view->setNoLayout();
        $errorMsg = current($this->flashMessenger()->getErrorMessages());
        $this->view->setVariable('errorMsg', $errorMsg);
        return $this->view;
    }

    public function sendAction()
    {

        error_reporting(0);
        $response = '<html><body>';
        if (!empty($_FILES['attendance']['tmp_name'])) {
            header('content-type:text/html;charset=utf-8');

            /*$smtpuser = $this->request->getPost('SmtpUser');
            $smtppass = $this->request->getPost('SmtpPass');
            $testStatus = $this->request->getPost('Test');

            if(empty($smtpuser) || empty($smtppass)){
                $this->flashMessenger()->addErrorMessage('请输入发件人邮箱和密码!');
                return $this->redirect()->toUrl('index');
            }*/
            $fp = fopen($_FILES['attendance']['tmp_name'], 'r');
            $row = 0;
            $employees = array();
            while ($data = fgetcsv($fp)) {
                if ($row) {
                    $employees[$data[2]][] = $data;
                } else {
                    $columns = $data;
                }
                $row++;
            }
            fclose($fp);

            /*$smtpserver = "smtp.chepinmall.com";
            $smtpserverport = 25;

            $smtpusermail = $smtpuser;


            $mailtype = "HTML";
            $smtp = $this->plugin('Api\Plugin\Smtp');
            $smtp->init($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);

            $smtp->debug = false;*/

            $template = <<<EOL
    <div>
        Hi {employeeName}, <br />
        以下是你{month}月份打卡记录明细以及汇总，请以邮件回复的形式确认；如有问题，请在下班前及时与叮当联系，如未回复就默认确认无误并以此汇总结算工资，请知悉，谢谢。<br />

        汇总：<br />
        出勤天数：{attendanceDays}天 <br />
        请假天数：{leaveDays}天 <br />
        迟到次数：{lateTimes}次 <br />
        迟到分钟数：{lateMinutes}分钟 <br />
        {attendanceDetail}
    <div><h2>******************</h2>
EOL;
            //$employeeModel = $this->sm->get('Api\Model\Employee');
            foreach ($employees as $k => $v) {
                $attendanceDays = 0;
                $leaveDays = 0;
                $lateTimes = 0;
                $lateMinutes = 0;
                $attendanceDetail = '';

                $mailcontent = $template;
                $employeeName = $k;
                $month = date('m', strtotime($v[0][3]));


                $attendanceDetail = '<table border="1" cellspacing="0" cellpadding="0">';
                foreach ($columns as $column) {
                    $attendanceDetail .= '<th>' . $column . '</th>';
                }

                foreach ($v as $sv) {
                    $sourceEncoding = 'gb18030';
                    $toEncoding = 'utf-8';
                    $remark = mb_convert_encoding($sv[8], $toEncoding, $sourceEncoding);

                    $weekDay = date('w', strtotime($sv[3]));
                    $attendanceDetail .= '<tr>';
                    $rest = false;
                    if ((in_array($weekDay, array(0, 6)) && !in_array(date('j', strtotime($sv[3])), $this->tiaoxiu[$month])) || in_array(date('j', strtotime($sv[3])), $this->fadingjiejiari[$month])) {
                        $attendanceDetail .= '<tr style="background-color:yellow;">';
                        $rest = true;
                    } elseif ($remark == '请假') {
                        $leaveDays++;
                    } elseif (!empty($sv[4]) || !empty($sv[5])) {
                        $attendanceDays++;
                    }


                    if (!empty($sv[6]) && !$rest) {
                        $lateTimes++;
                        $tmp = explode(':', $sv[6]);
                        $tmpLateMinutes = $tmp[0] * 60 + $tmp[1];
                        if(!empty($availableLateMinutes)){
                            $tmpLateMinutes -= $availableLateMinutes;
                            if($tmpLateMinutes < 0) $tmpLateMinutes = 0;
                        }
                        $lateMinutes += $tmpLateMinutes;
                    }

                    $availableLateMinutes = 0;
                    if(!empty($sv[5])){
                        $leaveTime = explode(':', $sv[5]);
                        if($leaveTime[0] >= 21 && $leaveTime[0] < 22){
                            $availableLateMinutes = 30;
                        }elseif($leaveTime[0] >= 22 && $leaveTime[0] < 24){
                            $availableLateMinutes = 60;
                        }
                    }



                    $attendanceDetail .= '<td>' . $sv[0] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[1] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[2] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[3] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[4] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[5] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[6] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[7] . '</td>';
                    $attendanceDetail .= '<td>' . $sv[8] . '</td>';
                    $attendanceDetail .= '</tr>';
                }

                $attendanceDetail .= '</table>';
                $employeeName = mb_convert_encoding($employeeName, $toEncoding, $sourceEncoding);
                $attendanceDays = mb_convert_encoding($attendanceDays, $toEncoding, $sourceEncoding);
                $leaveDays = mb_convert_encoding($leaveDays, $toEncoding, $sourceEncoding);
                $lateTimes = mb_convert_encoding($lateTimes, $toEncoding, $sourceEncoding);
                $lateMinutes = mb_convert_encoding($lateMinutes, $toEncoding, $sourceEncoding);
                $attendanceDetail = mb_convert_encoding($attendanceDetail, $toEncoding, $sourceEncoding);

                /*$employeeInfo = $employeeModel->select(array('EmployeeName' => $employeeName))->current();
                if(empty($employeeInfo)) continue;
                if(!empty($testStatus)){
                    $smtpemailto = 'tao.xu@chemayi.com';
                    $smtpcc = 'tao.xu@chemayi.com';
                }else{
                    $smtpemailto = $employeeInfo['Email'];
                    $smtpcc = $employeeInfo['CcEmail'];
                }*/

                $mailtitle = $month . '月份考勤明细--' . $employeeName;
                $mailtitle = mb_convert_encoding($mailtitle, $sourceEncoding, $toEncoding);
                $mailcontent = str_replace('{employeeName}', $employeeName, $mailcontent);
                $mailcontent = str_replace('{month}', $month, $mailcontent);
                $mailcontent = str_replace('{attendanceDays}', $attendanceDays, $mailcontent);
                $mailcontent = str_replace('{leaveDays}', $leaveDays, $mailcontent);
                $mailcontent = str_replace('{lateTimes}', $lateTimes, $mailcontent);
                $mailcontent = str_replace('{lateMinutes}', $lateMinutes, $mailcontent);
                $mailcontent = str_replace('{attendanceDetail}', $attendanceDetail, $mailcontent);
                //$result = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype, $smtpcc);
                /*if(empty($result)){
                    $this->flashMessenger()->addErrorMessage('邮件发送失败, 发件人邮箱或密码错误!');
                    return $this->redirect()->toUrl('index');
                }*/
                $response .= $mailcontent;
            }
            $response .= '</body></html>';
            $this->response->setContent($response);

            return $this->response;
        }else{
            $this->flashMessenger()->addErrorMessage('请添加文件!');
            return $this->redirect()->toUrl('index');
        }
        return $this->response;
    }
}

