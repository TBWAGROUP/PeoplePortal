<script src="../../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<div id="TabbedPanelsHead" class="TabbedPanels">

	<ul class="TabbedPanelsTabGroup">
		<li class="TabbedPanelsTab" tabindex="0">
			<div class="tabtype" id="tabtype">Title 1</div>
		</li>
		<li class="TabbedPanelsTab" tabindex="0">
			<div class="tabtype" id="tabtype">Title 2</div>
		</li>
	</ul>  <!-- /Titles Head-->

	<div class="TabbedPanelsContentGroup">

		<div class="TabbedPanelsContent">
			<div id="TabbedPanels1" class="TabbedPanels">

				<ul class="TabbedPanelsTabGroup2">
					<li class="TabbedPanelsTab" tabindex="0">
						<div class="tabtype" id="tabtype">Title 1.1</div>
					</li>
					<li class="TabbedPanelsTab" tabindex="0">
						<div class="tabtype" id="tabtype">Title 1.2</div>
					</li>
				</ul>  <!-- /Subtitles Tab 1.x-->

				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">Content 1.1</div>
					<div class="TabbedPanelsContent">Content 1.2</div>
				</div>  <!-- /ContentGroup Tab 1.x-->

			</div>  <!-- /Panels Tab 1.x-->
		</div>  <!-- /Content Tab 1-->

		<div class="TabbedPanelsContent">
			<div id="TabbedPanels2" class="TabbedPanels">

				<ul class="TabbedPanelsTabGroup2">
					<li class="TabbedPanelsTab" tabindex="0">
						<div class="tabtype" id="tabtype">Title 2.1</div>
					</li>
					<li class="TabbedPanelsTab" tabindex="0">
						<div class="tabtype" id="tabtype">Title 2.2</div>
					</li>
					<li class="TabbedPanelsTab" tabindex="0">
						<div class="tabtype" id="tabtype">Title 2.3</div>
					</li>
				</ul>  <!-- /Subitles Tab 2.x-->

				<div class="TabbedPanelsContentGroup">
					<div class="TabbedPanelsContent">Content 2.1</div>
					<div class="TabbedPanelsContent">Content 2.2</div>
					<div class="TabbedPanelsContent">

						<div class="TabbedPanelsContent">
							<div id="TabbedPanels3" class="TabbedPanels">

								<ul class="TabbedPanelsTabGroup3">
									<li class="TabbedPanelsTab" tabindex="0">
										<div class="tabtype" id="tabtype">Title 2.2.1</div>
									</li>
									<li class="TabbedPanelsTab" tabindex="0">
										<div class="tabtype" id="tabtype">Title 2.2.2</div>
									</li>
									<li class="TabbedPanelsTab" tabindex="0">
										<div class="tabtype" id="tabtype">Title 2.2.3</div>
									</li>
								</ul>  <!-- /Subitles Tab 2.2.x-->

								<div class="TabbedPanelsContentGroup">
									<div class="TabbedPanelsContent">Content 2.2.1</div>
									<div class="TabbedPanelsContent">Content 2.2.2</div>
									<div class="TabbedPanelsContent">Content 2.2.3</div>
								</div>  <!-- /ContentGroup Tab 2.2.x-->

							</div>  <!-- /Panels Tab 2.2.x-->
						</div>  <!-- /Content Tab 2.2-->


					</div>
				</div>  <!-- /ContentGroup Tab 2.x-->

			</div>  <!-- /Panels Tab 2.x-->
		</div>  <!-- /Content Tab 2-->

	</div>  <!-- /ContentGroup Head-->

</div> <!-- /Panels Head-->

<script type="text/javascript">
	var TabbedPanelsHead = new Spry.Widget.TabbedPanels("TabbedPanelsHead");
	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
	var TabbedPanels2 = new Spry.Widget.TabbedPanels("TabbedPanels2");
	var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
</script>