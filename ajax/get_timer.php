<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
if($sale = getProductSale($_REQUEST['ID'])){
	$dateAr = downcounter($sale['ACTIVE_TO']);
}
?>
<?if($dateAr):?>
<div class="timer-wrp">
	<div class="timer__ttl">Акция действительна</div>
	<div class="timer timer-<?=$_REQUEST['ID']?>">
		<div class="timer__day"><span><?=$dateAr["DAYS"]?></span><p>дней</p></div> :
		<div class="timer__hour"><span><?=$dateAr["HOURS"]?></span><p>часов</p></div> :
		<div class="timer__minutes"><span><?=$dateAr["MINUTES"]?></span><p>минут</p></div> :
		<div class="timer__seconds"><span><?=$dateAr["SECONDS"]?></span><p>секунд</p></div>
	</div>
</div>
<br>
	<script>
		$('.timer-<?=$_REQUEST["ID"]?>').each(function(){
			var _T = this;
			var ti = setInterval(function(){
				var sec = parseInt($('.timer__seconds span',_T).text()),
					min = parseInt($('.timer__minutes span',_T).text()),
					hour = parseInt($('.timer__hour span',_T).text()),
					day = parseInt($('.timer__day span',_T).text());
				sec--;
				if(sec<0){sec=59;min--}
				if(min<0){min=59;hour--}
				if(hour<0){hour=23;day--}
				if(day<0){sec=0;min=0;hour=0;day=0;clearInterval(ti)}
				if(sec<10){sec='0'+sec}
				if(min<10){min='0'+min}
				if(hour<10){hour='0'+hour}
				$('.timer__seconds span',_T).text(sec);
				$('.timer__minutes span',_T).text(min);
				$('.timer__hour span',_T).text(hour);
				$('.timer__day span',_T).text(day);
			},1000);
		});
	</script>
<?endif;?>