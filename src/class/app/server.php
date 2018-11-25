<?php
namespace app;

class server {

    public $path;
    public $server_name;
    public $server_addr;
    public $server_port;
    public $remote_addr;
    public $document_root;
    //public $request_scheme; //Not always available.
    public $server_admin;
    public $script_filename;
    public $remote_port;
    public $redirect_url;
    public $gateway_interface;
    public $server_protocol;
    public $script_name;
    public $php_self;
    public $request_time_float;
    public $request_time;

    public function __construct(array $_server){
        foreach($_server as $k=>$v){
            $property=strtolower($k);

            if(property_exists($this, $property)) {
                $this->$property=$v;
            }
        }
    }
}
