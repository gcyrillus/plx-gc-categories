<?php
if(!defined('PLX_ROOT')) {
	die('oups, says v3.5.8.10');
}

class categories extends plxPlugin {

	public $aCats = array();
	public $okay =false;	

	const HOOKS = array(
		'plxShowPluginsCss',
		'plxShowLastCatList',
		'plxShowLastArtList',
		'plxShowTagList',
		'plxShowLastArtListContent',
		'plxMotorGetCategories',
		'plxAdminEditCategoriesNew',
		'plxAdminEditCategoriesUpdate',
		'plxAdminEditCategoriesXml',
		'plxAdminEditCategorie',
		'AdminCategoryTop',
		'AdminCategoriesTop',
		'AdminCategory',
		'AdminCategoriesPrepend',
		'AdminArticlePrepend',
		'plxShowStaticListEnd',		
		'ThemeEndHead',
	);
	const BEGIN_CODE = '<?php' . PHP_EOL;
	const END_CODE = PHP_EOL . '?>';


	public function __construct($default_lang) {
		# appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);


		# Ajoute des hooks
		foreach(self::HOOKS as $hook) {
			$this->addHook($hook, $hook);
		}
			
		# droits pour accèder à la page config.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);	
		
		# limite l'accès à l'écran d'administration du plugin
        $this->setAdminProfil(PROFIL_ADMIN);
        //$this->setAdminMenu( ''. $this->getLang("L_PLUGINS_HELP") .' '. $this->getLang("L_MENU_CATEGORIES").''  , 7,  ''.$this->getLang("L_PLUGINS_HELP_TITLE").'');	

	}
	
		
	
	/**
	 * Méthode qui ajoute le fichier site.css au théme 
	 *
	 * @complete la fonction native qui va le chercher ailleurs
	 * @author	gcyrillus
	 **/	
	public function plxShowPluginsCss() {
		echo self::BEGIN_CODE;
?>
			echo '	<link rel="stylesheet" href="'.PLX_ROOT.'plugins/categories/css/site.css?v=3.1.5" type="text/css" media="screen" />'.PHP_EOL;
<?php
		echo self::END_CODE;	
	}


	/**
	 * Méthode qui recherche si il y a une catégorie mére et tri les articles a afficher
	 *
	 * @remplace la fonction native de pluxml
	 * @author	gcyrillus
	 **/
	public function plxShowLastCatList($extra = '', $format = '<li id="#cat_id" class="#cat_status" data-mother="#cat_mother" data-daughter="#data_daughter"><a href="#cat_url" title="#cat_name">#cat_name</a> <span> (#art_nb)</span></li>'.PHP_EOL,  $include = '', $exclude = '') {
		# liste initiale des catégories disponible
		global $plxMotor;
		$plxMotor->getCategories(path('XMLFILE_CATEGORIES'));
		$AllMyCats =  addslashes(json_encode($plxMotor->aCats, JSON_FORCE_OBJECT));		
		echo self::BEGIN_CODE;
	?>

		
		#Initialisation de variables
		$okay=false;	       		// tant que l'on a pas trouvé de mother="1" 
		$currentCats=array();  		// pour ajouter une premiere clé si non initialisé
		$keySearch = array();  		// tableau de categorie rechercher
		$mother_Set='';     		// tant que l'on a pas trouvé de categorie mother principale
		$cat_to_set = array(); 		// stocke les catégorie a affiché
		$cats_found = array(); 		// stocke toutes les catégories trouvées -- doublon ?
		$cat_to_remove = array();	// resultat cats to remove
		$sister= array();			// stocke/ajoute la catégorie au tableau des catégories a afficher

		if ($format =='<li id="#cat_id"><a class="#cat_status" href="#cat_url" title="#cat_name">#cat_name</a></li>') {$format= '<li id="#cat_id" class="#cat_status" data-mother="#cat_mother" data-daughter="#data_daughter"><a href="#cat_url" title="#cat_name">#cat_name</a> </li>'.PHP_EOL;}
		if($format == '<li id="#cat_id"><a class="#cat_status" href="#cat_url" title="#cat_name">#cat_name</a> (#art_nb)</li>'){$format= '<li id="#cat_id" class="#cat_status" data-mother="#cat_mother" data-daughter="#data_daughter"><a href="#cat_url" title="#cat_name">#cat_name</a> (#art_nb)</li>'.PHP_EOL;}
		$json_str =stripslashes('<?= $AllMyCats ?>');
		$AllOMyCats = json_decode($json_str, TRUE);
		
		#on recherche le mode dans lequel nous sommes
		$modeFound='tags';// valeur par défaut arbitraire
		if($this->plxMotor->mode =='home') {$modeFound='home';}
		
		#on recherche si l'on est dans un mode géneré par un plugin
		foreach($this->plxMotor->plxPlugins->aPlugins as $plugtofind => $arr){			
			if($this->plxMotor->plxPlugins->aPlugins[$plugtofind]->getParam('url') != null && $this->plxMotor->mode == $this->plxMotor->plxPlugins->aPlugins[$plugtofind]->getParam('url') ) {$modeFound = $this->plxMotor->plxPlugins->aPlugins[$plugtofind]->getParam('url');}
			if( $this->plxMotor->mode == $plugtofind ) {$modeFound = $plugtofind;}			
		}	
		
		#alimentation tableau categories sur 3 niveaux
		foreach( $AllOMyCats as $catKey => $catValue){
			$checkmother=array();// demie-soeur
			if($this->plxMotor->aCats[$catKey]['mother'] =='1'){
				$catMothers[]=$catKey;
				$lastMother = $catKey;
			}
			//if($this->plxMotor->aCats[$catKey]['daughterOf'] !=='000'  &&  $this->plxMotor->aCats[$this->plxMotor->cible]['articles'] !== 0 ){			
			if($this->plxMotor->aCats[$catKey]['daughterOf'] !=='000'  &&  $this->plxMotor->aCats[$catKey]['articles'] !== 0 ){
				$catdaughters[]=$catKey;
				
				# test si 3eme niveau				
				if(isset($this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']])   ) {
				if($this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']]['mother'] !=='1' ) {
					if($this->plxMotor->mode !=='home' && !isset($_GET['preview']) && $this->plxMotor->mode !=='static' && $this->plxMotor->mode !==$modeFound && isset($this->catId(true)[0]) ){
						$checkmother[]=$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf'];
						if(isset($this->plxMotor->aCats[$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf']]['daughterOf']) &&$this->plxMotor->aCats[$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf']]['daughterOf'] == $this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']]['daughterOf'] && $this->plxMotor->aCats[$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf']]['daughterOf'] === $lastMother ||$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf'] == $this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']]['daughterOf']   ){
							$cat_to_set[]=$catKey;	
						}	
					$checkmother[]=$this->plxMotor->aCats[$this->catId(true)[0]]['daughterOf'];	

					}							
					$checkmother[]=$this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']]['daughterOf'];
					
					if(in_array($this->catId(true)[0],$checkmother)) {
						$cat_to_set[]=$catKey;  
					}	
				}
				}
			}
		}
				
		#on recherche le mode dans lequel nous sommes
		if (($this->plxMotor->aCats) && ($this->plxMotor->mode !== 'static') || ($this->plxMotor->mode === 'article' )  ) {
		if (version_compare(PLX_VERSION, '5.8.5', '<')) {
			$currentCats=  $this->plxMotor->activeCats ;
			}
			else  {
				$currentCats = $this->catId(true);
			}
		}

		
		#initialison $currentsCats par défaut pour le mode home et preview 
		if ($this->plxMotor->mode === 'home' || (isset($_GET['preview'])))  $currentCats[]='';
	    if (version_compare(PLX_VERSION, '5.8.5', '<')) $currentCats=$this->plxMotor->activeCats ;

		
		#en mode article, on recherche la catégorie a mother="1"
		if ((!isset($_GET['preview'])) && ($this->plxMotor->mode === 'article' ) ){
		if (version_compare(PLX_VERSION, '5.8.5', '<')) { 
			$currentCats=explode('|',$currentCats);
			$currentCats = array_diff($currentCats, '000' );
			$this->currentCats = $currentCats;
		}
			foreach($currentCats as $art_cat => $cat_moth ) {
				if(($this->plxMotor->aCats[$cat_moth]['mother'] !=="0")) {
					$mother_Set=$cat_moth;
				}
				if(isset($this->plxMotor->aCats[$this->plxMotor->aCats[$cat_moth]['daughterOf'] ]) && $this->plxMotor->aCats[$this->plxMotor->aCats[$cat_moth]['daughterOf']]['daughterOf'] !=='000') { 
					if($this->plxMotor->aCats[$this->plxMotor->aCats[$this->plxMotor->aCats[$cat_moth]['daughterOf']]['daughterOf']]['mother'] !=="0") {
						$mother_Set=$this->plxMotor->aCats[$this->plxMotor->aCats[$cat_moth]['daughterOf']]['daughterOf'];
						
					}
				}
			}
		}

		if(($this->plxMotor->mode !='archives')  && ($this->plxMotor->mode !=='static') && ($this->plxMotor->mode !== 'search')  && ($this->plxMotor->mode !==$modeFound )   ){
			#recherche categorie en cours 	
		 
				if (is_array($currentCats) || is_object($currentCats)) {}
				else{
					$currentCats=explode('|',$currentCats);
				}

			foreach($currentCats as $catKey) {				
				array_push( $keySearch, $catKey) ; 
				array_push( $cat_to_set, $catKey);	
				
			}
		}


		#nombre de categorie recherchées
		$keySearchCount = count(array_column($keySearch, null));


		#on verifie si c'est une page catègorie et si celle ci est daughterOf .
		if(	$this->plxMotor->mode === 'categorie') {
			if (version_compare(PLX_VERSION, '5.8.5', '<'))  {
				if ($this->plxMotor->aCats[$this->plxMotor->cible]['daughterOf'] != '000'){
					array_push($keySearch,  $this->plxMotor->aCats[$this->plxMotor->cible]['daughterOf']);
					array_push($cat_to_set, $this->plxMotor->aCats[$this->plxMotor->cible]['daughterOf']);
				}
			}
			else {
				if ($this->plxMotor->aCats[ $keySearch[0]]['daughterOf'] != '000'){
					array_push($keySearch,  $this->plxMotor->aCats[ $keySearch[0]]['daughterOf']);
					if($this->plxMotor->aCats[$this->plxMotor->aCats[ $keySearch[0]]['daughterOf']]['mother']!=='1') {array_push($keySearch,$this->plxMotor->aCats[$this->plxMotor->aCats[ $keySearch[0]]['daughterOf']]['daughterOf'] );}
					array_push($cat_to_set, $this->plxMotor->aCats[ $keySearch[0]]['daughterOf']);

				}

				}
			
		}

		
		#on regarde si on est en preview, si l'on a plus d'une categorie soeur et on alimente le tableau.
		if((!isset($_GET['preview']))  && ($keySearchCount === 1 )  && ($this->plxMotor->mode !==$modeFound )) {
			 if ( isset($this->plxMotor->aCats[$this->plxMotor->cible]['daughterOf']) && $this->plxMotor->aCats[$this->plxMotor->cible]['articles'] > 0) {
				$sister= $this->plxMotor->aCats[ $keySearch[0]]['daughterOf'];				
				$cat_to_set[]=$sister;
			 }
			 

		} else {
				$keySearch[]=$mother_Set;
		}

		#boucle sur les catégories
		foreach(array_keys($this->plxMotor->aCats) as $array_key) {

			#on recherche si l'on a des categorie avec le statut mother a 1 si vrai alors okay est true :)
			if ($this->plxMotor->aCats[$array_key]['mother'] ==='1') {
				$okay=true;
			}

			#préremplissage liste catégories a retirées. nettoyage en fin de script si okay est true.
			$cats_found[]=$array_key;
			
            

			#recherche de valeur de clé correspondant a une valeur de $keySearch  pour alimenter la collection à l'affichage			
			foreach($keySearch as $keytest => $ask ) {				
				if(preg_match("/\b$ask\b/i", $this->plxMotor->aCats[$array_key]['daughterOf']) && isset($currentCats[0]) && $currentCats[0] !='' ){					
						$cat_to_set[]=$array_key;
						#recherche categorie fille non reliée à une catégorie mére
						if(
						!isset($_GET['preview']) 
						&& $this->plxMotor->mode !=='home'  
						&& $this->plxMotor->mode !=='archives'  
						&& $this->plxMotor->mode !=='static' 
						&& $this->plxMotor->mode !== $modeFound		
						&& $this->plxMotor->aCats[$currentCats[0]]['daughterOf'] !='000' 						
						&& isset($this->plxMotor->aCats[$this->plxMotor->aCats[$currentCats[0]]['daughterOf']]) 						
						&&       $this->plxMotor->aCats[$this->plxMotor->aCats[$currentCats[0]]['daughterOf']]['mother'] =='0'
						){							
							$cat_to_set[] = $this->plxMotor->aCats[$this->plxMotor->aCats[$currentCats[0]]['daughterOf']]['daughterOf']; 								
						}			
	
				#on verifie si l'on veut generé un lien pour la catégorie mére pour le fil d'arianne.
				if (($include === '9999') && ($this->plxMotor->aCats[$array_key]['daughterOf'] !==$keySearch[0])) {					
					if($this->plxMotor->aCats[$array_key]['daughterOf'] !=='000' && $array_key !== $this->plxMotor->cible )
						{
						 // ajoute un niveau au fil d'arianne si catégorie de 3eme niveau					
							$include=$this->plxMotor->aCats[$this->plxMotor->cible]['daughterOf'];						
							$include .=' | ' .$this->plxMotor->aCats[$array_key]['daughterOf'];								
						}					
					else{	$include=$this->plxMotor->aCats[$array_key]['daughterOf'];}
					
				}
				}
			}#fin ajout clé
		} #fin de boucle sur les catégories actives
		 
		if($this->plxMotor->mode !=='article' &&  $this->plxMotor->mode !=='categorie' ){
			$catfilter=0;
		}
		else{
			if($this->catId(true) && $this->catId(true)[0] !=='home'){	
				$catfilter=$this->plxMotor->aCats[$this->catId(true)[0]]['articles'];
			}
			else {
				$catfilter=0;// defaut
			}
		}
		if(($this->plxMotor->mode ==='archives') || ($this->plxMotor->mode ==='search') || '<?= $this->getParam('catDisplay') ?>' =="1" || $catfilter === 0  )$okay=false;

		#Si l'on a trouvé au moins une categorie mere ont fait le tri de l'affichage dans le menu catégorie.
		if(($okay)&& ($this->plxMotor->mode !== 'static')  ) {

			$cat_to_remove = array_diff( $cats_found , $cat_to_set);
			foreach($cat_to_remove as $unset) {
			if ($include!=='10000')	unset($this->plxMotor->aCats[$unset]);
			}
		}
		
		#tri catégorie mere pour affichage menu 
		if ($include==='10000') {
			foreach(array_keys($this->plxMotor->aCats) as $array_key) {
				
				#on recherche si l'on a des categorie avec l'attribut mother a 1  
			if ($this->plxMotor->aCats[$array_key]['mother'] ==='1') {
				$mothersMenu[]=$array_key;
			  }
				
			}
					$include = implode(' | ', $mothersMenu);
		}
	 
		# Si on a la variable extra, on affiche un lien vers la page d'accueil (avec $extra comme nom)
		if (!empty($extra)) {

			echo strtr($format, array(
				'#cat_id' => 'cat-home',
				'#cat_url' => $this->plxMotor->urlRewrite(),
				'#cat_name' => plxUtils::strCheck($extra),
				'#cat_mother' => plxUtils::strCheck($mother_Set),
				'#data_daughter' => 'home',
				'#cat_status' => ($this->catId() == 'home') ? 'active' : 'noactive',
				'#art_nb' => '',
			));

		}

		# On verifie qu'il y a des categories
		if ($this->plxMotor->aCats) {
			$currentCats = $this->catId(true);
			$mothCount='0';
			$loops='0';
			foreach ($this->plxMotor->aCats as $idCatStr => $v) {
			$loops++;
				# On vérifie qu'on peut afficher cette catégorie et qu'elle est active
				if (in_array($v['menu'], array('oui', 1)) && $v['active']) {
					$idCatNum = intval($idCatStr);
					$pattern = '@\b0*' . $idCatNum . '\b@';
					if (empty($include) or preg_match($pattern, $include)) {
						if (empty($exclude) || !preg_match($pattern, $exclude)) {
							if ($v['articles'] > 0 || $this->plxMotor->aConf['display_empty_cat']) {
								# on a des articles pour cette catégorie ou on affiche les catégories sans article
								
								#experimental - test brut avec 3eme niveau
								if($this->plxMotor->mode !=='erreur' && isset($this->plxMotor->aCats[$this->plxMotor->aCats[$catKey]['daughterOf']]) && $v['daughterOf'] !=='000' && $AllOMyCats[$v['daughterOf']]['mother'] !="1" ) {$myCatName = '&nbsp;'. plxUtils::strCheck($v['name']);}
								else{
									$myCatName = plxUtils::strCheck($v['name']);
								}
								
										if (version_compare(PLX_VERSION, '5.8.5', '<'))  {
									# On modifie nos motifs
										$name = str_replace('#cat_id','cat-'.$idCatNum,$format);
										$name = str_replace('#cat_url',$this->plxMotor->urlRewrite('?categorie'.$idCatNum.'/'.$v['url']),$name);
										$name = str_replace('#cat_name',$myCatName,$name);
										$name = str_replace('#cat_mother',plxUtils::strCheck($v['mother']),$name);
										$name = str_replace('#data_daughter',plxUtils::strCheck($v['daughterOf']),$name);										
										$name = str_replace('#cat_status',($this->catId()==$idCatStr ? 'active':'noactive'), $name);
										$name = str_replace('#cat_description',plxUtils::strCheck($v['description']),$name);
										$name = str_replace('#art_nb',$v['articles'],$name);
										echo $name;
								} 	else {
								
								
									# On modifie nos motifs
									if($mothCount>0 && $loops !== count($this->plxMotor->aCats) && $v['mother'] =='1' && $include =='' ) {echo '</ul>' . PHP_EOL .'<ul  class="cat-list unstyled-list">';}
									if($v['mother']=='1') {$mothCount++;}									
									echo strtr($format, array(
										'#cat_id' => 'cat-' . $idCatNum,
										'#cat_url' => $this->plxMotor->urlRewrite('?categorie' . $idCatNum . '/' . $v['url']),										
										'#cat_name' => $myCatName,
										'#cat_mother' => plxUtils::strCheck($v['mother']),
										'#data_daughter' => plxUtils::strCheck($v['daughterOf']),
										'#cat_status' => !empty($currentCats) && in_array($idCatStr, $currentCats) ? 'active' : 'noactive',
										'#cat_description' => plxUtils::strCheck($v['description']),
										'#art_nb' => $v['articles'],
									));

								}
							}
						}
					}
				}
			} # Fin du while
		}

		return true;
		
<?php
		echo self::END_CODE;
	}
	
	
	 /**
	 * Méthode qui recupere la(les) categorie(s) mere(s) de l'affichage en cours
	 *
	 * @complete la fonction native
	 * @author	gcyrillus
	 **/
	
	public function plxShowLastArtList() {
			
		echo self::BEGIN_CODE;
?>		
		#on recherche le mode dans lequel nous sommes
		$modeFound='';
		
		if ($this->plxMotor->mode !='article' && $this->plxMotor->mode !='categorie') {$modeFound=$this->plxMotor->mode;}
	if (($this->plxMotor->aCats) && ($this->plxMotor->mode !== $modeFound)    ) 	
	{
		$currentCats = $this->catId(true);

		if (is_array($currentCats) || is_object($currentCats)) {}
		else{
			$currentCats=explode('|',$currentCats);
		}
		if ( version_compare(PLX_VERSION, '5.8.5', '<') )  {
			$currentCats =  $this->plxMotor->activeCats ;
			$currentCats =explode('|',$currentCats);
			$currentCats = array_diff($currentCats, ['000'] ); 
		}

		$currentCats = array_diff($currentCats, ['home'] );							
		$catIdCount = count(array_column($currentCats, null));
		if('<?= $this->getParam('catDisplay') ?>'!="1") {
			if (($catIdCount === 1 ) && ($this->plxMotor->aCats[ $currentCats[0]]['mother'] !=="1")){
				$cat_id .= $this->plxMotor->aCats[ $currentCats[0]]['daughterOf'];
				} else {
				foreach($currentCats as $art_cat => $cat_moth ) {
					if ($this->plxMotor->aCats[$cat_moth]['mother'] !=="0") {
					$cat_id=$cat_moth;
					}
				}
			}
		}
	}
	
<?php
		echo self::END_CODE;		
	}

	
	
	 /**
	 * Méthode qui fait le filtrage des tags a afficher dans la sidebar
	 *
	 * @complete la fonction native en preparant le tableau $this->plxMotor->aTags
	 * @author	gcyrillus
	 **/
	
	#tri des tags selon la page affichée
	public function plxShowTagList() {
		echo self::BEGIN_CODE;
?>		
		#tableau des catégories filtreés
		$newTagList = array();
		#notre filtre
		$filter= array();

		#on boucle sur les catégories
		foreach ($this->plxMotor->aCats as $catNum => $v) {			
			#on recherche la catégorie mother principale
			if ($this->plxMotor->aCats[$catNum]['mother'] !=="0") {
				$filter[]=$catNum;		
				}
		}

		#on verifie que l'on a bien un filtre, sinon on sort:
		if($filter) {
		
		#on filtre les articles reliée a la categorie $filter
		foreach($filter as $keytest => $ask ) {
			foreach($this->plxMotor->plxGlob_arts->aFiles as $artNum) {
			
				if(preg_match("/\b\,$ask\b/i", $artNum)){   //okay

					$newartNum= preg_match('/(^[0-9]{4})/', $artNum, $match);

					#si trouvé , on recherche si il y a un fichier tag associé
					foreach($this->plxMotor->aTags as  $tagNum => $val) {

						#on alimente notre liste de tag associé
						if ($match[$newartNum] === $tagNum) {
						
							$newTagList[$tagNum]=$this->plxMotor->aTags[$tagNum];									
						} 
					}						
				}
			}
		}		
		#On ecrase la liste des tags avec la nouvelle
		$this->plxMotor->aTags = $newTagList;	
		}
<?php

		echo self::END_CODE;		
	}
	
	 /**
	 * Méthode qui reformate la balise <categorie>
	 *
	 * @ajout des champs mother et daughterOf dans le tableau  nouvelle catégorie
	 * @author	gcyrillus
	 **/	
	public function plxAdminEditCategoriesNew() {
		
		echo self::BEGIN_CODE;
?>

	if(!$_POST['archive']) {//filter plugin LesFables 
		#si ajout catégorie a partir de la page d'edition d'un article
		if($_POST['new_catname']) { // on genere les deux attributs avec leur valeurs par défaut
	
			$this->aCats[$cat_id]['mother'] = '0';
			$this->aCats[$cat_id]['daughterOf'] = '000';	//home

		}
		#sinon
		 else {
			$this->aCats[$content['new_catid']]['mother'    ] = '0';
			$this->aCats[$content['new_catid']]['daughterOf'] = '';
		}
}	

<?php
		echo self::END_CODE;
	}

	 /**
	 * Méthode qui met a jours les deux attributs mother et daughterOf
	 *
	 * @recuperation des valeurs mother et daughterOf pour l'edition
	 * @author	gcyrillus
	 **/
	public function plxAdminEditCategoriesUpdate() {

		echo self::BEGIN_CODE;
?>

		$this->aCats[$cat_id]['mother'    ] = $content[$cat_id.'_mother'];
		$this->aCats[$cat_id]['daughterOf'] = $content[$cat_id.'_daughterOf'];

<?php
		echo self::END_CODE;
	}
	
	 /**
	 * Méthode qui ajoute les deux attributs mother et daughterOf au moment de l'edition d'une categorie
	 *
	 * @ajout des attributs mother et daughterOf aux tags <categorie> dans le fichier categories.xml
	 * @author	gcyrillus
	 **/
	public function plxAdminEditCategoriesXml() {
		
		echo self::BEGIN_CODE;
?>

		$mother= $cat['mother'];
		$daughterOf= $cat['daughterOf'];
		$attr1 = ' mother="'.$mother.'" ';
		$attr2 = ' daughterOf="'.$daughterOf.'" ';
		$search='<categorie ';
		/*if ( version_compare(PLX_VERSION, '5.8.10', '<') )  { // no longer needed as of 08/2023
		$xml = preg_replace('~(.*)' . preg_quote($search, '~') . '~su', '${1}'.$search.$attr1.$attr2, $xml);
		}
		else {*/
		echo"		<mother>$mother</mother>
		<daughterOf>$daughterOf</daughterOf>".PHP_EOL;
		/*}*/
<?php
		echo self::END_CODE;
	}

	 /**
	 * Méthode qui recupere ou ajoute les valeurs par défaut des attributs mother et daughterOf
	 *
	 * @recuperation et ajout des valeurs des attributs mother et daughterOf.
	 * @author	gcyrillus
	 **/
	public function plxMotorGetCategories() {

		echo self::BEGIN_CODE;
?>
		/*if ( version_compare(PLX_VERSION, '5.8.10', '<') )  { //  no longer needed as of 08/2023
			$this->aCats[$number]['mother']    =isset($attributes['mother'])     ? $attributes['mother']:'0';
			$this->aCats[$number]['daughterOf']=isset($attributes['daughterOf']) ? $attributes['daughterOf']:'000';
		}else {*/
		# Récupération des statuts mother et daughterOf
			$mother = plxUtils::getValue($iTags['mother'][$i]);
			$this->aCats[$number]['mother']=plxUtils::getValue($values[$mother]['value']);
			$daughterOf = plxUtils::getValue($iTags['daughterOf'][$i]);
			$this->aCats[$number]['daughterOf']=plxUtils::getValue($values[$daughterOf]['value']);
		/*}*/
		
<?php
		echo self::END_CODE;
	}
	
	 /**
	 * nettoyage des attributs mother et daughterOf
	 *
	 * @complete la fonction native
	 **/
	public function plxAdminEditCategorie() {
		
		echo self::BEGIN_CODE;
?>
		$this->aCats[$content['id']]['mother']     = trim($content['mother']);
		$this->aCats[$content['id']]['daughterOf'] = trim($content['daughterOf']);
<?php
		echo self::END_CODE;
	}
	
	 /**
	 * Méthode qui remplie les valeurs pour le select affichant les categories 'daughterOf', par défaut 'orpheline' à l'affichage
	 *
	 * @recuperation et ajout des valeurs des attributs mother et daughterOf.
	 * @complement fonction native
	 * @author	gcyrillus
	 **/
	public function AdminCategoriesTop() {
		
		echo self::BEGIN_CODE;
?>
#remplissage des select fille
		$cssSelector=array();
		if($plxAdmin->aCats) {
			$MotherArray['000']='orpheline';//defaut - home
				foreach($plxAdmin->aCats as $key=>$value) {//boucle si il y a des catégories meres.
					if($value['mother']==="1"){
						$MotherArray[$key]=$value['name'];// on rempli le tableau
					}
					if($value['daughterOf'] != true) $value['daughterOf']="000";
					if($value['daughterOf']!=="000" && $plxAdmin->aCats[$value['daughterOf']]['mother']==="1"){
						$MotherArray[$key]=$value['name'];// on rempli le tableau
						$cssSelector[]='option[value="'.$key.'"] ';
					}
				}
				
				
				
		
		echo '<style>[id*="daughterOf"] option{font-weight:bold;}
		'. implode(',',$cssSelector) .'{
			font-size:0.8em;
			color:#333;
			font-weight:normal;
		}</style>
		 <script>(function () {
			  window.onload =  function(){ 
				for (let e of document.querySelectorAll(\''. implode(',',$cssSelector) .'\')) {
					 var text= e.textContent;
					  e.textContent = " - "+ text;
					}
			  }
		})();
		 </script>';			
		}
		
<?php
		echo self::END_CODE;
	}

	 /**
	 * Méthode qui ajoute les deux champs mother et daughterOf dans la page d'edition d'une catégorie
	 *
	 * @recuperation et ajout des valeurs des attributs mother et daughterOf pour l'edition.
	 * @author	gcyrillus
	 **/
	public function AdminCategoryTop() {
		
		echo self::BEGIN_CODE;
?>
		echo '		<div style="display:none">';
		 plxUtils::printSelect('mother',array('1'=>L_YES,'0'=>L_NO), $plxAdmin->aCats[$id]['mother']);
		echo '<br>'; 
		 plxUtils::printInput('daughterOf',plxUtils::strCheck($plxAdmin->aCats[$id]['daughterOf']),'text','255-255',false,'full-width'); 
		echo'</div>';
		
<?php
		echo self::END_CODE;		
	}


	 /**
	 * Méthode qui fait la mise a jour du renvoi vers la page plg_categories.php au lieu de categories.php si le plugin est actif
	 *
	 * @author	bazooka07
	 **/
	#mise a jour du renvoi vers la page plg_categories.php au lieu de categories.php si le plugin est actif
	public function AdminCategoriesPrepend() {
		
		echo self::BEGIN_CODE;
?>
		$plgPlugin = $plxAdmin->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
		include PLX_PLUGINS . '<?= __CLASS__ ?>/plg_categories.php';
		exit;
<?php
		echo self::END_CODE;
	}

	 /**
	 * Méthode qui fait la mise a jour du renvoi vers la page plg_article.php au lieu de article.php si le plugin est actif
	 *
	 * @author	bazooka07
	 **/
	public function AdminArticlePrepend() {
		echo self::BEGIN_CODE;
?>
		$plgPlugin = $plxAdmin->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
		include PLX_PLUGINS . '<?= __CLASS__ ?>/plg_article.php';
		exit;
<?php
		echo self::END_CODE;
	}
	
	
	
	
public function plxShowStaticListEnd() {
    # ajout au menu en tant que groupe
    if($this->getParam('mnuDisplay')) {
		$menuDisplay='okay';
	}
        echo '<?php' . PHP_EOL;
?>
		if ( stristr($format, '#no_static' ) || '<?= $menuDisplay ?>' =='okay'){
		# Injection de code par le plugin  '<?= __CLASS__  ?>'	
 		// parcours catégories , recherche statut mère/fille et création de tableau.
		
		foreach(array_keys($this->plxMotor->aCats) as $array_key) {
			#on recherche si l'on a des categorie avec l'attribut mother a 1  et l'on crée une entrée
			
			if ($this->plxMotor->aCats[$array_key]['mother'] =='1') {
				$catGroup[$array_key][]=$this->plxMotor->aCats[$array_key]['name'];
			  }
			#on recherche si l'on a des categorie filles , on la class dans la clé de sa catégories mere.
			if ($this->plxMotor->aCats[$array_key]['daughterOf'] !='000' && $this->plxMotor->aCats[$array_key]['active'] =='1') {
				$catGroup[$this->plxMotor->aCats[$array_key]['daughterOf']][] = $array_key;
			  }	

			if (isset($this->plxMotor->aCats[$this->plxMotor->aCats[$array_key]['daughterOf']]['daughterOf']) &&$this->plxMotor->aCats[$this->plxMotor->aCats[$array_key]['daughterOf']]['daughterOf'] !='000' && $this->plxMotor->aCats[$this->plxMotor->aCats[$array_key]['daughterOf']]['active'] =='1') {
				$this->plxMotor->aCats[$array_key]['name'] = chr(032).' '.chr(032).' '.chr(055).' '.$this->plxMotor->aCats[$array_key]['name'];
				$catGroup[$this->plxMotor->aCats[$this->plxMotor->aCats[$array_key]['daughterOf']]['daughterOf']][] = $array_key;
			  }							  
		}



		if (stristr($format, '#no_static')){
			$menus=array();
			$format = str_replace('#no_static','',$format);	
		}




		 $catGroup_active = "";
        if ($catGroup ) {

            foreach ($catGroup  as $k => $v) {
				$i='0';
				if(count($v)>1) {
					//usort($v);		
					foreach ($v as $num => $name) {	
						if( $i =='0') { $catGroupName = $name; }// nom de groupe/catégorie mère
						if ( $i>0 && $this->plxMotor->aCats[$name]['active'] == 1 and $this->plxMotor->aCats[$name]['menu'] == 'oui') {
							// on genere les liens à partir de $format	
							$cat = str_replace('#static_id', 'categorie-' . intval($name), $format);
							$cat = str_replace('#static_class', 'categorie menu', $cat);
							$cat = str_replace('#static_url', $this->plxMotor->urlRewrite('?categorie' . intval($name) . '/' .$this->plxMotor->aCats[$name]['url']), $cat);
							$cat = str_replace('#static_name', plxUtils::strCheck($this->plxMotor->aCats[$name]['name']), $cat);
							$cat = str_replace('#static_status', ($this->catId() == $name ? 'active' : 'noactive'), $cat);
							//ajout au groupe
							$menus[$catGroupName][] = $cat;
							}
					$i++;				
					}		
				}           
							
        	}	
		}
		}			


		
<?php
        echo PHP_EOL . '?>';
    


}	
	
	 /**
	 * Méthode qui injecte une feuille de style pour filtrée les catégories  d'un article
	 *
	 * Sont exclues à l'affichage les catégories mère
	 * Ne sont affichée que les catégories filles auxquelles l'article est rattaché.
	 *
	 **/
		
public function ThemeEndHead() {	
?>
		<!-- <?= __CLASS__ ?> plugin -->
		<style type="text/css">
		<?php
	// parcours catégories , recherche statut mère/fille et création de tableau
	global $plxShow;
		foreach(array_keys($plxShow->plxMotor->aCats) as $array_key) {
			#on recherche si l'on a des categorie avec l'attribut mother a 1  et l'on crée une entrée
			if ($plxShow->plxMotor->aCats[$array_key]['mother'] =='1') {
				$mothersGroup[$array_key][]=$plxShow->plxMotor->aCats[$array_key]['name'];
				echo '.classified-in a[href*="categorie'.intval($array_key).'"], article a[href*="categorie'.intval($array_key).'"] {display:none;}
				' ;
			  }
		}
		echo '.classified-in  a[href*="categorie"] + a:before{ content:\', \';color:gray;}';		?>
		</style>

			<?php
}		
	
	
}
