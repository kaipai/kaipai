<?php
namespace Base\ConstDir;
class BaseConst
{
    static $adPositions = array(
        self::AD_POSITION_INDEX_BANNER => '首页banner',
        self::AD_POSITION_INDEX_SECOND => '首页第二排广告位',
        self::AD_POSITION_INDEX_THIRD_LEFT => '首页第三排左侧广告位',
        self::AD_POSITION_INDEX_THIRD_RIGHT => '首页第三排右侧广告位',
        self::AD_POSITION_PRODUCT_LIST_LEFT => '拍品列表左侧广告位',
        self::AD_POSITION_PRODUCT_LIST_RIGHT => '拍品列表右侧广告位',
        self::AD_POSITION_SPECIAL_INDEX => '专场拍卖首页广告位',
    );
    const AD_POSITION_INDEX_BANNER = 1;
    const AD_POSITION_INDEX_SECOND = 2;
    const AD_POSITION_INDEX_THIRD_LEFT = 3;
    const AD_POSITION_INDEX_THIRD_RIGHT = 4;
    const AD_POSITION_PRODUCT_LIST_LEFT = 5;
    const AD_POSITION_PRODUCT_LIST_RIGHT = 6;
    const AD_POSITION_SPECIAL_INDEX = 7;


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
