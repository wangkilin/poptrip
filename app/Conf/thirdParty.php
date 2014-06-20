<?php
###################################
### Dianping.com
###################################
define('DIANPING_APPKEY','5466970029');
define('DIANPING_SECRET','740d342ab29041659d736965a3b73479');

###################################
### Weixin
###################################
define('WECHAT_APP_ID', 'wxd25e67dfa7b2dd59');
define('WECHAT_APP_SECRET', 'd91611978da6c8b67f41a9945c0599ae');

###################################
### Yixin
###################################
define('YIXIN_APP_ID', 'd54e53621b514858b4ebbc149fcd95e8');
define('YIXIN_APP_SECRET', '48ed9e6a9fc544479e608f578d25b1be');

return array(
        'dianping' => array('appkey'=>DIANPING_APPKEY,
                            'secret'=>DIANPING_SECRET
                      ),
        'wechat'   => array('appid'=>WECHAT_APP_ID,
                            'secret'=>WECHAT_APP_SECRET
                      ),
        'yixin'    => array('appid'=>YIXIN_APP_ID,
                            'secret'=>YIXIN_APP_SECRET
                      ),
        );