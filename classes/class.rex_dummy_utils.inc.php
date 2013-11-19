<?php
class rex_dummy_utils {
	public static function getSettingsFile() {
		global $REX;

		return $REX['INCLUDE_PATH'] . '/data/addons/dummy/settings.inc.php';
	}

	public static function getDefaultSettings() {
		global $REX;

		$settings['version'] = $REX['ADDON']['version']['dummy'];
		$settings['foo'] = 'batz';
		$settings['dude'] = 'sweet';

		return $settings;
	}

	public static function includeSettings() {
		global $REX; // needed because of require

		$settingsFile = self::getSettingsFile();

		if (file_exists($settingsFile)) {
			require_once($settingsFile);
			
			if ($REX['ADDON']['dummy']['settings']['version'] != $REX['ADDON']['version']['dummy']) {
				// update settings
				$defaultSettings = self::getDefaultSettings();

				// remove obsolete keys
				foreach ($REX['ADDON']['dummy']['settings'] as $key => $value) {
					if (!isset($defaultSettings[$key])) {
						unset($REX['ADDON']['dummy']['settings'][$key]);
					}
				}

				// merge old settings with new
				$REX['ADDON']['dummy']['settings'] = array_merge($defaultSettings, $REX['ADDON']['dummy']['settings']);
				$REX['ADDON']['dummy']['settings']['version'] = $REX['ADDON']['version']['dummy'];

				// save settings file
				self::generateSettingsFile($REX['ADDON']['dummy']['settings']);	

				require_once($settingsFile);

				// user info
				//echo rex_info('Settings file updated.');
			}
		} else {
			self::generateSettingsFile(self::getDefaultSettings());
			require_once($settingsFile);
		}
	}

	public static function generateSettingsFile($settings = array()) {
		global $REX;

		$content = '';		
		$settingsFile = self::getSettingsFile();
		$settingsDir = dirname($settingsFile);

		if (!file_exists($settingsDir)) {
			mkdir($settingsDir, $REX['DIRPERM'], true);
		}

		rex_dummy_utils::createDynFile($settingsFile);

		foreach ($settings as $key => $value) {
            $content .= "\$REX['ADDON']['dummy']['settings']['$key'] = '" . $value . "';" . PHP_EOL;
        }

		return rex_replace_dynamic_contents($settingsFile, $content);
	}

	public static function createDynFile($file) {
		$fileHandle = fopen($file, 'w');

		fwrite($fileHandle, "<?php\r\n");
		fwrite($fileHandle, "// --- DYN\r\n");
		fwrite($fileHandle, "// --- /DYN\r\n");

		fclose($fileHandle);
	}
	
	public static function getHtmlFromMDFile($mdFile, $search = array(), $replace = array()) {
		global $REX;

		$curLocale = strtolower($REX['LANG']);

		if ($curLocale == 'de_de') {
			$file = $REX['INCLUDE_PATH'] . '/addons/dummy/' . $mdFile;
		} else {
			$file = $REX['INCLUDE_PATH'] . '/addons/dummy/lang/' . $curLocale . '/' . $mdFile;
		}

		if (file_exists($file)) {
			$md = file_get_contents($file);
			$md = str_replace($search, $replace, $md);
			$md = self::makeHeadlinePretty($md);

			return Parsedown::instance()->parse($md);
		} else {
			return '[translate:' . $file . ']';
		}
	}

	public static function makeHeadlinePretty($md) {
		return str_replace('Dummy - ', '', $md);
	}
}

