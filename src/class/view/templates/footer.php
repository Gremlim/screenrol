<?php
namespace view\templates;

//!This is the class that encapsulates the header of the site.
class footer extends \view\view {

	public function create_view() {

		return <<<R
			</div>
		</div>
	</div>
	</div> <!-- Close dash-container -->
	
	</body>
</html>
R;
	}
}
