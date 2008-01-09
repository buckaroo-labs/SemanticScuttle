<?php
/***************************************************************************
Copyright (C) 2006 Scuttle project
http://sourceforge.net/projects/scuttle/
http://scuttle.org/

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
***************************************************************************/

require_once('header.inc.php');
$tag2tagservice = & ServiceFactory :: getServiceInstance('Tag2TagService');
$templateservice = & ServiceFactory :: getServiceInstance('TemplateService');
$userservice = & ServiceFactory :: getServiceInstance('UserService');

list ($url, $tag1, $tag2) = explode('/', $_SERVER['PATH_INFO']);

if ($_POST['confirm']) {
    if ($tag2tagservice->removeLinkedTags($_POST['tag1'], $_POST['tag2'], '>', $userservice->getCurrentUserId())) {
        $tplVars['msg'] = T_('Tag link deleted');
        $logged_on_user = $userservice->getCurrentUser();
        header('Location: '. createURL('bookmarks', $logged_on_user[$userservice->getFieldName('username')]));
    } else {
        $tplVars['error'] = T_('Failed to delete the link');
        $templateservice->loadTemplate('error.500.tpl', $tplVars);
        exit();
    }
} elseif ($_POST['cancel']) {
    $logged_on_user = $userservice->getCurrentUser();
    header('Location: '. createURL('bookmarks', $logged_on_user[$userservice->getFieldName('username')] .'/'. $tags));
}

$tplVars['tag1']	= $tag1;
$tplVars['tag2']	= $tag2;
$tplVars['subtitle']    = T_('Delete Link Between Tags') .': '. $tag1.' > '.$tag2;
$tplVars['formaction']  = $_SERVER['SCRIPT_NAME'] .'/'. $tag;
$tplVars['referrer']    = $_SERVER['HTTP_REFERER'];
$templateservice->loadTemplate('tag2tagdelete.tpl', $tplVars);
?>
