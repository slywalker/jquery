<?php
class JqueryUiHelper extends AppHelper {
	public $helpers = array('Html', 'Javascript');

	public function accordion($contents, $options = array()) {
		$default = array(
			'id'=>'accordion',
			'class'=>null,
		);
		$options = am($default, $options);
		if (!is_array($contents) && !isset($contents[0]['title']) && !isset($contents[0]['div'])) {
			return false;
		}
		$this->Javascript->codeBlock('$("#'.$options['id'].'").accordion({header:"h3"});', array('inline' => fasle));
		$out = '';
		foreach ($contents as $content) {
			$h3 = $this->Html->tag('h3', '<a href="#">'.$content['title'].'</a>');
			$div = $this->Html->div(null, $content['div']);
			$out .= $h3.$div;
		}
		return $this->Html->div($options['class'], $out, array('id'=>$options['id']));
	}

	public function tabs($contents, $options = array()) {
		$default = array(
			'id'=>'tabs',
			'class'=>null,
		);
		$options = am($default, $options);
		if (!is_array($contents) && !isset($contents[0]['title']) && !isset($contents[0]['div'])) {
			return false;
		}
		$this->Javascript->codeBlock('$("#'.$options['id'].'").tabs();', array('inline' => false));
		$li = array();
		$divs = '';
		$i = 1;
		foreach ($contents as $content) {
			$li[] = $this->Html->tag('h3', '<a href="#'.$options['id'].'-'.$i.'">'.$content['title'].'</a>');
			$divs .= $this->Html->div(null, $content['div'], array('id'=>$options['id'].'-'.$i));
			$i++;
		}
		$out = $this->Html->nestedList($li);
		$out .= $divs;
		return $this->Html->div($options['class'], $out, array('id'=>$options['id']));
	}
	
	public function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		$default = array(
			'id'=>null,
			'class'=>null,
			'icon'=>'newwin',
			'state'=>'default',
			'corner'=>'all',
		);
		$htmlAttributes = am($default, $htmlAttributes);
		
		$span = '';
		if ($htmlAttributes['icon']) {
			$attr = array(
				'class'=>'ui-icon ui-icon-'.$htmlAttributes['icon'],
				'style'=>'left:0.2em;margin:-8px 5px 0 0;position:absolute;top:50%;',
			);
			$span = $this->Html->tag('span', '', $attr);
		}
		$attr = array(
			'class'=>$htmlAttributes['class'].' ui-state-'.$htmlAttributes['state'].' ui-corner-'.$htmlAttributes['corner'],
			'style'=>'padding:0.6em 1em 0.4em 20px;position:relative;text-decoration:none;',
		);
		if ($htmlAttributes['id']) {
			$attr = am($attr, array('id'=>$htmlAttributes['id']));
		}
		if ($escapeTitle) {
			$title = h($title);
		}
		$a = $this->Html->link($span.$title, $url, $attr, $confirmMessage, false);
		$code =
<<<EOT
$(function(){
	$('p.ui-button a').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
});
EOT;
		$this->Javascript->codeBlock($code, array('inline' => false));
		return $this->Html->para('ui-button', $a, array('style' => 'margin:.5em 0;'));
	}

	public function datepicker($id) {
		$this->Javascript->link('jquery-ui-i18n', false);
		$code =
<<<EOT
$(function(){
	$.datepicker.setDefaults($.extend($.datepicker.regional['ja']));
	$('#{$id}').datepicker();
});
EOT;
		$this->Javascript->codeBlock($code, array('inline' => false));
	}

}