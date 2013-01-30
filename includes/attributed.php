<?php

/*
  @author bigoven
*/

if ( !class_exists('BOAttributedBase') ):

class BOAttributedBase {

	var $content = null;

	function initFromArray($arrayData) {

		// Need something...
		assert(null != $arrayData);

		// Stash what we have...
		$this->content = $arrayData;
	}

	// Content...
	function hasContentValue($key) {
		return array_key_exists($key, $this->content) && !!$this->content[$key];
	}

	function contentValue($key, $default = null) {
		$val = $default;
		if ( $this->hasContentValue($key) ) {
			$val = $this->content[$key];
		}
		return $val;
	}
}

endif;

?>