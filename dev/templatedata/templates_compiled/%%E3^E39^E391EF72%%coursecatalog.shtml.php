<?php /* Smarty version 2.6.6-dev-2, created on 2010-04-24 05:02:54
         compiled from coursecatalog.shtml */ ?>


<form action="<?php echo $this->_tpl_vars['searchform']->action; ?>
" name="<?php echo $this->_tpl_vars['searchform']->name; ?>
" method="post">

	<table cellpadding="0" cellspacing="0">
		<tr>
			<?php if (count($_from = (array)$this->_tpl_vars['searchform']->fields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
				<td style="border:1px solid black">
					<span class="normaltext">
						<?php echo $this->_tpl_vars['field']->label; ?>
 
					</span>
				</td>
			<?php endforeach; unset($_from); endif; ?>
		</tr>
		<tr>
			<?php if (count($_from = (array)$this->_tpl_vars['searchform']->fields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
				<td style="border:1px solid black">
					<input type="<?php echo $this->_tpl_vars['field']->type; ?>
" name="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" size="<?php echo $this->_tpl_vars['field']->size; ?>
" maxlength="<?php echo $this->_tpl_vars['field']->maxlength; ?>
">
				</td>
			<?php endforeach; unset($_from); endif; ?>
		</tr>
	</table>

	<?php if (count($_from = (array)$this->_tpl_vars['searchform']->hiddenFields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<input type="hidden" name="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
">
	<?php endforeach; unset($_from); endif; ?>
	
	<input type="submit" value="<?php echo $this->_tpl_vars['searchform']->submitString; ?>
">
</form>







<table>
	<tr>
		<td class="normaltext" style="border:1px solid black">CourseID</td>
		<td class="normaltext" style="border:1px solid black">Status</td>
		<td class="normaltext" style="border:1px solid black">Department</td>
		<td class="normaltext" style="border:1px solid black">Number</td>
		<td class="normaltext" style="border:1px solid black">Component</td>
		<td class="normaltext" style="border:1px solid black">Section</td>
		<td class="normaltext" style="border:1px solid black">Descr</td>
		<td class="normaltext" style="border:1px solid black">Credits</td>
		<td class="normaltext" style="border:1px solid black">Open Seats</td>
		<td class="normaltext" style="border:1px solid black">Wait#</td>
	</tr>
	<?php if (count($_from = (array)$this->_tpl_vars['courses'])):
    foreach ($_from as $this->_tpl_vars['course']):
?>
	<tr>
		
		<td class="normaltext" style="border:1px solid black" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->courseID; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->status; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->subject; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->number; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->component; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->section; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->desc; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->credits; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->openSeats; ?>
</td>
		<td class="normaltext" style="border:1px solid black"><?php echo $this->_tpl_vars['course']->waitNumber; ?>
</td>
	</tr>
		
	<?php endforeach; unset($_from); endif; ?>
</table>