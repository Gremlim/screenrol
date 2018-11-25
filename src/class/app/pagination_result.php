<?php
namespace app;

class pagination_result {

	private					$current;
	private					$registers_per_page;
	private					$total;
	private					$data;

	public function 		__construct($_c, $_r, $_t, $_d) {

		$this->current=$_c;
		$this->registers_per_page=$_r;
		$this->total=$_t;
		$this->data=$_d;
	}

	public function			get_current() {

		return $this->current;
	}

	public function			get_registers_per_page() {

		return $this->registers_per_page;
	}

	public function			get_total() {

		return $this->total;
	}

	public function 		get_data() {

		return $this->data;
	}

	public function			create_markup($_url, array $_array_data, $_page_param_name='page', $_margin=4) {

		$extra_params=http_build_query($_array_data);

		$create_page=function($_page, $_content, $_class=null) use ($_url, $_page_param_name, $extra_params) {

			$url=null!==$_page ? $_url.'?'.$_page_param_name.'='.$_page.'&'.$extra_params : null;
			$view_href=$url ? 'href="'.$url.'"' : null;

			return <<<R
				<li class="{$_class}">
					<a {$view_href}>{$_content}</a>
				</li>
R;
		};

		$npag=ceil($this->total/$this->registers_per_page);

		$dif=$this->current-$_margin;
		$comienzo=$dif<1 ? 1 : $dif;
		$x=$dif<1 ? 1 : $dif;

		$dif=$this->current+4;
		$fin=$dif>$npag ? $npag : $dif;

		$pages=null;

		if($this->total > $this->registers_per_page) {
			$pages.=$create_page($this->current-1, '&laquo;'); //Prev...
		}
		$pages.=$comienzo==1 ? null : $create_page(1, 1, null).$create_page(null, '...', 'disabled'); 		//First...
		//Paginas individuales
		while ($x<=$fin){
			$class=$x == $this->current ? 'active' : null;
			$pages.=$create_page($x, $x, $class);
			++$x;
		}

		$pages.=$fin==$npag ? null : $create_page(null, '...', 'disabled').$create_page($npag, $npag, null); //Last
		if($this->total > $this->registers_per_page) {
			$pages.=$create_page($this->current+1, '&raquo;'); //Next
		}

		return <<<R
	<div class="pagination">
		<ul class="pagination">
			{$pages}
		</ul>
	</div>
R;
	}
}
