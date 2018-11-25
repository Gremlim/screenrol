<?php
namespace controller\##FULL_NAMESPACE##;

class ##CLASSNAME## extends \controller\section {

    public function				__construct(\app\dependency_injector $_di) {

		parent::__construct($_di);
	}

	public function 			example_view($_tipo,$_procesados,$_buscar,$_pagina) {

		//Paginacion
		$pager=new \app\pagination($_pagina, 50, function($_page, $_pagesize) use ($filters) {
            return get_data_function($filters['search'],$_page,$_pagesize,$filters['field'],$filters['order']);
        });
		$section_data=$pager->get();

        return $this->reply_with_view(
            $view=new \view\##FULL_NAMESPACE##\##CLASSNAME##($section_data),
            [],
            \app\response::status_code_200_ok,
			['##CLASSNAME##']			// Language templates
        );
	}

	public function				example_modal() {

		return $this->reply_with_modal(
			$view=new \view\##FULL_NAMESPACE##\##CLASSNAME##(),
			[],
			\app\response::status_code_200_ok,
			['##CLASSNAME##']			// Language templates
		);
	}

	public function				example_ajax() {

		$status_code=null;
		$headers=[];
		$result=null;

		$success=true;
		if(!$success) {
			$result=\app\api_result::from_error('Something failed');
			$status_code=\app\response::status_code_500_internal_server_error;
		}
		else {
			$result=\app\api_result::from_data(['val' => 666]);
			$status_code=\app\response::status_code_200_ok;
		}

		return $this->reply_with_api_result($result, $headers, $status_code);
	}

	public function				example_redirect() {

		$url='http://theurlgoeshere.com';
		$status_code=\app\response::status_code_303_see_other;
		$headers=[];

		return $this->reply_with_redirection($url, $status_code, $headers);
	}

	public function				example_file_download() {

		$file_path="path/to/file";
		$download_name="downloaded_file.txt";
		return $this->reply_with_file_download($file_path, $download_name);
	}

	public function				example_raw_text() {

		$text="This will be returned as raw text";
		$status_code=\app\response::status_code_200_ok;
		$headers=[]; //TODO: Headers must depend on your content-type!.

		return $this->reply_with_raw_text($text, $headers, $status_code);
	}
}
