<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Repair_checkin_model extends App_Model
{
    protected $table = 'repair_checkins';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_orders()
    {
        return $this->db->get(db_prefix() . $this->table)->result_array();
    }
    // In your model
public function get_devices_by_client($client_id)
{
    return $this->db->get_where(db_prefix() . 'wshop_devices', ['client_id' => $client_id])->result_array();
}


}
