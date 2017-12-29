<?php
return [
    'adminEmail' => 'admin@example.com',
    'avatar'   =>[
        'small' =>'/statics/images/avatar/small/small.png',
    ],
    'log_version'=>'0.0.1',
    'bucket_name'=>array(
        'match_a' => 'perobot-match-a',
        'match_b' => 'perobot-match-b',
        'train_a' => 'perobot-train-a',
        'train_b' => 'perobot-train-b',
    ),
    'pipeline' => array(
        'match_a' => 'perobot-match-a',
        'match_b' => 'perobot-match-b',
        'train_a' => 'perobot-train-a',
        'train_b' => 'perobot-train-b',
    ),

    'access_key' => '-uqVagAj6vzN-m4oBfvXeek5BzcQLqvWPzdcLtJP',
    'secret_key' => '03nB8KGWktib0_-bN0hJXm5tW_8HvYbElv273BFG',
    'watermark' => 'http://ou71f9z6j.bkt.clouddn.com/logo_mark.png',

    'policy' => array(
        'callbackUrl' => "",
        'callbackBody' => "",
        'MimeLimit' => "video/*",
        'persistentOps' => "",
        'saveKey' => "",
        'persistentPipeline' => "",
    ),

];
