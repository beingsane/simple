<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_menu
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.

$dropdown_number=1;
?>
<ul class="menu<?php echo $class_sfx;?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($list as $i => &$item) :
	$class = 'item-'.$item->id;
	if ($item->id == $active_id) {
		$class .= ' current';
	}
	
	if (in_array($item->id, $path)) {
		$class .= ' active';
	}
	elseif ($item->type == 'alias') {
		$aliasToId = $item->params->get('aliasoptions');
		if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path)) {
			$class .= ' alias-parent-active';
		}
	}

	if ($item->deeper) {
		$class .= ' deeper dropdown';
		$id = 'dmenu'.$dropdown_number;
		$dropdown_number++;
		$item->type = 'dropdown';
	}

	if ($item->parent) {
		$class .= ' parent';
	}

	if ($item->type == 'separator')
	{
		$class .= ' separator';
	}


	if (!empty($class)) {
		$class = ' class="'.trim($class) .'"';
	}

	echo '<li'.$class.'>';
	
	
	// Render the menu item.
	switch ($item->type) :
		case 'separator':
			require JModuleHelper::getLayoutPath('mod_menu', 'dropdown_'.$item->type);
			break;		
		case 'url':
		case 'component':
			require JModuleHelper::getLayoutPath('mod_menu', 'default_'.$item->type);
			break;
		case 'dropdown':
?>
			<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="<?php echo $item->flink;?>">
			<?php echo $item->title;?>
			<b class="caret"></b>
			</a>
<?php 			
			break;
		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul class="dropdown-menu">';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>