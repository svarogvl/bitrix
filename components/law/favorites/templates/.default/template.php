
<div class="yith-wcwl-add-to-wishlist">
	<div class="yith-wcwl-add-button show">
		<?if ($arResult["SELECTED"]){?>
			<a href="<?=$arResult["DEL_URL"]?>"  class="add_to_wishlist"><b>Удалить из избранного</b></a>
		<?}else{?>
			<a href="<?=$arResult["ADD_URL"]?>" class="add_to_wishlist"><b>В избранное</b></a>
		<?}?>
	</div>	
</div>
