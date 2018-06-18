<?php
/**
* @link http://www.diecoding.com/
* @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
* @copyright Copyright (c) 2018
*/


namespace diecoding\components;

use Yii;
use DOMDocument;
use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;

use common\models\User;
use common\models\Peserta;

class ApiRequest extends Component
{
    const PHOTO_DEFAULT      = "default.png";

    public function getNisn($nisn)
    {
        $client = new Client;

        $data = [
      'nisn'          => '',
      'nama'          => '',
      'jenis_kelamin' => '',
      'tempat_lahir'  => '',
      'tanggal_lahir' => '',
    ];

        $replace = [
      'Januari'      => 'January',
      'Februari'     => 'February',
      'Maret'        => 'March',
      // 'April'     => 'April',
      'Mei'          => 'May',
      'Juni'         => 'June',
      'Juli'         => 'July',
      'Agustus'      => 'August',
      // 'September' => 'September',
      'Oktober'      => 'October',
      'Nopember'     => 'November',
      'Desember'     => 'December',
    ];

        $response = $client->createRequest()
    ->setMethod('POST')
    ->setUrl('http://nisn.data.kemdikbud.go.id/page/data')
    ->setData([
      'ctl00$RadScriptManager1'                              => 'ctl00$ctl00$contentCenter$RadAjaxPanel1Panel|ctl00$contentCenter$RadButton1',
      'RadStyleSheetManager1_TSSM'                           => ';Telerik.Web.UI, Version=2013.2.611.40, Culture=neutral, PublicKeyToken=121fae78165ba3d4:en-US:8a277cf4-155d-4ba9-b3c0-d6f62646e5f2:45085116:ed2942d4:c5d7b491:8cee9284:ed057d30:a3b7d93f:fe53831e:aac1aeb7:c73cf106:c86a4a06:4c651af2:9e1572d6:e25b4b77;Telerik.Web.UI.Skins, Version=2013.2.611.40, Culture=neutral, PublicKeyToken=121fae78165ba3d4:en-US:ad97f8cd-7edf-40af-89fd-7eff634976a9:929e740d:f7a27b73',
      'RadScriptManager1_TSM'                                => ';;System.Web.Extensions, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35:en-US:50b12c66-1dd3-4ebf-87e6-55014086ad94:ea597d4b:b25378d2;Telerik.Web.UI:en-US:8a277cf4-155d-4ba9-b3c0-d6f62646e5f2:16e4e7cd:f7645509:22a6274a:ed16cbdc:24ee1bba:e330518b:2003d0b8:1e771326:c8618e41:8e6f0d33:6a6d718d:7165f74:7c926187:8674cba1:b7778d6c:c08e9f8a:59462f1:a51ee93e:58366029:e085fe68:82923ac3:1bb784d4',
      '__EVENTTARGET'                                        => 'ctl00$contentCenter$RadButton1',
      '__EVENTARGUMENT'                                      => '',
      '__VIEWSTATE'                                          => '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',
      '__VIEWSTATEGENERATOR'                                 => '9ED6FB81',
      '__VIEWSTATEENCRYPTED'                                 => '',
      '__EVENTVALIDATION'                                    => 'ULiNxfs2JRDRlhqc8WOdStf27oqn8JO5q2uBO51E0e5Q9oquNea7ej+uz3XIddQW/b07WACq738lHsRn/+4CGtNcrPazxQwm7d44cmu0mdcvmDEkkCWKhHxMxNdON7KEACBMmy7rPKTkWb3OH/F69JNFyfapMsPDrWO8OJKIUZEEdsI7jxZiJ96K/YmRxazu',
      'ctl00_RadMenu1_ClientState'                           => '',
      'ctl00_contentCenter_RadTabStrip1_ClientState'         => '{"selectedIndexes":["0"],"logEntries":[],"scrollState":{}}',
      'ctl00$contentCenter$edNISN'                           => $nisn,
      'ctl00_contentCenter_RadButton1_ClientState'           => '',
      'ctl00$contentCenter$edNama'                           => '',
      'ctl00$contentCenter$edTmptLahir'                      => '',
      'ctl00$contentCenter$edTglLahir'                       => '',
      'ctl00$contentCenter$edTglLahir$dateInput'             => '',
      'ctl00_contentCenter_edTglLahir_dateInput_ClientState' => '{"enabled":true,"emptyMessage":"","validationText":"","valueAsString":"","minDateStr":"1980-01-01-00-00-00","maxDateStr":"2030-12-31-00-00-00","lastSetTextBoxValue":""}',
      'ctl00_contentCenter_edTglLahir_calendar_SD'           => '[]',
      'ctl00_contentCenter_edTglLahir_calendar_AD'           => '[[1980,1,1],[2030,12,31],[2018,3,10]]',
      'ctl00_contentCenter_edTglLahir_ClientState'           => '{"minDateStr":"1980-01-01-00-00-00","maxDateStr":"2030-12-31-00-00-00"}',
      'ctl00_contentCenter_RadButton2_ClientState'           => '',
      'ctl00_contentCenter_gridRes2_ClientState'             => '',
      'ctl00_contentCenter_RadMultiPage1_ClientState'        => '',
      '__ASYNCPOST'                                          => 'false',
      // 'RadAJAXControlID'                                  => 'ctl00_RadAjaxManager1',
      'RadAJAXControlID'                                     => 'ctl00_contentCenter_RadAjaxPanel1',
    ])
    ->send();

        try {
            preg_match('/<table id=\"mygrid\">(.*?)<\/table>/s', $response->content, $match);
            $dom = new DOMDocument();
            $dom->loadHTML($match[0]);

            $data = [
        // 'nisn'          => $dom->getElementById('contentCenter_lRes1NISN')->nodeValue,
        // 'nama'          => strtoupper($dom->getElementById('contentCenter_lRes1Nama')->nodeValue),
        // 'jenis_kelamin' => substr($dom->getElementById('contentCenter_lRes1Kelamin')->nodeValue, 0, 1),
        // 'tempat_lahir'  => strtoupper($dom->getElementById('contentCenter_lRes1Tmptlahir')->nodeValue),
        // 'tanggal_lahir' => Yii::$app->formatter->asDate(strtr($dom->getElementById('contentCenter_lRes1TglLahir')->nodeValue, $replace), 'php:Y-m-d'),

        'nisn'          => $dom->getElementsByTagName("span")->item(0)->nodeValue,
        'nama'          => strtoupper($dom->getElementsByTagName("span")->item(1)->nodeValue),
        'jenis_kelamin' => substr($dom->getElementsByTagName("span")->item(3)->nodeValue, 0, 1),
        'tempat_lahir'  => strtoupper($dom->getElementsByTagName("span")->item(4)->nodeValue),
        'tanggal_lahir' => Yii::$app->formatter->asDate(strtr($dom->getElementsByTagName("span")->item(5)->nodeValue, $replace), 'php:Y-m-d'),
      ];

            if (ArrayHelper::getValue($data, 'nisn')) {
                $params = [
          'status'      => true,
          'isOk'        => $response->isOk,
          'statusCode'  => $response->statusCode,
          'description' => null,
          'data'        => $data,
        ];
            } else {
                preg_match('/<div id=\"ctl00_contentCenter_RadAjaxPanel1\"(.*?)\/div>/s', $response->content, $match);
                $error = new DOMDocument();
                $error->loadHTML($match[0]);

                $params = [
          'status'      => false,
          'isOk'        => $response->isOk,
          'statusCode'  => $response->statusCode,
          'description' => $error->getElementsByTagName("span")->item(1)->nodeValue,
          'data'        => $data,
        ];
            }
        } catch (\Exception $e) {
            $params = [
        'status'      => false,
        'isOk'        => $response->isOk,
        'statusCode'  => $response->statusCode,
        'description' => $e->getMessage(),
        'data'        => $data,
      ];
        }

        return $params;
    }

    public function getSekolah($q)
    {
        $client = new Client;
        $out = [
      'results' => [
        0 => ['id' => '', 'text' => 'TIDAK DITEMUKAN'],
      ]
    ];

        if ($q) {
            $response = $client->createRequest()
      ->setMethod('GET')
      ->setUrl('http://referensi.data.kemdikbud.go.id/carisatpen.php')
      ->setData([
        'limit'	     => 20,
        'q'          => $q,
        'timestamp'	 => time(),
      ])
      ->send();

            $result = explode("<br>", str_replace("\n", "<br>", $response->content));
            $r      = array_diff($result, [""]);

            $j = 1;
            for ($i=0; $i < count($r)/3-1; $i++) {
                $out['results'][$i]['id']   = trim("{$r[$j++]}_{$r[$j]}");
                $out['results'][$i]['text'] = trim("{$r[$j++]} | {$r[$j]}");
                $j++;
            }
        }

        return $out;
    }

    public function getPhoto($user_id = null)
    {
        if (!$user_id && Yii::$app->user->isGuest) {
            return self::PHOTO_DEFAULT;
        } elseif (!$user_id) {
            $user_id = Yii::$app->user->id;
        }

        $model = User::findOne($user_id);

        if (!$model || !$model->photo) {
            $photo = self::PHOTO_DEFAULT;
        } else {
            $photo = $model->photo;
        }

        return $photo;
    }

    public function getStatusPeserta($user_id = null)
    {
        $model = new Peserta();

        if (!$user_id && Yii::$app->user->isGuest) {
            return null;
        } elseif (!$user_id) {
            $user_id = Yii::$app->user->id;
        }

        $model = $model::findOne(['id_user' => $user_id]);
        return $model ? $model->status : null;
    }

    public function ukuran($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
