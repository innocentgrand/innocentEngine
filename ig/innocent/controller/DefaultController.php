<?php
class DefaultController extends Controller {
		public function Index(){

			$this->set('test','うぇるかむ。');
			$this->log('tesu');	
			$this->tpl();
		}

}
