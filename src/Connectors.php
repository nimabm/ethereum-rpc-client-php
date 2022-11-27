<?php
namespace nimabm\Ethereum;

class Connectors
{
    public $rpcUrl;
    public $id;
    public function __construct($apiUrl) {
        $this->rpcUrl = $apiUrl;
    }

    public function call($method,$params=[]){
        $input['jsonrpc'] = "2.0";
        $input['method'] = $method;
        $input['params'] = $params;
        $input['id'] = $this->id;

        $json = json_encode($input);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->rpcUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result);
    }

    function __call($name, $value){
        $this->response = $this->call($name,$value);
        return $this;
    }

    public function setId($id){
        return  $this->id = $id;
    }

    public function getResult(){
        return  $this->response->result;
    }

    public function getError(){
        return  $this->response->error;
    }
    public function getId(){
        return  $this->response->result->id;
    }
    public function checkError(){
        if(isset($this->response->error)){
            return true;
        }else{
            return false;
        }
    }

}