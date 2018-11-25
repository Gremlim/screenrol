<?php
namespace view\templates;

class menu_desktop extends \view\view {

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

				$fallback=\app\path_authority::check_fallback($section->get_path(), $this->current_profile);
				$auth=\app\path_authority::check($section->get_path(), $this->current_profile);

				if($fallback && $auth) {
					$available[]=$section;
				}
			}

			if(!count($available)) {
				continue;
			}

			$section_list='';
			$opened=false;

			//Compose categories...
			foreach($available as $section) {

				$lowername=str_replace(' ', '_', strtolower($section->get_name()));
				$is_current=$this->current_path===$section->get_path();
				//$color=$is_current ? 'style="background:#0AA699;color:white;"' : '';
				$color=$is_current ? 'current' : '';

				$opened=$opened || $is_current;
				$url=\app\tools::build_url(substr($section->get_path(), 1)); //Remove leading /

				$section_list.=<<<R

					<div class="bot-menLat {$color}" >
						<span class="{$section->get_icon()}" ></span>
						<a href="{$url}" >
							<span class="section-name">{$section->get_name()}</span>
						</a>
						<span class="oculto badge pull-right {$lowername}-badge">0</span>
						<span class="pull-right iconmenLat">&lt;&nbsp;</span>
					</div>

R;
			}

			$open_class=$opened ? '' : 'oculto';
			$chevron_type=$opened ? 'down' : 'left';
			$nameupper=strtoupper($category->get_name());

			$result.=<<<R
			<div>
				<div class="bot-menLat pointer depart" style="margin-top:-10px;">
					<span class="fa {$category->get_icon()}">
						<span class="category-name">{$category->get_name()}</span>
						<span style="font-size:10px;" class="fa fa-chevron-{$chevron_type} chevron-depart"></span>
					</span>
				</div>
				<div class="depart-section catmenLat {$open_class}" style="background:#22262E;">
					{$section_list}
				</div>
			</div>

			<br/>
R;

		}

		return $result;
	}
}
