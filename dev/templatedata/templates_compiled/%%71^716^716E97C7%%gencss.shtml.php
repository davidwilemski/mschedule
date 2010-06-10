<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:20:20
         compiled from elements/maps/gencss.shtml */ ?>

#mapThumb_<?php echo $this->_tpl_vars['map']->name; ?>
 {
	
	background-image: url('<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/<?php echo $this->_tpl_vars['map']->name; ?>
_thumb.gif');
	width: <?php echo $this->_tpl_vars['map']->thumb['x']; ?>
px;
	height: <?php echo $this->_tpl_vars['map']->thumb['y']; ?>
px;
	border: 1px solid black;

	position: relative;
	left: 0px;
	top: 0px;

	z-index: 20;
	overflow: hidden;
}

#mapSmall_<?php echo $this->_tpl_vars['map']->name; ?>
 {
	
	background-image: url('<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/<?php echo $this->_tpl_vars['map']->name; ?>
_small.gif');
	width: <?php echo $this->_tpl_vars['map']->small['x']; ?>
px;
	height: <?php echo $this->_tpl_vars['map']->small['y']; ?>
px;

	position: relative;
	left: 0px;
	top: 0px;
}

#mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
 {

	background: #222;
	background-image: url('<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/<?php echo $this->_tpl_vars['map']->name; ?>
_large.gif');
	width: <?php echo $this->_tpl_vars['map']->large['x']; ?>
px;
	height: <?php echo $this->_tpl_vars['map']->large['y']; ?>
px;

	position: relative;
	left: 0px;
	top: 0px;

	z-index: 10;
}