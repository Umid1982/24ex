<? if (count($var_news)>0) foreach ($var_news as $one) { ?>

<div style="border:1px solid #555; padding: 10px; margin: 10px;">
<h3><?=$one['n_title'];?></h3>

	<?=$one['n_body'];?>
<p><i><?=$one['n_dt_pub_line'];?></i></p>
</div>

<? } ?>