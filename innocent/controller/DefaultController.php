<?php
class DefaultController extends Controller {
		public function index(){

			$this->set('test','うぇるかむ。');
					
			$this->tpl();
		}

}
