<?php
/**
  * wechat php test
  */
//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
//$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		// 接收微信服务器转发过来的内容（XML格式）
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"] ? : file_get_contents('php://input');

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	// 将内容转换成XML对象（每个标签都变成属性，内容变成值）
				$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
				// 接收到的消息类型
				$msgType = $postObj->MsgType;
                $time = time();
				
				
				// 文本
				if($msgType == 'text' && $keyword == '新闻')
				{
					die("<xml>
	<ToUserName><![CDATA[$fromUsername]]></ToUserName>
	<FromUserName><![CDATA[$toUsername]]></FromUserName>
	<CreateTime>$_SERVER[REQUEST_TIME]</CreateTime>
	<MsgType><![CDATA[news]]></MsgType>
	<ArticleCount>2</ArticleCount>
	<Articles>
		<item>
			<Title><![CDATA[才一年，H5的发展就成这样了]]></Title> 
			<Description><![CDATA[关于H5的发展，分享几个最近看到的惊人数据和新闻]]></Description>
			<PicUrl><![CDATA[http://s5.51cto.com/wyfs02/M00/81/69/wKioL1dFzmGiaTdAAAB8VAtVT4E962.jpg-wh_651x-s_2050537243.jpg]]></PicUrl>
			<Url><![CDATA[http://developer.51cto.com/art/201509/491955.htm]]></Url>
		</item>
		<item>
			<Title><![CDATA[PHP 7 的五大新特性]]></Title>
			<Description><![CDATA[把这个放在第一个说是因为我觉得它很有用]]></Description>
			<PicUrl><![CDATA[http://s2.51cto.com/wyfs02/M01/81/81/wKioL1dF0DuTK06YAAHBitIVaG0045.jpg-wh_651x-s_3983514842.jpg]]></PicUrl>
			<Url><![CDATA[http://developer.51cto.com/art/201510/494674.htm]]></Url>
		</item>
	</Articles>
</xml>");
				}
				// 文本
				else if($msgType == 'text' && $keyword == '音乐')
				{
					die("<xml>
<ToUserName><![CDATA[$fromUsername]]></ToUserName>
<FromUserName><![CDATA[$toUsername]]></FromUserName>
<CreateTime>$_SERVER[REQUEST_TIME]</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
	<Music>
		<Title><![CDATA[测试音乐]]></Title>
		<Description><![CDATA[这是描述]]></Description>
		<MusicUrl><![CDATA[http://wxdemo.lamson.cc/jwyx.mp3]]></MusicUrl>
		<HQMusicUrl><![CDATA[http://wxdemo.lamson.cc/jwyx.mp3]]></HQMusicUrl>
	</Music>
</xml>");
				}
				
				// 文本
				if($msgType == 'text')
				{
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[您发送的内容是：$keyword]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				// 图片
				else if($msgType == 'image')			
				{
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[图片的网址是：$postObj->PicUrl]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				// 语音
				else if($msgType == 'voice')			
				{
					// 开启语音识别后，语音的内容会被自动存储到这个字段里
					$rec = $postObj->Recognition;
					
					if( strpos($rec, '今天几号') !== false)
					{
						$content = '今天是' . date('Y年m月d日');						
					}
					
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[$content]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				// 视频
				else if($msgType == 'video' || $msgType == 'shortvideo')			
				{				
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[你发的不会是大片吧]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				// 地理位置
				else if($msgType == 'location')			
				{				
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[你的经度为$postObj->Location_Y 纬度是$postObj->Location_X 位置为$postObj->Label 你跑不了了]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				// 链接
				else if($msgType == 'link')			
				{				
					die("<xml>
							<ToUserName><![CDATA[$fromUsername]]></ToUserName>
							<FromUserName><![CDATA[$toUsername]]></FromUserName>
							<CreateTime>$time</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[你发的链接是：$postObj->Url]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>");
				}
				
				/**/
				
				
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = $keyword;
					// 将$textTpl模板中的点位符都替换成真正的内容
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	
					$msgType = "text";
                	$contentStr = "Input something...";
					// 将$textTpl模板中的点位符都替换成真正的内容
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
					
					
					
					//echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>