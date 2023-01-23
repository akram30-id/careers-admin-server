<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PendidikanModel extends CI_Model
{
    function getPendidikan ()
    {
        return $this->db->get('tbl_pendidikan');
    }
}