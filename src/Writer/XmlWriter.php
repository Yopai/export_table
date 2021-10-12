<?php

declare(strict_types=1);

/*
 * This file is part of Export Table for Contao CMS.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/export_table
 */

namespace Markocupic\ExportTable\Writer;

use Contao\File;
use Markocupic\ExportTable\Config\Config;

class XmlWriter extends AbstractWriter implements WriterInterface
{
    public const FILE_ENDING = 'xml';

    public function write(array $arrData, Config $objConfig): void
    {
        $objXml = new \XMLWriter();
        $objXml->openMemory();
        $objXml->setIndent(true);
        $objXml->setIndentString("\t");
        $objXml->startDocument('1.0', 'UTF-8');

        $objXml->startElement($objConfig->getTable());

        foreach ($arrData as $row => $arrRow) {
            // Skip the headline
            if (0 === $row) {
                continue;
            }

            // Add a new row
            $objXml->startElement('datarecord');

            foreach ($arrRow as $i => $fieldvalue) {
                // Add a field
                $objXml->startElement($arrData[0][$i]);

                if (is_numeric($fieldvalue) || null === $fieldvalue || '' === $fieldvalue) {
                    $objXml->text($fieldvalue);
                } else {
                    // Write CDATA
                    $objXml->writeCdata($fieldvalue);
                }

                // Add the closing field tag
                $objXml->endElement();
            }
            // Add the closing row tag
            $objXml->endElement();
        }
        // Add the closing table tag
        $objXml->endElement();

        // Add the closing document tag
        $objXml->endDocument();

        // Write output to the file system
        $targetPath = $this->getTargetPath($objConfig, self::FILE_ENDING);

        $objFile = new File($targetPath);
        $objFile->write($objXml->outputMemory());
        $objFile->close();

        // E.g send notifications, etc.
        $objFile = $this->runPostWriteHook($objFile, $objConfig);

        $this->log($objFile, $objConfig);

        if ($objConfig->getSendFileToTheBrowser()) {
            $this->sendFileToTheBrowser($objFile, false);
        }

        $this->sendBackendMessage($objFile);
    }
}
