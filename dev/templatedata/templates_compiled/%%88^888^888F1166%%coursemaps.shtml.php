<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:24:59
         compiled from coursemaps.shtml */ ?>

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



	
	<?php if ($this->_tpl_vars['showParagraph'] == true): ?>
		<?php echo @constant('_BLOCKTEXT_SEARCH_MAPS'); ?>

		<br><br>
	<?php endif; ?>




<center>
<div class="outerbox" align="center">
	<form name="<?php echo $this->_tpl_vars['form']->name; ?>
" action="<?php echo $this->_tpl_vars['form']->action; ?>
" method="">

		<table>
				<tr>
				<?php if (count($_from = (array)$this->_tpl_vars['form']->fields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
					<td align="right" class="smalltext"><?php echo $this->_tpl_vars['field']->label; ?>
: </td>
					<td class="normaltext"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/formfield.shtml", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
				<?php endforeach; unset($_from); endif; ?>
					<td class="normaltext">
						<?php if (count($_from = (array)$this->_tpl_vars['form']->hiddenFields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/formfieldhidden.shtml", 'smarty_include_vars' => array('field' => $this->_tpl_vars['field'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php endforeach; unset($_from); endif; ?>
						<input type="submit" value="<?php echo $this->_tpl_vars['form']->submitString; ?>
">
					</td>
				</tr>
		</table>
	</form>
</div>
</center>





<table>
	<?php if (count($_from = (array)$this->_tpl_vars['maps'])):
    foreach ($_from as $this->_tpl_vars['map']):
?>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td class="normaltext" style="border: 0px solid black;" valign="top">
			<br>
			<b style="font-size: 18px;"><?php echo $this->_tpl_vars['map']->title; ?>
</b>

			<br>
			
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
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/maps/renderpoints.shtml", 'smarty_include_vars' => array('map' => $this->_tpl_vars['map'],'waypoints' => $this->_tpl_vars['map']->waypoints,'size' => 'thumb')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</div>

			<div class="smalltext" style="width: <?php echo $this->_tpl_vars['map']->thumb['x']; ?>
px;" align="center">
				<i><?php echo @constant('_CLICK_HERE_TO_ZOOM'); ?>
</i>
			</div>
			<br>
			
			<br>
			<span class="smalltext">
				<b><?php echo @constant('_BUILDINGS_FOUND'); ?>
:</b>

								<?php if (count($_from = (array)$this->_tpl_vars['map']->waypoints)):
    foreach ($_from as $this->_tpl_vars['waypoint']):
?>
					<a href="#nothing" style="text-decoration: none; font-weight: normal;" onclick="center_large_onCoords('mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
',<?php echo $this->_tpl_vars['waypoint']->x; ?>
,<?php echo $this->_tpl_vars['waypoint']->y; ?>
,mapZoomContainerSize,'mapThumbPrevBox_<?php echo $this->_tpl_vars['map']->name; ?>
',mapLargeScale_<?php echo $this->_tpl_vars['map']->name; ?>
)">
						<div style="border: 1px solid #ccc; padding: 2px;">
							<?php echo $this->_tpl_vars['waypoint']->label; ?>

						</div>
					</a>
				<?php endforeach; unset($_from); endif; ?>
			</span>
		</td>
		<td valign="top">
			
			<div class="mapZoomContainer">
								<div id="mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
">
										<div class="smalltext" style="z-index: 1; height: 0px; overflow: visible; color: white;">
						<?php echo @constant('_WORD_LOADING'); ?>
...
					</div>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/maps/renderpoints.shtml", 'smarty_include_vars' => array('map' => $this->_tpl_vars['map'],'waypoints' => $this->_tpl_vars['map']->waypoints,'size' => 'large')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</div>
			</div>
		</td>
	</tr>
	

		<script language="javascript">
		center_large_onCoords('mapLarge_<?php echo $this->_tpl_vars['map']->name; ?>
',<?php echo $this->_tpl_vars['map']->waypoints['0']->x; ?>
,<?php echo $this->_tpl_vars['map']->waypoints['0']->y; ?>
,mapZoomContainerSize,'mapThumbPrevBox_<?php echo $this->_tpl_vars['map']->name; ?>
',mapLargeScale_<?php echo $this->_tpl_vars['map']->name; ?>
);
	</script>
	
	<?php endforeach; unset($_from); endif; ?>
</table>
