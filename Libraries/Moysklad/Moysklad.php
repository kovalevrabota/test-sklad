<?php
namespace Libraries\Moysklad;

/**
 * Class Moysklad
 * @package Libraries\Moysklad
 */
class Moysklad
{
    /**
     * @var string
     */
    const API_PATH = 'https://api.moysklad.ru/api/remap/1.2/';

    /**
     * @var string
     */
    private $password = null;

    /**
     * Moysklad constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get order list.
     *
     * @return array
     */
    public function getOrder() : array
    {
        $result = $this->curlTg('entity/customerorder?order=name,desc');

        return $result;
    }

    /**
     * Get states list.
     *
     * @return array
     */
    public function getStates() : array
    {
        $result = $this->curlTg('entity/customerorder/metadata');

        return $result;
    }

    /**
     * Get agents list.
     *
     * @return array
     */
    public function getAgents() : array 
    {
        $result = $this->curlTg('entity/counterparty');

        return $result;
    }

    /**
     * Get organizations list.
     *
     * @return array
     */
    public function getOrganizations() : array
    {
        $result = $this->curlTg('entity/organization');

        return $result;
    }

    /**
     * Get currency list.
     *
     * @return array
     */
    public function getCurrency() : array
    {
        $result = $this->curlTg('entity/currency');

        return $result;
    }

    /**
     * Update state.
     *
     * @param string $orderId
     * @param string $stateId
     * 
     * @return array
     */
    public function updateState(string $orderId, string $stateId) : array 
    {
        $data = array(
            'state' => array(
                'meta' => array(
                    'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/states/' . $stateId,
                    'metadataHref' => 'https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata',
                    'type' => 'state',
                    'mediaType' => 'application/json'
                ))
        );

        $result = $this->curlTg('entity/customerorder/' . $orderId, 'PUT', $data);

        return $result;
    }

    /**
     * Check login.
     * 
     * @param string $password
     *
     * @return array
     */
    public static function checkLogin(string $password) : array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.moysklad.ru/api/remap/1.2/security/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Authorization: Basic ' . $password;
        $headers[] = 'Accept-Encoding: gzip';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * Curl.
     *
     * @param string $path
     * @param string $type
     * @param array $data
     * @param bool $gzip
     * @param mixed $password
     * 
     * @return array
     */
    private function curlTg(string $path, string $type = 'GET', array $data = array(), bool $gzip = true, mixed $password = null) : array
    {
        $password = $password ? $password : $this->password;

        $headers = array();
        $headers[] = 'Authorization: Basic ' . $password;
        $headers[] = 'Accept-Encoding: gzip';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::API_PATH . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if($type == 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        }elseif($type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }elseif($type == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

            if(!empty($data) && count($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close($ch);
        
        if($gzip) {
            $result = gzdecode($result);
        }
        
        $result = json_decode($result, true);

        return $result;
    }
}