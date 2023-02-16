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
        $this->db->select('tbl_vacancy.id_vacancy, tbl_vacancy.status, tbl_vacancy.posisi, tbl_vacancy.id_divisi, tbl_salary.min_salary, tbl_salary.max_salary, tbl_vacancy.level, tbl_vacancy.created_at, tbl_divisi.nama_divisi');
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

    function getDataFilter($status, $level, $idDivisi)
    {
        if ($status == '' || $status == NULL) {
            if ($level == '' || $level == NULL) {
                return $this->db->query("SELECT * FROM tbl_vacancy INNER JOIN tbl_salary ON tbl_vacancy.id_vacancy = tbl_salary.id_vacancy INNER JOIN tbl_divisi ON tbl_vacancy.id_divisi = tbl_divisi.id_divisi WHERE tbl_vacancy.id_divisi = '$idDivisi';");
            } else {
                return $this->db->query("SELECT * FROM tbl_vacancy INNER JOIN tbl_salary ON tbl_vacancy.id_vacancy = tbl_salary.id_vacancy INNER JOIN tbl_divisi ON tbl_vacancy.id_divisi = tbl_divisi.id_divisi WHERE tbl_vacancy.id_divisi = '$idDivisi' AND tbl_vacancy.level = '$level';");
            }
        } else {
            if ($level == '' || $level == NULL) {
                return $this->db->query("SELECT * FROM tbl_vacancy INNER JOIN tbl_salary ON tbl_vacancy.id_vacancy = tbl_salary.id_vacancy INNER JOIN tbl_divisi ON tbl_vacancy.id_divisi = tbl_divisi.id_divisi WHERE tbl_vacancy.id_divisi = '$idDivisi' AND tbl_vacancy.status = '$status';");
            } else {
                return $this->db->query("SELECT * FROM tbl_vacancy INNER JOIN tbl_salary ON tbl_vacancy.id_vacancy = tbl_salary.id_vacancy INNER JOIN tbl_divisi ON tbl_vacancy.id_divisi = tbl_divisi.id_divisi WHERE tbl_vacancy.id_divisi = '$idDivisi' AND (tbl_vacancy.status = '$status' AND tbl_vacancy.level = '$level');");
            }
        }

        // return $this->db->query("SELECT * FROM tbl_vacancy INNER JOIN tbl_salary ON tbl_vacancy.id_vacancy = tbl_salary.id_vacancy INNER JOIN tbl_divisi ON tbl_vacancy.id_divisi = tbl_divisi.id_divisi WHERE tbl_vacancy.id_divisi = '$idDivisi' AND (tbl_vacancy.status = '$status' OR tbl_vacancy.level = '$level' OR tbl_salary.min_salary BETWEEN $min_minimum AND $min_maximum OR tbl_salary.max_salary BETWEEN $max_minimum AND $max_maximum);");
    }

    function getExpired ()
    {
        return $this->db->query("SELECT * FROM `tbl_vacancy` WHERE expired_at < CURRENT_DATE() AND expired_at != '0000-00-00';");
    }
}
