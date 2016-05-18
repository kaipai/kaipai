<?php
namespace Base\ConstDir;
class BaseConst
{
    static $adPositions = array(
        1 => '首页banner',
        2 => '首页第二排广告位',
        3 => '首页第三排左侧广告位',
        4 => '首页第三排右侧广告位',
    );
    const ARTICLE_INDEX_LEFT = 1;
    const ARTICLE_INDEX_RIGHT = 2;
    const ARTICLE_CONTACT = 3;
    const ARTICLE_ABOUT = 4;
    const ARTICLE_STATUS_ENABLE = 1;
    const ARTICLE_STATUS_DISABLE = 2;
    const AD_STATUS_ENABLE = 1;
    const AD_STATUS_DISABLE = 2;
    const PRODUCT_DISPLAY_PIC = 1;
    const PRODUCT_DISPLAY_TEXT = 2;
    const STORE_TYPE_INDEX = 1;
    const PRODUCT_STATUS_ENABLE = 1;
    const PRODUCT_STATUS_DISABLE = 2;
    const MEMBER_MESSAGE_STATUS_READ = 2;
    const MEMBER_MESSAGE_STATUS_NOT_READ = 1;
    const NOTIFICATION_TYPE_WEBSITE = 1;
    const NOTIFICATION_TYPE_AUCTION = 2;
    const NOTIFICATION_TYPE_ORDER = 3;
    const NOTIFICATION_TYPE_COMMENT = 4;

    const PAY_DETAIL_STATUS_PAID = 1;
    const PAY_DETAIL_STATUS_NOT_PAID = 0;
    const ORDER_STATUS_PAID = 1;
    const ORDER_STATUS_NOT_PAID = 0;
    const AUCTION_STATUS_COMING = 1;
    const AUCTION_STATUS_PROCESSING = 2;
    const AUCTION_STATUS_DONE = 3;
}
