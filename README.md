



# plx-gc-categories


Un thème https://github.com/gcyrillus/theme-sub-cat-list embarque un script permettant de structurer sur plusieurs niveaux l'affichage des catégories dans la sidebar. Ce thème embarque aussi les scripts générant les données structurées ainsi que quelques attributs dédié à l'accéssibilité numérique. Ce thème est dèjà configurer pour le plugin, mais ne necessite pas que le plugin soit activé.


[+] Compatibilité pluxml 5.8.10 partielle suite à l'évolution  des maj de pluxml. 

[+] donne le choix de filtrer ou non les catégories dans la sidebar , par défaut le filtre est activé si la page en cours est réliées à une catégorie mére.

[+] un troisieme niveau est visuellement possible

[-] bug affichage catégories vides.

[+] La fonction staticList() est detournée avec le mot clé `#no_static` à inserer dans la variable $format , Cela permet de n'afficher que les catégories groupées par catégories mères et en menu déroulant avec la même architecture que les groupes de pages statiques. Cette fonction ne gére pas l'affichage des catégorie de niveau 3.
Pour en faire usage: remplacer dans sidebar.php la fonction `<?php $plxShow->catList(); ?>` par `<?php  $plxShow->staticList('','<li class="#static_class #static_status" id="#static_id"><a href="#static_url" title="#static_name">#static_name</a>#no_static</li>'); ?>` ou le format que vous utilisez en y ajoutant la chaine `#no_static` .

[+] javascript . ajout d'un tiret devant les catégories filles de premier niveau dans les select filles


[+] breadcrumbs now can show the 3 levels
[+] init var $cssSelector to avoid warnings
[+] fr-help update 
