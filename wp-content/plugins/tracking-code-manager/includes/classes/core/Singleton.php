<?php
class TCM_Singleton {
    var $Lang;
    var $Utils;
    var $Form;
    var $Check;
    var $Options;
    var $Log;
    var $Cron;
    var $Tracking;
    var $Manager;
    var $Ecommerce;
    var $Plugin;
    var $Tabs;

    function __construct() {
        $this->Lang=new TCM_Language();
        $this->Utils=new TCM_Utils();
        $this->Form=new TCM_Form();
        $this->Check=new TCM_Check();
        $this->Options=new TCM_Options();
        $this->Log=new TCM_Logger();
        $this->Cron=new TCM_Cron();
        $this->Tracking=new TCM_Tracking();
        $this->Manager=new TCM_Manager();
        $this->Ecommerce=new TCM_Ecommerce();
        $this->Plugin=new TCM_Plugin();
        $this->Tabs=new TCM_Tabs();
    }
    public function init() {
        $this->Lang->load('tcm', TCM_PLUGIN_ROOT.'languages/Lang.txt');
        $this->Cron->init();
        $this->Tabs->init();
    }
}