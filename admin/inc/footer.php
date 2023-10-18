	<?
		//관리자 비밀번호 변경기간 체크
		$pwd_change_check = $cls_member->admin_pwd_check($MEM_ADM['usr_id']);
	?>
	<?if ($pwd_change_check) {?>
		<!-- 레이어팝업 : 비밀번호 변경 -->
		<article class="layer_popup password_change_popup"></article>
	<?} else {?>
		<!-- 레이어팝업 : 관리자 정보 수정 -->
		<article class="layer_popup admin_modify_popup"></article>
	<?}?>

	<!-- footer -->
	<footer class="footer">
		<div class="area">
			<p class="copyright">copyright©<span class="mark"><?=SITE_NAME?></span> All Rights Reserved.</p>
			<p class="ie">본 시스템은 Chrome, 인터넷 익스플로어(IE 11 이상)에 최적화되었습니다.</p>
		</div>
	</footer>
	<!-- //footer -->

	<script type="text/javascript">
		$(function(){
			selectboxInit();
			formSubmitInit();
			autoCompleteInit();

			//검색박스 키보드 한글기본지정
			$("#searchFrm").find("#search_word").css({
				"-webkit-ime-mode": "active",
				"-moz-ime-mode": "active",
				"-ms-ime-mode": "active",
				"ime-mode": "active"
			});
			$("#searchFrm").attr("autocomplete", "off");
			$("#searchFrm").keyup(function(){
				try {
					enters(function(){ searchGo(); })
				} catch(e) {}
			})

			//기본비밀번호 체크
			<?if ($pwd_change_check) {?>
			passwordChangePopup();
			$(".header").find(".btn.modify").attr("onclick","passwordChangePopup();");
			<?}?>
		})

		//Input 자동완성기능 끔
		function autoCompleteInit() {
			$(":input[type=text]").each(function(){
				$(this).attr("autocomplete", "off")
			})
		}

		//FORM 전송시 INPUT BOX 한개 있을경우 자동전송 막기위한 핵
		function formSubmitInit() {
			$("form").each(function(){
				if ($(this).find("input[id=tmpFormHack]").size() == 0) {
					$(this).prepend("<input type='text' id='tmpFormHack' style='display:none' />");
				}
			})
		}

		//셀렉트박스 설정
		function selectboxInit() {
			//custom selectbox
			var $select = $('select');

			for(var i = 0; i < $select.length; i++){
				var idxData = $select.eq(i).find('option:selected').text();
				$select.eq(i).siblings('label').text(idxData);
			}

			$select.change(function(){
				var select_name = $(this).find("option:selected").text();
				$(this).siblings("label").text(select_name);
			});
		}

		<?if ($pwd_change_check) {?>
			//공통 > 비밀번호 변경 팝업
			function passwordChangePopup() {
				AJ.callAjax("../common/password_change_popup.php", null, function(data){
					$(".password_change_popup").html(data);
					commonLayerOpen('password_change_popup');
				}, "html");
			}
		<?} else {?>
			//공통 > 관리자 정보 변경 팝업
			function adminModifyPopup() {
				AJ.callAjax("../common/admin_modify_popup.php", null, function(data){
					$(".admin_modify_popup").html(data);
					commonLayerOpen('admin_modify_popup');
				}, "html");
			}
		<?}?>
	</script>
	<script type="text/javascript" src="/module/js/jquery.form.js"></script>
	<script type="text/javascript" src="/module/js/jquery.tmpl.js"></script>
	<script type="text/javascript" src="/module/js/jquery.moment.js"></script>
	<script type="text/javascript" src="/module/js/fn.util.js"></script>
	<script type="text/javascript" src="/module/js/fn.check.field.js"></script>
</body>
</html>