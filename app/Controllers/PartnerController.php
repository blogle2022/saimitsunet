<?php

namespace App\Controllers;

use App\Services\Model;
use App\Services\Request;
use App\Services\Response;

class PartnerController
{
    private $partner;

    function __construct()
    {
        $this->partner = new Model('partnerinfo');
    }
    public function index(Response $response)
    {
        $partners = $this->partner->find('mailaddr', '=', $_SESSION['user']['mail']);
        $params = [
            'partners' => $partners,
        ];

        return $response->view('partner.index', $params);
    }

    public function regist(Request $request)
    {
        $insert = [
            'id' => md5(uniqid("NEWPERSONASDFGFAA")),
            'username' => $request->post('username'),
            'sex' => $request->post('sex'),
            'mar' => $request->post('mar'),
            'mailaddr' => $_SESSION['user']['mail'],
            'pid' => $_SESSION['user']['id'],
        ];

        $birthdayKeys = ['b_year', 'b_mon', 'b_day'];
        $birthday = explode('-', $request->post('born'));
        $birthdayArray = array_combine($birthdayKeys, $birthday);

        $bornTime = explode(':', $request->post('time'));
        $timeKeys = ['b_hour', 'b_min'];
        $timeArray = array_combine($timeKeys, $bornTime);

        $insert['b_place'] = $request->post('foreign') === '0' ? $request->post('area') : '';
        $insert['b_place'] .= $request->post('city');

        $mergedInsert = array_merge($insert, $birthdayArray, $timeArray);

        $results = $this->partner->addSingle($mergedInsert);

        $_SESSION['partner_regist'] = $results;

        redirect(get_referer());
    }

    public function delete(Request $request)
    {
        $this->partner->delete('id', $request->post('id'));
        redirect(get_referer());
    }
}
