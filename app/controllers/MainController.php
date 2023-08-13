<?php

namespace MugshotGame\App\Controllers;

use SimplePHP\Core\Infrastructure\Controller;
use MugshtGame\App\Views\MainView;

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return new MainView( $this->_repo );
    }
}