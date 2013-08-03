<?php

/**
 * Classe de helper de email 
 *
 * @package     Pilot
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 * @copyright   Copyright (c) 2013 ARG Softwares (http://www.argsoftwares.com.br)  
 * @license     Commercial
 */
class emailHelper extends Pilot_Lib {

    const sendTypeMail = 'mail';
    const sendTypeSMPT = 'smtp';
    
    private $_phpmailer;
    
    private $_nameTo;
    private $_mailTo;
    private $_addressTo = array();
    private $_nameFrom;
    private $_mailFrom;
    private $_subject;
    private $_body;
    private $_smtp = null;
    private $_userSMTP = null;
    private $_passSMTP = null;
    
    
    public function __construct() {
        $this->_phpmailer = new PHPMailer(true);
        $this->_phpmailer->IsHTML(true);
    }
    
    /**
     * 
     * Método que faz o envio do email.
     * 
     * Antes de usar esse método defina com o método setEmailTo()
     * para qual pessoa o email será enviado
     * 
     * @param type $sendMethod
     * @return boolean
     * @throws Exception
     */
    public function enviaEmail($sendMethod = self::sendTypeMail) {
        
        try {
            switch ($sendMethod) {
                case 'mail':
                    $this->_phpmailer->IsMail(true);
                    break;
                case 'smtp':
                    $this->_phpmailer->IsSMTP();
                    $this->fillSMTPFields();
                    break;
                default:
                    break;
            }
            
            $this->fillToFields();
            $this->fillFromFields();
            
            //Send the message, check for errors
            if(!$this->_phpmailer->Send()) {
                throw new Exception(self::___(self::MSG_ERRO_MAILER, $this->_phpmailer->ErrorInfo), self::MSG_ERRO_MAILER_COD);
            }
            
            return true;
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
    }
    
    
    /**
     * Método que faz o envio do email com arquivo.
     * 
     * Antes de usar esse método defina com o método setEmailTo()
     * para qual pessoa o email será enviado
     * 
     * @param type $fileName
     * @param type $filePath
     * @param type $sendMethod
     * @return type
     * @throws Exception
     */
    public function enviaEmailAttachment($fileName, $filePath, $sendMethod = self::sendTypeMail) {
        
        try {
            $this->_phpmailer->AddAttachment($filePath, $fileName);
            return $this->enviaEmail($sendMethod);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        
    }
    
    
    private function fillSMTPFields() {
        if (!$this->checkFillSMTP()) {
            throw new Exception(self::___(self::MSG_ERRO_SMTP), self::MSG_ERRO_SMTP_COD);
        }
        
        $this->_phpmailer->Host = $this->_smtp;
        $this->_phpmailer->SMTPAuth = true;
        $this->_phpmailer->Username = $this->_userSMTP;
        $this->_phpmailer->Password = $this->_passSMTP;
        
        $this->_phpmailer->SMTPDebug   = 2;
        $this->_phpmailer->Debugoutput = 'html';
        $this->_phpmailer->Port        = 587;
        $this->_phpmailer->SMTPSecure  = 'tls';
        
        return $this;
    }
    
    private function fillFromFields() {
        $this->_phpmailer->From = $this->_mailFrom;
        $this->_phpmailer->FromName = $this->_nameFrom;
        return $this;
    }
    
    private function fillToAddressFields() {
        foreach ($this->_addressTo as $value) {
            $this->_phpmailer->AddAddress($value['email'], $value['name']);
        }
        return $this;
    }
    
    private function fillToFields() {
        $this->fillToAddressFields();
        $this->_phpmailer->Subject = $this->_subject;
        $this->_phpmailer->Body = $this->_body;
        return $this;
    }

    private function checkFillSMTP() {
        return !is_null($this->_smtp);
    }
    
    public function setSMTPAuth($username, $password) {
        $this->_userSMTP = $username;
        $this->_passSMTP = $password;
        
        return $this;
    }


    public function setSubject($subject) {
        $this->_subject = $subject;
        
        return $this;
    }

    public function addEmailTo($name, $email) {
        $this->_addressTo[] = array('name' => $name, 'email' => $email);
        
        return $this;
    }
    
    public function setEmailFrom($name, $email) {
        $this->_nameFrom = $name;
        $this->_mailFrom = $email;
        
        return $this;
    }

    public function getEmailTo() {
        return $this->_addressTo;
    }
    
    public function getEmailFrom() {
        $email = array();
        $email['name'] = $this->_nameFrom;
        $email['mail'] = $this->_mailFrom;
        
        return $email;
    }
    
    public function setBody($body) {
        $this->_body = $body;
        return $this;
    }
    
    public function setSMTPHost($host) {
        $this->_smtp = $host;
        return $this;
    }
    
}