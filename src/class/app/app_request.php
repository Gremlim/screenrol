<?php
namespace app;
class app_request extends \request\urlencoded_request{
	private $files=[];

	public function has_file($_key){

		return isset($this->files[$_key]);
	}

	public function file($_key){

		return $this->has_file($_key) ? $this->files[$_key] : null;
	}
	public static function 			factory(\request\request $_req) {
		if($_req instanceof \request\urlencoded_request) {
			return new app_request($_req->get_method(), $_req->get_uri(), $_req->get_query_string(), $_req->get_protocol(), $_req->get_headers(), $_req->get_body());
		}
		else if($_req instanceof \request\multipart_request) {

			$content=[];
			$files=[];
			for($i=0;$i<$_req->count();$i++){
				$temp=$_req->get_body_by_index($i);
				if($temp->is_file()){
					$files[$temp->get_name()]=$temp;
				}else{
					$content[$temp->get_name()]=$temp->get_body();
				}
			}

			$new_body=http_build_query($content);

			return new app_request($_req->get_method(), $_req->get_uri(), $_req->get_query_string(), $_req->get_protocol(), $_req->get_headers(), $new_body,$files);
		}
		else {
			throw new \Exception("?????");
		}
	}

	public function 				__construct($_method, $_uri, $_query_string, $_protocol, $_headers, $_body, $_files=null) {
		$this->files=$_files;
		parent::__construct($_method, $_uri, $_query_string, $_protocol, $_headers, $_body, $_files);
	}

}
