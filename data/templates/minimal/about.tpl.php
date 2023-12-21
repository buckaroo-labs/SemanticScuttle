<?php
$this->includeTemplate($GLOBALS['top_include']);
?>

<div class="container-full">
	<div class="row">

		<ul>
		<li><a target="_blank" href="https://github.com/buckaroo-labs/semantic-scuttle">Semantic Scuttle</a> <?php echo T_('is licensed under the ');?> <a  target="_blank"href="http://www.gnu.org/copyleft/gpl.html"><acronym title="GNU\'s Not Unix">GNU</acronym> General Public License</a> (<?php echo T_('you can freely host it on your own web server'); ?>)
		<?php echo sprintf(T_(' and supports most of the <a target="_blank" href="http://www.delicious.com/help/api">del.icio.us <abbr title="Application Programming Interface">API</abbr></a>. '), $GLOBALS['sitename']); ?></li>
		<li><a target="_blank" href="https://github.com/jonrandoem/sscuttlizr/">Sscuttlizr</a> <?php echo T_('is licensed under the ');?> <a target="_blank" href="https://github.com/jonrandoem/sscuttlizr/blob/master/LICENSE">MIT License</a>


		<?php if(!is_null($currentUser) && $currentUser->isAdmin()): ?>
		<li>SemanticScuttle v0.98.5</li>
		<?php endif ?>
		</ul>

		<h3><?php echo T_('Tips'); ?></h3>
		<ul>
		<li><?php echo T_('Add search plugin into your browser:'); ?> <a href="#" onclick="window.external.AddSearchProvider('<?php echo ROOT?>api/opensearch.php');">opensearch</a></li>
		<li><?php echo T_('The secret tag "system:unfiled" allows you to find bookmarks without tags.'); ?></li>
		<li><?php echo T_('The secret tag "system:imported" allows you to find imported bookmarks.'); ?></li>
		</ul>

	</div>
</div>

<?php
$this->includeTemplate($GLOBALS['bottom_include']);
?>
