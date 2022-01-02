<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class Member_msslim extends RestController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('msslim/Rest_api_member_msslim', 'member');
        $this->load->library('pagination');
    }

    //view registrant
    function index_get($id="")
    {   
        if ($id == "") {
            // $result = $this->member->view_('v_member_aktif');
            $table = 'v_member_aktif';
            $result = $table;

            // $lokasi_client  = $this->get_location();
            // $keyword        = $this->input->post('search');
            // $seller         = $this->input->post('search_id');
    
            // $config                     = array();
            // $config["base_url"]         = "#";
            // $config["total_rows"]       = $this->member->count_seller_location($keyword, $seller, $lokasi_client);
            // $config["per_page"]         = 6;
            // $config["uri_segment"]      = 3;
            // $config["use_page_numbers"] = TRUE;
            // $config["full_tag_open"]    = '<ul class="pagination justify-content-center text-center">';
            // $config["full_tag_close"]   = '</ul>';
            // $config["first_tag_open"]   = '<li>';
            // $config["first_tag_close"]  = '</li>';
            // $config["last_tag_open"]    = '<li>';
            // $config["last_tag_close"]   = '</li>';
            // $config['next_link']        = '<i class="mdi mdi-chevron-right f-15"></i>';
            // $config["next_tag_open"]    = '';
            // $config["next_tag_close"]   = '';
            // $config["prev_link"]        = '<i class="mdi mdi-chevron-left f-15"></i>';
            // $config["prev_tag_open"]    = '';
            // $config["prev_tag_close"]   = '';
            // $config["cur_tag_open"]     = '<li class="page-item disabled active"><a class="page-link" href="#">';
            // $config["cur_tag_close"]    = '</a></li>';
            // $config["num_tag_open"]     = '';
            // $config["num_tag_close"]    = '';
            // $config["num_links"]        = 3;
            // $this->pagination->initialize($config);
            // $page = $this->uri->segment(6);
            // $start = ($page - 1) * 4;
            // $result = array(
            //     'pagination_link'       =>    $this->pagination->create_links(),
            //     'seller'                =>    $this->member->get_search_seller_location($keyword, $seller, $lokasi_client, $config["per_page"], abs($start)),
            //     'showing_result'        =>    $this->member->get_show_locate_seller($keyword, $seller, $lokasi_client)
            // );

        } else {
            $result = $this->member->view_('v_member_aktif', 'member_id', $id);
        }

        if ($result) {
            $this->response($result, RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'data' => 'data_not_found'
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    public function get_location()
    {
        $ip_client = $_SERVER['REMOTE_ADDR'];

        $url = 'https://freegeoip.app/json/' . $ip_client;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        $data_ip_client = json_decode($result, true);

        if (!empty($data_ip_client)) {
            if (empty($data_ip_client['city'])) {
                $city = 'Unknown!';
                return $city;
            } else {
                $country_code = $data_ip_client['country_code'];
                $country = $data_ip_client['country_name'];
                $region_code = $data_ip_client['region_code'];
                $region = $data_ip_client['region_name'];
                $city = $data_ip_client['city'];
                $zip_code = $data_ip_client['zip_code'];
                $lat = $data_ip_client['latitude'];
                $lon = $data_ip_client['longitude'];
                $time_zone = $data_ip_client['time_zone'];

                // echo 'Country Name: ' . $country . '<br/>';
                // echo 'Country Code: ' . $country_code . '<br/>';
                // echo 'Region Code: ' . $region_code . '<br/>';
                // echo 'Region Name: ' . $region . '<br/>';
                // echo 'City: ' . $city . '<br/>';
                // echo 'Zipcode: ' . $zip_code . '<br/>';
                // echo 'Latitude: ' . $lat . '<br/>';
                // echo 'Longitude: ' . $lon . '<br/>';
                // echo 'Time Zone: ' . $time_zone;
                return $city;
            }
        } else {
            $city = 'Unknown!';
            return $city;
        }
    }

}
