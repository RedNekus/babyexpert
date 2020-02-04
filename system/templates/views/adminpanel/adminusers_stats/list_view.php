<script type="text/javascript" src="/js/admin/adminusers_stats/grid_stats.js"></script>
<script type="text/javascript" src="/js/admin/adminusers_stats/grid_tree.js"></script>

<div>
	
	<div id="tabs-stats">
		

		<div id="left">
			<div class="ui-jqgrid ui-widget ui-widget-content ui-corner-all">	
				<div style="height: 60px;" class="ui-userdata ui-state-default"></div>
				<div class="ui-state-default ui-jqgrid-hdiv" style="width: 100%; height: 22px;"></div>
				<div class="ui-jqgrid-bdiv">
					<ul id="tree_stats" class="filetree"><?php echo $adminusers; ?></ul>	
				</div>
				<div class="ui-state-default ui-jqgrid-pager corner-bottom" style="width: 100%;" dir="ltr"></div>
			</div>
		</div>
		<div id="right" >
			<table id="TableStats"></table>
			<div id="TableStatsPager"></div>	
		</div>	

	</div>
</div>	