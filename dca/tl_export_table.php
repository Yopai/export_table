<?php


/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package export_table
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_DCA']['tl_export_table'] = array(
    // Config
    'config'      => array(
        'dataContainer' => 'Table',
        'sql'           => array(
            'keys' => array(
                'id' => 'primary',
            ),
        ),
    ),
    // Buttons callback
    'edit'        => array(
        'buttons_callback' => array(array('tl_export_table', 'buttonsCallback')),
    ),

    // List
    'list'        => array(
        'sorting'           => array(
            'fields' => array('tstamp DESC'),
        ),
        'label'             => array(
            'fields' => array('title', 'export_table'),
            'format' => '%s Tabelle: %s',
        ),
        'global_operations' => array(),
        'operations'        => array(
            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['MSC']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
            ),
            'show'   => array(
                'label' => &$GLOBALS['TL_LANG']['MSC']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ),
        ),
    ),
    // Palettes
    'palettes'    => array(
        '__selector__' => array('activateDeepLinkExport'),
        'default'      => '{title_legend},title;{settings},export_table,exportType,fields,filterExpression,sortBy,sortByDirection;{deep_link_legend},activateDeepLinkExport',
    ),
    'subpalettes' => array(
        'activateDeepLinkExport' => 'deepLinkExportKey,deepLinkInfo',
    ),
    // Fields
    'fields'      => array(

        'id'                     => array(
            'label'  => array('ID'),
            'search' => true,
            'sql'    => "int(10) unsigned NOT NULL auto_increment",
        ),
        'tstamp'                 => array(
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ),
        'title'                  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['title'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'export_table'           => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_export_table']['export_table'],
            'inputType'        => 'select',
            'options_callback' => array(
                'tl_export_table',
                'optionsCbGetTables',
            ),
            'eval'             => array(
                'multiple'           => false,
                'mandatory'          => true,
                'includeBlankOption' => true,
                'submitOnChange'     => true,
            ),
            'sql'              => "varchar(255) NOT NULL default ''",
        ),
        'filterExpression'       => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['filterExpression'],
            'inputType' => 'text',
            'eval'      => array(
                'mandatory'      => false,
                'preserveTags'   => false,
                'allowHtml'      => true,
                'decodeEntities' => false,
            ),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'fields'                 => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_export_table']['fields'],
            'inputType'        => 'checkboxWizard',
            'options_callback' => array(
                'tl_export_table',
                'optionsCbSelectedFields',
            ),
            'eval'             => array(
                'multiple'   => true,
                'mandatory'  => true,
                'orderField' => 'orderFields',
            ),
            'sql'              => "blob NULL",
        ),
        'orderFields'            => array(
            'label' => &$GLOBALS['TL_LANG']['tl_export_table']['orderFields'],
            'sql'   => "blob NULL",
        ),
        'sortBy'                 => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_export_table']['sortBy'],
            'inputType'        => 'select',
            'options_callback' => array(
                'tl_export_table',
                'optionsCbSelectedFields',
            ),
            'eval'             => array(
                'multiple'  => false,
                'mandatory' => false,
            ),
            'sql'              => "blob NULL",
        ),
        'sortByDirection'        => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['sortByDirection'],
            'inputType' => 'select',
            'options'   => array('ASC', 'DESC'),
            'eval'      => array(
                'multiple'  => false,
                'mandatory' => false,
            ),
            'sql'       => "blob NULL",
        ),
        'exportType'             => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['exportType'],
            'inputType' => 'select',
            'options'   => array('csv', 'xml'),
            'eval'      => array(
                'multiple'  => false,
                'mandatory' => false,
            ),
            'sql'       => "blob NULL",
        ),
        'activateDeepLinkExport' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['activateDeepLinkExport'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true),
            'sql'       => "char(1) NOT NULL default ''",
        ),
        'deepLinkExportKey'      => array(

            'label'     => &$GLOBALS['TL_LANG']['tl_export_table']['deepLinkExportKey'],
            'exclude'   => true,
            'search'    => true,
            'default'   => md5(microtime() . rand()),
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 200),
            'sql'       => "varchar(255) NOT NULL default ''",
        ),
        'deepLinkInfo'           => array(
            'input_field_callback' => array('tl_export_table', 'generateDeepLinkInfo'),
            'eval'                 => array('doNotShow' => true),
        ),
    ),
);

/**
 * Class tl_export_table
 * Provide miscellaneous methods that are used by the data configuration array.
 * Copyright : &copy; 2014 Marko Cupic
 * @author Marko Cupic 2014
 * @package export_table
 */
class tl_export_table extends Backend
{

    public function __construct()
    {

        parent::__construct();
        if (isset($_POST['exportTable']) && $_POST['FORM_SUBMIT'] == 'tl_export_table')
        {
            unset($_POST['exportTable']);
            $objDb = Database::getInstance()->prepare('SELECT * FROM tl_export_table WHERE id=?')->execute(Input::get('id'));
            if ($objDb->numRows)
            {
                \Markocupic\ExportTable\ExportTable::prepareExport();
                exit();
            }
        }
    }


    /**
     * option_callback
     * @return array
     */
    public function optionsCbGetTables()
    {

        $objTables = $this->Database->listTables();
        $arrOptions = array();
        foreach ($objTables as $table)
        {
            $arrOptions[] = $table;
        }
        return $arrOptions;
    }


    /**
     * buttons_callback
     * @param $arrButtons
     * @param DC_Table $dc
     * @return mixed
     */
    public function buttonsCallback($arrButtons, DC_Table $dc)
    {

        if (Input::get('act') == 'edit')
        {
            $save = $arrButtons['save'];
            $exportTable = '<button type="submit" name="exportTable" id="exportTable" class="tl_submit" accesskey="n">' . $GLOBALS['TL_LANG']['tl_export_table']['launchExportButton'] . '</button>';
            $saveNclose = $arrButtons['saveNclose'];

            unset($arrButtons);

            // Set correct order
            $arrButtons = array(
                'save'        => $save,
                'exportTable' => $exportTable,
                'saveNclose'  => $saveNclose,
            );
        }

        return $arrButtons;
    }


    /**
     * option_callback
     * @return array
     */
    public function optionsCbSelectedFields()
    {

        $objDb = $this->Database->prepare("SELECT * FROM tl_export_table WHERE id = ? LIMIT 0,1")->execute(\Input::get('id'));
        if ($objDb->export_table == '')
        {
            return;
        }
        $objFields = $this->Database->listFields($objDb->export_table, 1);
        $arrOptions = array();
        foreach ($objFields as $field)
        {
            if (in_array($field['name'], $arrOptions))
            {
                continue;
            }
            $arrOptions[$field['name']] = $field['name'] . ' [' . $field['type'] . ']';
        }
        return $arrOptions;
    }


    /**
     * Input-field-callback
     * @return string
     */
    public function generateDeepLinkInfo()
    {

        $objDb = $this->Database->prepare('SELECT * FROM tl_export_table WHERE id=? LIMIT 0,1')->execute($this->Input->get('id'));
        $host = Environment::get('host');
        $query = '?action=exportTable&amp;key=' . $objDb->deepLinkExportKey;
        $href = 'http://' . $host . $query;

        $html = '
<div class="clr widget deep_link_info">
<br /><br />
<table cellpadding="0" cellspacing="0" width="100%" summary="">
	<tr class="odd">
		<td><h2>' . $GLOBALS['TL_LANG']['tl_export_table']['deepLinkInfoText'] . '</h2></td>
    </tr>
	<tr class="even">
		<td><a href="' . $href . '">' . $href . '</a></td>
	</tr>
</table>
</div>
				';

        return $html;
    }
}           
              