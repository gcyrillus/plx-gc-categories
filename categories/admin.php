<link rel="stylesheet" href="<?php echo PLX_PLUGINS.$plugin?>/css/admin.css" type="text/css" media="screen" />
<div class="col-2">
	<h1>Welcome for some help.</h1>
	<p>In order to use this plugin, you have first to activate it<p>
	<p>Once activated, to be fully working, the <code>categories.xml</code> file requires  to be updated to add the two new attributes : mother and daughterOf.</p>
	<p>You only need to hit once the button "Change categories list" , nothing will be modified beside filling the two new fields with their defaut values</p>
	<p><strong>A catégorie cannot be at the same time a mother and a daughterOf, <b>DO not select both !</b></strong></p>
	<p><i>You already have read this part and validate  with  <?php plxUtils::printSelect( 'seen',      array('0'=>L_NO ,'1'=>L_YES), $plxPlugin->getParam('seen'),'readonly'); ?></i>.</p>
	<h2> extras</h2>
	<h3>Insert in breadcrumbs</h3>
	<p> You can insert the mother categorie within the breadcrumbs menu within the <code>categorie.php</code> et <code>categorie-full-width.php</code> files of your current theme:<br>
	<pre><code>$plxShow->catList('','&lt;?li>&lt;?a href="#cat_url">#cat_name</a></li>', '9999');</code></pre></p>
	<p>where 9999 will trigger the link to the mother categorie.</p>
	<p>It has to be set in between :
	<pre><code>&lt;li>&lt;a href="&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); ?></a></li>
&lt;li>&lt;?php $plxShow->catName(); ?></li></code></pre></p>
    <p> So it becomes :
	<pre><code>&lt;li>&lt;a href="&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); </a></li>
$plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name</a></li>', '9999');
&lt;li>&lt;?php $plxShow->catName(); ?></li></code></pre>
	</p>
	<h3>Insert in the navbar</h3>
	<p>You can also insert the mother categorie(s) within the main menu within <code>header.php</code> file from your theme:<br>
	<pre><code>$plxShow->catList('','&lt;?li>&lt;?a href="#cat_url">#cat_name</a></li>', '10000');</code></pre></p>
    <p>where 10000 will trigger the link to the mother categorie(s).</p>
	<p>example:<pre><code>&lt;?php $plxShow->staticList($plxShow->getLang('HOME'),'&lt;li class="#static_class #static_status" id="#static_id">&lt;a href="#static_url" title="#static_name">#static_name</a></li>'); ?>
&lt;?php $plxShow->pageBlog('&lt;li class="#page_class #page_status" id="#page_id">&lt;a href="#page_url" title="#page_name">#page_name</a></li>'); ?>
&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name</a></li>', '10000');  ?></code></pre></p>
	<h3>Sidebar, give some style to mother & daughter</h3>
	<p>An attribute and a value will be generated in the menu if it is a mother or a a daughter, you need to update the <code>sidebar.php</code> file template to allow it to print that attribute and its value inside the code </p>
	<p>here is the code to replace (default preformated HTML):<pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status">&lt;a href="#cat_url" title="#cat_name">#cat_name</a> (#art_nb)</li>'); ?>
&lt;/ul></code></pre></p>
	<p> with : <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList(); ?>
&lt;/ul></code></pre></p>
	<p>Preformated code will to the plugin's, so a<code>data-mother</code> attribute is generated with either "0" or "1" for a value.</p>
	<p>It gives you two selectors you can use in the style sheet, the plugin will insert an extra style sheet to your theme with the following code :<pre></code>[data-mother="1"] {font-weight:bold;max-width:max-content;margin:0.25ch 0;}
[data-mother="0"] {margin:0 0 2px 2ch;max-width:max-content;}
.menu.breadcrumb.repertory a {width:auto; /* overwrites themes.css line 139 */}</code></pre></p>
	<hr>
     <p>That's about all what you need to know to fully use this plugin.</p>
	 <p>G.C.</p>
	
	<h1 class="b2">Aide de Bienvenue.</h1>
	<p>Pour pouvoir utiliser ce plugin, vous devez d'abord l'activer.<p>
	<p>Une fois activité, pour fonctionné pleinement, le fichier <code>categories.xml</code> doit être mis à jour en y ajoutant deux nouveau champs: mother et daughterOf.</p>
	<p>Pour cela il vous suffit de cliquez sur le bouton "modifier la liste des categories". Cela ne modifiera pas votre liste mais y ajouteras les deux nouveau champs avec leur valeurs par défaut </p>
	<p><strong>Une catégorie ne peut pas être à la fois mother et daughterOf, alors <b>NE selectionnez pas les deux !</b></strong></p>
	<p><i> Vous avez déjà lu(ou pas) cette partie en validant sa comprehension par  <?php plxUtils::printSelect( 'seen',      array('0'=>L_NO ,'1'=>L_YES), $plxPlugin->getParam('seen'), 'readonly'); ?>.</i></p>
	<h2> Extras</h2>
	<h3>Ajout au fil d'ariane</h3>
	<p>Vous pouvez inserer la categorie mére dans le fil d'arianne avec le code suivant dans les fichiers <code>categorie.php</code> et <code>categorie-full-width.php</code> du theme:<br>
	<pre><code>$plxShow->catList('','&lt;?li>&lt;?a href="#cat_url">#cat_name</a></li>', '9999');</code></pre></p>
	<p>Où  9999 affichera le lien vers la catégorie mére.</p>
	<p>Il faut l'inserer entre  :
	<pre><code>&lt;li>&lt;a href="&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); ?></a></li>
&lt;li>&lt;?php $plxShow->catName(); ?></li></code></pre></p>
    <p> Ce qui devient:
	<pre><code>&lt;li>&lt;a href="&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); </a></li>
$plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name</a></li>', '9999');
&lt;li>&lt;?php $plxShow->catName(); ?></li></code></pre>
	</p>
	<h3>Ajout au menu principal</h3>
	<p>Vous pouvez aussi ajouter les catégories mére au menu principale depuis le fichier<code>header.php</code> du theme:<br>
	<pre><code>$plxShow->catList('','&lt;?li>&lt;?a href="#cat_url">#cat_name</a></li>', '10000');</code></pre></p>
    <p>Où 10000 affichera le(s) lien(s) de categorie(s) mére(s).</p>
	<p>exemple:<pre><code>&lt;?php $plxShow->staticList($plxShow->getLang('HOME'),'&lt;li class="#static_class #static_status" id="#static_id">&lt;a href="#static_url" title="#static_name">#static_name</a></li>'); ?>
&lt;?php $plxShow->pageBlog('&lt;li class="#page_class #page_status" id="#page_id">&lt;a href="#page_url" title="#page_name">#page_name</a></li>'); ?>
&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name</a></li>', '10000');  ?></code></pre></p>

	<h3>Sidebar, donner du style à vos mères et filles.</h3>
	<p>Un attribut et sa valeur seront ajouter dans le menu des catégories, pour cela vous devez modifier le fichier <code>sidebar.php</code> de votre théme.</p>
	<p>voici le code à remplacer (format par défaut):<pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status">&lt;a href="#cat_url" title="#cat_name">#cat_name</a> (#art_nb)</li>'); ?>
&lt;/ul></code></pre></p>
	<p> par : <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList(); ?>
&lt;/ul></code></pre></p>
	<p>Le format par defaut sera remplacer par celui du plugin et un attribut <code>data-mother</code> est générer recevant la valeur "0" ou "1".</p>
	<p>Cela vous donne deux selecteurs utilisables , le plugin ajoute une feuille de style a votre théme avec le code suivant :<pre></code>[data-mother="1"] {font-weight:bold;max-width:max-content;margin:0.25ch 0;}
[data-mother="0"] {margin:0 0 2px 2ch;max-width:max-content;}
.menu.breadcrumb.repertory a {width:auto; /* reset de themes.css ligne 139 */}</code></pre></p>
	<hr>
	<p>C'est a peu prés tout ce que vous devez savoir pour utiliser pleinement ce plugin.</p>
	 <p>G.C.</p>

</div>