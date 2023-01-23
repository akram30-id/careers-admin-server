<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Divisi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('DivisiModel');
    }

    public function index($id = null)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'GET':
                $queryDivisi = $this->DivisiModel->getDivisi();
                $cek = $queryDivisi->num_rows();

                if ($cek > 0) {
                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Data Found',
                        'data' => $queryDivisi->result()
                    ];
                } else {
                    $data['response'] = [
                        'success' => true,
                        'status' => 404,
                        'message' => 'Data is Empty'
                    ];
                }
                break;

            case 'POST':

                $namaDivisi = $this->input->post('nama_divisi');
                $id_divisi = 'NPI_DIV_' . strtoupper(str_replace(" ", '-', $namaDivisi));

                try {
                    $cek_id = $this->DivisiModel->getDivisiById($id_divisi)->num_rows();
                    if ($cek_id > 0) {
                        $data['response'] = [
                            'success' => false,
                            'status' => 501,
                            'message' => 'Divisi Sudah Ada',
                        ];
                    } else {
                        $queryInsert = $this->DivisiModel->insertDivisi(['id_divisi' => $id_divisi, 'nama_divisi' => ucwords($namaDivisi)]);
                        if ($queryInsert) {
                            $data['response'] = [
                                'success' => true,
                                'status' => 200,
                                'message' => 'Divisi Berhasil Ditambahkan',
                            ];
                        } else {
                            $data['response'] = [
                                'success' => false,
                                'status' => 500,
                                'message' => 'Internal Server Error',
                            ];
                        }
                    }
                } catch (Exception $e) {
                    $data['response'] = [
                        'success' => false,
                        'status' => 500,
                        'message' => $e->getMessage(),
                    ];
                }
                break;

            case 'PUT':

                $nama_divisi = $this->input->input_stream('nama_divisi');

                $cek = $this->DivisiModel->getDivisiById($id)->num_rows();

                if ($cek > 0) {
                    $queryUpdate = $this->DivisiModel->updateDivisi(['nama_divisi' => $nama_divisi], $id);

                    if ($queryUpdate == true) {
                        $data['response'] = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Divisi Berhasil Diupdate'
                        ];
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 500,
                            'message' => 'Internal Server Error. Please Try Again.'
                        ];
                    }
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No Data Found'
                    ];
                }

                break;

            case 'DELETE':

                $cek = $this->DivisiModel->getDivisiById($id)->num_rows();

                if ($cek > 0) {
                    $queryDelete = $this->DivisiModel->deleteDivisi($id);

                    if ($queryDelete == true) {
                        $data['response'] = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Divisi Berhasil Dihapus',
                        ];
                    } else {
                        $data['response'] = [
                            'success' => true,
                            'status' => 500,
                            'message' => 'Internal Server Error. Please Try Again.',
                        ];
                    }
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No Divisi Found',
                        'id' => strval($id)
                    ];
                }

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

    public function ajax_list()
    {
        header('Content-Type: application/json');

        $list = $this->DivisiModel->get_datatables();
        $data = [];
        $no = $this->input->post('start');

        //looping data
        foreach ($list as $data_divisi) {
            $no++;
            $row = [];

            // row terakhir untuk button update dan delete
            $row[] = $no;
            $row[] = $data_divisi->nama_divisi;
            $row[] = '<button class="btn btn-primary btn-edit-divisi" data-id="' . $data_divisi->id_divisi . '">Edit</button> <button class="btn btn-danger btn-delete-divisi" data-id="' . $data_divisi->id_divisi . '">Delete</button>';
            $data[] = $row;
        }
        $output = [
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $this->DivisiModel->count_all(),
            'recordsFiltered' => $this->DivisiModel->count_filtered(),
            'data' => $data
        ];

        echo json_encode($output);
    }
}
