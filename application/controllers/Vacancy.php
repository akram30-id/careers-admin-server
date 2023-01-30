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
                        'id_vacancy' => $this->session->id_vacancy,
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

            case 'PUT':

                // jobdesc update to database
                //delete first
                $id_jobdesc_session = $this->session->id_jobdesc;
                for ($i=0; $i < count($id_jobdesc_session); $i++) { 
                    $queryInsertJobDesc = $this->VacancyModel->deleteLowongan('tbl_jobdesc', ['id_jobdesc' => $id_jobdesc_session[$i]]);
                }
                
                // then update
                $jobdesc = $this->session->jobdesc;
                for ($i = 0; $i < count($jobdesc); $i++) {
                    $dataJobdesc = [
                        'id_vacancy' => $idVacancy,
                        'deskripsi_jobdesc' => $jobdesc[$i],
                    ];
                    $queryInsertJobDesc = $this->VacancyModel->insertLowongan('tbl_jobdesc', $dataJobdesc);
                }

                // tambahan persyaratan update to database
                //delete first
                $id_persyaratan_tambahan = $this->session->id_tambahan_persyaratan;
                for ($i=0; $i < count($id_persyaratan_tambahan); $i++) { 
                    $queryInsertJobDesc = $this->VacancyModel->deleteLowongan('tbl_tambahan_persyaratan', ['id_tambahan_persyaratan' => $id_persyaratan_tambahan[$i]]);
                }
                
                // then update
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
                $queryInsertPengalaman =  $this->VacancyModel->updateLowongan('tbl_pengalaman', ['id_pengalaman' => $this->session->id_pengalaman], $dataPengalaman);

                // salary input to database
                $dataSalary = [
                    'id_vacancy' => $idVacancy,
                    'min_salary' => $this->session->salary['min_salary'],
                    'max_salary' => $this->session->salary['max_salary'],
                ];
                $queryInsertSalary = $this->VacancyModel->updateLowongan('tbl_salary', ['id_salary' => $this->session->id_salary], $dataSalary);

                if ($queryInsertJobDesc && $queryInsertTambahanPersyaratan && $queryInsertPengalaman && $queryInsertSalary) {
                    $data['response'] = [
                        'success' => true,
                        'status' => 200,
                        'id_vacancy' => $this->session->id_vacancy,
                        'message' => 'Update Lowongan Berhasil',
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
                $jobdesc = explode(',', $this->input->post('jobdesc'));

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
                        'input' => $jobdesc_session,
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
                                'id_persyaratan' => $this->session->id_persyaratan
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
                                'id_persyaratan' => $this->session->id_persyaratan
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

                    $tambahanPersyaratan = explode(',', $this->input->post('tambahan_persyaratan'));

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
                        'input' => $tambahanPersyaratan_session,
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
                if ($this->session->id_vacancy != "" || $this->session->id_vacancy != null) {
                    $minSalary = str_replace(',', '', $this->input->post('min_salary'));
                    $maxSalary = str_replace(',', '', $this->input->post('max_salary'));

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
                        'message' => 'No id_vacancy Session Set'
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

    public function updateVacancy($idVacancy)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':
                $this->session->set_userdata(['id_vacancy' => $idVacancy]);
                $data['response'] = [
                    'status' => 200,
                    'id_vacancy' => $this->session->id_vacancy
                ];
                break;

            case 'GET':
                $vacancy = $this->VacancyModel->getData('tbl_vacancy', ['id_vacancy' => $idVacancy]);
                $persyaratan = $this->VacancyModel->getData('tbl_persyaratan', ['id_vacancy' => $idVacancy]);
                $salary = $this->VacancyModel->getData('tbl_salary', ['id_vacancy' => $idVacancy]);
                $jobdesc = $this->VacancyModel->getData('tbl_jobdesc', ['id_vacancy' => $idVacancy]);
                $tambahan_persyaratan = $this->VacancyModel->getData('tbl_tambahan_persyaratan', ['id_persyaratan' => $persyaratan->result()[0]->id_persyaratan]);
                $pengalaman = $this->VacancyModel->getData('tbl_pengalaman', ['id_persyaratan' => $persyaratan->result()[0]->id_persyaratan]);

                if ($vacancy->num_rows() > 0) {
                    $this->session->set_userdata(['id_persyaratan' => $persyaratan->result()[0]->id_persyaratan]);
                    $this->session->set_userdata(['id_salary' => $salary->result()[0]->id_salary]);

                    $id_jobdesc_session = [];
                    foreach ($jobdesc->result() as $jobs) {
                        $id_jobdesc_session[] = $jobs->id_jobdesc;
                    }
                    $this->session->set_userdata(['id_jobdesc' => $id_jobdesc_session]);

                    $id_persyaratan_tambahan = [];
                    foreach ($tambahan_persyaratan->result() as $tambahan) {
                        $id_persyaratan_tambahan[] = $tambahan->id_tambahan_persyaratan;
                    }
                    $this->session->set_userdata(['id_tambahan_persyaratan' => $id_persyaratan_tambahan]);

                    $this->session->set_userdata(['id_pengalaman' => $pengalaman->result()[0]->id_pengalaman]);

                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancy Found',
                        'session' => [
                            'id_vacancy' => $this->session->id_vacancy,
                            'id_persyaratan' => $this->session->id_persyaratan,
                            'id_salary' => $this->session->id_salary,
                            'id_jobdesc' => $this->session->id_jobdesc,
                            'id_tambahan_persyaratan' => $this->session->id_tambahan_persyaratan,
                            'id_pengalaman' => $this->session->id_pengalaman,
                        ],
                        'data' => [
                            'vacancy' => $vacancy->result(),
                            'persyaratan' => $persyaratan->result(),
                            'salary' => $salary->result(),
                            'jobdesc' => $jobdesc->result(),
                            'tambahan_persyaratan' => $tambahan_persyaratan->result(),
                            'pengalaman' => $pengalaman->result(),
                        ]
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancy Found'
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



    // splitter
    public function index_limited($idVacancy = NULL)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];

        switch ($request_method) {
            case 'GET':
                $query = $this->VacancyModel->getAllVacancy();
                $divisi = $this->VacancyModel->getAllDivisi();
                $cek = $query->num_rows();

                $grouping = [];
                foreach ($divisi->result() as $div) {
                    $dataVacancy = $this->VacancyModel->getLimitedVacancy($div->id_divisi)->result();
                    foreach ($dataVacancy as $vacancies) {
                        $grouping[$div->nama_divisi][] = $vacancies;
                        if (count($grouping[$div->nama_divisi]) >= 3) {
                            break;
                        }
                    }
                }

                if ($cek > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancies Found',
                        'count' => count($grouping),
                        'data' => $grouping
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancies Found',
                    ];
                }

                break;

            case 'DELETE':

                $idPersyaratan = $this->VacancyModel->getData('tbl_persyaratan', ['id_vacancy' => $idVacancy])->result()[0]->id_persyaratan;

                $response_object = [];
                $berhasil = 'Berhasil Dihapus';
                $gagal = 'Gagal Dihapus';

                if (isset($idPersyaratan)) {
                    $tambahanSyarat = $this->VacancyModel->getData('tbl_tambahan_persyaratan', ['id_persyaratan' => $idPersyaratan]);
                    $pengalaman = $this->VacancyModel->getData('tbl_pengalaman', ['id_persyaratan' => $idPersyaratan]);

                    if ($tambahanSyarat->num_rows() > 0) {
                        $deleteTambahanSyarat = $this->VacancyModel->deleteLowongan('tbl_tambahan_persyaratan', ['id_persyaratan' => $idPersyaratan]);

                        if ($deleteTambahanSyarat != FALSE) {
                            array_push($response_object, ['tambahan_syarat' => 'Berhasil Dihapus']);
                        } else {
                            array_push($response_object, ['tambahan_syarat' => 'Gagal Dihapus']);
                        }
                    }

                    if ($pengalaman->num_rows() > 0) {
                        $deletePengalaman = $this->VacancyModel->deleteLowongan('tbl_pengalaman', ['id_persyaratan' => $idPersyaratan]);

                        if ($deletePengalaman != FALSE) {
                            array_push($response_object, ['pengalaman' => $berhasil]);
                        } else {
                            array_push($response_object, ['pengalaman' => $gagal]);
                        }
                    }
                }

                $jobdesc = $this->VacancyModel->getData('tbl_jobdesc', ['id_vacancy' => $idVacancy]);

                if ($jobdesc->num_rows() > 0) {
                    $deleteJobdesc = $this->VacancyModel->deleteLowongan('tbl_jobdesc', ['id_vacancy' => $idVacancy]);
                    if ($deleteJobdesc != FALSE) {
                        array_push($response_object, ['jobdesc' => $berhasil]);
                    } else {
                        array_push($response_object, ['jobdesc' => $gagal]);
                    }
                }

                $salary = $this->VacancyModel->getData('tbl_salary', ['id_vacancy' => $idVacancy]);

                if ($salary->num_rows() > 0) {
                    $deleteSalary = $this->VacancyModel->deleteLowongan('tbl_salary', ['id_vacancy' => $idVacancy]);
                    if ($deleteSalary != FALSE) {
                        array_push($response_object, ['salary' => $berhasil]);
                    } else {
                        array_push($response_object, ['salary' => $gagal]);
                    }
                }

                $persyaratan = $this->VacancyModel->getData('tbl_persyaratan', ['id_vacancy' => $idVacancy]);

                if ($persyaratan->num_rows() > 0) {
                    $deletePersyaratan = $this->VacancyModel->deleteLowongan('tbl_persyaratan', ['id_vacancy' => $idVacancy]);
                    if ($deletePersyaratan != FALSE) {
                        array_push($response_object, ['persyaratan' => $berhasil]);
                    } else {
                        array_push($response_object, ['persyaratan' => $gagal]);
                    }
                }

                $vacancy = $this->VacancyModel->getData('tbl_vacancy', ['id_vacancy' => $idVacancy]);

                if ($vacancy->num_rows() > 0) {
                    $deleteVacancy = $this->VacancyModel->deleteLowongan('tbl_vacancy', ['id_vacancy' => $idVacancy]);
                    if ($deleteVacancy != FALSE) {
                        array_push($response_object, ['vacancy' => $berhasil]);
                    } else {
                        array_push($response_object, ['vacancy' => $gagal]);
                    }
                }


                $data['response'] = [
                    'status' => 200,
                    'message' => 'Delete Result Genereated',
                    'data' => $response_object
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

    public function index_update_status()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'PUT':
                $idVacancy = $this->input->input_stream('id_vacancy');
                $status = $this->input->input_stream('status');

                $query = $this->VacancyModel->updateStatus($idVacancy, $status);

                if ($query) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancy Updated',
                        'data' => [$idVacancy, $status]
                    ];
                } else {
                    $data['response'] = [
                        'status' => 500,
                        'message' => 'Internal Server Error',
                    ];
                }
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

    public function index_sortby_divisi($idDivisi)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $grouping = [];
                $query = $this->VacancyModel->getLimitedVacancy($idDivisi);
                $divisi = $this->VacancyModel->getDivisi($idDivisi)->result();
                foreach ($query->result() as $vacancies) {
                    $grouping[$divisi[0]->nama_divisi][] = $vacancies;
                    if (count($grouping[$divisi[0]->nama_divisi]) >= 3) {
                        break;
                    }
                }

                if ($query->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancy Found',
                        'data' => $grouping
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancy Available',
                    ];
                }

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

    public function index_search_limited_vacancy($idDivisi = NULL)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $searchValue = $this->input->get('search_value');

                $grouping = [];

                if ($idDivisi == "" || $idDivisi == NULL) {
                    $like = ['posisi' => $searchValue];

                    $query = $this->VacancyModel->searchLimitedVacancy($like);
                    $divisi = $this->VacancyModel->getAllDivisi();
                    foreach ($divisi->result() as $div) {
                        foreach ($query->result() as $vacancies) {
                            if ($div->nama_divisi == $vacancies->nama_divisi) {
                                $grouping[$div->nama_divisi][] = $vacancies;
                                if (count($grouping[$div->nama_divisi]) >= 6) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $like = ['tbl_vacancy.posisi' => $searchValue, 'tbl_vacancy.id_divisi' => $idDivisi];

                    $query = $this->VacancyModel->searchLimitedVacancy($like);
                    $divisi = $this->VacancyModel->getDivisi($idDivisi)->result();
                    foreach ($query->result() as $vacancies) {
                        $grouping[$divisi[0]->nama_divisi][] = $vacancies;
                        if (count($grouping[$divisi[0]->nama_divisi]) >= 6) {
                            break;
                        }
                    }
                }

                if ($query->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancy Found',
                        'data' => $grouping
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancy Available',
                    ];
                }

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

    public function index_vacancy_divisi($idDivisi, $page)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $query = $this->VacancyModel->getLimitedVacancy($idDivisi);

                $page = intval($page);

                if ($query->num_rows() > 0) {

                    $result = $query->result();
                    $pageCount = ceil(count($result) / 9);

                    if (!isset($page)) {
                        $page = 1;
                    }

                    if ($page <= 1) {
                        $page = 1;
                    }

                    if ($page > $pageCount) {
                        $page = $pageCount;
                    }

                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancies Found',
                        'page' => $page,
                        'page_count' => $pageCount,
                        'data' => array_slice($result, ($page * 9) - 9, $page * 9)
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Data Available',
                    ];
                }

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

    public function index_vacancy_detail($idVacancy)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':
                $vacancy = $this->VacancyModel->getData('tbl_vacancy', ['id_vacancy' => $idVacancy]);
                $jobdesc = $this->VacancyModel->getData('tbl_jobdesc', ['id_vacancy' => $idVacancy]);
                $salary = $this->VacancyModel->getData('tbl_salary', ['id_vacancy' => $idVacancy]);
                $divisi = $this->VacancyModel->getData('tbl_divisi', ['id_divisi' => $vacancy->result()[0]->id_divisi]);

                $persyaratan = $this->VacancyModel->getData('tbl_persyaratan', ['id_vacancy' => $idVacancy]);
                $idPersyaratan = $persyaratan->result()[0]->id_persyaratan;
                $pendidikan = $this->VacancyModel->getData('tbl_pendidikan', ['id_pendidikan' => $persyaratan->result()[0]->id_pendidikan]);
                $pengalaman = $this->VacancyModel->getData('tbl_pengalaman', ['id_persyaratan' => $idPersyaratan]);
                $tambahan_persyaratan = $this->VacancyModel->getData('tbl_tambahan_persyaratan', ['id_persyaratan' => $idPersyaratan]);

                if ($vacancy->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Vacancy Found',
                        'data' => [
                            'divisi' => $divisi->result(),
                            'vacancy' => $vacancy->result(),
                            'persyaratan' => $persyaratan->result(),
                            'pendidikan' => $pendidikan->result(),
                            'jobdesc' => $jobdesc->result(),
                            'salary' => $salary->result(),
                            'pengalaman' => $pengalaman->result(),
                            'tambahan_persyaratan' => $tambahan_persyaratan->result()
                        ]
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancy Found',
                    ];
                }

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

    public function index_filter_vacancy_in_divisi($idDivisi)
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'GET':

                $status = $this->input->get('status');
                $level = $this->input->get('level');

                $vacancies = $this->VacancyModel->getDataFilter($status, $level, $idDivisi);

                if ($vacancies->num_rows() > 0) {
                    $data['response'] = [
                        'status' => 200,
                        'message' => 'Data Found',
                        'data' => $vacancies->result()
                    ];
                } else {
                    $data['response'] = [
                        'status' => 404,
                        'message' => 'No Vacancy Found',
                    ];
                }

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
