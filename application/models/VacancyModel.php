<?php

defined('BASEPATH') or exit('No direct script access allowed');

class VacancyModel extends CI_Model
{
    function insertLowongan ($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    function deleteLowongan($table, $id)
    {
        return $this->db->delete($table, $id);
    }

    function updateLowongan ($table, $id, $data)
    {
        return $this->db->update($table, $data, $id);
    }

    function getJobdesc($idVacancy)
    {
        return $this->db->get_where('tbl_jobdesc', ['id_vacancy' => $idVacancy]);
    }
}