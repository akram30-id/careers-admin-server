<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DivisiModel extends CI_Model
{

    //set nama tabel yang akan kita tampilkan datanya
    var $table = 'tbl_divisi'; 

    //set kolom order, kolom pertama saya null untuk kolom edit dan hapus
    var $column_order = ['nama_divisi', null];

    var $column_search = ['nama_divisi'];

    // default order
    var $order = ['id_divisi', 'asc'];

    function getDivisi()
    {
        return $this->db->get('tbl_divisi');
    }

    function getDivisiById($idDivisi)
    {
        return  $this->db->get_where('tbl_divisi', ['id_divisi' => $idDivisi]);
    }

    function insertDivisi($dataInsert)
    {
        // $dataInsert must be an array with ['table_field' => 'value_set']
        return $this->db->insert('tbl_divisi', $dataInsert);
    }

    function updateDivisi($dataUpdate, $idDivisi)
    {
        return $this->db->update('tbl_divisi', $dataUpdate, ['id_divisi' => $idDivisi]);
    }

    function deleteDivisi($idDivisi)
    {
        return $this->db->delete('tbl_divisi', ['id_divisi' => $idDivisi]);
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) { // loop kolom
            if ($this->input->post('search')['value']) { // jika datatable mengirim POST untuk search
                if ($i === 0) { // looping pertama
                    $this->db->group_start();
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) { // looping terakhir
                    $this->db->group_end();
                }
            }
            $i++;
        }

        // jika datatable mengirim POST untuk order
        if ($this->input->post('order')) {
            $this->db->order_by($this->column_order[$this->input->post('order')[0]['column']], $this->input->post('order')[0]['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables ()
    {
        $this->_get_datatables_query();
        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered ()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all ()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}
