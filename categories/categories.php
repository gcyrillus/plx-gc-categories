<?php
if(!defined('PLX_ROOT')) {
	die('Are you silly ?');
}

class categories extends plxPlugin {

	public $aCats = array();
	public $okay =false;

	const HOOKS = array(
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
		

	}

	public function plxShowLastCatList($extra = '', $format = '<li id="#cat_id" class="#cat_status" data-mother="#cat_mother"><a href="#cat_url" title="#cat_name">#cat_name</a></li>', $include = '', $exclude = '') {

		echo self::BEGIN_CODE;
?>

    
	#Initialisation de variables
	$okay=false;	       // tant que l'on a pas trouvé de mother="1" 
	$currentCats=array();  // pour ajouter une premiere clé si non initialisé
	$keySearch = array();  // tableau de categorie rechercher
	$mother_Set='000';     // tant que l'on a pas trouvé de categorie mother principale
	$cat_to_set = array(); // stocke les catégorie a affiché
	$cats_found = array(); // stocke toutes les catégories trouvées -- doublon ?
	$sister='';            // stocke ajoute la catégorie au tableau des catégories a afficher
		
	
	#on recherche le mode dans lequel nous sommes
	if (($this->plxMotor->aCats) && ($this->plxMotor->mode !== 'static') || ($this->plxMotor->mode === 'article' )  ) {
		$currentCats = $this->catId(true);
	}
	
	#initialison $currentsCats par défaut pour le mode home
	if ($this->plxMotor->mode === 'home' )  $currentCats[]='';

	
	#en mode article, on recherche la catégorie a mother="1"
	if ((!isset($_GET['preview'])) && ($this->plxMotor->mode === 'article' ) ){
		foreach($currentCats as $art_cat => $cat_moth ) {
			if ($this->plxMotor->aCats[$cat_moth]['mother'] !=="0") {
				$mother_Set=$cat_moth;
			}
		}
	}

	if(($this->plxMotor->mode !=='archives') && ($this->plxMotor->mode !=='tags') ){
		#recherche categorie en cours 
		foreach($currentCats as $catKey) {	
			array_push( $keySearch, $catKey) ;
			$cat_to_set[]=$catKey;
		}
	}


	#nombre de categorie recherchées
	$keySearchCount = count(array_column($keySearch, null));


	#on verifie si c'est une page catègorie et si celle ci est daughterOf .
	if(	$this->plxMotor->mode === 'categorie') {
		if ($this->plxMotor->aCats[ $keySearch[0]]['daughterOf'] != '000'){
			array_push($keySearch,  $this->plxMotor->aCats[ $keySearch[0]]['daughterOf']);
			array_push($cat_to_set, $this->plxMotor->aCats[ $keySearch[0]]['daughterOf']);
		}
	}



	#on regarde si on est en preview, si l'on a plus d'une categorie soeur et on alimente le tableau.
	if((!isset($_GET['preview'])) && ($keySearchCount === 1 )) {
			$sister= $this->plxMotor->aCats[ $keySearch[0]]['daughterOf'];
			$cat_to_set[]=$sister;
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
			if(preg_match("/\b$ask\b/i", $this->plxMotor->aCats[$array_key]['daughterOf'])){
					$cat_to_set[]=$array_key;
					
			#on verifie si l'on veut generé un lien pour la catégorie mére pour le fil d'arianne.
			if (($include === '9999') && ($this->plxMotor->aCats[$array_key]['daughterOf'] !==$keySearch[0])) $include=$this->plxMotor->aCats[$array_key]['daughterOf'];
			}
		}#fin ajout clé
	} #fin de boucle sur les catégories actives
	
	if($this->plxMotor->mode ==='archives') $okay=false;

	#Si l'on a trouvé au moins une categorie mere ont fait le tri de l'affichage dans le menu catégorie.
	if(($okay)&& ($this->plxMotor->mode !== 'static')) {
		$cat_to_remove = array_diff( $cats_found,$cat_to_set);
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
                '#cat_status' => ($this->catId() == 'home') ? 'active' : 'noactive',
                '#art_nb' => '',
            ));
        }

        # On verifie qu'il y a des categories
        if ($this->plxMotor->aCats) {
            $currentCats = $this->catId(true);
            foreach ($this->plxMotor->aCats as $idCatStr => $v) {
                # On vérifie qu'on peut afficher cette catégorie et qu'elle est active
                if (in_array($v['menu'], array('oui', 1)) && $v['active']) {
                    $idCatNum = intval($idCatStr);
                    $pattern = '@\b0*' . $idCatNum . '\b@';
                    if (empty($include) or preg_match($pattern, $include)) {
                        if (empty($exclude) || !preg_match($pattern, $exclude)) {
                            if ($v['articles'] > 0 || $this->plxMotor->aConf['display_empty_cat']) {
                                # on a des articles pour cette catégorie ou on affiche les catégories sans article
                                # On modifie nos motifs
                                echo strtr($format, array(
                                    '#cat_id' => 'cat-' . $idCatNum,
                                    '#cat_url' => $this->plxMotor->urlRewrite('?categorie' . $idCatNum . '/' . $v['url']),
                                    '#cat_name' => plxUtils::strCheck($v['name']),
									'#cat_mother' => plxUtils::strCheck($v['mother']),
                                    '#cat_status' => !empty($currentCats) && in_array($idCatStr, $currentCats) ? 'active' : 'noactive',
                                    '#cat_description' => plxUtils::strCheck($v['description']),
                                    '#art_nb' => $v['articles'],
                                ));
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
	
	#filtrage catégories pour affichage derniers articles dans la side bar 
	public function plxShowLastArtList() {
		echo self::BEGIN_CODE;
?>		
		#on recherche le mode dans lequel nous sommes
	if (($this->plxMotor->aCats) && ($this->plxMotor->mode !== 'static')&& ($this->plxMotor->mode !== 'tags') && ($this->plxMotor->mode !=='home')&& ($this->plxMotor->mode !=='archives') || ($this->plxMotor->mode === 'article' )  || ($this->plxMotor->mode === 'categorie' )  ) {
		$currentCats = $this->catId(true);
		$catIdCount = count(array_column($currentCats, null));
		if (($catIdCount === 1 ) && ($this->plxMotor->aCats[ $currentCats[0]]['mother'] !=="1" )){
			$cat_id = $this->plxMotor->aCats[ $currentCats[0]]['daughterOf'];
			} else {
			foreach($currentCats as $art_cat => $cat_moth ) {
				if ($this->plxMotor->aCats[$cat_moth]['mother'] !=="0") {
				$cat_id=$cat_moth;
				}
			}
		}
	}
<?php

		echo self::END_CODE;		
	}

	
	
	public function plxShowLastArtListContent() {
		echo self::BEGIN_CODE;
?>		

 //code
<?php

		echo self::END_CODE;		
		
	}
	
	
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
		if($filter !=='') {
		
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

	
	#ajout des champs mother et daughterOf dans le tableau  nouvelle catégorie
	public function plxAdminEditCategoriesNew() {
		echo self::BEGIN_CODE;
?>

#si ajout catégorie a partir de la page d'edition d'un article
if($_POST['new_catname']) {
	$this->aCats[$cat_id]['mother'] = '0';
	$this->aCats[$cat_id]['daughterOf'] = '';	
}
#sinon
 else {
	$this->aCats[$content['new_catid']]['mother'    ] = '0';
	$this->aCats[$content['new_catid']]['daughterOf'] = '';
}
<?php
		echo self::END_CODE;
	}

	#recuperation des valeurs mother et daughterOf pour l'edition
	public function plxAdminEditCategoriesUpdate() {
		echo self::BEGIN_CODE;
?>
$this->aCats[$cat_id]['mother'    ] = $content[$cat_id.'_mother'];
$this->aCats[$cat_id]['daughterOf'] = $content[$cat_id.'_daughterOf'];

<?php
		echo self::END_CODE;
	}
	#ajout des attributs mother et daughterOf aux tags <categorie> dans le fichier categories.xml
	public function plxAdminEditCategoriesXml() {
		echo self::BEGIN_CODE;
?>

$mother= $cat['mother'];
$daughterOf= $cat['daughterOf'];
$attr1 = ' mother="'.$mother.'" ';
$attr2 = ' daughterOf="'.$daughterOf.'" ';
$search='<categorie ';
$xml = preg_replace('~(.*)' . preg_quote($search, '~') . '~su', '${1}'.$search.$attr1.$attr2, $xml);

<?php

		echo self::END_CODE;
	}

	#recuperation et ajout des valeurs des attributs mother et daughterOf.
	public function plxMotorGetCategories() {
		echo self::BEGIN_CODE;
?>
$this->aCats[$number]['mother']    =isset($attributes['mother'])     ? $attributes['mother']:'0';
$this->aCats[$number]['daughterOf']=isset($attributes['daughterOf']) ? $attributes['daughterOf']:'';
<?php
		echo self::END_CODE;
	}

	#nettoyage des valeurs de mother et daughterOf
	public function plxAdminEditCategorie() {
		echo self::BEGIN_CODE;
?>
$this->aCats[$content['id']]['mother']     = trim($content['mother']);
$this->aCats[$content['id']]['daughterOf'] = trim($content['daughterOf']);
<?php
		echo self::END_CODE;
	}

	#remplissage des valeur pour le select affichant les categories 'daughterOf', par défaut 'orpheline' à l'affichage
	public function AdminCategoriesTop() {
		echo self::BEGIN_CODE;
?>
#remplissage des select fille
if($plxAdmin->aCats) {
	$MotherArray['000']='orpheline';//defaut
		foreach($plxAdmin->aCats as $key=>$value) {//boucle si il y a des catégories meres.
			if($value['mother']==="1"){
				$MotherArray[$key]=$value['name'];// on rempli le tableau
			}
		}
}
<?php
		echo self::END_CODE;
	}

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

	#remplacement du lien d'edition des categories categories.php par plg_categories.php ou sont ausssi affichés les champs(select) de mother et daughterOf
	public function AdminTopMenus() {
		echo self::BEGIN_CODE;
?>
/*
#remplace la page categories.php par celle du plugin
$search = 'categories.php';
foreach($menus as $k=>$v) {
	if(preg_match("/\b$search\b/i", $v)) {
		$menus[$k] = $v;
		$v = str_replace($search, PLX_ROOT.'plugins/categories/plg_categories.php', $v);
		$menus[$k] = $v;
	}
}


#remplace la page article.php par celle du plugin
$search = 'article.php';
foreach($menus as $k=>$v) {
	if(preg_match("/\b$search\b/i", $v)) {
		$menus[$k] = $v;
		$v = str_replace($search, PLX_ROOT.'plugins/categories/plg_article.php', $v);
		$menus[$k] = $v;
	}
}*/
<?php
		echo self::END_CODE;
	}

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

	public function AdminArticlePrepend() {
		echo self::BEGIN_CODE;
?>
$plgPlugin = $plxAdmin->plxPlugins->aPlugins['<?= __CLASS__ ?>'];
include PLX_PLUGINS . '<?= __CLASS__ ?>/plg_article.php';
exit;
<?php
		echo self::END_CODE;
	}
}
