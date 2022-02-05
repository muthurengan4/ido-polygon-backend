<?php

/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
|
| 
|
*/

if(!defined('TAKE_COUNT')) define('TAKE_COUNT', 6);

if(!defined('NO')) define('NO', 0);
if(!defined('YES')) define('YES', 1);

if(!defined('PAID')) define('PAID',1);
if(!defined('UNPAID')) define('UNPAID', 0);

if(!defined('DEVICE_ANDROID')) define('DEVICE_ANDROID', 'android');
if(!defined('DEVICE_IOS')) define('DEVICE_IOS', 'ios');
if(!defined('DEVICE_WEB')) define('DEVICE_WEB', 'web');

if(!defined('MALE')) define('MALE', 'male');
if(!defined('FEMALE')) define('FEMALE', 'female');
if(!defined('OTHERS')) define('OTHERS', 'others');

if(!defined('APPROVED')) define('APPROVED', 1);
if(!defined('DECLINED')) define('DECLINED', 0);
if(!defined('PENDING')) define('PENDING', 0);

if(!defined('DEFAULT_TRUE')) define('DEFAULT_TRUE', true);
if(!defined('DEFAULT_FALSE')) define('DEFAULT_FALSE', false);

if(!defined('ADMIN')) define('ADMIN', 'admin');
if(!defined('USER')) define('USER', 'user');
if(!defined('ContentCreator')) define('ContentCreator', 'creator');

if(!defined('CRYPTO')) define('CRYPTO',   'CRYPTO');
if(!defined('COD')) define('COD',   'COD');
if(!defined('PAYPAL')) define('PAYPAL', 'PAYPAL');
if(!defined('CARD')) define('CARD',  'CARD');
if(!defined('BANK_TRANSFER')) define('BANK_TRANSFER',  'BANK_TRANSFER');
if(!defined('PAYMENT_OFFLINE')) define('PAYMENT_OFFLINE',  'OFFLINE');
if(!defined('PAYMENT_MODE_WALLET')) define('PAYMENT_MODE_WALLET',  'WALLET');

if(!defined('STRIPE_MODE_LIVE')) define('STRIPE_MODE_LIVE',  'live');
if(!defined('STRIPE_MODE_SANDBOX')) define('STRIPE_MODE_SANDBOX',  'sandbox');

//////// USERS

if(!defined('USER_PENDING')) define('USER_PENDING', 2);

if(!defined('USER_APPROVED')) define('USER_APPROVED', 1);

if(!defined('USER_DECLINED')) define('USER_DECLINED', 0);

if(!defined('USER_EMAIL_NOT_VERIFIED')) define('USER_EMAIL_NOT_VERIFIED', 0);

if(!defined('USER_EMAIL_VERIFIED')) define('USER_EMAIL_VERIFIED', 1);

//////// USERS END

/***** ADMIN CONTROLS KEYS ********/

if(!defined('ADMIN_CONTROL_ENABLED')) define('ADMIN_CONTROL_ENABLED', 1);
if(!defined('ADMIN_CONTROL_DISABLED')) define('ADMIN_CONTROL_DISABLED', 0);

if(!defined('NO_DEVICE_TOKEN')) define("NO_DEVICE_TOKEN", "NO_DEVICE_TOKEN");

if(!defined('PLAN_TYPE_MONTH')) define('PLAN_TYPE_MONTH', 'months');

if(!defined('PLAN_TYPE_YEAR')) define('PLAN_TYPE_YEAR', 'years');

if(!defined('PLAN_TYPE_WEEK')) define('PLAN_TYPE_WEEK', 'weeks');

if(!defined('PLAN_TYPE_DAY')) define('PLAN_TYPE_DAY', 'days');

if(!defined('TODAY')) define('TODAY', 'today');

if(!defined('COMPLETED')) define('COMPLETED',3);

if(!defined('SORT_BY_APPROVED')) define('SORT_BY_APPROVED',1);

if(!defined('SORT_BY_DECLINED')) define('SORT_BY_DECLINED',2);

if(!defined('SORT_BY_EMAIL_VERIFIED')) define('SORT_BY_EMAIL_VERIFIED',3);

if(!defined('SORT_BY_EMAIL_NOT_VERIFIED')) define('SORT_BY_EMAIL_NOT_VERIFIED',4);

if(!defined('SORT_BY_DOCUMENT_VERIFIED')) define('SORT_BY_DOCUMENT_VERIFIED',5);

if(!defined('SORT_BY_DOCUMENT_APPROVED')) define('SORT_BY_DOCUMENT_APPROVED',6);

if(!defined('SORT_BY_DOCUMENT_PENDING')) define('SORT_BY_DOCUMENT_PENDING',7);


if(!defined('STATIC_PAGE_SECTION_1')) define('STATIC_PAGE_SECTION_1', 1);

if(!defined('STATIC_PAGE_SECTION_2')) define('STATIC_PAGE_SECTION_2', 2);

if(!defined('STATIC_PAGE_SECTION_3')) define('STATIC_PAGE_SECTION_3', 3);

if(!defined('STATIC_PAGE_SECTION_4')) define('STATIC_PAGE_SECTION_4', 4);


if(!defined('TAKE_COUNT')) define('TAKE_COUNT', 12);

if(!defined('SHOW')) define('SHOW', 1);

if(!defined('HIDE')) define('HIDE', 0);

if(!defined('READ')) define('READ', 1);

if(!defined('UNREAD')) define('UNREAD', 0);

if(!defined('PUBLISHED')) define('PUBLISHED',1);

if(!defined('UNPUBLISHED')) define('UNPUBLISHED', 0);


if(!defined('USER_DOCUMENT_NONE')) define('USER_DOCUMENT_NONE', 0);
if(!defined('USER_DOCUMENT_PENDING')) define('USER_DOCUMENT_PENDING', 1);
if(!defined('USER_DOCUMENT_APPROVED')) define('USER_DOCUMENT_APPROVED', 2);
if(!defined('USER_DOCUMENT_DECLINED')) define('USER_DOCUMENT_DECLINED', 3);


if(!defined('SORT_BY_HIGH')) define('SORT_BY_HIGH',1);
if(!defined('SORT_BY_LOW')) define('SORT_BY_LOW',2);
if(!defined('SORT_BY_FREE')) define('SORT_BY_FREE',3);
if(!defined('SORT_BY_PAID')) define('SORT_BY_PAID',4);

if(!defined('SUPPORT_CHAT_TYPE_USER_TO_SUPPORT')) define('SUPPORT_CHAT_TYPE_USER_TO_SUPPORT', 'UTS');
if(!defined('SUPPORT_CHAT_TYPE_SUPPORT_TO_USER')) define('SUPPORT_CHAT_TYPE_SUPPORT_TO_USER', 'STU');

if(!defined('STORAGE_TYPE_S3')) define('STORAGE_TYPE_S3', 1);
if(!defined('STORAGE_TYPE_LOCAL')) define('STORAGE_TYPE_LOCAL', 0);


if(!defined('TYPE_PUBLIC')) define('TYPE_PUBLIC', 'public');
if(!defined('TYPE_PRIVATE')) define('TYPE_PRIVATE', 'private');

if(!defined('PROJECT_PUBLISH_STATUS_INITIATED')) define('PROJECT_PUBLISH_STATUS_INITIATED', 'initiated');
if(!defined('PROJECT_PUBLISH_STATUS_OPENED')) define('PROJECT_PUBLISH_STATUS_OPENED', 'opened');
if(!defined('PROJECT_PUBLISH_STATUS_CLOSED')) define('PROJECT_PUBLISH_STATUS_CLOSED', 'closed');
if(!defined('PROJECT_PUBLISH_STATUS_SCHEDULED')) define('PROJECT_PUBLISH_STATUS_SCHEDULED', 'scheduled');


if(!defined('TOKEN_PAYMENT_PAID')) define('TOKEN_PAYMENT_PAID', 1);

if(!defined('TOKEN_PAYMENT_UNPAID')) define('TOKEN_PAYMENT_UNPAID', 0);


if(!defined('CLAIM_INITIATED')) define('CLAIM_INITIATED', 0);

if(!defined('CLAIM_PAID')) define('CLAIM_PAID', 1);

if(!defined('CLAIM_UNPAID')) define('CLAIM_UNPAID', 2);

if(!defined('ACCESS_PENDING')) define('ACCESS_PENDING', 0);

if(!defined('ACCESS_GRANTED')) define('ACCESS_GRANTED', 1);

if(!defined('ACCESS_REVOKED')) define('ACCESS_REVOKED', 2);


if(!defined('CONTACT_FORM_INITIATED')) define('CONTACT_FORM_INITIATED', 1);
if(!defined('CONTACT_FORM_COMPLETED')) define('CONTACT_FORM_COMPLETED', 2);
if(!defined('CONTACT_FORM_DECLINED')) define('CONTACT_FORM_DECLINED', 3);