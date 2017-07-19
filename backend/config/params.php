<?php
return [
    'adminEmail' => 'admin@example.com',
    'qiniu'=>[
        //密钥在密钥管理中心
        'accessKey'=>'1u7Of3lFcGpDeugrpgaUyclzv8rW2esoqKy3AF5W',
        'secretKey'=>'fPsq1XTJNouOdnneYVqMhwCeVB9r-AnHPL2TTbNT',
        //域名，在储存空间里的测试域名
        'domain'=>'http://otbkpek0n.bkt.clouddn.com/',
        //储存空间名
        'bucket'=>'yiishop',
        //所选区域
        'area'=>\flyok666\qiniu\Qiniu::AREA_HUADONG
    ]
];
