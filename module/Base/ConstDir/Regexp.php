<?php
namespace Base\ConstDir;

class Regexp{
    const MOBILE_VALIDATE = '/^1(?:3\\d|4[57]|[7]\\d|[58][0-9])\\d{8}$/';
    const BODY_CONTENT = '/<body.*>(.*)<\/body>/is';
    const MEMBER_ARTICLE_CONTENT = '/<div class="space_conts">(.*)<\/div>/is';
}