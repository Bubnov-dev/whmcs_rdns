<?php

namespace WHMCS\Module\Addon\whmcs_rdns\Client;

/**
 * Sample Client Area Controller
 */
class Controller {

    /**
     * Index action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function index($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. whmcs_rdnss.php?module=whmcs_rdns
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables

        // Get module configuration parameters
        $configTextField = $vars['Text Field Name'];
        $configPasswordField = $vars['Password Field Name'];
        $configCheckboxField = $vars['Checkbox Field Name'];
        $configDropdownField = $vars['Dropdown Field Name'];
        $configRadioField = $vars['Radio Field Name'];
        $configTextareaField = $vars['Textarea Field Name'];

        $base = 'https://api.selectel.ru/domains/v1/';
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $base . 'ptr/',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "X-Token: tvPVEXfRSSPfY9IXyU2TngeHu_83138",
            )

        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);

        $response = json_decode($response, true);


        $uid = $_SESSION['uid'];
        $command = 'GetClientsProducts';
        $postData = array(
            'clientid' => $uid,
            'stats' => true,
        );

        $results = localAPI($command, $postData);

        $ips = [];
        var_dump($results['products']);
        echo "<br>";

        echo "<br>";

        foreach ($results['products']['product']  as $product){
            var_dump($product);
            echo "<br>";
            echo $product['dedicatedip'];
            echo "<br>";
            echo $product['assignedips'];
            echo "<br>";
            echo "<br>";
            $ips = array_merge($ips, explode(',', $product['dedicatedip']), explode(' ', $product['assignedips']));

        }

        $ips = array_filter($ips, function($v){
            return $v!='';
        });



        return array(
            'pagetitle' => 'Sample Addon Module',
            'breadcrumb' => array(
                'index.php?m=whmcs_rdns' => 'Sample Addon Module',
            ),
            'templatefile' => 'publicpage',
            'requirelogin' => false, // Set true to restrict access to authenticated client users
            'forcessl' => false, // Deprecated as of Version 7.0. Requests will always use SSL if available.
            'vars' => array(
                'modulelink' => print_r($ips, true),
                'configTextField' => $response,
                'customVariable' => print_r($results, true),
            ),
        );
    }

    /**
     * Secret action.
     *
     * @param array $vars Module configuration parameters
     *
     * @return array
     */
    public function secret($vars)
    {
        // Get common module parameters
        $modulelink = $vars['modulelink']; // eg. whmcs_rdnss.php?module=whmcs_rdns
        $version = $vars['version']; // eg. 1.0
        $LANG = $vars['_lang']; // an array of the currently loaded language variables

        // Get module configuration parameters
        $configTextField = $vars['Text Field Name'];
        $configPasswordField = $vars['Password Field Name'];
        $configCheckboxField = $vars['Checkbox Field Name'];
        $configDropdownField = $vars['Dropdown Field Name'];
        $configRadioField = $vars['Radio Field Name'];
        $configTextareaField = $vars['Textarea Field Name'];

        return array(
            'pagetitle' => 'Sample Addon Module',
            'breadcrumb' => array(
                'index.php?m=whmcs_rdns' => 'Sample Addon Module',
                'index.php?m=whmcs_rdns&action=secret' => 'Secret Page',
            ),
            'templatefile' => 'secretpage',
            'requirelogin' => true, // Set true to restrict access to authenticated client users
            'forcessl' => false, // Deprecated as of Version 7.0. Requests will always use SSL if available.
            'vars' => array(
                'modulelink' => $modulelink,
                'configTextField' => $configTextField,
                'customVariable' => 'your own content goes here',
            ),
        );
    }
}
