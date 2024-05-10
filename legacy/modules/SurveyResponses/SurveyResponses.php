<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

require_once 'modules/AOP_Case_Updates/util.php';

class SurveyResponses extends Basic
{
    public $new_schema = true;
    public $module_dir = 'SurveyResponses';
    public $object_name = 'SurveyResponses';
    public $table_name = 'surveyresponses';
    public $importable = false;
    public $disable_row_level_security = true; // to ensure that modules created and deployed under CE will continue to function under team security if the instance is upgraded to PRO

    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $SecurityGroups;

    public function __construct()
    {
        parent::__construct();
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    public function save($check_notify = false)
    {
        global $sugar_config;
        $res = parent::save($check_notify);

        if ($this->email_response_sent) {
            return $res;
        }

        if (!$this->employee_id) {
            return $res;
        }

        $employee = BeanFactory::getBean('Employees', $this->employee_id);

        if (empty($employee->id)) {
            return $res;
        }

        $email = $employee->emailAddress->getPrimaryAddress($employee);
        if (!$email) {
            return $res;
        }

        if ($this->happiness > 7 || $this->happiness == -1) {
            $templateId = $sugar_config['survey_positive_confirmation_email'];
        } else {
            $templateId = $sugar_config['survey_negative_confirmation_email'];
            //Create case
            $case = BeanFactory::newBean('Cases');
            /* MintHCM #126906 START */
            if(!empty($case)){
                $case->name = 'SurveyFollowup';
                $case->description = "Received the following dissatisfied response from " . $employee->name . "<br>";
                $case->description .= $this->happiness_text;
                $case->from_negative_survey = true;
                $case->status = 'Open_New';
                $case->priority = 'P1';
                $case->type = 'User';
                if (!empty($employee->assigned_user_id)) {
                    $case->assigned_user_id = $employee->assigned_user_id;
                    $case->auto_assigned = true;
                }
                $case->save();
                $case->load_relationship('employees');
                $case->employees->add($employee);
            }
            /* MintHCM #126906 END */
        }
        if (!$templateId) {
            return $res;
        }

        /* MintHCM #126906 START */
        //if ($this->sendEmail($employee, $email, $templateId, $case)) {
        if (!empty($case) && $this->sendEmail($employee, $email, $templateId, $case)) {
        /* MintHCM #126906 END */
            $this->email_response_sent = true;
            $this->save();
        }

        return $res;
    }
    private function sendEmail($employee, $email, $emailTemplateId, $case)
    {
        require_once "include/SugarPHPMailer.php";
        $mailer = new SugarPHPMailer();
        $admin = new Administration();
        $admin->retrieveSettings();

        $mailer->prepForOutbound();
        $mailer->setMailerForSystem();

        $email_template = new EmailTemplate();
        $email_template = $email_template->retrieve($emailTemplateId);

        if (!$email_template) {
            $GLOBALS['log']->warn("SurveyResponse: Email template is empty");

            return false;
        }
        $text = $this->populateTemplate($email_template, $employee, $case);
        $mailer->Subject = $text['subject'];
        $mailer->Body = $text['body'];
        $mailer->IsHTML(true);
        $mailer->AltBody = $text['body_alt'];
        $mailer->From = $admin->settings['notify_fromaddress'];
        isValidEmailAddress($mailer->From);
        $mailer->FromName = $admin->settings['notify_fromname'];

        $mailer->AddAddress($email);
        if (!$mailer->Send()) {
            $GLOBALS['log']->info("SurveyResponse: Could not send email:  " . $mailer->ErrorInfo);

            return false;
        } else {
            $this->logEmail($email, $mailer, $employee->id);

            return true;
        }
    }
    private function populateTemplate(EmailTemplate $template, $employee, $case)
    {
        global $sugar_config;
        $beans = array(
            "Employees" => $employee->id,
        );
        if ($case) {
            $beans['Cases'] = $case->id;
        }
        $ret = array();
        $ret['subject'] = from_html(aop_parse_template($template->subject, $beans));
        $ret['body'] =
            from_html(
            aop_parse_template(str_replace("\$sugarurl", $sugar_config['site_url'], $template->body_html), $beans)
        );
        $ret['body_alt'] =
            strip_tags(
            from_html(
                aop_parse_template(str_replace("\$sugarurl", $sugar_config['site_url'], $template->body), $beans)
            )
        );

        return $ret;
    }

    private function logEmail($email, $mailer, $employeeId = null)
    {

        require_once 'modules/Emails/Email.php';
        $emailObj = new Email();
        $emailObj->to_addrs = $email;
        $emailObj->type = 'out';
        $emailObj->deleted = '0';
        $emailObj->name = $mailer->Subject;
        $emailObj->description = $mailer->AltBody;
        $emailObj->description_html = $mailer->Body;
        $emailObj->from_addr = $mailer->From;
        isValidEmailAddress($emailObj->from_addr);
        if ($employeeId) {
            $emailObj->parent_type = "Employees";
            $emailObj->parent_id = $employeeId;
        }
        $emailObj->date_sent_received = TimeDate::getInstance()->nowDb();
        $emailObj->modified_user_id = '1';
        $emailObj->created_by = '1';
        $emailObj->status = 'sent';
        $emailObj->save();
    }

}
