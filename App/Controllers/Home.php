<?php
namespace App\Controllers;

use \Core\View;
use \Libraries\Moysklad\Moysklad;

/**
 * Class Home
 * @package App\Controllers
 */
class Home extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $moysklad = new Moysklad($_SESSION['password']);

        //Update state
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!empty($_POST['order_id']) && !empty($_POST['state_id'])) {
                $updateState = $moysklad->updateState($_POST['order_id'], $_POST['state_id']);

                echo json_encode(array('data' => $updateState)); exit;
            }else {
                echo json_encode(array('error' => true)); exit;
            }
        }

        //Order list
        $orders = $moysklad->getOrder();

        //States list
        $states = $moysklad->getStates();

        //Agents list
        $agents = $moysklad->getAgents();

        //organizations list
        $organizations = $moysklad->getOrganizations();

        //Currency list
        $currency = $moysklad->getCurrency();

        $data = array(
            'orders' => array(),
            'states' => $states['states'],
            'size' => $orders['meta']['size'],
            'summ' => 0,
            'login' => $_SESSION['login']
        );

        foreach($orders['rows'] as $item) {
            $agentItem = $item['agent']['meta'];
            $agentData = array();
            
            //agent information
            if (preg_match('/=.+/', $agentItem['uuidHref'], $matches)) {
                $agentId = substr($matches[0], 1);

                $key = array_search($agentId, array_column($agents['rows'], 'id'));

                if($key !== false) {
                    $agentData['id'] = $agentId;
                    $agentData['link'] = $agentItem['uuidHref'];
                    $agentData['name'] = $agents['rows'][$key]['name'];
                }
            }

            //org information
            $orgItem = $item['organization']['meta'];
            $orgData = array();
            
            if (preg_match('/=.+/', $orgItem['uuidHref'], $matches)) {
                $orgId = substr($matches[0], 1);

                $key = array_search($orgId, array_column($organizations['rows'], 'id'));

                if($key !== false) {
                    $orgData['id'] = $orgId;
                    $orgData['link'] = $orgItem['uuidHref'];
                    $orgData['name'] = $organizations['rows'][$key]['name'];
                }
            }

            //item state
            $stateItem = $item['state']['meta'];
            $stateData = array();
            $stateId = null;
            
            if (preg_match('/states\/.+/', $stateItem['href'], $matches)) {
                $stateId = substr($matches[0], 7);
            }

            //item currency
            $currencyItem = $item['rate']['currency']['meta'];
            $currencyData = array();
            $currencyId = null;
            $currencyName = '';
            
            if (preg_match('/currency\/.+/', $currencyItem['href'], $matches)) {
                $currencyId = substr($matches[0], 9);

                $key = array_search($currencyId, array_column($currency['rows'], 'id'));

                if($key !== false) {
                    $currencyName = $currency['rows'][$key]['name'];
                }
            }

            $sum = $item['sum'];
            $sum = substr($sum, 0, -2) . '.' . substr($sum, -2);

            $created = date_create($item['created']);
            $updated = date_create($item['updated']);

            $data['orders'][] = array(
                'id' => $item['id'],
                'number' => $item['name'],
                'created' => date_format($created, "d.m.Y H:i"),
                'agent' => $agentData,
                'organization' => $orgData,
                'sum' => number_format($sum, 2, ',', ' '),
                'currency' => $currencyName,
                'state' => $stateId,
                'updated' => date_format($updated, "d.m.Y H:i"),
                'link' => "https://online.moysklad.ru/app/#customerorder/edit?id=" . $item['id'],
            );

            $data['summ'] += $item['sum'];
        }

        if($data['summ'] > 0) {
            $data['summ'] = substr($data['summ'], 0, -2) . '.' . substr($data['summ'], -2);
        }
        
        View::render('Home/index.php', $data);
    }
}