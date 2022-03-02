<?php if(!defined("PLX_ROOT")) exit; 
   // initialisation variable 
	$var['mnuDisplay'		] =  $plxPlugin->getParam('mnuDisplay'			)=='' ? 0 								: $plxPlugin->getParam('mnuDisplay');
	$var['catDisplay'		] =  $plxPlugin->getParam('catDisplay'			)=='' ? 0 								: $plxPlugin->getParam('catDisplay');
	
     if(!empty($_POST)) {
		$plxPlugin->setParam('mnuDisplay', 				$_POST['mnuDisplay'], 'numeric');
		$plxPlugin->setParam('catDisplay', 				$_POST['catDisplay'], 'numeric');
        $plxPlugin->saveParams();
		header('Location: parametres_plugin.php?p='.$plugin);
        exit;
    }

?>


<link rel="stylesheet" href="<?php echo PLX_PLUGINS.$plugin?>/css/admin.css" type="text/css" media="screen" />
<section>

<form action="parametres_plugin.php?p=<?php echo $plugin ?>" method="post" class="">
	<fieldset class="">
		<p><label for="id_mnuDisplay"><?php echo $plxPlugin->lang('L_MENU_DISPLAY') ?></label><?php plxUtils::printSelect('mnuDisplay',array('1'=>L_YES,'0'=>L_NO),$var['mnuDisplay']); ?></p>
		<p><label for="id_catDisplay"><?php echo $plxPlugin->lang('L_CAT_DISPLAY') ?></label><?php plxUtils::printSelect('catDisplay',array('1'=>L_YES,'0'=>L_NO),$var['catDisplay']); ?></p>
	</fieldset>
	<label ><input type="submit" name="submit"  /></label>
</form>
</section>