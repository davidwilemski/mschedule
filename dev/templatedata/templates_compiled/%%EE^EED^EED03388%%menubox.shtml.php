<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:24:59
         compiled from elements/menubox.shtml */ ?>

<div class="outerbox">
	<div align="center" class="menubox_title"><?php echo $this->_tpl_vars['menuvars']->title; ?>
</div>
	<div class="menubox">

		<?php if (count($_from = (array)$this->_tpl_vars['menuvars']->linksArray)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
			<?php if ($this->_tpl_vars['link']->class): ?>
				<a href="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/<?php echo $this->_tpl_vars['link']->target; ?>
" class="<?php echo $this->_tpl_vars['link']->class; ?>
"><?php echo $this->_tpl_vars['link']->label; ?>
</a>
			<?php else: ?>
				<a href="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/<?php echo $this->_tpl_vars['link']->target; ?>
"><?php echo $this->_tpl_vars['link']->label; ?>
</a>
			<?php endif; ?>
		<?php endforeach; unset($_from); endif; ?>
			
	</div>
</div>