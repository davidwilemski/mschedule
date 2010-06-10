<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:20:20
         compiled from elements/maps/renderpoints.shtml */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup', 'elements/maps/renderpoints.shtml', 11, false),)), $this); ?>

<?php $this->assign('scale', $this->_tpl_vars['map']->{(($_var=$this->_tpl_vars['size']) && substr($_var,0,2)!='__') ? $_var : $this->trigger_error("cannot access property \"$_var\"")}['x']/$this->_tpl_vars['map']->large['x']); ?>

<?php if (count($_from = (array)$this->_tpl_vars['waypoints'])):
    foreach ($_from as $this->_tpl_vars['waypoint']):
?>

	<?php $this->assign('locx', $this->_tpl_vars['scale']*$this->_tpl_vars['waypoint']->x); ?>
	<?php $this->assign('locy', $this->_tpl_vars['scale']*$this->_tpl_vars['waypoint']->y); ?>

	<?php $this->assign('htmlPopup', $this->_tpl_vars['waypoint']->popupHTML); ?>
	<?php $this->assign('htmlPopup', "<span style='font-size: 9px;'>".($this->_tpl_vars['htmlPopup'])."</span>"); ?>
	<div class="mapWaypointContainer" style="left: <?php echo $this->_tpl_vars['locx']; ?>
; top: <?php echo $this->_tpl_vars['locy']; ?>
;" <?php echo smarty_function_popup(array('text' => $this->_tpl_vars['htmlPopup'],'bgcolor' => "#666666",'fgcolor' => "#FFCC66",'center' => true,'above' => true), $this);?>
>
		<div class="mapWaypoint_<?php echo $this->_tpl_vars['size']; ?>
"></div>
	</div>
	
<?php endforeach; unset($_from); endif; ?>