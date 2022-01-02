<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


// Syarat  :  

// 1 . Select  = View 
// 2 . Insert  = Ins
// 3 . Update  = Updt
// 4 . Delete  = Del

class Model extends CI_Model
{



	////// MASTER //////
	function get_provinsi_name($id)
	{
		$hasil = $this->db->query("SELECT * FROM mst_provinsi WHERE id_provinsi='$id'")->row_array();
		return $hasil['nama_provinsi'];
	}

	function get_kota_name($id)
	{
		$hasil = $this->db->query("SELECT * FROM mst_kota WHERE id_kota='$id'")->row_array();
		return $hasil['nama_kota'];
	}

	function get_kecamatan_name($id)
	{
		$hasil = $this->db->query("SELECT * FROM mst_kecamatan WHERE id_kecamatan='$id'")->row_array();
		return $hasil['nama_kecamatan'];
	}

	public function Login($user, $pass)
	{
		$Query = $this->db->query("SELECT * FROM username WHERE username = '$user' AND password = '$pass' ");
		return $Query;
	}

	public function LoginAntrian($user, $pass)
	{
		$Query = $this->db->query("SELECT * FROM table_prakter WHERE email = '$user' AND password = '$pass' ");
		return $Query;
	}

	public function Code($Query)
	{
		$Query = $this->db->query("  " . $Query . "  ");
		return $Query->result_array();
	}
	public function LastId($kolom, $table)
	{
		$Query = $this->db->query("SELECT MAX($kolom) AS LastIdFix FROM  $table");
		return $Query->result_array();
	}
	public function View($Table, $Order)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " ORDER BY " . $Order . " DESC");
		return $Query->result_array();
	}
	public function View2($Table)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . "");
		return $Query->result_array();
	}
	public function ViewASC($Table, $Order)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " ORDER BY " . $Order . " ASC");
		return $Query->result_array();
	}
	public function ViewLimit($Table, $Order, $Limit)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " ORDER BY " . $Order . " DESC LIMIT 0,$Limit");
		return $Query->result_array();
	}
	public function ViewWhere($Table, $WhereField, $WhereValue)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " WHERE " . $WhereField . " = '" . $WhereValue . "'");
		return $Query->result_array();
	}
	public function ViewWhereLimit($Table, $WhereField, $WhereValue)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " WHERE " . $WhereField . " = '" . $WhereValue . "' Limit 0,3");
		return $Query->result_array();
	}
	public function ViewWhereAktor($Table, $WhereField, $WhereValue)
	{
		$Query = $this->db->query("SELECT * FROM " . $Table . " WHERE " . $WhereField . " = '" . $WhereValue . "' ORDER BY id DESC");
		return $Query->result_array();
	}

	public function Insert($Table, $Value)
	{
		$Query = $this->db->insert($Table, $Value);
		return $Query;
	}
	public function InsertOrUpdate($Table, $Where, $WhereValue, $Value)
	{
		if (count($this->ViewWhere($Table, $Where, $WhereValue)) > 0) {
			$this->db->where($Where, $WhereValue);
			$Query = $this->db->update($Table, $Value);
		} else {
			$Query = $this->db->insert($Table, $Value);
		}
		return $Query;
	}
	public function Update($Table, $Where, $WhereValue, $Value)
	{
		$this->db->where($Where, $WhereValue);
		$this->db->update($Table, $Value);
	}
	public function Delete($Table, $Where, $WhereValue)
	{
		$this->db->where($Where, $WhereValue);
		$this->db->delete($Table);
	}

	public function GetId($Id, $Table)
	{
		$Query = $this->db->query("SELECT max($Id) FROM " . $Table . " ");
		return $Query->result_array();
	}

	function get_kota($id)
	{
		$hasil = $this->db->query("SELECT * FROM mst_kota WHERE id_provinsi='$id' GROUP BY nama_kota");
		return $hasil->result();
	}

	function get_kecamatan($id)
	{
		$hasil = $this->db->query("SELECT * FROM mst_kecamatan WHERE id_kota='$id' GROUP BY nama_kecamatan");
		return $hasil->result();
	}
}
