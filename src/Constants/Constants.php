<?php

namespace App\Constants;

class Constants
{

    // EMAILS ADDRESS

    //const EMAIL_CONTACT = 'hrdominguez89@gmail.com';
    // const EMAIL_PRICE_LIST = 'hrdominguez89@gmail.com';


    // END EMAILS ADDRESS


    // EMAIL TYPES

    const EMAIL_TYPE_VALIDATION = 1;
    const EMAIL_TYPE_WELCOME = 2;
    const EMAIL_TYPE_FORGET_PASSWORD = 3;
    const EMAIL_TYPE_PASSWORD_CHANGE_REQUEST = 4;
    const EMAIL_TYPE_PASSWORD_CHANGE_SUCCESSFUL = 5;
    const EMAIL_TYPE_CONTACT = 6;
    const EMAIL_TYPE_PRICE_LIST = 7;
    const EMAIL_TYPE_WELCOME_BACKOFFICE = 8;




    // END EMAIL TYPES

    // STATUS EMAIL

    const EMAIL_STATUS_PENDING = 1;
    const EMAIL_STATUS_ERROR = 2;
    const EMAIL_STATUS_SENT = 3;
    const EMAIL_STATUS_CANCELED = 4;

    // END STATUS EMAIL

    // STATUS CUSTOMER

    const CUSTOMER_STATUS_PENDING = 1;
    const CUSTOMER_STATUS_VALIDATED = 2;
    const CUSTOMER_STATUS_DISABLED = 3;

    // END STATUS CUSTOMER


    // STATUS COMMUNICATION BETWEN PLATFORMS

    const CBP_STATUS_PENDING = 1;
    const CBP_STATUS_ERROR = 2;
    const CBP_STATUS_SENT = 3;

    // END COMMUNICATION BETWEN PLATFORMS


    //REGISTRATION TYPE

    const REGISTRATION_TYPE_WEB = 1;
    const REGISTRATION_TYPE_BACKEND = 2;
    const REGISTRATION_TYPE_IMPORT = 3;

    // END REGISTRATION TYPE

    //STATUS SHOPPING CART TYPE

    const STATUS_SHOPPING_CART_ACTIVO = 1;
    const STATUS_SHOPPING_CART_ELIMINADO = 2;
    const STATUS_SHOPPING_CART_EN_ORDEN = 3;

    // END STATUSSHOPPINGCART TYPE

    //STATUS FAVORITE TYPE

    const STATUS_FAVORITE_ACTIVO = 1;
    const STATUS_FAVORITE_ELIMINADO = 2;
    const STATUS_FAVORITE_EN_CARRITO = 3;

    // END STATUSFAVORITETYPE

    //SPECIFICATION TYPES

    const SPECIFICATION_SCREEN_RESOLUTION = 1;
    const SPECIFICATION_SCREEN_SIZE = 2;
    const SPECIFICATION_CPU = 3;
    const SPECIFICATION_GPU = 4;
    const SPECIFICATION_MEMORY = 5;
    const SPECIFICATION_STORAGE = 6;
    const SPECIFICATION_SO = 7;
    const SPECIFICATION_COLOR = 8;
    const SPECIFICATION_MODEL = 9;


    //FIN SPECIFICATION TYPES

    //STATUS ORDERS

    const STATUS_ORDER_OPEN = 1;
    const STATUS_ORDER_PARTIALLY_SHIPPED = 2;
    const STATUS_ORDER_SHIPPED = 3;
    const STATUS_ORDER_PICKED = 4;
    const STATUS_ORDER_PACKED = 5;
    const STATUS_ORDER_CONFIRMED = 6;
    const STATUS_ORDER_CANCELED = 7;
    const STATUS_ORDER_PENDING = 8;

    //FIN STATUS ORDERS

    // ROLES

    const ROLE_SUPER_ADMIN = 1;
    const ROLE_SUCURSAL = 2;

    // FIN ROLES

}
