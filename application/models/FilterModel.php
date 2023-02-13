<?php

defined('BASEPATH') or exit('No direct script access allowed');

class FilterModel extends CI_Model
{
    function cek_bidang_pengalaman($idVacancy, $jabatan)
    {
        $idPersyaratan = $this->db->get_where('tbl_persyaratan', ['id_vacancy' => $idVacancy])->result()[0]->id_persyaratan;

        $this->db->select('*');
        $this->db->like(['id_persyaratan' => $idPersyaratan, 'bidang_pengalaman' => $jabatan]);
        $this->db->from('tbl_pengalaman');
        return $this->db->get();
    }

    function getData($table, $id)
    {
        return $this->db->get_where($table, $id);
    }
}
