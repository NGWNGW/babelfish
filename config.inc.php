<?php
// todo
// * mkdir fehlermeldung + generateSettingsFile meldungen
// * settings array in einstellungsseite wie developer
// * settings.website1.inc.php realisieren
// * settings löschen wenn uninstall 
// * backup der bestehenden settings datei
// * evtl. eigene settings klasse
// * meldungen an user wenn update oder generate etc. (per EP)

// init addon
$REX['ADDON']['name']['dummy'] = 'Dummy';
$REX['ADDON']['page']['dummy'] = 'dummy';
$REX['ADDON']['version']['dummy'] = '1.0.0';
$REX['ADDON']['author']['dummy'] = '<author>';
$REX['ADDON']['supportpage']['dummy'] = 'forum.redaxo.de';
$REX['ADDON']['perm']['dummy'] = 'dummy[]';

// permissions
$REX['PERM'][] = 'dummy[]';

// includes
require($REX['INCLUDE_PATH'] . '/addons/dummy/classes/class.rex_dummy_utils.inc.php');

// settings
rex_dummy_utils::includeSettings();

if ($REX['REDAXO']) {
	// add lang file
	$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/dummy/lang/');

	// add subpages
	$REX['ADDON']['dummy']['SUBPAGES'] = array(
		array('', $I18N->msg('dummy_settings')),
		array('setup', $I18N->msg('dummy_setup')),
		array('help', $I18N->msg('dummy_help'))
	);
}

