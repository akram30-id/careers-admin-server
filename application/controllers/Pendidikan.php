<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pendidikan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('PendidikanModel');
    }

    public function index()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $data['response'] = [
                    'success' => true,
                    'status' => 200,
                    'data' => $this->PendidikanModel->getPendidikan()->result()
                ];
                break;

            default:
                $data['response'] = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Bad Request'
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
