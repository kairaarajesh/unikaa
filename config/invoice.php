<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Invoice Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for invoice generation
    | including company details, payment information, and styling options.
    |
    */

    'company' => [
        'name' => env('INVOICE_COMPANY_NAME', 'UNIKAA CRM'),
        'address' => env('INVOICE_COMPANY_ADDRESS', '123 Business Street'),
        'city' => env('INVOICE_COMPANY_CITY', 'City, State 12345'),
        'phone' => env('INVOICE_COMPANY_PHONE', '(555) 123-4567'),
        'email' => env('INVOICE_COMPANY_EMAIL', 'info@unikaacrm.com'),
        'website' => env('INVOICE_COMPANY_WEBSITE', 'www.unikaacrm.com'),
        'logo' => env('INVOICE_COMPANY_LOGO', null), // Path to logo image
    ],

    'payment' => [
        'terms' => env('INVOICE_PAYMENT_TERMS', 'Due upon receipt'),
        'methods' => env('INVOICE_PAYMENT_METHODS', 'Cash, Check, Bank Transfer, Credit Card'),
        'bank_name' => env('INVOICE_BANK_NAME', 'Sample Bank'),
        'bank_account' => env('INVOICE_BANK_ACCOUNT', '1234567890'),
        'bank_routing' => env('INVOICE_BANK_ROUTING', '123456789'),
        'currency' => env('INVOICE_CURRENCY', 'USD'),
        'currency_symbol' => env('INVOICE_CURRENCY_SYMBOL', '$'),
    ],

    'invoice' => [
        'prefix' => env('INVOICE_PREFIX', 'INV'),
        'number_format' => env('INVOICE_NUMBER_FORMAT', '0000'), // Leading zeros
        'auto_increment' => env('INVOICE_AUTO_INCREMENT', true),
        'default_notes' => env('INVOICE_DEFAULT_NOTES', 'Thank you for your business!'),
        'footer_text' => env('INVOICE_FOOTER_TEXT', 'This is a computer generated invoice. No signature required.'),
    ],

    'styling' => [
        'primary_color' => env('INVOICE_PRIMARY_COLOR', '#2c3e50'),
        'secondary_color' => env('INVOICE_SECONDARY_COLOR', '#f8f9fa'),
        'font_family' => env('INVOICE_FONT_FAMILY', 'DejaVu Sans, Arial, sans-serif'),
        'font_size' => env('INVOICE_FONT_SIZE', '12px'),
        'show_logo' => env('INVOICE_SHOW_LOGO', true),
        'show_payment_info' => env('INVOICE_SHOW_PAYMENT_INFO', true),
    ],

    'email' => [
        'subject_template' => env('INVOICE_EMAIL_SUBJECT', 'Invoice #{invoice_number} - {company_name}'),
        'from_name' => env('INVOICE_EMAIL_FROM_NAME', 'UNIKAA CRM'),
        'from_email' => env('INVOICE_EMAIL_FROM_EMAIL', 'invoices@unikaacrm.com'),
        'reply_to' => env('INVOICE_EMAIL_REPLY_TO', 'support@unikaacrm.com'),
    ],

    'pdf' => [
        'paper_size' => env('INVOICE_PDF_PAPER_SIZE', 'A4'),
        'orientation' => env('INVOICE_PDF_ORIENTATION', 'portrait'),
        'margin_top' => env('INVOICE_PDF_MARGIN_TOP', 10),
        'margin_bottom' => env('INVOICE_PDF_MARGIN_BOTTOM', 10),
        'margin_left' => env('INVOICE_PDF_MARGIN_LEFT', 10),
        'margin_right' => env('INVOICE_PDF_MARGIN_RIGHT', 10),
    ],
];
