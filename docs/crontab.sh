SHELL=/bin/bash
PATH=/sbin:/bin:/usr/sbin:/usr/bin
MAILTO=root
HOME=/

# run-parts
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-auction
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-products-on
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-specials-on
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-specials-off
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-interest-product-start
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-interest-product-end
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-del-unsold-product
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-del-over-time-special
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-proxy-price-invalid
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-cancel-order
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-confirm-delivery-done
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-auto-accept-return-apply
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-return-no-delivery
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-confirm-return-delivery-done
*/1 * * * * root /alidata/server/php/bin/php /alidata/www/kaipai/public/index.php crontab-remove-interest-products