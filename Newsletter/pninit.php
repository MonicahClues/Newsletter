<?php

function Newsletter_init()
{
	
	$dbconn  =& pnDBGetConn(true);
   	$prefix = pnConfigGetVar('prefix');
    $nl_users = $prefix.'_newsletter_users';
    $nl_archives = $prefix.'_newsletter_archives';
    
    $dbconn->Execute("CREATE TABLE $nl_users (
					  pn_id smallint(11) unsigned NOT NULL auto_increment,
					  pn_user_id smallint(11) unsigned NOT NULL,
					  pn_user_name varchar(100) NOT NULL default 'anonymous',
					  pn_user_email varchar(100) NOT NULL default 'none@none.com',
					  pn_nl_type tinyint(1) unsigned NOT NULL default '1',
					  pn_nl_frequency tinyint(1) unsigned NOT NULL default '1',
					  pn_active tinyint(1) unsigned NOT NULL default '0',
					  pn_approved tinyint(1) unsigned NOT NULL default '0',
					  pn_last_send_date int(15) NOT NULL,
					  pn_join_date int(15) NOT NULL,
					  INDEX pn_user_id (pn_user_id),
					  INDEX pn_user_email (pn_user_email),
					  INDEX pn_active (pn_active),
					  INDEX pn_approved (pn_approved),
					  INDEX pn_last_send_date (pn_last_send_date),
  					  PRIMARY KEY (pn_id))") or die($dbconn->ErrorMsg());
  	
  	$dbconn->Execute("CREATE TABLE $nl_archives (
  					  pn_archive_date int(15) NOT NULL,
  					  pn_archive_text TEXT NOT NULL,
  					  PRIMARY KEY (pn_archive_date))") or die($dbconn->ErrorMsg());
  	
  	pnModSetVar('Newsletter','send_from_address',pnConfigGetVar('adminmail'));
	pnModSetVar('Newsletter','archive_type','1'); // db/file
	pnModSetVar('Newsletter','archive_directory','modules/Newsletter/archives');
  	pnModSetVar('Newsletter','archive_expire','6'); // months
  	pnModSetVar('Newsletter','notify_admin','1');
  	pnModSetVar('Newsletter','allow_anon_registration','0');
  	pnModSetVar('Newsletter','auto_approve_registrations','1');
  	pnModSetVar('Newsletter','default_type','1'); //text/html/web
  	pnModSetVar('Newsletter','default_frequency','2');
  	pnModSetVar('Newsletter','allow_frequency_change','1');
	pnModSetVar('Newsletter','import_per_request','2');
	pnModSetVar('Newsletter','import_frequency','1');
	pnModSetVar('Newsletter','import_active_status','1');
	pnModSetVar('Newsletter','import_approval_status','1');
  	pnModSetVar('Newsletter','send_day','5'); 
  	pnModSetVar('Newsletter','send_per_request','5');  
  	pnModSetVar('Newsletter','personalize_email','0');
  	pnModSetVar('Newsletter','admin_key',substr(md5(time()),-10));
  	pnModSetVar('Newsletter','max_send_per_hour',0);
	pnModSetVar('Newsletter', 'enablecategorization', true);
    pnModSetVar('Newsletter', 'addcategorytitletopermalink', true);
	
  	 if ($dbconn->ErrorNo() != 0) {
        pnSessionSetVar('errormsg', 'Create Table Failed');
        return false;
    }
	
	if (!_newsletter_createdefaultcategory()) {
        return LogUtil::registerError (_CREATEFAILED);
    }
    
    return true;
}

function Newsletter_upgrade($oldversion) {

	switch($oldversion) {
		case '1.0': break;		
		case '1.1':
			pnModSetVar('Newsletter','personalize_email','0');
			pnModSetVar('Newsletter','admin_key',substr(md5(time()),-10));
		break;
		case '1.2':
			pnModSetVar('Newsletter','max_send_per_hour',0);
		break;
    }
    return true;
}

function Newsletter_delete()
{
    // Get database information
    $dbconn =& pnDBGetConn(true);
    $prefix = pnConfigGetVar('prefix');
    $nl_users = $prefix.'_newsletter_users';
    $nl_archives = $prefix.'_newsletter_archives';
    $dbconn->Execute("DROP TABLE IF EXISTS $nl_users, $nl_archives") or die($dbconn->ErrorMsg());

	 // Delete entries from category registry 
    pnModDBInfoLoad ('Categories');
    Loader::loadArrayClassFromModule('Categories', 'CategoryRegistry');
    $registry = new PNCategoryRegistryArray();
    $registry->deleteWhere ('crg_modname=\'Newsletter\'');
	
return true;   
}

function _newsletter_createdefaultcategory($regpath = '/__SYSTEM__/Modules/Global')
{
    // load necessary classes
    Loader::loadClass('CategoryUtil');
    Loader::loadClassFromModule('Categories', 'Category');
    Loader::loadClassFromModule('Categories', 'CategoryRegistry');

    // get the language file
    $lang = pnUserGetLang();

    // get the category path for which we're going to insert our place holder category
    $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules');
    $pCat    = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Newsletter');

    if (!$pCat) {
        // create placeholder for all our migrated categories
        $cat = new PNCategory ();
        $cat->setDataField('parent_id', $rootcat['id']);
        $cat->setDataField('name', 'Newsletter');
        $cat->setDataField('display_name', array($lang => _NEWSLETTER_NAME));
        $cat->setDataField('display_desc', array($lang => _NEWSLETTER_CATEGORY_DESCRIPTION));
        if (!$cat->validate('admin')) {
            return false;
        }
        $cat->insert();
        $cat->update();
    }

    // get the category path for which we're going to insert our upgraded categories
    $rootcat = CategoryUtil::getCategoryByPath($regpath);
    if ($rootcat) {
        // create an entry in the categories registry
        $registry = new PNCategoryRegistry();
        $registry->setDataField('modname', 'Newsletter');
        $registry->setDataField('table', 'newsletter');
        $registry->setDataField('property', 'Main');
        $registry->setDataField('category_id', $rootcat['id']);
        $registry->insert();
    } else {
        return false;
    }

    return true;
}