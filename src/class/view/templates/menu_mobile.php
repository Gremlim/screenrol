<?php
namespace view\templates;

class menu_mobile  extends \view\view {

	private 			$categories=null;
	private				$current_path=null;
	private				$current_profile=null;

	public function 	__construct(array $_categories, $_cur, $_prof) {

			$this->categories=$_categories;
			$this->current_path=$_cur;
			$this->current_profile=$_prof;
	}

	public function 	create_view() {

		$result='';

		foreach($this->categories as $category) {

			//Prune empty categories...
			$available=array();
			foreach($category->get_sections() as $section) {

				if(\app\path_authority::check_fallback($section->get_path(), $this->current_profile)
					&& \app\path_authority::check($section->get_path(), $this->current_profile)) {
					$available[]=$section;
				}
			}

			if(!count($available)) {
				continue;
			}

			$sections=null;
			foreach($available as $section) {



				$lowername=strtolower($section->get_name());
				$sections.=<<<R
	<a href="{$section->get_path()}">
		<div class="bot-menLat pointer" data-toggle="tooltip" data-placement="right" title="{$section->get_name()}">
			<span class="{$section->get_icon()}"></span>
			<span class="oculto badge {$lowername}-badge" style="position:relative;top:-10px;left:-5px;">0</span>
		</div>
	</a>
R;
			}

			$result.=<<<R
	<div class="lat-sec-mobile">
		{$sections}
	</div>
R;
		}

		return <<<R
			<div class="catmenLat">
				{$result}
			</div>
R;
	}
}
