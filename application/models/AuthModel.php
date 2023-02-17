<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Jakarta");

class AuthModel extends CI_Model
{

    function getUsername($username)
    {
        return $this->db->get_where('tbl_auth', ['username' => $username]);
    }

    function getPassword($password)
    {
        return $this->db->get_where('tbl_auth', ['password' => $password]);
    }

    function set_attempt()
    {
        $now = date('Y-m-d H:i:s');
        return $this->db->update('tbl_auth', ['attempt' => $now]);
    }

    function change_password($username, $newPassword)
    {
        return $this->db->update('tbl_auth', ['password' => $newPassword], ['username' => $username]);
    }
}
