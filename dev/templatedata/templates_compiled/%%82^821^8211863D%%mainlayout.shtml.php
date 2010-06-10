<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:24:59
         compiled from mainlayout.shtml */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'mainlayout.shtml', 12, false),)), $this); ?>
<html>
<head>
	<title>Mschedule - Campus Building Search</title>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/stylesheets/main.css">
	<?php if ($this->_tpl_vars['cfg']['stopSearchEngineIndexing']): ?>
		<meta name="robots" content="noindex,nofollow,noarchive">
	<?php endif; ?>
</head>
<body>

<?php echo smarty_function_popup_init(array('src' => $this->_tpl_vars['cfg']['smarty']['overlibPath']), $this);?>


<center>

<div style="height: 10px;"><!-- spacer --></div>

<div id="globalcontainer">

	<div id="topbar">
		<!--<a href="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
">--><a href="http://mschedule.com"><img src="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/images/topbar.jpg"></a>
	</div>

	<!--begin login bar-->
<!--
	<div style="width:800px">

		<div id="loginbar" align="right">
			<?php echo $this->_tpl_vars['loginbar']; ?>

		</div>
	
	</div>
-->
	<!--end login bar-->
	
		<div id="secondarycontainer">

			<?php if ($this->_tpl_vars['cfg']['showErrors'] == 'yes' && $this->_tpl_vars['MSERROR']->num > 0): ?>
				<div id="errorbox" align="left">

					<table width="100%">
					<?php if (count($_from = (array)$this->_tpl_vars['MSERROR']->messages)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
						<tr>
							<td width="200"><b><?php echo $this->_tpl_vars['message']['origin']; ?>
</b></td>
							<td><?php echo $this->_tpl_vars['message']['msg']; ?>
</td>
						</tr>
					<?php endforeach; unset($_from); endif; ?>
					</table>
					
				</div>
				
				<br>
			<?php endif; ?>

			<?php if ($this->_tpl_vars['cfg']['showDebug'] == 'yes' && $this->_tpl_vars['MSDEBUG']->num > 0): ?>
				<div id="debugbox" align="left">

					<table width="100%">
					<?php if (count($_from = (array)$this->_tpl_vars['MSDEBUG']->messages)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
						<tr>
							<td width="200"><b><?php echo $this->_tpl_vars['message']['origin']; ?>
</b></td>
							<td><?php echo $this->_tpl_vars['message']['msg']; ?>
</td>
						</tr>
					<?php endforeach; unset($_from); endif; ?>
					</table>
					
				</div>
				
				<br>
			<?php endif; ?>
			
			<table style="width: 100%;">
				<tr>
<!--
					<td style="width: 150px" valign="top">
													
						<?php if (count($_from = (array)$this->_tpl_vars['menuboxes'])):
    foreach ($_from as $this->_tpl_vars['menu']):
?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "elements/menubox.shtml", 'smarty_include_vars' => array('menuvars' => $this->_tpl_vars['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
							<br>
						<?php endforeach; unset($_from); endif; ?>
					</td>
					<td style="width:20px">&nbsp;</td>
-->

					<!--begin content area-->
					<td valign="top">
					
						<center>
							<h2><?php echo $this->_tpl_vars['pagetitle']; ?>
</h2>
							<br>
						</center>
						
						<!--begin general text box-->
						<div class="outerbox">
							<div class="textbox">
							
								<div class="normaltext">
								<?php echo $this->_tpl_vars['content']; ?>

								</div>
								
							</div>
						</div>
						<!--end general text box-->
					</td>
					<!--end content area-->
				</tr>
			</table>
			
		</div>
		</div>
	
	
</div>
</center>
</body>
</html>