<?php /* Smarty version 2.6.6-dev-2, created on 2006-09-05 07:15:28
         compiled from admin/editlocations.shtml */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'admin/editlocations.shtml', 39, false),)), $this); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/maps/init.shtml", 'smarty_include_vars' => array('zoomW' => '400','zoomH' => '300')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
	<?php if (count($_from = (array)$this->_tpl_vars['maps'])):
    foreach ($_from as $this->_tpl_vars['map']):
?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/maps/genjscript.shtml", 'smarty_include_vars' => array('map' => $this->_tpl_vars['map'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endforeach; unset($_from); endif; ?>
</script>




<style type="text/css">
	<?php if (count($_from = (array)$this->_tpl_vars['maps'])):
    foreach ($_from as $this->_tpl_vars['map']):
?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/maps/gencss.shtml", 'smarty_include_vars' => array('map' => $this->_tpl_vars['map'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endforeach; unset($_from); endif; ?>
</style>




<form action="" name="locationEditor" method="post">

	<div class="outerbox" align="center">
	choose a building:
		<select NAME="locName">
			<option value=""></option>
			<?php if (count($_from = (array)$this->_tpl_vars['locations'])):
    foreach ($_from as $this->_tpl_vars['location']):
?>
				
				
				<?php if ($this->_tpl_vars['location']->map): ?>
					<option value="<?php echo $this->_tpl_vars['location']->name; ?>
">
					***DONE***
					[<?php echo ((is_array($_tmp=$this->_tpl_vars['location']->map)) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
] (<?php echo $this->_tpl_vars['location']->x; ?>
,<?php echo $this->_tpl_vars['location']->y; ?>
) :: <?php echo $this->_tpl_vars['location']->name; ?>

				
				<?php else: ?>
					<option value="<?php echo $this->_tpl_vars['location']->name; ?>
" <?php if ($this->_tpl_vars['ALREADYSELECTED'] == false): ?>selected <?php $this->assign('ALREADYSELECTED', true);  endif; ?>>
					<?php echo $this->_tpl_vars['location']->name; ?>

				
				<?php endif; ?>
								
			<?php endforeach; unset($_from); endif; ?>
		</select>

		<br><br>
	</div>
	
		<table>
		<?php if (count($_from = (array)$this->_tpl_vars['maps'])):
    foreach ($_from as $this->_tpl_vars['map']):
?>
		<tr>
			<td class="normaltext" style="border: 0px solid black;" valign="top">
				<br>
				<b><?php echo $this->_tpl_vars['map']->title; ?>
</b>

				<br>
				(<?php echo $this->_tpl_vars['map']->large['x']; ?>
 x <?php echo $this->_tpl_vars['map']->large['y']; ?>
)

				<br><br>
				
				<div id="mapThumb_<?php echo $this->_tpl_vars['map']->name; ?>
" onclick="move_large(event,'mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
',this,mapLargeScale_<?php echo $this->_tpl_vars['map']->name; ?>
,mapZoomContainerSize,'mapThumbPrevBox_<?php echo $this->_tpl_vars['map']->name; ?>
',true)" onmousemove="move_large(event,'mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
',this,mapLargeScale_<?php echo $this->_tpl_vars['map']->name; ?>
,mapZoomContainerSize,'mapThumbPrevBox_<?php echo $this->_tpl_vars['map']->name; ?>
')" onmousedown="move_large_allow()" onmouseup="move_large_disallow()">
					<div id="mapThumbPrevBox_<?php echo $this->_tpl_vars['map']->name; ?>
" class="mapPreviewBox"></div>
				</div>
			</td>
			<td>

				<div class="mapZoomContainer">
					<div id="mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
">
						<input name="<?php echo $this->_tpl_vars['map']->name; ?>
" type="image" src="<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/<?php echo $this->_tpl_vars['map']->name; ?>
_large.<?php echo $this->_tpl_vars['cfg']['maps']['ext']; ?>
">
					</div>
				</div>
			</td>
		</tr>
		<?php endforeach; unset($_from); endif; ?>
	</table>
	
	<br><br>
	
	<div class="outerbox" align="center">
		<input type="submit" name="noMapFound" value="<?php echo @constant('_ADMIN_CLICKHERE_IFNOMAPSMATCH'); ?>
">
	</div>
	
	<input type="hidden" value="<?php echo $this->_tpl_vars['msAction']; ?>
" name="msAction">
</form>