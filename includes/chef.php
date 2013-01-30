<?php

/*
  @author bigoven
*/

if ( !class_exists('BOChef') ):

global $BO_directory;
require $BO_directory . '/includes/attributed.php';

class BOChef extends BOAttributedBase {
	const NO_CHEF_IMAGE	=   'http://www.bigoven.com/images/avatar-nopicture48.png';

	function imageURL() { return $this->contentValue('ImageURL',self::NO_CHEF_IMAGE); }
	function username() { return $this->contentValue('UserName',null); }

	function webURL() { return "http://www.bigoven.com/user/" . $this->username(); }
}

endif;

?>