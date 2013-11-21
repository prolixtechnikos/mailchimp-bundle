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

use Prolix\MailchimpBundle\Mailchimp\MailchimpAPIException,
    Buzz\Exception\InvalidArgumentException as InvalidArgumentException;

/**
 * Custom Exception Class for Mailchimp Errors
 *
 * @package    MailchimpBundle
 * @subpackage Services
 */
class Lists
{

    protected $merge_vars = array();
    protected $grouping_id = NULL;
    protected $group_name = NULL;

    /**
     * set list id
     *
     * @param string $listId
     *
     * @return \Prolix\MailchimpBundle\Services\Lists
     */
    public function setListId($listId)
    {
        $this->listId = $listId;

        return $this;
    }

    /**
     * set grouping id
     *
     * @param int $grouping_id grouping id
     *
     * @return \Prolix\MailchimpBundle\Services\Lists
     */
    public function setGrouping_id($grouping_id)
    {
        $this->grouping_id = $grouping_id;

        return $this;
    }

    /**
     * Add to merge vars array
     *
     * @param mix $merge_vars
     *
     * @return \Prolix\MailchimpBundle\Services\Lists
     */
    public function addMerge_vars($merge_vars)
    {
        $this->merge_vars[] = $merge_vars;

        return $this;
    }

    /**
     * set to merge vars
     *
     * @param mix $merge_vars
     *
     * @return \Prolix\MailchimpBundle\Services\Lists
     */
    public function setMerge_vars($merge_vars)
    {
        $this->merge_vars = $merge_vars;

        return $this;
    }

    /**
     * Get all email addresses that complained about a campaign sent to a list
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/abuse-reports.php
     *
     * @param int    $start
     * @param int    $limit
     * @param string $string
     *
     * @return array
     * @throws MailchimpAPIException
     */
    public function abuseReport($start = 0, $limit = 2000, $string = null)
    {
        $payload = array(
            'id' => $this->listId,
            'start' => $start,
            'limit' => $limit,
            'string' => $string
        );
        $apiCall = 'lists/abuse-reports';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Access up to the previous 180 days of daily detailed aggregated activity stats for a given list. Does not include AutoResponder activity.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/activity.php
     * @return array
     * @throws MailchimpAPIException
     */
    public function getActivity()
    {
        $payload = array(
            'id' => $this->listId
        );
        $apiCall = 'lists/activity';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    public function batchSubscribe()
    {
        $payload = array(
            'id' => $this->listId
        );
        $apiCall = 'lists/batch-subscribe';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Subscribe a batch of email addresses to a list at once,
     * These calls are also long, so be sure you increase your timeout values
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/batch-subscribe.php
     *
     * @param string  $email
     * @param string  $email_type
     * @param boolean $double_optin      optional
     * @param boolean $update_existing   optional
     * @param boolean $replace_interests optional
     * @param boolean $send_welcome      optional
     * @param string  $email_identifier  optional can be (email,euid, leid)
     *
     * @return array
     * @throws MailchimpAPIException
     */
    public function subscribe($email_id, $email_type = 'html', $double_optin = true, $update_existing = true, $replace_interests = true, $send_welcome = false, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'merge_vars' => $this->merge_vars,
            'email_type' => $email_type,
            'double_optin' => $double_optin,
            'update_existing' => $update_existing,
            'replace_interests' => $replace_interests,
            'send_welcome' => $send_welcome
        );

        $apiCall = 'lists/subscribe';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Unsubscribe the given email address from the list
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/unsubscribe.php
     *
     * @param  string  $email_id
     * @param  boolean $delete_member
     * @param  boolean $send_goodbye
     * @param  boolean $send_notify
     * @param  string  $email_identifier optional can be (email,euid, leid)
     * @return boolean true on success
     *
     * @throws InvalidArgumentException
     * @throws MailchimpAPIException
     */
    public function unsubscribe($email_id, $delete_member = false, $send_goodbye = true, $send_notify = true, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'delete_member' => $delete_member,
            'send_goodbye' => $send_goodbye,
            'send_notify' => $send_notify
        );

        $apiCall = 'lists/unsubscribe';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Get all the information for particular members of a list
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/member-info.php
     *
     * @param mix    $email_id         email id or ids array of emails or string
     * @param string $email_identifier optional can be (email,euid, leid)
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function memberInfo($email_id, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');
        $email_ids = array();
        if (is_array($email_id)) {
            foreach ($email_id as $email) {
                $email_ids[] = array($email_identifier => $email);
            }
        } else {
            $email_ids[] = array($email_identifier => $email_id);
        }
        $payload = array(
            'id' => $this->listId,
            'emails' => $email_ids
        );
        $apiCall = 'lists/member-info';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Edit the email address, merge fields, and interest groups for a list member. If you are doing a batch update on lots of users, consider using listBatchSubscribe() with the update_existing and possible replace_interests parameter.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/update-member.php
     *
     * @param string  $email_id          optinal
     * @param string  $email_type        optional can be "html" or "text", defaults "html"
     * @param boolean $replace_interests optional
     * @param string  $email_identifier  optional can be (email,euid, leid)
     *
     * @return array                    email information (email,euid, leid)
     * @throws InvalidArgumentException
     * @throws MailchimpAPIException
     */
    public function updateMember($email_id, $email_type = 'html', $replace_interests = true, $email_identifier = 'email')
    {
        if (!in_array($email_identifier, array("email", "euid", "leid")))
            throw new InvalidArgumentException('email identifier should be one of ("email","euid","leid")');

        $payload = array(
            'id' => $this->listId,
            'email' => array(
                $email_identifier => $email_id
            ),
            'merge_vars' => $this->merge_vars,
            'email_type' => $email_type,
            'replace_interests' => $replace_interests
        );

        $apiCall = 'lists/update-member';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Get the list of interest groupings for a given list, including the label, form information, and included groups for each
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-groupings.php
     *
     * @param bool $count optional wether to get subscriber count or not
     *
     * @return array                 all groups information for specific list
     * @throws MailchimpAPIException
     */
    public function interestGroupings($count = null)
    {
        $payload = array(
            'id' => $this->listId,
            'count' => $count
        );

        $apiCall = 'lists/interest-groupings';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Add a new Interest Grouping - if interest groups for the List are not yet enabled, adding the first grouping will automatically turn them on.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-add.php
     *
     * @param string $name   the interest grouping to add - grouping names must be unique
     * @param string $type   The type of the grouping to add - one of "checkboxes", "hidden", "dropdown", "radio"
     * @param array  $groups The lists of initial group names to be added - at least 1 is required and the names must be unique within a grouping. If the number takes you over the 60 group limit
     *
     * @return array                 contains id of the new group
     * @throws MailchimpAPIException
     */
    public function addInterestGroupings($name, $type, array $groups)
    {
        $payload = array(
            'id' => $this->listId,
            'name' => $name,
            'type' => $type,
            'groups' => $groups
        );

        $apiCall = 'lists/interest-grouping-add';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return isset($data) ? $data : false;
    }

    /**
     * Delete an existing Interest Grouping - this will permanently delete all contained interest groups and will remove those selections from all list members
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-del.php
     *
     * @param int $group_id optional the interest grouping id
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function deleteInterestGrouping($group_id = false)
    {
        $payload = array(
            'grouping_id' => (FALSE === $group_id) ? $this->grouping_id : $group_id
        );

        $apiCall = 'lists/interest-grouping-del';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Update an existing Interest Grouping
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-grouping-update.php
     *
     * @param string $name     The name of the field to update - either "name" or "type". Groups within the grouping should be manipulated using the standard listInterestGroup* methods
     * @param string $value    The new value of the field. Grouping names must be unique - only "hidden" and "checkboxes" grouping types can be converted between each other.
     * @param int    $group_id optional unless not has been set before
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function updateInterestGrouping($name, $value, $group_id = false)
    {
        $payload = array(
            'grouping_id' => (FALSE === $group_id) ? $this->grouping_id : $group_id,
            'name' => $name,
            'value' => $value
        );

        $apiCall = 'lists/interest-grouping-update';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Add a single Interest Group - if interest groups for the List are not yet enabled, adding the first group will automatically turn them on.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-add.php
     *
     * @param string $name     the interest group to add - group names must be unique within a grouping
     * @param int    $group_id optional The grouping to add the new group to - get using listInterestGrouping() . If not supplied, the first grouping on the list is used.
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function addInterestGroup($name, $group_id = NULL)
    {
        $payload = array(
            'id' => $this->listId,
            'group_name' => $name,
            'grouping_id' => (NULL === $group_id) ? $this->grouping_id : $group_id,
        );

        $apiCall = 'lists/interest-group-add';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Change the name of an Interest Group
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-update.php
     *
     * @param string $old_name    the interest group name to be changed
     * @param string $new_name    the new interest group name to be set
     * @param int    $grouping_id optional  The grouping to delete the group from  If not supplied, the first grouping on the list is used.
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function updateInterestGroup($old_name, $new_name, $grouping_id = NULL)
    {
        $payload = array(
            'id' => $this->listId,
            'old_name' => $old_name,
            'new_name' => $new_name,
            'grouping_id' => (NULL === $grouping_id) ? $this->grouping_id : $grouping_id
        );

        $apiCall = 'lists/interest-group-update';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

    /**
     * Delete a single Interest Group - if the last group for a list is deleted, this will also turn groups for the list off.
     *
     * @link http://apidocs.mailchimp.com/api/2.0/lists/interest-group-del.php
     *
     * @param string $name        the name of interest group to delete
     * @param int    $grouping_id optional The grouping to delete the group from. If not supplied, the first grouping on the list is used
     *
     * @return boolean               true on success
     * @throws MailchimpAPIException
     */
    public function delInterestGroup($name, $grouping_id = NULL)
    {
        $payload = array(
            'id' => $this->listId,
            'group_name' => $name,
            'grouping_id' => (NULL === $grouping_id) ? $this->grouping_id : $grouping_id,
        );

        $apiCall = 'lists/interest-group-del';
        $data = $this->mailchimp->request($apiCall, $payload);
        $data = json_decode($data, true);
        if (isset($data['error']))
            throw new MailchimpAPIException($data);
        else
            return true;
    }

}
