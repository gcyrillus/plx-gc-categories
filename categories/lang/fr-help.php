<section id=help>
<style>#help,img {box-sizing:border-box;  max-width:900px; margin:0 auto 1.6em;  padding:0 3rem;}img {display:block;margin:0 auto 1.6em;max-width:90%;}#help :is(h1,h2,h3,h4,h5,h6) {text-indent:-2.5rem;color: brown}</style>
<h1>Aide du plugin <b>PLX_GC_CATEGORIES</b></h1>
<h2>Les configurations d'affichages</h2>
<p>Il n'y a pas de réelle page de configuration, la seule configuration actuelle est   une option d'affichage des catégories méres en onglet déroulant dans le menu principal, similaire au principe de <i>groupe de pages statiques</i>.</p>
<p>le fonctionnement générale du plugin est déjà configuré, il faut cependant mettre à jour les fonctions d'affichages dans vos thèmes pour accéder aux possibilités d'affichages que procure ce plugin.</p>

<p>Les fichiers du thème en cours sont éditables depuis l’administration du site. Accessible sur le menu à l'onglet <i>Paramètres</i> puis <i>Thème</i> et le bouton "Éditer les fichiers du thème".
<img src="/plugins/categories/img/page-theme.jpg" alt="page administration des thème">
     Une liste déroulante s'affiche vous permettant de sélectionner le fichier à modifier.
<img src="/plugins/categories/img/edit-theme.jpg" alt="page édition des thèmes">
    <p>
        Il est cependant préférable de faire une copie de votre théme (tout le repertoire) avant d'effectuer des modifications. Dans le cas d'une mauvaise manipulation ou d'un résultat qui vous déplait, votre copie vous sera de bon secours. Il faut parfois quelques essais et un peu de temps avant de valider completement une modification.
</p>
<h3>
    Modifications recommandées pour un thème classique.
</h3>
	<h4>Ajout au fil d'Ariane</h4>
	<p>Vous pouvez insérer la catégorie mère dans le fil d'Ariane avec le code suivant dans les fichiers <code>categorie.php</code> et <code>categorie-full-width.php</code> du theme:</p>
	<pre><code>&lt;?php  $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '9999'); ?></code></pre>
	<p>Où  9999 affichera le lien vers la catégorie mère.<b>si le plugin est désactivé, rien ne s'affichera.</b></p>
	<p>Il faut l'inserer entre  :</p>
	<pre><code>&lt;li>&lt;a href = "&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); ?>&lt;/a>&lt;/li>
&lt;li>&lt;?php $plxShow->catName(); ?>&lt;/li></code></pre>
    <p> Ce qui devient:</p>
	<pre><code>&lt;li>&lt;a href = "&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); &lt;/a>&lt;/li>
&lt;?php $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '9999'); ?&gt;
&lt;li>&lt;?php $plxShow->catName(); ?>&lt;/li></code></pre>
	
	<h4>Ajout au menu principal</h4>
  <p>2 options se présente à vous et il est conseillé d'en utiliser une seule.</p>
  <p><b>Une premiere option</b> en passant par la configuration vous permet d'ajouter automatiquement au menu principale les catégories mère avec un sous menu déroulant où sont placés les liens vers les catégories filles</p>
  <p><b>une seconde option </b>Vous pouvez aussi ajouter les liens des catégories méres au menu principale depuis le fichier<code>header.php</code> du theme, cette option ne genere pas de sous menus:</p>
	<pre><code>&lt;?php $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000'); ?></code></pre>
    <p>Où 10000 affichera le(s) lien(s) de catégorie(s) mére(s),<b>si le plugin est désactivé, rien ne s'affichera.</b></p>
	<p>exemple:</p><pre><code>&lt;?php $plxShow->staticList($plxShow->getLang('HOME'),'&lt;li class="#static_class #static_status" id="#static_id">&lt;a href="#static_url" title="#static_name">#static_name&lt;/a>&lt;/li>'); ?>

&lt;?php $plxShow->pageBlog('&lt;li class="#page_class #page_status" id="#page_id">&lt;a href="#page_url" title="#page_name">#page_name&lt;/a>&lt;/li>'); ?>

&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000');  ?></code></pre>
<p>Ce code d'affichage utilise et detourne une fonction native de PluXml. Le nombre maximum de catégories que PluXml peut gérer est de 999, en utilisant un chiffre supérieur à 999 , il n'y aura aucune catégorie répondant au critére du filtre, on peut donc utiliser tous les numéros supérieur à 999 sans créer de paradoxe ... ou bugs  
</p>
  <p>Cette fonction native de PluXml vous permet aussi d'exclure , une ou plusieurs catégories mères, par exemple, vous ne voulez pas affiché la catégorie mère qui à le numéro '001' </p>
  <pre><code>&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000', '001');  ?></code></pre>
  <p>Pour cela, le numéro des catégories à exclure peuvent être indiquées en les indiquant dans la fonction aprés le chiffre '10000' à l'aide d'une virgule et entre parenthése le numéro de catégories à exclure, ici nous ajoutons <code>,'001'</code></p><p> pour en exclure plusieurs, il suffit de séparé chaques numéros d'un barre vertical (touches : <kbd> ALT GR + 6 </kbd>), par exemple la 001 et la 004 nous donne <code>, '001 | 004' </code>. Les numéros de catégories doivent être ecrit avec 3 chiffres. exemples : 100, 040 ou 001 </p>

	<h4>Sidebar, identifier visuellement les catégories principales et secondaires..</h4>
	<p>Un attribut avec une valeur reflétant le type de catégorie peut-être ajouter dans le menu de la <i>sidebar</i>, pour cela vous devez modifier le fichier <code>sidebar.php</code> de votre thème.</p>
	<p>voici le code à remplacer (format par défaut):</p><pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status">&lt;a href="#cat_url" title="#cat_name">#cat_name&lt;/a> (#art_nb)&lt;/li>'); ?>
&lt;/ul></code></pre>
	<p> par :</p> <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList(); ?>
&lt;/ul></code></pre>
	<p>Le format par défaut sera remplacer par celui du plugin et un attribut <code>data-mother</code> est générer recevant la valeur "0" ou "1".</p>
<p> Le nouveau format par défaut, générer par le plugin, équivaut à l’écrire comme ceci :</p> <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status" data-mother="#cat_mother">&lt;a href="#cat_url" title="#cat_name">#cat_name&lt;/a> &lt;span> (#art_nb)&lt;/span>&lt;/li>'); ?>
&lt;/ul></code></pre>
	<p>Cela vous donne deux sélecteurs utilisables dans la feuille de style, la difference est l'attribut <code>data-mother</code> inserer dans le format d'origine.Le plugin ajoute aussi une feuille de style a votre thème avec le code suivant :</p><pre><code>[data-mother="1"] {font-weight:bold;max-width:max-content;margin:0.25ch 0;}
[data-mother="0"] {margin:0 0 2px 2ch;max-width:max-content;}
.menu.breadcrumb.repertory a {width:auto; /* reset de themes.css ligne 139 */}</code>
</pre><p>Ces styles sont eux aussi modifiable dans l'administration.
</p>
<p>Si le nouveau format par défaut ne vous convient pas, vous pouvez le réécrire en modifiant sa structure , ajouter un attribut ou y insérer les class que votre thème utilise.<br>Par exemple: pour retirer l'affichage du nombre d'article il suffit d'enlever le <code>span</code> avec son contenu, <code>#art_nb</code> servant lui à afficher nativement le nombre d'article. Les codes précédés d'un <b>#</b> ne doivent pas être modifiés. <code>#cat_url</code> genere le lien de catégorie et <code>#cat_name</code> le nom, ces deux là sont essentiels pour créer votre lien.</p>
<p>
    exemple, sans l'inserer dans une liste mais dans un element de navigation:
</p>
<pre><code>&lt;nav class="nav-cat-links">
	&lt;?php $plxShow->catList('','&lt;a href="#cat_url"  id="#cat_id" class="#cat_status" data-mother="#cat_mother" title="#cat_name">#cat_name&lt;/a>'); ?> 
&lt;/nav></code></pre>

<h2>Utilisation et mise en place des catégories principales</h2>
<p>Après l'installation du plugin, vous n'avez encore aucune catégories principales. Il est temps de les créer et d'y rattacher vos catégories secondaires.</p>
<ol class="mw70rem"><li>Créer une nouvelle catégorie et sélectionnez <b>oui</b> pour le choix dans la colonne <b>mère</b>. Enregistrer vos modification.</li>
<li>Votre catégorie mère a maintenant une couleur de fond pour la différencier des autres dans l'administration.</li>
<li>Vous pouvez maintenant , dans la colonne <b>fille</b>, sélectionner  l'une des catégories mère précédemment enregistrée pour y rattacher une catégorie fille. "orpheline" indique qu'il s'agit d'une catégorie non rattachée à une autre catégorie.</li>
<p>Chaques catégories fille(s) , dans l'administration, prendront une couleur de fond identique à celle de leur  catégorie <b>mére</b>.</p>
<img src="/plugins/categories/img/edit-cat.jpg" alt="aperçu edition catégorie">
<p>Dans la partie édition ou création d'article, seules les catégories filles seront listées avec la couleur de fond correspondant à leur catégorie mère.</p>

<img src="/plugins/categories/img/edit-art.jpg" alt="aperçu edition article">
<h2>Pages sans tris</h2>
<p>Toutes les pages, ne correspondant pas à un article ou une catégorie, rattachées à une catégorie mère, afficheront toutes les catégories principales. Ce sont par exemple les pages archives, les pages de mots clés, les pages statiques et les pages injectées par un plugin ainsi que les pages de <i>prévisualisation lors de l'édition d'un article.</i> </p>
    <h4>
        tip
    </h4>
    <p>
        Un defaut, un truc en trop ou qui manque, vous pouvez le signaler sur le forum de PluXml au sujet :<a href="https://forum.pluxml.org/discussion/6932/plugin-categories-meres-filles-suite-dun-precedent-sujet">plugin-categories-meres-filles-suite-dun-precedent-sujet</a> .
    </p>
  </section>