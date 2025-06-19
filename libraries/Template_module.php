<?php

use Carbon\Carbon;

defined('BASEPATH') or exit('No direct script access allowed');

class Repair_checkin
{
    private $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

}
