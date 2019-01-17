<?php

class ReceiptNalog {

    private $_headers;

    public function __construct() {
        $deviceId = uniqid();
        $this->_headers = array(
            "Device-Id: $deviceId",
            "Device-OS: Android 4.4.4",
            "Version: 2",
            "ClientVersion: 1.4.1.3",
            "UserAgent: okhttp/3.0.1",
        );
    }

    private function load_url($url) {
        global $db;
        
        $stmtS = $db->prepare(
            "SELECT fns_userpwd
             FROM ".DB_TABLE_PREFIX."receipt_user 
             WHERE dtLastLimit < NOW() - INTERVAL 1 DAY");
        $stmtS->execute();
        $row = $stmtS->fetch();
        if(!$row)
            return "No available logins";
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
        curl_setopt($curl, CURLOPT_USERPWD, $row['fns_userpwd']);
        
        $data = curl_exec($curl);
        if($data == "daily limit reached for the specified user"){
            $stmtU = $db->prepare(
                "UPDATE ".DB_TABLE_PREFIX."receipt_user
                 SET dtLastLimit = NOW()
                 WHERE fns_userpwd = ?");
            $stmtU->execute(array($row['fns_userpwd']));
        }
        return $data;
    }

    /**
     * string $fiscalDriveNumber ФН
     * string $fiscalDocumentNumber ФД
     * string $fiscalSign ФП
     */
    public function get($fiscalDriveNumber, $fiscalDocumentNumber, $fiscalSign) {
        return $this->load_url("https://proverkacheka.nalog.ru:9999/v1/inns/*/kkts/*/fss/$fiscalDriveNumber/tickets/$fiscalDocumentNumber?fiscalSign=$fiscalSign&sendToEmail=no");
    }

    /**
     * string $fiscalDriveNumber ФН
     * string $fiscalDocumentNumber ФД
     * string $fiscalSign ФП
     */
    public function check($fiscalDriveNumber, $fiscalDocumentNumber, $fiscalSign, $dt, $sum) {
        return $this->load_url("https://proverkacheka.nalog.ru:9999/v1/ofds/*/inns/*/fss/$fiscalDriveNumber/operations/1/tickets/$fiscalDocumentNumber?fiscalSign=$fiscalSign&date=$dt&sum=$sum");
    }

}
