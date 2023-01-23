<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class Vacancy extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('VacancyModel');
    }


    public function index()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $idVacancy = $this->session->id_vacancy;
        $idPersyaratan = $this->session->id_persyaratan;

        switch ($request_method) {
            case 'POST':

                // jobdesc input to database
                $jobdesc = $this->session->jobdesc;
                for ($i = 0; $i < count($jobdesc); $i++) {
                    $dataJobdesc = [
                        'id_vacancy' => $idVacancy,
                        'deskripsi_jobdesc' => $jobdesc[$i],
                    ];
                    $queryInsertJobDesc = $this->VacancyModel->insertLowongan('tbl_jobdesc', $dataJobdesc);
                }

                // tambahan persyaratan input to database
                $tambahanPersyaratan = $this->session->tambahan_persyaratan;
                for ($i = 0; $i < count($tambahanPersyaratan); $i++) {
                    $dataTambahanPersyaratan = [
                        'id_persyaratan' => $idPersyaratan,
                        'tambahan_persyaratan' => $tambahanPersyaratan[$i],
                    ];
                    $queryInsertTambahanPersyaratan = $this->VacancyModel->insertLowongan('tbl_tambahan_persyaratan', $dataTambahanPersyaratan);
                }

                // pengalaman input to database
                $dataPengalaman = [
                    'id_persyaratan' => $idPersyaratan,
                    'min_lama_pengalaman' => $this->session->pengalaman['min_lama_pengalaman'],
                    'max_lama_pengalaman' => $this->session->pengalaman['max_lama_pengalaman'],
                    'bidang_pengalaman' => $this->session->pengalaman['bidang_pengalaman'],
                ];
                $queryInsertPengalaman =  $this->VacancyModel->insertLowongan('tbl_pengalaman', $dataPengalaman);

                // salary input to database
                $dataSalary = [
                    'id_vacancy' => $idVacancy,
                    'min_salary' => $this->session->salary['min_salary'],
                    'max_salary' => $this->session->salary['max_salary'],
                ];
                $queryInsertSalary = $this->VacancyModel->insertLowongan('tbl_salary', $dataSalary);

                if ($queryInsertJobDesc && $queryInsertTambahanPersyaratan && $queryInsertPengalaman && $queryInsertSalary) {
                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Buka Lowongan Berhasil',
                    ];
                    $this->session->unset_userdata(['jobdesc', 'pengalaman', 'tambahan_persyaratan', 'salary', 'id_vacancy', 'id_persyaratan']);
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 500,
                        'message' => 'Internal Server Error',
                    ];
                }

                break;

            case 'DELETE':
                $idVacancy = $this->session->id_vacancy;
                $idPersyaratan = $this->session->id_persyaratan;

                // delete persyaratan row
                if ($idPersyaratan != null || $idPersyaratan != "") {
                    $queryDeletePersyaratan = $this->VacancyModel->deleteLowongan('tbl_persyaratan', ['id_persyaratan' => $idPersyaratan]);

                    if ($queryDeletePersyaratan != FALSE) {
                        $this->session->unset_userdata('id_persyaratan');
                        $data['response_persyaratan'] = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Delete Persyaratan Succeed',
                        ];
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 500,
                            'message' => 'Internal Server Error',
                        ];
                    }
                }

                // delete vacancy row
                if ($idVacancy != null || $idVacancy != "") {
                    $queryDeleteVacancy = $this->VacancyModel->deleteLowongan('tbl_vacancy', ['id_vacancy' => $idVacancy]);

                    if ($queryDeleteVacancy != FALSE) {
                        $this->session->unset_userdata('id_vacancy');
                        $this->session->unset_userdata('jobdesc');

                        $data['response_vacancy'] = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Delete Vacancy Succeed',
                        ];
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 500,
                            'message' => 'Internal Server Error',
                        ];
                    }
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

    public function main()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        if ($this->session->userdata('id_vacancy') == null || $this->session->userdata('id_vacancy') == "") {
            switch ($request_method) {
                case 'POST':
                    $idDivisi = $this->input->post('id_divisi');
                    $level = $this->input->post('level');
                    $posisi = $this->input->post('posisi');
                    $deskripsi = $this->input->post('deskripsi');
                    $created_at = strval(date('Y-m-d H:i:s'));
                    $expired_at = $this->input->post('expired_at');

                    $idVacancy = ($level) . date('dmyHis');

                    $dataVacancy = [
                        'id_vacancy' => $idVacancy,
                        'id_divisi' => $idDivisi,
                        'level' => $level,
                        'posisi' => $posisi,
                        'deskripsi_lowongan' => $deskripsi,
                        'created_at' => $created_at,
                        'expired_at' => $expired_at,
                    ];
                    $queryInsert = $this->VacancyModel->insertLowongan('tbl_vacancy', $dataVacancy);

                    if ($queryInsert) {
                        $this->session->set_userdata(['id_vacancy' => $idVacancy]);
                        $this->session->mark_as_temp('id_vacancy', 86400);

                        $data['response'] = [
                            'success' => true,
                            'status' => 201,
                            'message' => 'Lowongan Baru Berhasil Ditambah',
                            'id_vacancy' => $this->session->id_vacancy
                        ];
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 500,
                            'message' => 'Internal Server Error'
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
        } else {
            switch ($request_method) {
                case 'PUT':
                    $idDivisi = $this->input->input_stream('id_divisi');
                    $level = $this->input->input_stream('level');
                    $posisi = $this->input->input_stream('posisi');
                    $deskripsi = $this->input->input_stream('deskripsi');
                    $expired_at = $this->input->input_stream('expired_at');
                    $idVacancy = $this->session->userdata('id_vacancy');

                    $dataVacancy = [
                        'id_divisi' => $idDivisi,
                        'level' => $level,
                        'posisi' => $posisi,
                        'deskripsi_lowongan' => $deskripsi,
                        'expired_at' => $expired_at
                    ];

                    $queryUpdate = $this->VacancyModel->updateLowongan('tbl_vacancy', ['id_vacancy' => $idVacancy], $dataVacancy);

                    if ($queryUpdate) {
                        $data['response'] = [
                            'success' => true,
                            'status' => 200,
                            'message' => 'Updated',
                            'id_vacancy' => $this->session->id_vacancy
                        ];
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 500,
                            'message' => 'Internal Server Error',
                            'data' => $this->session->userdata()
                        ];
                    }

                    break;

                case 'GET':
                    $data['response'] = [
                        'id_vacancy' => $this->session->userdata('id_vacancy'),
                        'id_persyaratan' => $this->session->id_persyaratan,
                        'jobdesc' => $this->session->jobdesc,
                        'tambahan_persyaratan' => $this->session->tambahan_persyaratan,
                        'salary' => $this->session->salary,
                        'pengalaman' => $this->session->pengalaman,
                        'tempdata' => $this->session->tempdata('id_vacancy')
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
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function jobdesc()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':
                $jobdesc = $this->input->post('jobdesc');

                $idVacancy = $this->session->id_vacancy;

                if ($idVacancy != null || $idVacancy != "") {

                    $jobdesc_session = [];
                    for ($i = 0; $i < count($jobdesc); $i++) {
                        array_push($jobdesc_session, $jobdesc[$i]);
                    }

                    $this->session->set_userdata(['jobdesc' => $jobdesc_session]);
                    $this->session->mark_as_temp('jobdesc', 86400);
                    // $this->session->unset_userdata('jobdesc');

                    $data['response'] = [
                        'success' => true,
                        'message' => 'Session Set',
                        'data' => $this->session->userdata('jobdesc'),
                    ];
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No id vacancy session set'
                    ];
                }
                break;

            case 'GET':
                $idVacancy = $this->session->id_vacancy;
                
                $queryGet = $this->VacancyModel->getJobdesc($idVacancy);
                $cek = $queryGet->num_rows();

                if ($cek < 1) {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No Data Found'
                    ];
                } else {
                    $dataJobdesc = $queryGet->result();
                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'data' => $dataJobdesc
                    ];
                }
                break;

                // case 'PUT':
                // $queryGet = $this->VacancyModel->getJobdesc($idVacancy);
                // $cek = $queryGet->num_rows();

                // if ($cek < 1) {
                //     $data['response'] = [
                //         'success' => false,
                //         'status' => 404,
                //         'message' => 'No Data Found'
                //     ];
                // } else {
                //     $dataJobdesc = $queryGet->result();
                //     $data['response'] = [
                //         'success' => true,
                //         'status' => 200,
                //         'data' => $dataJobdesc
                //     ];
                // !!write query to update jobdesc below!!
                // }
                // break;

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

    public function persyaratan()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        if ($this->session->id_persyaratan == null || $this->session->id_persyaratan == "") {
            switch ($request_method) {
                case 'POST':

                    $gender = $this->input->post('gender');
                    $minUsia = $this->input->post('min_usia');
                    $maxUsia = $this->input->post('max_usia');
                    $jurusan = $this->input->post('jurusan');
                    $vaksin = $this->input->post('vaksin');
                    $idPendidikan = $this->input->post('id_pendidikan');

                    $idVacancy = $this->session->id_vacancy;

                    if ($idVacancy != null || $idVacancy != "") {
                       
                        $idPersyaratan = $idVacancy . "999" . date('d');

                        $dataPersyaratan = [
                            'id_persyaratan' => $idPersyaratan,
                            'id_vacancy' => $idVacancy,
                            'id_pendidikan' => $idPendidikan,
                            'gender' => $gender,
                            'min_usia' => $minUsia,
                            'max_usia' => $maxUsia,
                            'jurusan' => $jurusan,
                            'dosis_vaksin' => $vaksin
                        ];

                        $queryInsert = $this->VacancyModel->insertLowongan('tbl_persyaratan', $dataPersyaratan);

                        if ($queryInsert) {
                            $this->session->set_userdata(['id_persyaratan' => $idPersyaratan]);
                            $this->session->mark_as_temp('id_persyaratan', 86400);

                            $data['response'] = [
                                'success' => true,
                                'message' => 'Session Set',
                                'data' => $this->session->id_persyaratan
                            ];
                        } else {
                            $data['response'] = [
                                'success' => false,
                                'status' => 500,
                                'message' => 'Internal Server Error',
                            ];
                        }
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'No id vacancy set',
                        ];
                    }
                    break;

                default:
                    $data['response'] = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'Bad Request',
                    ];
                    break;
            }
        } else {
            switch ($request_method) {
                case 'PUT':

                    $gender = $this->input->input_stream('gender');
                    $minUsia = $this->input->input_stream('min_usia');
                    $maxUsia = $this->input->input_stream('max_usia');
                    $jurusan = $this->input->input_stream('jurusan');
                    $vaksin = $this->input->input_stream('vaksin');
                    $idPendidikan = $this->input->input_stream('id_pendidikan');

                    $idVacancy = $this->session->id_vacancy;

                    if ($idVacancy != null || $idVacancy != "") {
                        $dataPersyaratan = [
                            'id_pendidikan' => $idPendidikan,
                            'id_vacancy' => $idVacancy,
                            'gender' => $gender,
                            'min_usia' => $minUsia,
                            'max_usia' => $maxUsia,
                            'jurusan' => $jurusan,
                            'dosis_vaksin' => $vaksin
                        ];

                        $queryUpdate = $this->VacancyModel->updateLowongan('tbl_persyaratan', ['id_persyaratan' => $this->session->id_persyaratan], $dataPersyaratan);

                        if ($queryUpdate) {
                            $data['response'] = [
                                'success' => true,
                                'status' => 200,
                                'message' => 'Persyaratan Updated',
                            ];
                        } else {
                            $data['response'] = [
                                'success' => false,
                                'status' => 500,
                                'message' => 'Internal Server Error',
                            ];
                        }
                    } else {
                        $data['response'] = [
                            'success' => false,
                            'status' => 404,
                            'message' => 'No id vacancy set',
                        ];
                    }
                    break;

                case 'GET':
                    $data['response'] = [
                        'session' => $this->session->id_persyaratan
                    ];
                    break;

                default:
                    $data['response'] = [
                        'success' => false,
                        'status' => 400,
                        'message' => 'Bad Request',
                    ];
                    break;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function persyaratanTambahan()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':
                if ($this->session->id_persyaratan != "" || $this->session->id_persyaratan != null) {

                    $tambahanPersyaratan = $this->input->post('tambahan_persyaratan');

                    $tambahanPersyaratan_session = [];
                    for ($i = 0; $i < count($tambahanPersyaratan); $i++) {
                        array_push($tambahanPersyaratan_session, $tambahanPersyaratan[$i]);
                    }

                    $this->session->set_userdata(['tambahan_persyaratan' => $tambahanPersyaratan_session]);
                    $this->session->mark_as_temp('tambahan_persyaratan', 86400);

                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Session Set',
                        'persyaratan_tambahan' => $this->session->tambahan_persyaratan
                    ];
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No id_persyaratan Session Set',
                    ];
                }
                break;

            case 'GET':
                $data['response'] = [
                    'data' => $this->session->tambahan_persyaratan
                ];
                break;

            default:
                $data['response'] = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Bad Request',
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function pengalaman()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'POST':
                if ($this->session->id_persyaratan != "" || $this->session->id_persyaratan != null) {
                    $minLamaPengalaman = $this->input->post('min_lama_pengalaman');
                    $maxLamaPengalaman = $this->input->post('max_lama_pengalaman');
                    $bidangPengalaman = $this->input->post('bidang_pengalaman');

                    $dataPengalaman = [
                        'min_lama_pengalaman' => $minLamaPengalaman,
                        'max_lama_pengalaman' => $maxLamaPengalaman,
                        'bidang_pengalaman' => $bidangPengalaman
                    ];
                    $this->session->set_userdata(['pengalaman' => $dataPengalaman]);
                    $this->session->mark_as_temp('pengalaman', 86400);

                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'message' => 'Session Set',
                        'data' => $this->session->pengalaman
                    ];
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No id_persyaratan Session Set',
                    ];
                }
                break;

            default:
                $data['response'] = [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Bad Request',
                ];
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function salary()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'POST':
                if ($this->session->id_persyaratan != "" || $this->session->id_persyaratan != null) {
                    $minSalary = $this->input->post('min_salary');
                    $maxSalary = $this->input->post('max_salary');

                    $dataSalary = [
                        'min_salary' => $minSalary,
                        'max_salary' => $maxSalary
                    ];

                    $this->session->set_userdata(['salary' => $dataSalary]);
                    $this->session->mark_as_temp('salary', 86400);

                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'data' => $this->session->salary
                    ];
                } else {
                    $data['response'] = [
                        'success' => false,
                        'status' => 404,
                        'message' => 'No id_persyaratan Session Set'
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
}
