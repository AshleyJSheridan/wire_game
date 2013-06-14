<?php

class Utils
{

    /**
     * Returns the first non-null, non-empty parameter, or the last parameter
     *
     * @return mixed
     */
    public static function coalesce()
    {
        $args = func_get_args();
        $last = null;
        foreach ($args as $arg) {
            if (!empty($arg)) {
                return $arg;
            }
            $last = $arg;
        }
        return $last;
    }

    /**
     * Return text with a new line character appended
     *
     * @param string $text Text to print
     * @return string
     */
    public static function sprintLn($text = '', $indent_tabs = 0) {
        $tabs = ($indent_tabs > 0) ? str_repeat("    ", $indent_tabs) : '';
        return $tabs . $text . PHP_EOL;
    }


    /**
     * Print text with a new line character appended
     *
     * @param string $text Text to print
     * @return void
     */
    public static function printLn($text = '', $indent_tabs = 0) {
        print self::sprintLn($text, $indent_tabs);
    }

    /**
     * Select the most suitable language from a set
     *
     * @param array $translations A set of translations with language code as keys
     * @param array $languages An ordered list of preferred languages, default 0 => 'en'
     *
     * @return string a selected translation
     */
    public static function l18n($translations, $languages = array('en'))
    {
        if (!is_array($translations)) {
            return $translations;
        }

        $langTranslations = array();
        $retVal = '';

        foreach ($translations as $key => $value) {
            $parts = explode('-', $key);
            list($lang)= $parts;
            $langTranslations[$lang] = $value;
        }

        foreach ($languages as $lang) {
            $culture = explode('-', $lang);

            if (array_key_exists($lang, $translations)) {
                $retVal = $translations[$lang];
                break;
            }

            if (array_key_exists($culture[0], $translations)) {
                $retVal = $translations[$culture[0]];
                break;
            }

            if (array_key_exists($culture[0], $langTranslations)) {
                $retVal = $langTranslations[$culture[0]];
                break;
            }
        }

        return empty($retVal)?array_shift($translations):$retVal;
    }


    /**
     * Get application settings defined in "settings.*" key in application.ini
     *
     * @param string $name   Name of the option to fetch
     * @param mixed $default Default value for cases when key is not found
     * @return mixed
     */
    public static function getSettings($name, $default = null)
    {
        /**
         * @var Bootstrap
         */
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        if (!($bootstrap instanceof Zend_Application_Bootstrap_BootstrapAbstract)) {
            return $default;
        }

        $settings = $bootstrap->getOption('settings');

        // return the default value if settings not found
        if (empty($settings) || !is_array($settings)) {
            return $default;
        }

        // lowercase all keys
        $name = strtolower($name);
        $settings = array_change_key_case($settings, CASE_LOWER);

        // return the default value if the specified key not found
        if (isset($settings[$name])) {
            return $settings[$name];
        }

        return $default;
    }

}