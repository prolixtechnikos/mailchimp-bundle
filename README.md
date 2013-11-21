Prolix MailchimpBundle for API V2.0
========================

Symfony2.x bundle for 
[MailChimp](http://apidocs.mailchimp.com/api/2.0/) API V2 and [Export API](http://apidocs.mailchimp.com/export/1.0/) API V1
Wrapper bundle that makes accessing Mailchimp functions easily in object oriented using method chaining 


**License**

ProlixMailChimpBundle released under MIT LICENSE 

#Supported API Methods

**Campaigns related**

1. `campaigns/create`
2. `campaigns/content`
2. `campaigns/list`
2. `campaigns/delete`
2. `campaigns/pause`
2. `campaigns/ready`
2. `campaigns/replicate`
2. `campaigns/ready`
2. `campaigns/resume`
2. `campaigns/send`
2. `campaigns/send-test`
2. `campaigns/segment-test`
2. `campaigns/schedule`
2. `campaigns/schedule-batch`
2. `campaigns/unschedule`
2. `campaigns/update`

**Lists related**

1. `lists/abuse-reports`
1. `lists/activity`
1. `lists/subscribe`
1. `lists/unsubscribe`
1. `lists/member-info`
1. `lists/interest-groupings`
1. `lists/interest-grouping-add`
1. `lists/interest-grouping-del`
1. `lists/interest-grouping-update`
1. `lists/interest-group-add`
1. `lists/interest-group-update`
1. `lists/interest-group-del`

**Templates related**

1. `templates/add`
1. `templates/list`
1. `templates/del`
1. `templates/info`
1. `templates/undel`


Need support for a method not on the list submit an [issue](https://github.com/prolixtechnikos/mailchimp-bundle/issues/new)

## Setup

### Step 1: Download ProlixMailchimp using composer

Add ProlixMailchimp in your composer.json:

```js
{
    "require": {
        "prolixtechnikos/mailchimp-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update "prolixtechnikos/mailchimp-bundle"
```

Composer will install the bundle to your project's `vendor/prolixtechnikos/mailchimp-bundle` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Prolix\MailchimpBundle\ProlixMailchimpBundle(),
    );
}
```

### Step 3: Add configuration

``` yml
# app/config/config.yml
prolix_mailchimp:
    api_key: xxxxxxx-us5
    default_list: xxxxxxxx
    ssl: true #optional configuring curl connection
    # this will hold the curl options. Just use the php curl option constant as key and value
    curl_options:  
      curlopt_useragent: ProlixMailChimp
      curlopt_timeout: 30 
```

## Usage

**Using service**

``` php
<?php
        $mailchimp = $this->get('mailchimp');
?>
```

##Examples

###Create new campaign
``` php
<?php 
    $campaignApi = $this->get('mailchimp.campaign');
    $data = $campaignApi->create('regular', 
    array(
        'list_id' => 'xxxxxxxx',
        'from_name' => 'Ravindra Khokharia',
        'from_email' => 'ravindrakhokharia@gmail.com',
        'subject' => 'Subscribe to Prolix NewsLetter',
        'to_name' => 'ProlixTechnikos Subscriber'),
    array(
        'archive' => 'test'
        'sections' => array(),
        'text' => 'test',
        'html' => '<b>Test HTML Data</b>',
        'url' => 'http://www.prolixtechnikos.com',
    ));

    var_dump($data);
?>
```
###Delete existing campaign
``` php
<?php 
    $campaignApi = $this->get('mailchimp.campaign');
    $data = $campaignApi->setCampaignId('xxxxxxxx')->delete();

    var_dump($data);
?>
```

###Send campaign
``` php
<?php 
    $campaignApi = $this->get('mailchimp.campaign');
    $data = $campaignApi->setCampaignId('xxxxxxxx')->send();

    var_dump($data);
?>
```

###Subscribe new user to list
``` php
<?php 
    $listApi = $this->get('mailchimp.list');
    $data = $listApi->subscribe('subscriber@prolixtechnikos.com');
    
    var_dump($data);
?>
```
**Note** that the user will be subscriber to the default list set in `config.yml` 
if you want to change the list for this time only, you can use 
``` php
<?php 
    $listApi = $this->get('mailchimp.list');
    $data = $listApi->setListId('xxxxxxx')
        ->subscribe('subscriber@prolixtechnikos.com');

    var_dump($data);
?>
```