<?php
class JqueryHelper extends AppHelper {
	public $helpers = array('Html', 'Javascript');

	public function selectable($class = 'selectable') {
		$this->Javascript->link('/jquery/js/jquery.selectable', false);
		$html->css('/jquery/css/selectable_skin/selectable/style');
		$code =
<<<EOT
$(function(){
	$("select.{$class}").selectable({
		set:"slideDown",
		out: "slideUp",
		opacity:.9
	});
});
EOT;
		$this->Javascript->codeBlock($code, array('inline' => false));
	}

}
?>