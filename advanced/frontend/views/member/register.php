<?php
use yii\helpers\Html;
?>
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>    <div class="login_bd">
        <div class="login_form fl">
            <?php
                //注册表单
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]]
            );
            $captchaUrl=\yii\helpers\Url::to(["member/captcha"]);
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);
            echo $form->field($model,'password')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'password2')->passwordInput(['class'=>'txt']);
            echo $form->field($model,'email')->textInput(['class'=>'txt']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            $button =  Html::button('发送短信验证码',['id'=>'send_sms_button','style'=>'margin-left:4px']);
            echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt','id'=>'inputSmsCaptcha']);
//            echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>"{input}<iframe style='width:80px;height:40px;text-align:center;line-height: 40px;'>{image}</iframe><span id=\'changeImg\'>看不清？<a href='$captchaUrl'>换一张</a></span>",'captchaAction'=>'member/captcha']);
            //验证码
            echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
//            echo \yii\helpers\Html::submitButton('',['class'=>'login_btn','style'=>'margin:10px 0 0 40px']);
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
                    </li><li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn">
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>
<!--            <form action="" method="post">-->
<!--                <ul>-->
<!--                    <li>-->
<!--                        <label for="">用户名：</label>-->
<!--                        <input type="text" class="txt" name="username" />-->
<!--                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">密码：</label>-->
<!--                        <input type="password" class="txt" name="password" />-->
<!--                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">确认密码：</label>-->
<!--                        <input type="password" class="txt" name="password" />-->
<!--                        <p> <span>请再次输入密码</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">邮箱：</label>-->
<!--                        <input type="text" class="txt" name="email" />-->
<!--                        <p>邮箱必须合法</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">手机号码：</label>-->
<!--                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">验证码：</label>-->
<!--                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>-->
<!---->
<!--                    </li>-->
<!--                    <li class="checkcode">-->
<!--                        <label for="">验证码：</label>-->
<!--                        <input type="text"  name="checkcode" />-->
<!--                        <img src="images/checkcode1.jpg" alt="" />-->
<!--                        <span>看不清？<a href="">换一张</a></span>-->
<!--                    </li>-->
<!---->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="submit" value="" class="login_btn" />-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </form>-->


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<!--<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>-->
<script type="text/javascript">
//    function bindPhoneNum(){
//        //启用输入框
//        $('#captcha').prop('disabled',false);
//
//        var time=60;
//        var interval = setInterval(function(){
//            time--;
//            if(time<=0){
//                clearInterval(interval);
//                var html = '获取验证码';
//                $('#get_captcha').prop('disabled',false);
//            } else{
//                var html = time + ' 秒后再次获取';
//                $('#get_captcha').prop('disabled',true);
//            }
//
//            $('#get_captcha').val(html);
//        },1000);
//    }
</script>
</body>
</html>
<?php
/* @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['member/send-sms']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $("#send_sms_button").click(function(){
          //启用输入框
        $('#inputSmsCaptcha').prop('disabled',false);
        var time=60;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#send_sms_button').prop('disabled',false);
            } else{
                var html = '发送成功'+time + ' 秒后再次获取';
                $('#send_sms_button').prop('disabled',true);
            }

            $('#send_sms_button').html(html);
        },1000);
        //发送验证码按钮被点击时
        //手机号
        var tel = $("#member-tel").val();
        //AJAX post提交tel参数到 user/send-sms
        $.post('$url',{tel:tel},function(data){
            if(data == 'success'){
                console.log('短信发送成功');
                alert('短信发送成功');
            }else{
                console.log(data);
            }
        });
    });
JS
));
