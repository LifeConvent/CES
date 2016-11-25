<?php
/**
 * Created by PhpStorm.
 * User: lawrance
 * Date: 16/10/26
 * Time: 下午1:50
 * function  指定用户发送文本信息 POST数据：用户id数组、发送文本内容（支持HTML） 网址：http://HOST:PORT/IPSENWeChat/index.php/Home/GroupSend/allSendNews
 */

namespace Home\Controller;

use Think\Controller;
use Think\Model;

class GroupSendController extends Controller
{
    public $corpid = "wx2c307a1875247a02";
    public $corpsecret = "25cd61683a5b7282524f976cee2b0da9";
    public $corpidTest = 'wx72d997ae150ccf6f';
    public $corpsecretTest = 'Ia0CtpywryfU5VGNAlk23s8ctM-99v7we2HZmkVuh_szDz3dinL9aQ9gqslBTZk2';

    /**
     * @function 群发消息给用户，可通过标签发送，发送过程不涉及OpenID
     * @param null $tagID 用户标签，不针对标签群发时不用传入
     * @param null $type 群发消息类型 text、mpnews 其它暂未开放
     * @param null $content 文字消息内容
     * @return bool 成功发送时返回true
     */
    public function allSendNews($content = null, $userid = null)
    {
        $agentid = 7;
        $content = $_POST['content'];
        $userid = $_POST['openid'];

//        测试用例
        $userid = array('biag', 'biag', 'biag', 'biag');
        $content = 'test';

        if ($content == null) {
            $result['status'] = 'failed';
            $result['message'] = '发送内容不能为空';
            exit(json_encode($result));
        }

        if ($userid == null) {
            $result['status'] = 'failed';
            $result['message'] = '发送用户不能为空';
            exit(json_encode($result));
        }

        //发送消息样板
        $allSend = '{
                           "touser": "UserID1|UserID2|UserID3",
                           "toparty": " PartyID1 | PartyID2 ",
                           "totag": " TagID1 | TagID2 ",
                           "msgtype": "text",
                           "agentid": 1,
                           "text": {
                               "content": "Holiday Request For Pony(http://xxxxx)"
                           },
                           "safe":0
                        }';


        $openidList = array();
        if (!is_array($userid)) {
            $useridList[] = $userid;
        } else {
            $useridList = $userid;
        }
        $sendByUserID1 = '{"touser":"';
        $sendByUserID2 = '","msgtype": "text","agentid":' . $agentid . ',"text": {"content": "' . $content . '"},"safe":0}';
        if (sizeof($useridList) == 1) {
            $sendByUserID1 .= $userid[0];
        } else {
            for ($i = 0; $i < sizeof($useridList); $i++) {
                if ($i == 0) {
                    $sendByUserID1 .= $useridList[$i];
                } else {
                    $sendByUserID1 .= ('|' . $useridList[$i]);
                }
            }
        }
        $sendByUserID = $sendByUserID1 . $sendByUserID2;
        dump($sendByUserID);
        $access_token = $this->getAccessToken();
        $url = 'https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=' . $access_token;
        $send_result = $this->https_request($url, $sendByUserID);
        $data = new \stdClass();
        $data = json_decode($send_result);
        if ($data->errcode == 0) {
            $result['status'] = 'success';
        } else {
            $result['status'] = 'success';
            $result['message'] = $data->errcode;
        }
        exit(json_encode($result));
    }

    public function getAccessToken()
    {
        $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->corpidTest&corpsecret=$this->corpsecretTest";
        $output = $this->https_request($url);
        $jsoninfo = json_decode($output, true);
        return $jsoninfo["access_token"];
    }

    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        if (class_exists('/CURLFile')) {//php5.5跟php5.6中的CURLOPT_SAFE_UPLOAD的默认值不同
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}