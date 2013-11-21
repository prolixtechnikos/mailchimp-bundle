<?php
/**
 * This File is Part of Mailchimp Bundle Provided by Prolix Technikos
 *
 * @author  Ravindra Khokharia <ravindrakhokharia@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version 1.0
 * @link    https://github.com/prolixtechnikos/mailchimp-bundle
 * @since   version 1.0
 */

namespace Prolix\MailchimpBundle\Services;

use Buzz\Client\Curl,
    Buzz\Browser;

/**
 * Sevice Class for MailchimpBundle
 * Main Contact Point For calling all Api Requests
 *
 * @package    MailchimpBundle
 * @subpackage Services
 */
class MailChimp
{
    protected $apiKey;
    protected $listId;
    protected $dataCenter;
    protected $container;
    protected $browser;

    public function __construct($container)
    {
        $this->container = $container;
        $this->apiKey = $this->container->getParameter('prolix_mailchimp.api_key');
        $this->listId = $this->container->getParameter('prolix_mailchimp.default_list');

        $key = preg_split("/-/", $this->apiKey);

        if ($this->container->getParameter('prolix_mailchimp.ssl')) {
            $this->dataCenter = 'https://' . $key[1] . '.api.mailchimp.com/';
        } else {
            $this->dataCenter = 'http://' . $key[1] . '.api.mailchimp.com/';
        }

        if (!function_exists('curl_init')) {
            throw new \Exception('This bundle needs the cURL PHP extension.');
        }
        $this->initBrowser($this->container->getParameter('prolix_mailchimp.curl_options'));
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function initBrowser($curlOptions)
    {
        $curl = new Curl();
        foreach ($curlOptions as $key => $option) {
            $curl->setOption(constant(strtoupper($key)), $option);
        }

        //to avoid ssl certificate error
        $curl->setVerifyPeer(false);
        $this->browser = new Browser($curl);
    }

    /**
     * Set mailing list id
     *
     * @param string $listId mailing list id
     */
    public function setListID($listId)
    {
        $this->listId = $listId;
    }

    /**
     * get mailing list id
     *
     * @return string $listId
     */
    public function getListID()
    {
        return $this->listId;
    }

    /**
     *
     * @return string
     */
    public function getDatacenter()
    {
        return $this->dataCenter;
    }

    /**
     *
     * @return \Prolix\MailchimpBundle\Services\Lists
     */
    public function getList()
    {
        return $this->container('prolix.mailchimp.list');
    }

    /**
     *
     * @return \Prolix\MailchimpBundle\Services\Campaign
     */
    public function getCampaign()
    {
        return $this->container('prolix.mailchimp.campaign');
    }

    /**
     *
     * @return \Prolix\MailchimpBundle\Services\Template
     */
    public function getTemplate()
    {
        return $this->container('prolix.mailchimp.template');
    }

    /**
     * Prepare the curl request
     *
     * @param string $apiCall the API call function
     * @param array  $payload Parameters
     *
     * @return array
     */
    public function request($apiCall, $payload)
    {
        $payload['apikey'] = $this->apiKey;

        $url = $this->dataCenter . '2.0/' . $apiCall;

        $payload = json_encode($payload);

        $headers = array(
            "Accept" => "application/json",
            "Content-type" => "application/json"
        );
        $response = $this->browser->post($url, $headers, $payload);

        return $response->getContent();
    }

}
