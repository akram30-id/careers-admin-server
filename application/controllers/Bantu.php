<?php

class Bantu extends CI_Controller
{


    public function index()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':

                $data_upld = array();
                $data_upld['upload_1'] = NULL;
                $data_pyt = array();
                $data_pyt['pay_1'] = NULL;
                $booking = $this->input->post('bko');
                $data_all = array();
                $data_all['diskon'] = $this->input->post('dsk');
                $data_all['id_stats'] = '2'; // KURANG BAYAR
                $data_all['date_created'] = date("Y-m-d");
                $data_all['date_modified'] = date("Y-m-d");
                $data_apv = array();
                ## Initalize range of diskon with role 
                switch ($this->input->post('dsk')) {
                    case $this->input->post('dsk') >= '30':
                        $data_apv['id_level'] = '8';
                        $data_apv['status_apv'] = 'Pending';
                        break;
                    case $this->input->post('dsk') >= '21':
                        $data_apv['id_level'] = '7';
                        $data_apv['status_apv'] = 'Pending';
                        break;
                    case $this->input->post('dsk') >= '15':
                        $data_apv['id_level'] = '6'; // 
                        $data_apv['status_apv'] = 'Pending';
                        break;
                    case $this->input->post('dsk') >= '11':
                        $data_apv['id_level'] = '5'; // 
                        $data_apv['status_apv'] = 'Pending';
                        break;
                    case $this->input->post('dsk') >= '0':
                        $data_apv['id_level'] = '4'; // 
                        $data_apv['status_apv'] = 'Pending';
                        break;
                    default:
                        break;
                }
                $data_apv['date_created'] = date("Y-m-d");
                $data_apv['date_accepted'] = NULL;

                $data['response'] = [
                    'status' => 200,
                    'data' => [
                        'data_upld' => $data_upld,
                        'data_pyt' => $data_pyt,
                        'data_all' => $data_all,
                        'data_apv' => $data_apv
                    ]
                ];

                break;

            default:
                $data['response'] = [
                    'status' => 400,
                    'message' => 'Bad Request',
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
