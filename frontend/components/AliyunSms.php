<?php
namespace frontend\components;

use yii\base\Component;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use yii\helpers\Json;
use Aliyun\Core\Config;

class AliyunSms extends Component
{
    public $accessKeyId;//参考本文档步骤2
    public $accessKeySecret;//参考本文档步骤2
        //短信API产品名（短信产品名固定，无需修改）
    public $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
    public $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
    public $region = "cn-hangzhou";

    //定义短信签名
    public $signName;
    //定义模板编号
    public $templateCode;

    private $_acsClient;
    private $_request;

    public function init()
    {

        parent::init();
        // 加载区域结点配置
        Config::load();

        $profile = DefaultProfile::getProfile($this->region, $this->accessKeyId, $this->accessKeySecret);
        DefaultProfile::addEndpoint($this->region, $this->region, $this->product, $this->domain);
        $this->_acsClient= new DefaultAcsClient($profile);
        $this->_request = new SendSmsRequest;

    }
    public function setPhoneNumbers($value){
        $this->_request->setPhoneNumbers($value);
        return $this;
    }
    public function setSignName($value){
        //$this->_request->setSignName($value);
        $this->signName = $value;
        return $this;
    }
    public function setTemplateCode($value){
        //$this->_request->setTemplateCode($value);
        $this->templateCode = $value;
        return $this;
    }
    public function setTemplateParam($data){
        $json = Json::encode($data);
        $this->_request->setTemplateParam($json);
        return $this;
    }
    public function send(){
        $this->_request->setSignName($this->signName);
        $this->_request->setTemplateCode($this->templateCode);
        return  $this->_acsClient->getAcsResponse($this->_request);
    }

}
