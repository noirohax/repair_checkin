<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Repair_checkin extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clients_model');
        $this->load->model('repair_checkin_model'); // Your module model
        $this->load->model('workshop/Workshop_model', 'workshop_model'); // Load workshop model correctly
    }

    public function index()
    {
        $data['title'] = _l('New Repair Order');
        $data['customers'] = $this->clients_model->get();
        $this->load->view('repair_order_form', $data);
    }
public function get_customer_devices($client_id)
{
    if (!is_numeric($client_id)) {
        show_404();
    }

    $this->db->select('id, name, code, serial_no, model_id, type'); // Ensure `name` is explicitly selected
    $this->db->where('client_id', $client_id);
    $devices = $this->db->get(db_prefix() . 'wshop_devices')->result_array();

    foreach ($devices as &$device) {
        $device['display_name'] =
            ($device['name'] ?? 'Unnamed Device') . ' - ' .
            (($device['model_id'] !== '0' && $device['model_id']) ? $device['model_id'] : 'Unknown Model') .
            ' (' . $device['serial_no'] . ')';
    }

    echo json_encode($devices);
}


    
}
