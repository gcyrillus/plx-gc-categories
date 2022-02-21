<section id=help>
<style>#help,img {box-sizing:border-box;  max-width:900px; margin:0 auto 1.6em;  padding:0 3rem;}img {display:block;margin:0 auto 1.6em;max-width:90%;}#help :is(h1,h2,h3,h4,h5,h6) {text-indent:-2.5rem;color: brown}</style>
<h1>Help plugin <b>PLX_GC_CATEGORIES</b></h1>
<h2>Display configuration</h2>
<p>There is no real configuration that you have to do, The only option avalaible from a click is an option to display the mother categories on the main menu and showing daughter categories inside a submenu , <i>just alike it can be done for the static pages .</i>.</p>
<p>The plugin doesn't need to be configured to be working, but to fully use it it is advised to modify you theme's file, so you can fully benefits from it.</p>

<p>Editing the theme files is possible from the administration. Through the administration click on <i>Parameters</i> then <i>Theme</i> and finally the button  "Theme edit".
<img src="/plugins/categories/img/page-theme.jpg" alt="page administration des thème">
     A select list lets you choose the file to edit.
<img src="/plugins/categories/img/edit-theme.jpg" alt="page édition des thèmes">
    <p>
        However, it is best to backup the full directory of your theme before editing. If you make a mistake or dislike what you have done, your theme backup will be a good friend. It takes sometines and a few tries before to be fully happy with your modification.
</p>
<h3>Editing advise for a classic theme
</h3>
	<h4>Update the breadcrumbs</h4>
	<p>You can update your breadcrumb menu from the files <code>categorie.php</code> and  <code>categorie-full-width.php</code> of your theme:</p>
	<pre><code>&lt;?php  $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '9999'); ?></code></pre>
	<p>  9999 will generate the link to the mother catégorie.<b>if you deactivate or delete the plugin, nothing will be shown.</b></p>
	<p>It needs to be include inside this portion of code:</p>
	<pre><code>&lt;li>&lt;a href = "&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); ?>&lt;/a>&lt;/li>
&lt;li>&lt;?php $plxShow->catName(); ?>&lt;/li></code></pre>
    <p>Which becomes:</p>
	<pre><code>&lt;li>&lt;a href = "&lt;?php $plxShow->racine() ?>">&lt;?php $plxShow->lang('HOME'); &lt;/a>&lt;/li>
&lt;?php $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '9999'); ?&gt;
&lt;li>&lt;?php $plxShow->catName(); ?>&lt;/li></code></pre>
	
	<h4>Add links to the main menu</h4>
  <p>there is 2 options, best is to use one or the other.</p>
  <p><b>First option</b> via the configuration page of the plugin, you can insert inside the main menu  the names of the mother catégories, each will show a submenu listing its daughters catégories.This can actually the first thing to do and to test before going further editing your theme files.</p>
  <p><b>The second option</b>You can also add the links to the mother categories within the main menu editing the file <code>header.php</code> of your, this option will not generate submenus:</p>
	<pre><code>&lt;?php $plxShow->catList('','&lt;li>&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000'); ?></code></pre>
    <p>The key number 10000 will create the links to the mother categories pages,<b>if you deactivate or delete the plugin, nothing will be shown.</b></p>
	<p>example:</p><pre><code>&lt;?php $plxShow->staticList($plxShow->getLang('HOME'),'&lt;li class="#static_class #static_status" id="#static_id">&lt;a href="#static_url" title="#static_name">#static_name&lt;/a>&lt;/li>'); ?>

&lt;?php $plxShow->pageBlog('&lt;li class="#page_class #page_status" id="#page_id">&lt;a href="#page_url" title="#page_name">#page_name&lt;/a>&lt;/li>'); ?>

&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000');  ?></code></pre>
<p>This code is a natve function of PluXml, the keynumber is there to modify its original behavior and sort out only mother categories. The code of PluXml can manage at the most 999 categories, if you use a number out of range, it won't match any categories and will not create any bugs. any numbers higher than 999 can be used and later used for another purpose.  
</p>
  <p>This native function of PluXml allows you also to exclude , one or more catégories. With the keyNumber 10000, you can exclude mother categories. Example, You do not wish to show the mother categorie number  '001' </p>
  <pre><code>&lt;?php  $plxShow->catList('','&lt;li class="#cat_status">&lt;a href="#cat_url">#cat_name&lt;/a>&lt;/li>', '10000', '001');  ?></code></pre>
  <p>To do so, the mother categorie number to exclude needs to be added after the keyNumber '10000', using a comma and between parentheses the number of the categorie to be excluded, for the example we added to the function<code>,'001'</code></p><p> to exclude many, just separate each number with a vertical bar (keyboard : <kbd> ALT GR + 6 </kbd>), For example to exclude  001 and  004 we have to type  <code>, '001 | 004' </code>. Category numbers must be written with 3 digits. Examples: 100, 040 ou 001 </p>

	<h4>Sidebar, visually identify the main and secondary categories.</h4>
	<p>An attribute with a value reflecting the type of category can be added to the code inserted in the <i>sidebar</i>. You need to edit the file <code>sidebar.php</code> of your theme.</p>
	<p>Look for the code to replace (default format):</p><pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status">&lt;a href="#cat_url" title="#cat_name">#cat_name&lt;/a> (#art_nb)&lt;/li>'); ?>
&lt;/ul></code></pre>
	<p> par :</p> <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList(); ?>
&lt;/ul></code></pre>
	<p>The default format will be replaced and an attribute <code>data-mother</code> will be added, its value will be either "0" or "1".</p>
<p>The format used will be the same as if you wrote :</p> <pre><code>&lt;ul class="cat-list">
	&lt;?php $plxShow->catList('','&lt;li id="#cat_id" class="#cat_status" data-mother="#cat_mother">&lt;a href="#cat_url" title="#cat_name">#cat_name&lt;/a> &lt;span> (#art_nb)&lt;/span>&lt;/li>'); ?>
&lt;/ul></code></pre>
	<p>It gives you two extra selectors you can use from your style sheets, this extra attribute: <code>data-mother</code> can only have 2 values.The plugin also adds a style sheet to your theme with the following code :</p><pre><code>[data-mother="1"] {font-weight:bold;max-width:max-content;margin:0.25ch 0;}
[data-mother="0"] {margin:0 0 2px 2ch;max-width:max-content;}
.menu.breadcrumb.repertory a {width:auto; /* reset de themes.css ligne 139 */}</code>
</pre><p>Those styles can also be edited in the administration from the plugins pages, clicking on the link CSS.
</p>
<p>If that new format doesn't suit your needs, you can rewrite it from the function , add an attribute or insert the class your theme already uses.<br>Example: to remove the number of articles related to the categorie, you can erase the <code>span</code> and its content, <code>#art_nb</code> is used to show the number of artical. Codes prefixed with an <b>#</b> should not be modified. <code>#cat_url</code> generates the link to the categorie and <code>#cat_name</code> the name of the categorie, These two are essential to creat a link to a categorie .</p>
<p>
    Modification example: generates links with class and attributes inside a nav element.
</p>
<pre><code>&lt;nav class="nav-cat-links">
	&lt;?php $plxShow->catList('','&lt;a href="#cat_url"  id="#cat_id" class="#cat_status" data-mother="#cat_mother" title="#cat_name">#cat_name&lt;/a>'); ?> 
&lt;/nav></code></pre>

<h2>Use and setup for the main(mothers) catégories</h2>
<p>Once the plugin installed and activated, you have not yet any mother categories. It's time to set or create them , then set the daughter categories .</p>
<ol class="mw70rem"><li>Create a new categorie and select   <b>yes</b> for the choice of mother column <b>mère</b>. validate the modification.</li>
<li>Your mother catégorie has now a background color to easily notice it in the administration.</li>
<li>You can now select daughter catégories, inside the <b>daughter</b> column , select one of the mother categories avalaible . "orphan" means that it is not related to any mother categorie.Save your modifications.</li>
<p>Each daughter categories , will have a background partially painted of the same color of the mother categorie it is related to.</p>
<img src="/plugins/categories/img/edit-cat.jpg" alt="aperçu edition catégorie">
<p>While creating or editing an article, daughter categories  backgrounds will also be painted from the mother's background color to easily sort and select the category the article is related to.</p>

<img src="/plugins/categories/img/edit-art.jpg" alt="aperçu edition article">
<h2>Pages where categories are not filtered</h2>
<p>Any pages which is not an article or  a categorie front page  will show in the side bar all categories or content from any categories. Thoses pages are  archives, keywords, static pages , preview (while editing an article) or any page generated by a plugin (alike plxMySearch). </p>
    <h4>
        tip
    </h4>
    <p>
        You found a bug or want to make any suggestion, you can do it on the official forum of the CMS PluXml at :<a href="https://forum.pluxml.org/discussion/6932/plugin-categories-meres-filles-suite-dun-precedent-sujet">plugin-categories-meres-filles-suite-dun-precedent-sujet</a> You can use english , a few will be there to understand and respond.
    </p>
  </section>