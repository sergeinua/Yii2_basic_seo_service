<?php
namespace app\components;

use kartik\mpdf\Pdf;
use Yii;

class Additional
{
    public static function getPdf($content, $fileName, $header){
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            'filename' => $fileName,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:28px}',
            // set mPDF properties on the fly
            'options' => ['title' => Yii::t('app', 'Статистика позиций ключевых слов')],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>[$header],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }




}