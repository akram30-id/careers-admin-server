<?php

class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('AuthModel');
    }

    public function index()
    {
        $request_method = $_SERVER['REQUEST_METHOD'];
        switch ($request_method) {
            case 'POST':

                $username = $this->input->post('username');
                $password = $this->input->post('password');

                if ($this->session->has_userdata('username')) {

                    $data['response'] = [
                        'status' => 400,
                        'message' => 'Sudah Login'
                    ];
                } else {
                    $hashed_password = md5('$careers_' . $password . '_admin$');

                    $getUsername = $this->AuthModel->getUsername($username);

                    if ($getUsername->num_rows() > 0) {

                        $getPassword = $this->AuthModel->getPassword($hashed_password);

                        if ($getPassword->num_rows() > 0) {
                            $this->AuthModel->set_attempt();

                            $this->session->set_userdata('username', $username);
                            $this->session->set_userdata('attempt', $getUsername->result()[0]->attempt);

                            $data['response'] = [
                                'status' => 200,
                                'message' => 'Login Success'
                            ];
                        } else {
                            $data['response'] = [
                                'status' => 404,
                                'message' => 'Password Salah',
                            ];
                        }
                    } else {
                        $data['response'] = [
                            'status' => 404,
                            'message' => 'Username Tidak Ditemukan'
                        ];
                    }
                }

                break;

            case 'GET':

                $data['response'] = [
                    'status' => 200,
                    'message' => 'Session List',
                    'session' => $this->session->username,
                    'attempt' => $this->session->attempt
                ];

                break;

            case 'DELETE':

                $this->session->sess_destroy();
                $data['response'] = [
                    'status' => 200,
                    'message' => 'Sign Out Success'
                ];

                break;

            case 'PUT':

                $oldPassword = $this->input->input_stream('old_password');
                $newPassword = $this->input->input_stream('new_password');

                if ($this->session->has_userdata('username')) {

                    $hashed_password = md5('$careers_' . $oldPassword . '_admin$');

                    $getUsername = $this->AuthModel->getUsername($this->session->username);

                    $getPassword = $this->AuthModel->getPassword($hashed_password);

                    if ($getPassword->num_rows() > 0) {
                        $hash_new_password = md5('$careers_' . $newPassword . '_admin$');

                        $updatePassword = $this->AuthModel->change_password($getUsername->result()[0]->username, $hash_new_password);

                        if ($updatePassword) {
                            $data['response'] = [
                                'status' => 201,
                                'message' => 'Password Berhasil Diupdate'
                            ];
                        } else {
                            $data['response'] = [
                                'status' => 500,
                                'message' => 'Internal Server Error'
                            ];
                        }
                    } else {
                        $data['response'] = [
                            'status' => 404,
                            'message' => 'Password Lama Salah',
                        ];
                    }
                } else {
                    $data['response'] = [
                        'status' => 403,
                        'message' => 'Silahkan Login Terlebih Dahulu'
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
}
