<!doctype html>
<html>

<head>
    <meta charset="utf-8"/>
</head>
<body>
<div>
    <input type="button" value="send" onclick="send()" />
</div>
<script src="http://res.websdk.rongcloud.cn/RongIMClient-0.9.17.min.js"></script>
<script src="/admin/js/jquery-1.10.2.min.js"></script>
<script src="http://res.websdk.rongcloud.cn/Embedded.js"></script>
<script>

    RongIMClient.init("pgyu6atqylmeu");

    var token = 'HbSMxkxQPO47o5/dV3seSwh4wxUqe7BQQSFL2hZMREUlWg5tUbRW7xxRFCXtkH8HpHRpEBZJiRwLK7eFe+YZsQ==';

    // 连接融云服务器。
    RongIMClient.connect(token,{
        onSuccess: function (userId) {
            // 此处处理连接成功。
            console.log("Login successfully." + userId);
        },
        onError: function (errorCode) {
            // 此处处理连接错误。
            var info = '';
            switch (errorCode) {
                case RongIMClient.callback.ErrorCode.TIMEOUT:
                    info = '超时';
                    break;
                case RongIMClient.callback.ErrorCode.UNKNOWN_ERROR:
                    info = '未知错误';
                    break;
                case RongIMClient.ConnectErrorStatus.UNACCEPTABLE_PROTOCOL_VERSION:
                    info = '不可接受的协议版本';
                    break;
                case RongIMClient.ConnectErrorStatus.IDENTIFIER_REJECTED:
                    info = 'appkey不正确';
                    break;
                case RongIMClient.ConnectErrorStatus.SERVER_UNAVAILABLE:
                    info = '服务器不可用';
                    break;
                case RongIMClient.ConnectErrorStatus.TOKEN_INCORRECT:
                    info = 'token无效';
                    break;
                case RongIMClient.ConnectErrorStatus.NOT_AUTHORIZED:
                    info = '未认证';
                    break;
                case RongIMClient.ConnectErrorStatus.REDIRECT:
                    info = '重新获取导航';
                    break;
                case RongIMClient.ConnectErrorStatus.PACKAGE_ERROR:
                    info = '包名错误';
                    break;
                case RongIMClient.ConnectErrorStatus.APP_BLOCK_OR_DELETE:
                    info = '应用已被封禁或已被删除';
                    break;
                case RongIMClient.ConnectErrorStatus.BLOCK:
                    info = '用户被封禁';
                    break;
                case RongIMClient.ConnectErrorStatus.TOKEN_EXPIRE:
                    info = 'token已过期';
                    break;
                case RongIMClient.ConnectErrorStatus.DEVICE_ERROR:
                    info = '设备号错误';
                    break;
            }
            console.log("失败:" + info);
        }
    });

    // 设置连接监听状态 （ status 标识当前连接状态）
    // 连接状态
    RongIMClient.setConnectionStatusListener({
        onChanged: function (status) {
            switch (status) {
                //链接成功
                case RongIMClient.ConnectionStatus.CONNECTED:
                    console.log('链接成功');
                    break;
                //正在链接
                case RongIMClient.ConnectionStatus.CONNECTING:
                    console.log('正在链接');
                    break;
                //重新链接
                case RongIMClient.ConnectionStatus.RECONNECT:
                    console.log('重新链接');
                    break;
                //其他设备登陆
                case RongIMClient.ConnectionStatus.OTHER_DEVICE_LOGIN:
                //连接关闭
                case RongIMClient.ConnectionStatus.CLOSURE:
                //未知错误
                case RongIMClient.ConnectionStatus.UNKNOWN_ERROR:
                //登出
                case RongIMClient.ConnectionStatus.LOGOUT:
                //用户已被封禁
                case RongIMClient.ConnectionStatus.BLOCK:
                    break;
            }
        }});

    // 消息监听器
    RongIMClient.getInstance().setOnReceiveMessageListener({
        // 接收到的消息
        onReceived: function (message) {
            // 判断消息类型
            switch(message.getMessageType()){
                case RongIMClient.MessageType.TextMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.ImageMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.VoiceMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.RichContentMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.LocationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.DiscussionNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.InformationNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.ContactNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.ProfileNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.CommandNotificationMessage:
                    // do something...
                    break;
                case RongIMClient.MessageType.UnknownMessage:
                    // do something...
                    break;
                default:
                // 自定义消息
                // do something...
            }
        }
    });




    function send(){
        // 定义消息类型,文字消息使用 RongIMClient.TextMessage
        var msg = new RongIMClient.TextMessage();
        // 设置消息内容
        msg.setContent("hello");
        //或者使用RongIMClient.TextMessage.obtain方法.具体使用请参见文档
        var msg = RongIMClient.TextMessage.obtain("hello");
        var conversationtype = RongIMClient.ConversationType.PRIVATE; // 私聊
        var targetId = "44"; // 目标 Id
        RongIMClient.getInstance().sendMessage(conversationtype, targetId, msg, null, {
                // 发送消息成功
                onSuccess: function () {
                    console.log("Send successfully");
                },
                onError: function (errorCode) {
                    var info = '';
                    switch (errorCode) {
                        case RongIMClient.callback.ErrorCode.TIMEOUT:
                            info = '超时';
                            break;
                        case RongIMClient.callback.ErrorCode.UNKNOWN_ERROR:
                            info = '未知错误';
                            break;
                        case RongIMClient.SendErrorStatus.REJECTED_BY_BLACKLIST:
                            info = '在黑名单中，无法向对方发送消息';
                            break;
                        case RongIMClient.SendErrorStatus.NOT_IN_DISCUSSION:
                            info = '不在讨论组中';
                            break;
                        case RongIMClient.SendErrorStatus.NOT_IN_GROUP:
                            info = '不在群组中';
                            break;
                        case RongIMClient.SendErrorStatus.NOT_IN_CHATROOM:
                            info = '不在聊天室中';
                            break;
                        default :
                            info = x;
                            break;
                    }
                    console.alert('发送失败:' + info);
                }
            }
        );
    }

    function sendToCustomerService(){
        var customerServiceId = "XXXXXX";// 客服 Id
        var conversationtype = RongIMClient.ConversationType.CUSTOMER_SERVICE; // 客服会话类型
        RongIMClient.getInstance().sendMessage(conversationtype, customerServiceId, content, null, {
                onSuccess: function () {
                    // 发送消息成功
                    console.log("Send successfully");
                },
                onError: function (errorCode) {
                    // 发送消息失败
                    console.log(errorCode);
                }
            }
        );
    }

    function sync(){
        // 同步会话列表
        RongIMClient.getInstance().syncConversationList({
            onSuccess:function(){
                setTimeout(function(){
                    // 同步会话列表成功之后，重新获取会话列表
                    var ConversationList = RongIMClient.getInstance().getConversationList();
                    // do something...
                },1000)
            },onError:function(){
                // 失败
            }
        });
    }

    function history(){
        // 获取历史消息
        //此方法最多一次行拉取20条消息。拉取顺序按时间倒序拉取。
        RongIMClient.getInstance().getHistoryMessages(RongIMClient.ConversationType.PRIVATE,'targeid',20,{
            onSuccess:function(symbol,HistoryMessages){
                // symbol为boolean值，如果为true则表示还有剩余历史消息可拉取，为false的话表示没有剩余历史消息可供拉取。
                // HistoryMessages 为拉取到的历史消息列表，列表中消息为对应消息类型的消息实体
            },onError:function(){
                // APP未开启消息漫游或处理异常
                // throw new ERROR ......
            }
        });
    }


    window.RongCloudWebSDK.instantce({
        'style': {
            'headerColor': 'rgba(120, 208, 244, 0.6)',   //标题背景色
            'boxColor': 'rgba(120, 208, 244, 0.6)',  //消息盒子背景色
            'bodyColor': '#CDEEFC', //对话框背景色
            'kefuMsgColor': '#fff', //客服消息背景色
            'cusMsgColor': '#30b5ee',  //客服消息背景色
            'sendBtnColor': '#2992d9', //发送按钮背景色
            'expressionImg': 'http://rongcloud-web.qiniudn.com/expression.png',    //表情图
            'cursorcolor': '#78d0f4'    //滚动条色
        },
        'appKey':'pgyu6atqylmeu',
        'token':'llja3dGCy9M3',
        'targetId':'22'
    });

</script>
</body>
</html>