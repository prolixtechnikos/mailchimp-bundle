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

use Prolix\MailchimpBundle\Mailchimp\RestClient,
    Prolix\MailchimpBundle\Mailchimp\MailchimpAPIException;

/**
 * Custom Exception Class for Mailchimp Errors
 *
 * @package    MailchimpBundle
 * @subpackage Services
 */
class Template
{

    protected $templateId=null;

    /**
     * Set template id
     *
     * @param type $templateId
     *
     * @return \Prolix\MailchimpBundle\Services\MCTemplate
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

        /**
     * Create a new user template, NOT campaign content. These templates can then be applied while creating campaigns.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/add.php
     *
     * @param string $name     the name for the template - names must be unique and a max of 50 bytes
     * @param string $html     a string specifying the entire template to be created. This is NOT campaign conten
     * @param int    $folderId optional the folder to put this template in.
     *
     * @return int                   template_id on success
     * @throws MailchimpAPIException
     */
    public function add($name, $html, $folderId = null)
    {
        $payload = array(
            'name' => $name,
            'html' => $html,
            'folder_id' => $folderId
        );
        $apiCall = 'templates/add';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data['template_id']) ? $data['template_id'] : false;
    }

    /**
     * Retrieve various templates available in the system, allowing some thing similar to our template gallery to be created.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/list.php
     *
     * @param array $types   optional the types of templates to return
     * @param array $filters optional options to control how inactive templates are returned, if at all
     *
     * @return array
     * @throws MailchimpAPIException
     */
    public function listAll($types = array(), $filters = array())
    {
        $payload = array(
            'types' => $types,
            'filters' => $filters
        );
        $apiCall = 'templates/list';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data;
    }

    /**
     * Delete (deactivate) a user template
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/del.php
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function delete()
    {
        $payload = array(
            'template_id' => $this->templateId
        );
        $apiCall = 'templates/del';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Pull details for a specific template to help support editing
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/info.php
     *
     * @param string $type optional the template type to load - one of 'user', 'gallery', 'base', defaults to user.
     *
     * @return array
     * @throws MailchimpAPIException
     */
    public function info($type = 'user')
    {
        $payload = array(
            'template_id' => $this->templateId,
            'type' => $type
        );
        $apiCall = 'templates/info';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return $data;
    }

    /**
     * Undelete (reactivate) a user template
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/undel.php
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function undelete()
    {
        $payload = array(
            'template_id' => $this->templateId
        );
        $apiCall = 'templates/undel';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Replace the content of a user template, NOT campaign content.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/templates/update.php
     *
     * @param array $options the values to updates - while both are optional, at least one should be provided. Both can be updated at the same time.
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function update( $options = array())
    {
        $payload = array(
            'template_id' => $this->templateId,
            'values' => $options
        );
        $apiCall = 'templates/update';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data,true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /*     * ********************************** Custom functions ***************************** */

    /**
     * Delete template by name
     *
     * @param string $name
     *
     * @return boolean               on success
     * @throws MailchimpAPIException
     */
    public function deleteByName($name)
    {
        return $this->del($this->getIdByName($name));
    }

    /**
     * Get template id by Name
     *
     * @param type $name name of the template
     *
     * @return int|boolean $id template_id or false
     */
    public function getByName($name)
    {
        $templates = $this->listAll();
        if (empty($templates['user']))
            return false;
        foreach ($templates['user'] as $template) {
            if ($template['name'] === $name) {
                return $template['id'];
            }
        }

        return false;
    }

}
