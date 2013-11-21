<?php
/**
 * This File is Part of Mailchimp Bundle Provided by Prolix Technikos
 *
 * @author     Ravindra Khokharia <ravindrakhokharia@gmail.com>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version    1.0
 * @link       https://github.com/prolixtechnikos/mailchimp-bundle
 * @since      version 1.0
 */

namespace Prolix\MailchimpBundle\Exception;

/**
 * Custom Exception Class for Mailchimp Errors
 *
 * @package    MailchimpBundle
 * @subpackage Exception
 */
class MailchimpAPIException extends \Exception
{
    /**
     * Constructor
     *
     * @param array $data Exception Data extracted from Mailchimp
     */
    public function __construct($data)
    {
        parent::__construct(sprintf('Mailchimp API error : [ %s ] %s , code = %s', $data['name'], $data['error'], $data['code']));
    }

}
