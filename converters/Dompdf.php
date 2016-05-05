<?php
/**
 * @link https://github.com/yii2tech
 * @copyright Copyright (c) 2015 Yii2tech
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace yii2tech\html2pdf\converters;

use yii\helpers\ArrayHelper;
use yii2tech\html2pdf\BaseConverter;

/**
 * Dompdf converts file using [dompdf](https://github.com/dompdf/dompdf) library.
 *
 * This converter requires `dompdf` library to be installed. This can be done via composer:
 *
 * ```
 * composer require --prefer-dist dompdf/dompdf:0.7.x@beta
 * ```
 *
 * @see http://wkhtmltopdf.org/
 *
 * @author Paul Klimov <pklimov@quartsoft.com>
 * @package yii2tech\html2pdf\converters
 */
class Dompdf extends BaseConverter
{

    /**
     * @inheritdoc
     */
    protected function convertInternal($sourceFileName, $outputFileName, $options)
    {
        $pageSize = ArrayHelper::remove($options, 'pageSize', 'A4');
        $orientation = ArrayHelper::remove($options, 'orientation', 'landscape');

        if (empty($options)) {
            $dompdfOptions = null;
        } else {
            $dompdfOptions = new \Dompdf\Options();
            foreach ($options as $name => $value) {
                $dompdfOptions->set($name, $value);
            }
        }

        $dompdf = new \Dompdf\Dompdf($dompdfOptions);
        $dompdf->setPaper($pageSize, $orientation);

        $dompdf->loadHtmlFile($sourceFileName);
        $dompdf->render();

        file_put_contents($outputFileName, $dompdf->output());
    }
}