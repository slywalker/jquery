<?php
class JqueryUiHelper extends AppHelper {
	public $helpers = array('Html', 'Javascript');

	public function accordion($contents, $options = array()) {
		$default = array(
			'id' => 'accordion',
			'class' => null,
			'header' => 'h3',
			'autoHeight' => false,
			'collapsible' => true,
			'active' => 0,
		);
		$options = array_merge($default, $options);
		foreach ($options as $key => $option) {
			if ($option === true) {
				$options[$key] = 'true';
			}
			if ($option === false) {
				$options[$key] = 'false';
			}
		}
		if (!is_array($contents) && !isset($contents[0]['title']) && !isset($contents[0]['div'])) {
			return false;
		}
		$code =
<<<EOT
$(function(){
	$('#{$options['id']}').accordion({
		header : '{$options['header']}',
		autoHeight : {$options['autoHeight']},
		collapsible : {$options['collapsible']},
		active : {$options['active']}
	});
});
EOT;
		$this->Javascript->codeBlock($code, array('inline' => false));
		$out = array();
		foreach ($contents as $content) {
			$h3 = $this->Html->tag('h3', '<a href="#">'.$content['title'].'</a>');
			$div = $this->Html->div(null, $content['div']);
			$out[] = $h3.$div;
		}
		return $this->Html->div($options['class'], implode("\n", $out), array('id' => $options['id']));
	}

	public function tabs($contents, $options = array()) {
		$default = array(
			'id' => 'tabs',
			'class' => null,
		);
		$options = am($default, $options);
		if (!is_array($contents) && !isset($contents[0]['title']) && !isset($contents[0]['div'])) {
			return false;
		}
		$this->Javascript->codeBlock('$(function(){$("#'.$options['id'].'").tabs();});', array('inline' => false));
		$li = array();
		$divs = array();
		$i = 1;
		foreach ($contents as $content) {
			$li[] = $this->Html->tag('h3', '<a href="#'.$options['id'].'-'.$i.'">'.$content['title'].'</a>');
			$divs[] = $this->Html->div(null, $content['div'], array('id'=>$options['id'].'-'.$i));
			$i++;
		}
		$out = array();
		$out[] = $this->Html->nestedList($li);
		$out[] = implode("\n", $divs);
		return $this->Html->div($options['class'], implode("\n", $out), array('id' => $options['id']));
	}
	
	public function link($title, $url = null, $htmlAttributes = array(), $confirmMessage = false, $escapeTitle = true) {
		$default = array(
			'id' => null,
			'class' => null,
			'icon' => 'newwin',
			'state' => 'default',
			'corner' => 'all',
		);
		$htmlAttributes = array_merge($default, $htmlAttributes);
		
		$span = '';
		if ($htmlAttributes['icon']) {
			$attr = array(
				'class' => 'ui-icon ui-icon-'.$htmlAttributes['icon'],
				'style' => 'left:0.2em;margin:-8px 5px 0 0;position:absolute;top:50%;',
			);
			$span = $this->Html->tag('span', '', $attr);
		}
		$classes = array(
			$htmlAttributes['class'],
			'ui-button',
			'ui-state-'.$htmlAttributes['state'],
			'ui-corner-'.$htmlAttributes['corner'],
		);
		$attr = array(
			'class' => implode(' ', $classes),
			'style' => 'padding:.4em .8em .2em 1.4em;position:relative;text-decoration:none;',
		);
		if (empty($title)) {
			$attr['style'] = 'padding:.4em .4em .4em 20px;position:relative;text-decoration:none;';
		}
		if ($htmlAttributes['id']) {
			$attr = array_merge($attr, array('id' => $htmlAttributes['id']));
		}
		if ($escapeTitle) {
			$title = h($title);
		}
		$code =
<<<EOT
$(function(){
	$('a.ui-button').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
});
EOT;
		$this->Javascript->codeBlock($code, array('inline' => false));
		return $this->Html->link($span.$title, $url, $attr, $confirmMessage, false);;
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

	public function state($html, $options = array()) {
		$default = array(
			'type' => 'default',
			'corner' => 'all',
			'icon' => 'info',
		);
		$options = array_merge($default, $options);
		$class = array();
		$class[] = 'ui-state-'.$options['type'];
		unset($options['type']);
		
		if ($options['corner']) {
			$class[] = 'ui-corner-'.$options['corner'];
		}
		unset($options['corner']);
		
		if ($options['icon']) {
			$html = $this->Html->tag('span', '', array('class'=>'ui-icon ui-icon-'.$options['icon'], 'style'=>'left:0.2em;margin:-8px 5px 0 0;position:absolute;top:50%;')).$html;
		}
		unset($options['icon']);
		
		$style = 'padding:0.4em 1em 0.4em 20px;position:relative;text-decoration:none;';
		if (isset($options['style'])) {
			$style = $style.$options['style'];
		}
		$options['style'] = $style;
		return $this->Html->div(implode(' ', $class), $html, $options);
	}

	public function icon($icon) {
		$out = $this->Html->tag('span', '', array('class' => 'ui-icon ui-icon-'.$icon));
		$out = $this->Html->para('ui-state-default ui-corner-all', $out, array('style' => 'width:16px;margin:0 4px;float:left;'));
		return $out;
	}
}