<?php /* Smarty version 2.6.6-dev-2, created on 2010-05-07 08:13:07
         compiled from register_start.shtml */ ?>
<h1><?php echo @constant('_THANKSFORCHOOSING'); ?>
</h1>


<br>

<?php echo @constant('_BLOCKTEXT_REGISTERPAGE'); ?>


<form action="<?php echo $this->_tpl_vars['regForm']->action; ?>
" name="<?php echo $this->_tpl_vars['regForm']->name; ?>
" method="post">
	<?php if (count($_from = (array)$this->_tpl_vars['regForm']->fields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
	
		<br>
		
		<i><?php echo $this->_tpl_vars['field']->label; ?>
</i>

		<span class="outerbox" style="padding: 5px;">
			<input type="<?php echo $this->_tpl_vars['field']->type; ?>
" name="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
" size="<?php echo $this->_tpl_vars['field']->size; ?>
" maxlength="<?php echo $this->_tpl_vars['field']->maxlength; ?>
">
		
			<?php if ($this->_tpl_vars['field']->name == 'uniqname'): ?>
				<b>@<?php echo $this->_tpl_vars['cfg']['defaultDomain']; ?>
</b>
			<?php endif; ?>
		</span>
	
	<?php endforeach; unset($_from); endif; ?>

	
	<?php if (count($_from = (array)$this->_tpl_vars['regForm']->hiddenFields)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		<input type="hidden" name="<?php echo $this->_tpl_vars['field']->name; ?>
" value="<?php echo $this->_tpl_vars['field']->value; ?>
">
	<?php endforeach; unset($_from); endif; ?>
	
	<input type="submit" value="<?php echo $this->_tpl_vars['regForm']->submitString; ?>
">
</form>