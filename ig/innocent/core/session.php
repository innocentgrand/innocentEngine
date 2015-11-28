<?php 
class SessionClass extends core {
	private $sessionData;

	public function set($name, $data){
		session_start();
		$_SESSION[$name] = $data;
		session_write_close();		
	}

	public function read($key){
		$data = null;
		session_start();
		if($this->check($key)){
			$data = $_SESSION[$key];
		}
		session_write_close();
		return $data;
	}

	public function check($key){
		$r = true;
		session_start();
		if(empty($_SESSION[$key])){
			$r = false;
		}
		session_write_close();
		return $r;
	}
        
        public function del($key) {
            session_start();
            if(!empty($_SESSION[$key])){
                unset($_SESSION[$key]);
            }
            session_write_close();
        }
        
        public function destroy() {
            session_start();
            $r = session_destroy();
            session_write_close();
        }
}
