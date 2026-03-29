<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Device extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Web_Model', 'web');
        $this->load->library('session');
    }

    public function common_api() {
        // Get the POST JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        if(empty($input['api']) || empty($input['data'])){
            echo json_encode(["status" => 400, "msg" => "Invalid Request"]);
            return;
        }

        $apiName = $input['api'];
        $payload = json_encode($input['data']);

        // Call the model method to perform the CURL request
        $response = $this->web->callCommonApi($apiName, $payload);

        header('Content-Type: application/json');
        echo $response;
        exit;
    }
}
