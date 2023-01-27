<?php

defined('BASEPATH') or exit('No direct script access allowed');

class VacancyModel extends CI_Model
{
    function insertLowongan($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    function deleteLowongan($table, $id)
    {
        return $this->db->delete($table, $id);
    }

    function updateLowongan($table, $id, $data)
    {
        return $this->db->update($table, $data, $id);
    }

    function getJobdesc($idVacancy)
    {
        return $this->db->get_where('tbl_jobdesc', ['id_vacancy' => $idVacancy]);
    }

    function getVacancy($idVacancy)
    {
        return $this->db->get_where('tbl_vacancy', ['id_vacancy' => $idVacancy]);
    }

    function getAllVacancy()
    {
        $this->db->select('*');
        $this->db->from('tbl_vacancy');
        $this->db->join('tbl_divisi', 'tbl_vacancy.id_divisi = tbl_divisi.id_divisi', 'inner');
        return $this->db->get();
    }

    function getLimitedVacancy($idDivisi)
    {
        $this->db->select('tbl_vacancy.id_vacancy, tbl_vacancy.status, tbl_vacancy.posisi, tbl_vacancy.id_divisi, tbl_salary.min_salary, tbl_salary.max_salary, tbl_vacancy.level, tbl_vacancy.created_at');
        $this->db->from('tbl_vacancy');
        $this->db->join('tbl_divisi', 'tbl_vacancy.id_divisi = tbl_divisi.id_divisi', 'inner');
        $this->db->join('tbl_salary', 'tbl_salary.id_vacancy = tbl_vacancy.id_vacancy', 'inner');
        $this->db->where('tbl_divisi.id_divisi', $idDivisi);
        $this->db->order_by('tbl_vacancy.created_at', 'DESC');
        return $this->db->get();
    }

    function getAllDivisi()
    {
        return $this->db->get('tbl_divisi');
    }

    function getDivisi($idDivisi)
    {
        return $this->db->get_where('tbl_divisi', ['id_divisi' => $idDivisi]);
    }

    function updateStatus($idVacancy, $status)
    {
        return $this->db->update('tbl_vacancy', ['status' => $status], ['id_vacancy' => $idVacancy]);
    }

    function getData($table, $id)
    {
        // $id must be associative array
        return $this->db->get_where($table, $id);
    }

    function searchLimitedVacancy($like)
    {
        $this->db->select('tbl_vacancy.id_vacancy, tbl_vacancy.status, tbl_vacancy.posisi, tbl_vacancy.id_divisi, tbl_salary.min_salary, tbl_salary.max_salary, tbl_vacancy.level, tbl_vacancy.created_at, tbl_divisi.nama_divisi');
        $this->db->from('tbl_vacancy');
        $this->db->join('tbl_divisi', 'tbl_vacancy.id_divisi = tbl_divisi.id_divisi', 'inner');
        $this->db->join('tbl_salary', 'tbl_salary.id_vacancy = tbl_vacancy.id_vacancy', 'inner');
        $this->db->like($like);
        $this->db->order_by('tbl_vacancy.created_at', 'DESC');
        return $this->db->get();
    }
}
