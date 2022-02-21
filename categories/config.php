<?php if(!defined("PLX_ROOT")) exit; 
   // initialisation variable 
	$var['mnuDisplay'		] =  $plxPlugin->getParam('mnuDisplay'			)=='' ? 0 								: $plxPlugin->getParam('mnuDisplay');
	
     if(!empty($_POST)) {
		$plxPlugin->setParam('mnuDisplay', 				$_POST['mnuDisplay'], 'numeric');
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
	</fieldset>
	<label ><input type="submit" name="submit"  /></label>
</form>
</section>