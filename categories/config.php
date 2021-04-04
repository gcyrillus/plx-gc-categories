<?php if(!defined("PLX_ROOT")) exit; 

     if(!empty($_POST)) {
        $plxPlugin->setParam("seen", $_POST["seen"], "numeric");
        $plxPlugin->saveParams();
		header('Location: parametres_plugin.php?p='.$plugin);
        exit;
    }

?>


<link rel="stylesheet" href="<?php echo PLX_PLUGINS.$plugin?>/css/admin.css" type="text/css" media="screen" />
<div class="col-2">
	<h1>Welcome</h1>
	<p>In order to use this plugin, you have first to activate it<p>
	<p>Once activated, to be fully working, the categories.xml file requires  to be updated to add the two new attributes : mother and daughterOf.</p>
	<p>You only need to hit once the button "Change categories list" , nothing will be modified beside filling the two new fields with their defaut values</p>
	<p><strong>A catégorie cannot be at the same time a mother and a daughterOf, do not select both !</strong></p>

	<h1 class="b2">Bienvenue</h1>
	<p>Pour pouvoir utiliser ce plugin, vous devez d'abord l'activer.<p>
	<p>Une fois activité, pour fonctionné pleinement, le fichier categories.xml doit être mis à jour en y ajoutant deux nouveau champs: mother et daughterOf.</p>
	<p>Pour cela il vous suffit de cliquez sur le bouton "modifier la liste des categories". Cela ne modifiera pas votre liste mais y ajouteras les deux nouveau champs avec leur valeurs par défaut </p>
	<p><strong>Une catégorie ne peut pas être à la fois mother et daughterOf, alors ne selectionnez pas les deux !</strong></p>

<form action="parametres_plugin.php?p=<?php echo $plugin ?>" method="post" class="categoriesPLG c2 col-2 p-1">
	<fieldset class="">
		<label>Did I understood ? / Ai je compris ? </label> 	
		<?php plxUtils::printSelect( 'seen',      array('0'=>L_NO ,'1'=>L_YES), $plxPlugin->getParam('seen')); ?>
	</fieldset>
	<label class="b2 m-auto"><input type="submit" name="submit"  /></label>
</form>
</div>