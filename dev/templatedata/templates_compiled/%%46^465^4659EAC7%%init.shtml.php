<?php /* Smarty version 2.6.6-dev-2, created on 2010-06-09 05:24:59
         compiled from elements/maps/init.shtml */ ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/javascripts/msbrowserdetect.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/javascripts/mscoords.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['cfg']['ms_rootpath']['client']; ?>
/javascripts/msmapbrowse.js"></script>

<script type="text/javascript">
	var mapZoomContainerSize = new Coordinate(<?php echo $this->_tpl_vars['zoomW']; ?>
,<?php echo $this->_tpl_vars['zoomH']; ?>
); //gives the size (W/H) of the mapZoomContainer CSS element
</script>


<style type="text/css">
	.mapZoomContainer {
		
		background: black;
		border: 1px solid black;
		width: <?php echo $this->_tpl_vars['zoomW']; ?>
px;
		height: <?php echo $this->_tpl_vars['zoomH']; ?>
px;
		overflow: hidden;
	}

	.mapWaypoint_thumb {
		position: relative;
		left: -4px;
		top: -4px;
		background-image: url("<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/star_thumb.gif");

		width: 8px;
		height: 8px;
	}
	
	.mapWaypoint_small {
		position: relative;
		left: -7px;
		top: -7px;
		background-image: url("<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/star_small.gif");

		width: 15px;
		height: 15px;
	}

	.mapWaypoint_large {
		position: relative;
		left: -12px;
		top: -12px;
		background-image: url("<?php echo $this->_tpl_vars['cfg']['maps']['path']; ?>
/star_large.gif");
		
		width: 25px;
		height: 25px;
	}

	.mapWaypointContainer {
		position: absolute;
		width: 1px;
		height: 1px;
	}
	
	.mapPreviewBox {

		border: 0px solid #0066CC;
		//background: #99CCFF;
		position: relative;

		//z-index: 25;
	}
</style>