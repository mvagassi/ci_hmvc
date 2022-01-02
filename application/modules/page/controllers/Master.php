<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();
		error_reporting(0);
		$this->load->model('model');
		$this->load->model('relasi');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('download');
	}
	public  function Date2String($dTgl)
	{
		//return 2012-11-22
		list($cDate, $cMount, $cYear)	= explode("-", $dTgl);
		if (strlen($cDate) == 2) {
			$dTgl	= $cYear . "-" . $cMount . "-" . $cDate;
		}
		return $dTgl;
	}

	public  function String2Date($dTgl)
	{
		//return 22-11-2012  
		list($cYear, $cMount, $cDate)	= explode("-", $dTgl);
		if (strlen($cYear) == 4) {
			$dTgl	= $cDate . "-" . $cMount . "-" . $cYear;
		}
		return $dTgl;
	}

	public function TimeStamp()
	{
		date_default_timezone_set("Asia/Jakarta");
		$Data = date("H:i:s");
		return $Data;
	}

	public function DateStamp()
	{
		date_default_timezone_set("Asia/Jakarta");
		$Data = date("d-m-Y");
		return $Data;
	}

	public function DateTimeStamp()
	{
		date_default_timezone_set("Asia/Jakarta");
		$Data = date("Y-m-d h:i:s");
		return $Data;
	}

	public function DateStamp_card()
	{
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date("Y-m-d");
		$Data = date('m / y', strtotime('+1 year', strtotime($tanggal)));
		return $Data;
	}

	public function signin($Action = "")
	{
		$data = "";
		if ($Action == "error") {
			$data['notif'] = "Username / Password Salah";
		}
		$this->load->view('back-end/login', $data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(site_url('msslim/Master/signin'));
	}

	public function line_chart()
	{
		$this->load->view('back-end/dashboard/flotchart_line');
	}

	public function data_user($aksi = "", $id = "")
	{
		if ($aksi == "edit") {
			$data['data_user']			= $this->model->ViewWhere('username', 'id_user', $id);
		}
		$data['data_user_table']			= $this->model->View2('v_data_user_msslim');
		$data['action']			= $aksi;

		$dataHeader['file']   		= 'List Data User';
		$dataHeader['data_level']   		= $this->model->ViewAsc('level', 'id_level');

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/user/data_user', $data);
		$this->load->view('back-end/container/footer');
	}

	public function register($Aksi = "")
	{

		$dataHeader['title']		= "Ms Slim By Ms Glow | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Dashboard';
		$dataHeader['link']   		= 'index';
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $Aksi;

		/*$this->load->view('back-end/container/header',$dataHeader);*/
		$this->load->view('back-end/register', $data);
		/*$this->load->view('back-end/container/footer');*/
	}

	public function register2($Aksi = "")
	{

		$dataHeader['title']		= "Ms Slim By Ms Glow | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Dashboard';
		$dataHeader['link']   		= 'index';
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $Aksi;

		/*$this->load->view('back-end/container/header',$dataHeader);*/
		$this->load->view('back-end/Pendaftaran', $data);
		/*$this->load->view('back-end/container/footer');*/
	}

	public function suksesregister($Aksi = "")
	{

		$dataHeader['title']		= "Ms Slim By Ms Glow | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Dashboard';
		$dataHeader['link']   		= 'index';
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $Aksi;

		$this->load->view('back-end/register-terimakasih', $data);
	}

	public function index($Aksi = "")
	{
		$dateNow = date('Y-m');
		$xDate = date('Y-m', mktime(0, 0, 0, date('n') - 5, date('j'), date('y')));

		$dataHeader['title']		= "Ms Slim By Ms Glow | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Dashboard';
		$dataHeader['link']   		= 'index';
		$dataHeader['notification'] = $this->notif_pendaftar_baru();

		$data['action'] 			= $Aksi;
		$data['data_status_seller']	= $this->model->Code("SELECT A.status_seller AS id_status, B.status_seller AS nama_status, COUNT(A.status_seller) AS juml_seller 
										FROM pendaftaran A
										LEFT JOIN tb_status_seller B ON A.status_seller = B.id
										WHERE A.is_delete = 0 AND A.status_seller IS NOT NULL AND A.member_aktif = 1 GROUP BY A.status_seller");

		$data['data_peringkat_seller']	= $this->model->Code("SELECT A.city_id, B.nama_kota, COUNT(A.city_id) AS juml_kota
										FROM pendaftaran A
										LEFT JOIN mst_kota B ON A.city_id = B.id_kota
										WHERE A.is_delete = 0 AND A.status_seller IS NOT NULL AND A.member_aktif = 1 GROUP BY A.city_id ORDER BY juml_kota DESC LIMIT 10");

		$data['data_line_chart_reseller'] = $this->model->Code("SELECT A.status_seller AS id_status,	SUBSTR( A.member_date_approve, 1, 7 ) AS member_date_approve, COUNT(SUBSTR( A.member_date_approve, 1, 7 )) AS juml_member_date_approve,	B.status_seller AS nama_status 
									FROM pendaftaran A
									LEFT JOIN tb_status_seller B ON A.status_seller = B.id 
									WHERE
									A.is_delete = 0 
									AND A.status_seller = 1 
									AND A.member_date_approve IS NOT NULL  
									AND A.member_aktif = 1 
									AND SUBSTR( A.member_date_approve, 1, 7 )
									BETWEEN '" . $xDate . "' 
									AND '" . $dateNow . "' 
									GROUP BY A.status_seller, SUBSTR( A.member_date_approve, 1, 7)");

		$data['data_line_chart_member'] = $this->model->Code("SELECT A.status_seller AS id_status,	SUBSTR( A.member_date_approve, 1, 7 ) AS member_date_approve, COUNT(SUBSTR( A.member_date_approve, 1, 7 )) AS juml_member_date_approve,	B.status_seller AS nama_status 
									FROM pendaftaran A
									LEFT JOIN tb_status_seller B ON A.status_seller = B.id 
									WHERE
									A.is_delete = 0 
									AND A.status_seller = 2 
									AND A.member_date_approve IS NOT NULL  
									AND A.member_aktif = 1 
									AND SUBSTR( A.member_date_approve, 1, 7 )
									BETWEEN '" . $xDate . "' 
									AND '" . $dateNow . "' 
									GROUP BY A.status_seller, SUBSTR( A.member_date_approve, 1, 7)");

		$data['data_line_chart_agen'] = $this->model->Code("SELECT A.status_seller AS id_status,	SUBSTR( A.member_date_approve, 1, 7 ) AS member_date_approve, COUNT(SUBSTR( A.member_date_approve, 1, 7 )) AS juml_member_date_approve,	B.status_seller AS nama_status 
									FROM pendaftaran A
									LEFT JOIN tb_status_seller B ON A.status_seller = B.id 
									WHERE
									A.is_delete = 0 
									AND A.status_seller = 3 
									AND A.member_date_approve IS NOT NULL  
									AND A.member_aktif = 1 
									AND SUBSTR( A.member_date_approve, 1, 7 )
									BETWEEN '" . $xDate . "' 
									AND '" . $dateNow . "' 
									GROUP BY A.status_seller, SUBSTR( A.member_date_approve, 1, 7)");

		$data['data_line_chart_ao'] = $this->model->Code("SELECT A.status_seller AS id_status,	SUBSTR( A.member_date_approve, 1, 7 ) AS member_date_approve, COUNT(SUBSTR( A.member_date_approve, 1, 7 )) AS juml_member_date_approve,	B.status_seller AS nama_status 
									FROM pendaftaran A
									LEFT JOIN tb_status_seller B ON A.status_seller = B.id 
									WHERE
									A.is_delete = 0 
									AND A.status_seller = 4 
									AND A.member_date_approve IS NOT NULL  
									AND A.member_aktif = 1 
									AND SUBSTR( A.member_date_approve, 1, 7 )
									BETWEEN '" . $xDate . "' 
									AND '" . $dateNow . "' 
									GROUP BY A.status_seller, SUBSTR( A.member_date_approve, 1, 7)");

		$data['data_line_chart_distributor'] = $this->model->Code("SELECT A.status_seller AS id_status,	SUBSTR( A.member_date_approve, 1, 7 ) AS member_date_approve, COUNT(SUBSTR( A.member_date_approve, 1, 7 )) AS juml_member_date_approve,	B.status_seller AS nama_status 
									FROM pendaftaran A
									LEFT JOIN tb_status_seller B ON A.status_seller = B.id 
									WHERE
									A.is_delete = 0 
									AND A.status_seller = 5 
									AND A.member_date_approve IS NOT NULL  
									AND A.member_aktif = 1 
									AND SUBSTR( A.member_date_approve, 1, 7 )
									BETWEEN '" . $xDate . "' 
									AND '" . $dateNow . "' 
									GROUP BY A.status_seller, SUBSTR( A.member_date_approve, 1, 7)");

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/dashboard/home', $data);
		$this->load->view('back-end/container/footer');
	}

	public function pendaftaran($Aksi = "")
	{

		$dataHeader['title']		= "Pendaftaran Seller | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Seller';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->code("SELECT * FROM v_member WHERE member_code IS NULL AND tolak IS NULL AND member_aktif = 0 ORDER BY member_id DESC");

		$data['action'] 			= $Aksi;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/register_list', $data);
		$this->load->view('back-end/container/footer');
	}

	public function edit_data_seller($id = "")
	{
		$dataHeader['title']		= "Edit Data Seller Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'List Data Seller MS Slim';
		$dataHeader['fnct'] 		= 'edit_data_seller';
		$dataHeader['back'] 		= 'seller_aktif';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->ViewWhere('v_member_aktif', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['data_status']		= $this->model->ViewWhere('tb_status_seller', 'is_delete', '0');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/edit_data_seller', $data);
		$this->load->view('back-end/container/footer');
	}

	public function JSON_data_edit($id = "")
	{
		$data[] = $this->model->ViewWhere('v_member_aktif', 'member_id', $id);
		echo json_encode(array("result" => $data));
	}

	public function pendaftaran_aktif($Aksi = "")
	{

		$dataHeader['title']		= "Seller Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Seller';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->code("SELECT * FROM v_member WHERE member_code IS NOT NULL ORDER BY member_id DESC");

		$data['action'] 			= $Aksi;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/member_list', $data);
		$this->load->view('back-end/container/footer');
	}

	public function seller_aktif()
	{

		$dataHeader['title']		= "Seller Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Member List';
		$dataHeader['link']   		= 'index';

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/member_aktif');
		$this->load->view('back-end/container/footer');
	}

	public function seller_non_aktif($id = "")
	{

		$dataHeader['title']		= "Detail Seller Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Member Data';
		$dataHeader['fnct']			= 'seller_non_aktif';
		$dataHeader['back']   		= 'seller_aktif';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->ViewWhere('v_member', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/edit_data_seller', $data);
		$this->load->view('back-end/container/footer');
	}

	public function aktivasi_seller($id = "")
	{

		$dataHeader['title']		= "Detail Seller Non Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Member List';
		$dataHeader['fnct']   		= 'aktivasi_seller';
		$dataHeader['back']   		= 'data_seller_non_aktif';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->ViewWhere('v_member', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/edit_data_seller', $data);
		$this->load->view('back-end/container/footer');
	}


	public function data_seller_non_aktif()
	{

		$dataHeader['title']		= "Detail Seller Non Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Member List';
		$dataHeader['link']   		= 'index';

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/member_non_aktif');
		$this->load->view('back-end/container/footer');
	}

	public function pendaftaran_tolak($Aksi = "")
	{

		$dataHeader['title']		= "Seller Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Reject';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->code("SELECT * FROM v_member WHERE tolak IS NOT NULL ORDER BY member_id DESC");

		$data['action'] 			= $Aksi;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/member_decline', $data);
		$this->load->view('back-end/container/footer');
	}

	public function detail_pendaftaran($id = "")
	{

		$dataHeader['title']		= "Pendaftaran Seller | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Seller';
		$dataHeader['link']   		= 'index';
		$dataHeader['fnct']   		= 'pendaftar';

		$data['row']				= $this->model->ViewWhere('v_member', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['data_status']		= $this->model->ViewWhere('tb_status_seller', 'is_delete', '0');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/register_detail2', $data);
		$this->load->view('back-end/container/footer');
	}

	public function pendaftaran_reject($id = "")
	{

		$dataHeader['title']		= "Pendaftaran Seller | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Seller Reject';
		$dataHeader['link']   		= 'index';
		$dataHeader['fnct']   		= 'reject';

		$data['row']				= $this->model->ViewWhere('v_member', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['data_status']		= $this->model->ViewWhere('tb_status_seller', 'is_delete', '0');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/register_detail2', $data);
		$this->load->view('back-end/container/footer');
	}

	public function detail_seller($id = "")
	{

		$dataHeader['title']		= "Pendaftaran Seller | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Pendaftaran Seller';
		$dataHeader['link']   		= 'index';

		$data['row']				= $this->model->ViewWhere('v_member', 'member_id', $id);
		$data['location']			= $this->model->ViewWhere('lokasi', 'kode_seller', $data['row'][0]['member_code']);
		$data['provinsi']			= $this->model->ViewAsc('mst_provinsi', 'id_provinsi');
		$data['action'] 			= $id;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/register/register_detail', $data);
		$this->load->view('back-end/container/footer');
	}


	function get_kota()
	{
		$id 	= $this->input->post('id');
		$data 	= $this->model->get_kota($id);
		echo json_encode($data);
	}

	function get_kecamatan()
	{
		$id 	= $this->input->post('id');
		$data 	= $this->model->get_kecamatan($id);
		echo json_encode($data);
	}

	public function cetakcard($Id = "")
	{
		$data['kodeseller'] = $Id;
		$data['row']		= $this->model->code("SELECT * FROM pendaftaran WHERE member_id = '" . $Id . "'");

		$this->load->view('back-end/register/card', $data);
	}

	public function notif_pendaftar_baru()
	{
		$data = $this->model->Code("SELECT COUNT(member_name) AS pendaftar_baru FROM pendaftaran WHERE member_code IS NULL AND is_delete = 0");
		foreach ($data as $rowData) {
			$jml = $rowData['pendaftar_baru'];
		}
		return $jml;
	}

	public function hash_id_seller($id_seller = "")
	{
		$a_id_seller = sha1($id_seller);
		$salt = "Lc$%02d$%s";
		$c_id_seller = crypt($a_id_seller, $salt);
		$v = str_replace('/', '', $c_id_seller);

		$data = array(
			'hash_id_seller' => $v
		);

		$this->model->Update('pendaftaran', 'member_code', $id_seller, $data);
		$this->model->Update('lokasi', 'kode_seller', $id_seller, $data);
		return $v;
	}
	public function cetak_id_card_depan_msslim($id = "")
	{
		$this->load->library('Ciqrcode'); //pemanggilan library QR CODE
		$get_member_code = $this->model->code("SELECT * FROM pendaftaran WHERE member_id = '" . $id . "' ");
		foreach ($get_member_code as $rowMember) {
			$data_member = $rowMember['member_code'];
			$photo_member = $rowMember['member_photo'];
		}

		if (empty($data_member) || empty($photo_member)) {
			$data['result_member_code'] = "kosong";
			$data['result_member_photo'] = "kosong";
		} else {
			$data['result_member_code'] = "ada";
			$data['result_member_photo'] = "ada";
			$id_seller = $this->hash_id_seller($data_member);
			$getData = $this->model->ViewWhere('pendaftaran', 'hash_id_seller', $id_seller);

			foreach ($getData as $row) {
				$config['cacheable']    = true; //boolean, the default is true
				$config['cachedir']     = 'assets/'; //string, the default is application/cache/
				$config['errorlog']     = 'assets/'; //string, the default is application/logs/
				$config['imagedir']     = 'assets/msslim/'; //direktori penyimpanan qr code
				$config['quality']      = true; //boolean, the default is true
				$config['size']         = '1024'; //interger, the default is 1024
				$config['black']        = array(224, 255, 255); // array, default is array(255,255,255)
				$config['white']        = array(70, 130, 180); // array, default is array(0,0,0)
				$this->ciqrcode->initialize($config);

				$image_name = $row['member_code'] . '.png'; //buat name dari qr code sesuai dengan salt job id

				$params['data'] = "http://103.157.96.97/msglow-msslim/Result_our_seller?scaned=" . $row['hash_id_seller']; //data yang akan di jadikan QR CODE
				$params['level'] = 'H'; //H=High
				$params['size'] = 1;
				$params['savename'] = FCPATH . $config['imagedir'] . "$image_name"; //simpan image QR CODE ke folder assets/images/
				$this->ciqrcode->generate($params);

				//ambil logo
				$logopath = $config['imagedir'] . "logomsslim.png";

				//simpan file qrcode
				QRcode::png($params['data'], $config['imagedir'] . "$image_name", QR_ECLEVEL_H, 10, 4);


				// ambil file qrcode
				$QR = imagecreatefrompng($config['imagedir'] . "$image_name");

				// memulai menggambar logo dalam file qrcode
				$logo = imagecreatefromstring(file_get_contents($logopath));

				imagecolortransparent($logo, imagecolorallocatealpha($logo, 0, 0, 0, 127));
				imagealphablending($logo, false);
				imagesavealpha($logo, true);

				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);

				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);

				// Scale logo to fit in the QR Code
				$logo_qr_width = $QR_width / 3;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;

				imagecopyresampled($QR, $logo, $QR_width / 3, $QR_height / 2.6, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

				// Simpan kode QR lagi, dengan logo di atasnya
				imagepng($QR, $config['imagedir'] . "$image_name");
				// $chmod_filename = $_SERVER['DOCUMENT_ROOT'] . "/cetak-lisensi/assets2/images/id_card/$image_name";

				// $file_permission = decoct(fileperms("$chmod_filename"));
				// if ($file_permission != "100777") {
				// 	chmod("$chmod_filename", 0777);
				// } else {
				// }

				$response = array(
					'nama' => $row['member_name'],
					'member_photo' => $row['member_photo'],
					'id_seller' => $row['member_code'],
					'valid_thru' => $this->DateStamp_card()
				);

				$dataJson_view = json_encode($response, JSON_PRETTY_PRINT);
				$data['data_id_card'] = json_decode($dataJson_view, true);
			}
			$this->load->view('back-end/register/id_card_depan_msslim', $data);
		}
	}

	public function status_seller($Aksi = "", $id = "")
	{

		$dataHeader['title']		= "Seller Aktif Ms Slim | Official Register By  Ms Glow";
		$dataHeader['menu']   		= 'Master';
		$dataHeader['file']   		= 'Status Seller';
		$dataHeader['link']   		= 'index';
		if ($Aksi == "edit") {
			$data['row']				= $this->model->code("SELECT * FROM tb_status_seller WHERE is_delete = 0 AND id = '" . $id . "' ORDER BY id DESC");
		} else {
			$data['row']				= $this->model->code("SELECT * FROM tb_status_seller WHERE is_delete = 0 ORDER BY id DESC");
		}

		$data['action'] 			= $Aksi;

		$this->load->view('back-end/container/header', $dataHeader);
		$this->load->view('back-end/master/status_seller', $data);
		$this->load->view('back-end/container/footer');
	}

	public function Update_kode()
	{
		$get_data = $this->model->Code("SELECT member_id, member_code FROM pendaftaran WHERE member_code IS NOT NULL");
		foreach ($get_data as $row) {
			$data = array(
				'id_pendaftaran' => $row['member_id']
			);
			$this->model->Update('lokasi', 'kode_seller', $row['member_code'], $data);
		}
	}

	public function Update_seller_aktif()
	{
		$get_data = $this->model->Code("SELECT member_id, member_code, member_aktif, tolak FROM pendaftaran WHERE member_code IS NOT NULL ");
		foreach ($get_data as $row) {
			$data_aktif = array(
				'member_aktif' => 1
			);

			$data_non_aktif = array(
				'member_aktif' => 2
			);

			$data_pendaftaran = array(
				'member_aktif' => 0
			);

			if ($row['tolak'] == "YA" && $row['member_code'] != null) {
				$this->model->Update('pendaftaran', 'member_code', $row['member_code'], $data_non_aktif);
			} elseif ($row['tolak'] == null && $row['member_code'] == null) {
				$this->model->Update('pendaftaran', 'member_id', $row['member_id'], $data_pendaftaran);
			} elseif ($row['tolak'] == null && $row['member_code'] != null) {
				$this->model->Update('pendaftaran', 'member_id', $row['member_id'], $data_aktif);
			}else{
			}
		}
	}
}
