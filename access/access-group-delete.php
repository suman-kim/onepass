	<?php
		$id = $_POST["id"];
		$choice = $_POST["choice"];
		$job = $_POST["job"];
	?>
	<script src="../public/graindashboard/js/graindashboard.js"></script>
	<script src="../public/graindashboard/js/graindashboard.vendor.js"></script>
	<script src="../public/graindashboard/js/onepass.js"></script>

	<!-- DEMO CHARTS -->
	<script src="../public/demo/resizeSensor.js"></script>
	<script src="../public/demo/chartist.js"></script>
	<script src="../public/demo/chartist-plugin-tooltip.js"></script>
	<script src="../public/demo/gd.chartist-area.js"></script>
	<script src="../public/demo/gd.chartist-bar.js"></script>
	<script src="../public/demo/gd.chartist-donut.js"></script>

	<script>
		var id = '<?=$id?>';
		var job = '<?=$job?>';
		var choice = <?=json_encode($choice)?>;

		if (id == "" && choice == null) {
			alert("삭제할 DATA를 선택하지 않으셨습니다.");
			window.close();
		}
		else{
			access_group_delete();
		}
	</script>
</body>
</html>
