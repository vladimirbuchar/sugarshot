<?php

namespace Controller;

use Types\SortDatabase;

abstract class SettingsController extends AdminController {

    /** tuto třídu požívají číselníky */
    public function __construct() {

        parent::__construct();
        $this->SharedView = "List";
    }

}