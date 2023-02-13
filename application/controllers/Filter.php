<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Filter extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('FilterModel');
    }

    public function index($idVacancy = NULL)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'GET':

                $jabatan = str_replace('%', ' ', $this->input->get('jabatan'));

                $query = $this->FilterModel->cek_bidang_pengalaman($idVacancy, $jabatan);

                if ($query->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Found',
                        'bidang' => $jabatan,
                        'result' => $query->result()
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'Not Found'
                    ];
                }

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request'
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getPendidikan($idVacancy)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'GET':
                $id_pendidikan = $this->FilterModel->getData('tbl_persyaratan', ['id_vacancy' => $idVacancy])->result()[0]->id_pendidikan;

                $point = $this->FilterModel->getData('tbl_pendidikan', ['id_pendidikan' => $id_pendidikan])->result()[0]->value;

                $data['response'] = [
                    'status' => 200,
                    'value' => intval($point)
                ];

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request'
                ];
                break;
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
