<?php
/**
 * Show a list of bookmarks.
 *
 * SemanticScuttle - your social bookmark manager.
 *
 * PHP version 5.
 *
 * @category    Bookmarking
 * @package     SemanticScuttle
 * @subcategory Templates
 * @author      Benjamin Huynh-Kim-Bang <mensonge@users.sourceforge.net>
 * @author      Christian Weiske <cweiske@cweiske.de>
 * @author      Eric Dane <ericdane@users.sourceforge.net>
 * @license     GPL http://www.gnu.org/licenses/gpl.html
 * @link        http://sourceforge.net/projects/semanticscuttle
 */

/* Service creation: only useful services are created */
$bookmarkservice = SemanticScuttle_Service_Factory::get('Bookmark');
$tagservice      = SemanticScuttle_Service_Factory::get('Tag');
$cdservice       = SemanticScuttle_Service_Factory::get('CommonDescription');


$pageName   = isset($pageName) ? $pageName : '';
$user       = isset($user) ? $user : '';
$currenttag = isset($currenttag) ? $currenttag : '';


$this->includeTemplate($GLOBALS['top_include']);
?>

<?php if($pageName == PAGE_INDEX && $GLOBALS['welcomeMessage']):?>
<div class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p id="welcome"><?php echo $GLOBALS['welcomeMessage'];?></p>
</div>
<?php endif?>


<?php if($GLOBALS['enableAdminColors']!=false && isset($userid) && $userservice->isAdmin($userid) && $pageName != PAGE_WATCHLIST) : ?>
<div style="width:70%;text-align:center;">
 <img src="<?php echo $theme->resource('images/logo_24.gif'); ?>" width="12px"/>
 <?php echo T_('Bookmarks on this page are managed by an admin user.'); ?>
 <img src="<?php echo $theme->resource('images/logo_24.gif'); ?>" width="12px"/>
</div>
<?php endif?>


<?php
// common tag description
if(($currenttag!= '' && $GLOBALS['enableCommonTagDescription'])
|| (isset($hash) && $GLOBALS['enableCommonBookmarkDescription'])):?>


<p class="commondescription"><?php
$cDescription = '';
if($currenttag!= '' && $cdservice->getLastTagDescription($currenttag)) {
	$cDescription = $cdservice->getLastTagDescription($currenttag);
	echo nl2br(filter($cDescription['cdDescription']));
} elseif(isset($hash) && $cdservice->getLastBookmarkDescription($hash)) {
	$cDescription = $cdservice->getLastBookmarkDescription($hash);
	echo nl2br(filter($cDescription['cdTitle'])). "<br/>";
	echo nl2br(filter($cDescription['cdDescription'])). "<br/>";
}

//common tag description edit
if ($userservice->isLoggedOn()) {
	if ($currenttag != ''
        && ($GLOBALS['enableCommonTagDescriptionEditedByAll']
            || $currentUser->isAdmin()
        )
    ) {
		echo ' <a href="'. createURL('tagcommondescriptionedit', $currenttag).'" title="'.T_('Edit the common description of this tag').'">';
		echo !is_array($cDescription) || strlen($cDescription['cdDescription'])==0?T_('Edit the common description of this tag'):'';
		echo ' <img src="' . $theme->resource('images/b_edit.png') . '" /></a>';
	} else if (isset($hash)) {
		echo ' (<a href="'.createURL('bookmarkcommondescriptionedit', $hash).'" title="'.T_('Edit the common description of this bookmark').'">';
		echo T_('Edit the common description of this bookmark').'</a>)';
	}
}
?></p>
<?php endif ?>


<?php
/* personal tag description */
if($currenttag!= '' && $user!='') {
	$userObject = $userservice->getUserByUsername($user);
	if($tagservice->getDescription($currenttag, $userObject['uId'])) { ?>

<p class="commondescription"><?php
$pDescription = $tagservice->getDescription($currenttag, $userObject['uId']);
echo nl2br(filter($pDescription['tDescription']));

//personal tag description edit
if($userservice->isLoggedOn()) {
	if($currenttag!= '') {
		echo ' <a href="'. createURL('tagedit', $currenttag).'" title="'.T_('Edit your personal description of this tag').'" >';
		echo strlen($pDescription['tDescription'])==0?T_('Edit your personal description of this tag'):'';
		echo ' <img src="' . $theme->resource('images/b_edit.png') . '" /></a>';
	}
}
?></p>

<?php
	}
}
?>

<?php if (isset($bookmarks) && count($bookmarks) > 0) { ?>
<script type="text/javascript">
window.onload = playerLoad;
<?php if ( isset($GLOBALS['enableQrCodes']) && $GLOBALS['enableQrCodes'] == true ) { ?>
jQuery(document).ready(function () {
	jQuery('.qr-code-load').click(function() {
		var bk = jQuery(this).parent().parent();
		if ( bk.find('.qr-code .qrcodeCanvas').length > 0 ) {
			bk.find('.qr-code').find('.qrcodeCanvas').remove();
		} else {
			bk.find('.qr-code').html('<div class="qrcodeCanvas"></div>');
			var o = {
				text: bk.find('.link a').attr('href'),
				width: 128,
				height: 128
			};
			if ( ! jQuery('html.canvas').length ) {
				o.render = "table";
			}
			bk.find('.qr-code .qrcodeCanvas').qrcode(o);
			bk.find('.qr-code').show();
		}
		return false;
	});
});
<?php } ?>
<?php if ( isset($GLOBALS['wallabagUrl']) && $GLOBALS['wallabagUrl'] != '' ) { ?>
jQuery(document).ready(function () {
	jQuery('.wallabag-share').click(function() {
		var bk = jQuery(this).parent().parent();
		var url = bk.find('.link a').attr('href');
		url = '<?php echo $GLOBALS['wallabagUrl']; ?>/index.php?action=add&plainurl=' + encodeURIComponent(url) + '&autoclose=true';
		window.open(url, 'wallabag', 'toolbar=no,width=800,height=600');
		return false;
	});
});
<?php } ?>
<?php if ( isset($GLOBALS['yourlsUrl']) && $GLOBALS['yourlsUrl'] != '' && isset($GLOBALS['yourlsApiKey']) && preg_match('#^[a-z0-9]{10}$#', $GLOBALS['yourlsApiKey']) ) { ?>
jQuery(document).ready(function () {
	jQuery('.yourls-share').click(function() {
		var bk = jQuery(this).parent().parent();
		var url = bk.find('.link a').attr('href');
		var title = bk.find('.link a').html();
		url = '<?php echo $GLOBALS['yourlsUrl']; ?>?signature=<?php echo $GLOBALS['yourlsApiKey']; ?>&action=shorturl&format=simple&url=' + encodeURIComponent(url) + '&title=' + encodeURIComponent(title) + '&autoclose=true';
		window.open(url, 'yourls', 'toolbar=no,width=800,height=600');
		return false;
	});
});
<?php } ?>
</script>
<div class="container-full">
	<div class="row">
		<div class="col-12 col-xs-12 col-sm-12 col-lg-12">
<p id="sort"><?php echo $total.' '.T_("bookmark(s)"); ?> - <?php echo T_("Sort by:"); ?>
 <?php
$titleArrow  = '';
$dateArrow   = '';
$votingArrow = '';
$dateSort    = 'date_desc';
$titleSort   = 'title_asc';
$votingSort  = 'voting_desc';

switch(getSortOrder()) {
case 'date_asc':
	$dateArrow = ' <i class="fa fa-caret-up"></i>';
	$dateSort  = 'date_desc';
	break;

case 'title_asc':
	$titleArrow = ' <i class="fa fa-caret-up"></i>';
	$titleSort  = 'title_desc';
	break;

case 'title_desc':
	$titleArrow = ' <i class="fa fa-caret-downp"></i>';
	$titleSort  = 'title_asc';
	break;

case 'voting_asc':
	$votingArrow = ' <i class="fa fa-caret-up"></i>';
	$votingSort  = 'voting_desc';
	break;

case 'voting_desc':
	$votingArrow = ' <i class="fa fa-caret-down"></i>';
	$votingSort  = 'voting_asc';
	break;

case 'date_desc':
default:
	$dateArrow = ' <i class="fa fa-caret-down"></i>';
	$dateSort = 'date_asc';
	break;
}
?>
 <a href="?sort=<?php echo $dateSort ?>"><?php echo T_("Date").$dateArrow; ?></a>
 <span>/</span>
 <a href="?sort=<?php echo $titleSort ?>"><?php echo T_("Title").$titleArrow; ?></a>
 <span>/</span>
<?php if ($GLOBALS['enableVoting']) { ?>
 <a href="?sort=<?php echo $votingSort ?>"><?php echo T_("Voting").$votingArrow; ?></a>
 <span>/</span>
<?php } ?>

<?php
if ($currenttag!= '') {
	if ($user!= '') {
		echo ' - ';
		echo '<a href="'. createURL('tags', $currenttag) .'">';
		echo T_('Bookmarks from other users for this tag').'</a>';
		//echo T_(' for these tags');
	} else if ($userservice->isLoggedOn()){
		echo ' - ';
		echo '<a href="'. createURL('bookmarks', $currentUser->getUsername().'/'.$currenttag) .'">';
		echo T_('Only your bookmarks for this tag').'</a>';
		//echo T_(' for these tags');
	}
}
?></p>
		</div>
	</div>
</div>

<div class="container-full">
	<div class="row">
		<div class="col-12 col-xs-12 col-sm-12 col-lg-12">
<?php
	// PAGINATION

	// Ordering
	$sortOrder = '';
	if (GET_SORT != '') {
		$sortOrder = 'sort=' . getSortOrder();
	}

	$sortAmp = (($sortOrder) ? '&amp;'. $sortOrder : '');
	$sortQue = (($sortOrder) ? '?'. $sortOrder : '');

	// Previous
	$perpage = getPerPageCount($currentUser);
	if (!$page || $page < 2) {
		$page = 1;
		$start = 0;
		$bfirst = '<span class="disable"><i class="fa fa-angle-double-left"></i> '. T_('First') .'</span>';
		$bprev = '<span class="disable"><i class="fa fa-angle-left"></i> '. T_('Previous') .'</span>';
	} else {
		$prev = $page - 1;
		$prev = 'page='. $prev;
		$start = ($page - 1) * $perpage;
		$bfirst= '<a href="'. sprintf($nav_url, $user, $currenttag, '') . $sortQue .'"><i class="fa fa-angle-double-left"></i> '. T_('First') .'</a>';
		$bprev = '<a href="'. sprintf($nav_url, $user, $currenttag, '?') . $prev . $sortAmp .'"><i class="fa fa-angle-left"></i> '. T_('Previous') .'</a>';
	}

	// Next
	$next = $page + 1;
	$totalpages = ceil($total / $perpage);
	if (count($bookmarks) < $perpage || $perpage * $page == $total) {
		$bnext = '<span class="disable">'. T_('Next') .' <i class="fa fa-angle-right"></i></span>';
		$blast = '<span class="disable">'. T_('Last') .' <i class="fa fa-angle-double-right"></i></span>' . "\n";
	} else {
		$bnext = '<a href="'. sprintf($nav_url, $user, $currenttag, '?page=') . $next . $sortAmp .'">'. T_('Next') .' <i class="fa fa-angle-right"></i></a>';
		$blast = '<a href="'. sprintf($nav_url, $user, $currenttag, '?page=') . $totalpages . $sortAmp .'">'. T_('Last') .' <i class="fa fa-angle-double-right"></i></a>' . "\n";
	}

	// RSS
	$brss = '';
	$size = count($rsschannels);
	for ($i = 0; $i < $size; $i++) {
            $brss =  '<a style="background:#FFFFFF"'
                . ' href="'. htmlspecialchars($rsschannels[$i][1]) . '"'
                . ' title="' . htmlspecialchars($rsschannels[$i][0]) . '">'
                . '<img src="' . $theme->resource('images/rss.gif') . '"'
                . ' width="16" height="16"'
                . ' alt="' . htmlspecialchars($rsschannels[$i][0]) .'"/>'
                . '</a>';
	}

	$pagesBanner = '<p class="paging">'. $bfirst . $bprev . $bnext . $blast . sprintf(T_('Page %d of %d'), $page, $totalpages) ." ". $brss ." </p>\n";

	if (getPerPageCount($currentUser) > 10) {
		echo $pagesBanner; // display a page banner if too many bookmarks to manage
	}


?>
		</div>
	</div>
</div>


<div class="container-full">
	<div class="row">
		<div class="col-8 col-xs-12 col-sm-8 col-lg-8">
<ol<?php echo ($start > 0 ? ' start="'. ++$start .'"' : ''); ?> id="bookmarks">
<?php
    $addresses = array();
    foreach ($bookmarks as $key => &$row) {
        $addresses[$row['bId']] = $row['bAddress'];
    }
    $otherCounts = $bookmarkservice->countOthers($addresses);
    if ($userservice->isLoggedOn()) {
        $existence = $bookmarkservice->bookmarksExist(
            $addresses, $currentUser->getId()
        );
    }

    if ($userservice->isLoggedOn()) {
        $watchedNames = $userservice->getWatchNames(
            $currentUser->getId(), true
        );
    } else {
        $watchedNames = null;
    }

	foreach ($bookmarks as $key => &$row) {
		switch ($row['bStatus']) {
			case 0:
				$access = '';
				break;
			case 1:
				$access = ' shared';
				break;
			case 2:
				$access = ' private';
				break;
		}

		$cats = '';
		$tagsForCopy = '';
		$tags = $row['tags'];
		foreach ($tags as $tkey => &$tag) {
            $tagcaturl = sprintf(
                $cat_url,
                filter($row['username'], 'url'),
                filter($tag, 'url')
            );
			$cats .= sprintf(
                '<a class="btn btn-sm btn-default" href="%s" rel="tag"><i class="fa fa-tag"></i>%s</a>, ',
                $tagcaturl, filter($tag)
            );
			$tagsForCopy .= $tag . ',';
		}
		$cats = substr($cats, 0, -2);
		if ($cats != '') {
			$cats = T_('Tags:') . ' ' . $cats;
		}

		// Edit and delete links
		$edit = '';
		if ($bookmarkservice->editAllowed($row)) {
			$edit = ' - <a class="btn btn-sm btn-default" href="' . createURL('edit', $row['bId']) . '"><i class="fa fa-edit"></i> '
                . T_('Edit')
                . '</a>'
                . ' <a class="btn btn-sm btn-default" href="#" onclick="deleteBookmark(this, '. $row['bId'] .'); return false;"><i class="fa fa-trash-o"></i> '
                . T_('Delete')
                .'</a>';
		}

		// Last update
		$update = '   <small title="'. T_('Last update') .'">('. date($GLOBALS['shortdate'], strtotime($row['bModified'])). ') </small>';

		// User attribution
		$copy = '   ' . T_('by') . ' ';
		if ($userservice->isLoggedOn()
            && $currentUser->getUsername() == $row['username']
        ) {
			$copy .= T_('you');
		} else {
			$copy .= '<a href="' . createURL('bookmarks', $row['username']) . '">'
                . SemanticScuttle_Model_UserArray::getName($row)
                . '</a>';
		}

		// others
		if (!isset($hash)) {
			$others = $otherCounts[$row['bAddress']];
			$ostart = '<a href="' . createURL('history', $row['bHash']) . '">';
			$oend = '</a>';
			switch ($others) {
				case 0:
					break;
				case 1:
					$copy .= sprintf(T_(' and %s1 other%s'), $ostart, $oend);
					break;
				default:
					$copy .= sprintf(T_(' and %2$s%1$s others%3$s'), $others, $ostart, $oend);
			}
		}

		// Copy link
		if ($userservice->isLoggedOn()
            && ($currentUser->getId() != $row['uId'])
            && !$existence[$row['bAddress']]
        ) {
			$copy .= ' - <a href="'
                . createURL(
                    'bookmarks',
                    $currentUser->getUsername()
                    . '?action=add&amp;copyOf=' . $row['bId'])
                . '" title="'.T_('Copy this bookmark to YOUR bookmarks.').'">'
                . T_('Copy')
                . '</a>';
		}

		// Nofollow option
		$rel = '';
		if ($GLOBALS['nofollow']) {
			$rel = ' rel="nofollow"';
		}

		$address  = $row['bAddress'];
		$oaddress = $address;
		// Redirection option
		if ($GLOBALS['useredir']) {
			$address = $GLOBALS['url_redir'] . $address;
		}

		// Admin specific design
		if ($userservice->isAdmin($row['username'])
            && $GLOBALS['enableAdminColors']
        ) {
			$adminBgClass = ' class="adminBackground"';
			$adminStar    = ' <img'
                . ' src="' . $theme->resource('images/logo_24.gif') . '"'
                . ' width="12px"'
                . ' title="' . T_('This bookmark is certified by an admin user.') . '"'
                . '/>';
		} else {
			$adminBgClass = '';
			$adminStar    = '';
		}

		// Private Note (just visible by the owner and his/her contacts)
        if ($watchedNames !== null
            && ($currentUser->getId() == $row['uId']
                || in_array($row['username'], $watchedNames)
            )
        ) {
			$privateNoteField = $row['bPrivateNote'];
		} else {
			$privateNoteField = '';
		}

        if ($GLOBALS['enableVoting'] && $GLOBALS['hideBelowVoting'] !== null
            && $row['bVoting'] < $GLOBALS['hideBelowVoting']
        ) {
            $access .= ' below-threshold';
        }

		// Output
		echo ' <li class="xfolkentry'. $access .'">'."\n";
        include 'bookmarks-thumbnail.inc.tpl.php';
        include 'bookmarks-vote.inc.tpl.php';

		echo '  <div' . $adminBgClass . '>' . "\n";

		echo '   <div class="link">'
            . '<a href="'. htmlspecialchars($address) .'"'. $rel .' class="taggedlink">'
            . filter($row['bTitle'])
            . '</a>' . $adminStar . "</div>\n";
		if ($row['bDescription'] == '') {
			$bkDescription = $GLOBALS['blankDescription'];
		} else {
			// Improve description display (anchors, links, ...)
			$bkDescription = preg_replace('|\[\/.*?\]|', '', filter($row['bDescription'])); // remove final anchor
			$bkDescription = preg_replace('|\[(.*?)\]|', ' <span class="anchorBookmark">$1</span> » ', $bkDescription); // highlight starting anchor
			$bkDescription = preg_replace('@((http|https|ftp)://.*?)( |\r|$)@', '<a href="$1" rel="nofollow">$1</a>$3', $bkDescription); // make url clickable

		}
		echo '   <div class="description">'. nl2br($bkDescription) ."</div>\n";
        echo '   <div class="address">' . htmlspecialchars(shortenString($oaddress)) . "</div>\n";

		echo '   <div class="meta">'
            . $cats . "\n"
            . $copy . "\n"
            . $edit . "\n"
            . $update . "\n";
		if ( isset($GLOBALS['enableQrCodes']) && $GLOBALS['enableQrCodes'] == true ) {
			echo '<a href="#" class="qr-code-load btn btn-sm btn-default"><i class="fa fa-qrcode"></i> QR Code</a> ' . "\n";
		}
		if ( isset($GLOBALS['pocheUrl']) && $GLOBALS['pocheUrl'] != '' ) {
			echo '<a href="#" class="poche-share btn btn-sm btn-default"><img src="' . $theme->resource('images/poche.png') . '" /> Poche</a> ' . "\n";
		}
		if ( isset($GLOBALS['yourlsUrl']) && $GLOBALS['yourlsUrl'] != '' && isset($GLOBALS['yourlsApiKey']) && preg_match('#^[a-z0-9]{10}$#', $GLOBALS['yourlsApiKey']) ) {
			echo '<a href="#" class="yourls-share btn btn-sm btn-default"><img src="' . $theme->resource('images/yourls.png') . '" /> Yourls</a> ' . "\n";
		}
		echo "  </div>\n";
		echo $privateNoteField != ''
            ? '    <div class="privateNote" title="'. T_('Private Note on this bookmark') .'">'.$privateNoteField."</div>\n"
            : '';
        echo '  ';
        include 'bookmarks-vote-horizontal.inc.tpl.php';
		//echo " </div>\n";
		echo '<div class="qr-code"></div>';
		echo " </li>\n";
	}
	?>

</ol>

		</div>

		<div class="col-4 col-xs-12 col-sm-4 col-lg-4">
			<?php
			$this->includeTemplate('sidebar.tpl');
			?>
		</div>
	</div>
</div>

	<?php
	if(getPerPageCount($currentUser)>7) {
		echo '<p class="backToTop"><a href="#header" title="'.T_('Come back to the top of this page.').'">'.T_('Top of the page').'</a></p>';
	}
	echo $pagesBanner;  // display previous and next links pages + RSS link


} else {
	echo '<p class="error">'.T_('No bookmarks available').'</p>';
}

$this->includeTemplate($GLOBALS['bottom_include']);
?>
