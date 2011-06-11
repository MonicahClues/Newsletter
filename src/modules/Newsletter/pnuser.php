<?php
/**
 * Newletter Module for Zikula
 *
 * @copyright © 2001-2010, Devin Hayes (aka: InvalidReponse), Dominik Mayer (aka: dmm), Robert Gasch (aka: rgasch)
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Support: http://support.zikula.de, http://community.zikula.org
 */

function Newsletter_user_main ()
{

    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Overview rights for this module.", $dom));
    }
    $ot         = FormUtil::getPassedValue ('ot', 'main', 'GETPOST');
    $offset     = FormUtil::getPassedValue ('startnum', 0, 'GETPOST');
    $pagesize   = FormUtil::getPassedValue ('pagesize', ModUtil::getVar ('Newsletter', 'itemsperpage', 30), 'GETPOST');
    $startpage  = isset($args['startpage']) ? 1 : 0;
    $sort       = null;

    $view = Zikula_View::getInstance('Newsletter', false);
    $view->assign ('ot', $ot);
    $view->assign ('startpage', $startpage);

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError (__('Unable to load class [newsletter_util]', $dom));
    }

    $data = array();
    if (($ot && $class = Loader::loadArrayClassFromModule ('Newsletter', $ot))) {
        $objectArray = new $class ();
        $where       = $objectArray->genFilter ();
        $sort        = $sort ? $sort : $objectArray->_objSort;
        $data        = $objectArray->get ($where, $sort, $offset, $pagesize);

        $pager = array();
        $pager['numitems']     = $objectArray->getCount ($where);
        $pager['itemsperpage'] = $pagesize;
        $view->assign ('startnum', $offset);
        $view->assign ('pager', $pager);
    } //elseif ($debug) {
    //    return "Unable to load array class [$ot]";
    //}
    $view->assign ('objectArray', $data);

    if (Loader::loadClassFromModule ('Newsletter', 'user')) {
        $object = new PNUser ();
        if (UserUtil::isLoggedIn()) {
            $user = $object->getUser (UserUtil::getVar('uid'));
        }
        $validation = $object->getValidation();
    }
    $view->assign ('user', $user);

    $tpl = 'newsletter_user_view_' . $ot . '.html';
    return $view->fetch($tpl);
}


function Newsletter_user_detail () // hardcoded for archives
{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_OVERVIEW)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Overview rights for this module.", $dom));
    }

    $ot  = 'archive';
    $id  = (int)FormUtil::getPassedValue ('id', 0);
    $url = ModUtil::url('Newsletter', 'user', 'main');

    if (!$id) {
        return LogUtil::registerError (__('Invalid [id] parameter received', $dom), null, $url);
    }

    if (!($class = Loader::loadClassFromModule ('Newsletter', $ot))) {
        return LogUtil::registerError (__("Unable to load class [$ot]", $dom), null, $url);
    }

    $obj  = new PNArchive ();
    $data = $obj->get ($id);

    // just echo text and exit; no need to use template
    print $data['text'];
    exit ();
}


function Newsletter_user_send ()
{
    $dom = ZLanguage::getModuleDomain('Newsletter');
    if (!SecurityUtil::checkPermission('Newsletter::', '::', ACCESS_READ)) {
        return DataUtil::formatForDisplayHTML(__("You don't have Read rights for this module.", $dom));
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_util', false, false, '')) {
        return LogUtil::registerError (__('Unable to load class [newsletter_util]', $dom));
    }

    if (!Loader::loadClassFromModule ('Newsletter', 'newsletter_send')) {
        return LogUtil::registerError (__('Unable to load class [newsletter_send]', $dom));
    }

    $scheduled = (int)FormUtil::getPassedValue ('scheduled', 0);

    $obj = new PNNewsletterSend ();
    return $obj->save (array('respond' => 1, 'scheduled' => $scheduled));
}
