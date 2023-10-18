<div class="page_header">
	<h2 class="p_title"><?=iif($pageSubName2!="", $pageSubName2, $pageSubName)?></h2>
	<?if ($pageExplain!="") {?>
		<p class="p_summary"><?=$pageExplain?></p>
	<?}?>
	<div class="breacrumb">
		<a href="javascript:;" class="home"><img src="../images/ico_breadcrumb_home.gif" alt="home" /></a>
		<a href="javascript:;"><?=$pageName?></a>
		<a href="javascript:;" <?if (chkBlank($pageSubName2)) {?>class="now"<?}?>><?=$pageSubName?></a>
		<?if ($pageSubName2!="") {?>
			<a href="javascript:;" class="now"><?=$pageSubName2?></a>
		<?}?>
	</div>
</div>