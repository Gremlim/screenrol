<?php
namespace app;

class pagination {

	private					$current;
	private					$registers_per_page;
	private					$callback;

	public function			__construct($_cur, $_rpp, $_cb) {

		$this->current=$_cur > 0 ? $_cur : 1;
		$this->registers_per_page=$_rpp;
		$this->callback=$_cb;
	}

	public function			get() {

		$data=call_user_func($this->callback, $this->current, $this->registers_per_page);
		$total=$data['total'];
		unset($data['total']);

		return new pagination_result($this->current, $this->registers_per_page, $total, $data);
	}
}
