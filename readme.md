# E-Commerce in Plain PHP

## Setup

```
git clone https://github.com/Ruslan-Aliyev/ECommerce-Plain-PHP.git
cd ECommerce-Plain-PHP
composer install
```
Visit `localhost//ECommerce-Plain-PHP/`

## Notes

### Omnipay

`composer require league/omnipay omnipay/paypal omnipay/stripe`

See https://github.com/Ruslan-Aliyev/ECommerce-Plain-PHP/blob/master/index.php

- Paypal developer dashboard: https://developer.paypal.com/developer/accounts/ then https://developer.paypal.com/developer/applications/  
- Stripe developer dashboard: https://dashboard.stripe.com/

### Tutorials

- Stripe
	- Plain PHP
		- https://github.com/Ruslan-Aliyev/Stripe-API-Plain-PHP
	- Laravel
		- https://medium0.com/@juangsalazprabowo/how-to-integrate-laravel-with-stripe-fc54e54a767c
	- Misc
		- https://stackoverflow.com/questions/29851706/how-to-create-a-customer-in-omnipay-stripe
- Paypal
	- Plain PHP
		- https://www.youtube.com/watch?v=5AbkSomC-a4
	- Laravel
		- https://www.youtube.com/watch?v=_7YBIRQfSN0
		- https://sujipthapa.co/blog/a-guide-to-integrate-omnipay-paypal-with-laravel
	- WP-WooCommerce
		- https://github.com/atabegruslan/WP_WooCommerce
	- Laravel Bagisto
		- https://github.com/atabegruslan/Others/blob/master/Illustrations/bagisto.md
	- Magento 2
		- https://github.com/atabegruslan/Magento

### Database design

- https://moqups.com/templates/diagrams-flowcharts/erd/ecommerce-database-diagram/
- https://creately.com/diagram/example/he7cxejx1/e-Commerce%20Database
- https://github.com/woocommerce/woocommerce/wiki/Database-Description

Typical:

![](/Illustrations/typical_ec_er.png)

### Plugins

- https://github.com/srmklive/laravel-paypal
- https://laravel-vuejs.com/paystack-payment-gateway-plugin/
- https://www.positronx.io/how-to-integrate-paypal-payment-gateway-in-laravel/
- https://github.com/shetabit/payment
- https://codecanyon.net/search/php%20stripe%20payments
- https://www.expresstechsoftwares.com/integrate-laravel-stripe-payment-gateway-online-payments/
- https://laravel.com/docs/8.x/billing
- https://onlinewebtutorblog.com/stripe-payment-gateway-integration-in-laravel-8/
- https://www.tutsmake.com/laravel-8-stripe-payment-gateway-integration-tutorial/
- https://github.com/rap2hpoutre/laravel-stripe-connect
