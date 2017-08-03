<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->
<!-- 右侧内容区域 start -->
		<div class="content fl ml10">
			<div class="address_hd">
				<h3>收货地址薄</h3>
                <?php
                if(empty($addressList)){
                echo '<h1 style="color:#ccc;font-family:Microsoft Yahei">您尚未添加默认地址,如需添加，请填写如下表单</h1>';}
                ?>
                <?php
                    foreach ($addressList as  $address):
                        $province=isset($address->province)?$address->province.' ':'';
                        $city=isset($address->city)?$address->city.' ':'';
                        $county=isset($address->county)?$address->county.' ':'';
                        $detail=isset($address->detail)?$address->detail.' ':'';
                        $tel=isset($address->tel)?$address->tel.' ':'';
                        $is_default=isset($address->is_default)?$address->is_default:0;
                        $set_default_address=\yii\helpers\Url::to(['member/set-default-address']);
                ?>
                <dl>
					<dt>
                        <?=$address->id.' '.$address->name.' '.$province.''.$city.''.$county.''.$detail.''.$tel?>
                    </dt>
					<dd>
						<a href="#address-name"     onclick="alert('请新增地址后，删除要修改的地址')">修改</a>
						<a href="<?=\yii\helpers\Url::to(['member/del-address','id'=>$address->id])?>" onclick="return confirm('确定删除？')">删除</a>
                        <?php
                        echo $is_default==1?'<a href="javascript:void(0)" style="color:red">默认地址</a>':\yii\helpers\Html::a('设为默认地址',['member/set-default-address','id'=>$address->id,'member_id'=>$address->member_id]);
                        ?>
					</dd>
				</dl>
                <?php endforeach;?>
			</div>
			<div class="address_bd mt10">
				<h4>新增收货地址</h4>
                <?php $form=\yii\widgets\ActiveForm::begin([
                        'fieldConfig'=>[
                                'options'=>[
                                  'tag'=>'li',
                                ],
                            'errorOptions'=>[
                                    'tag'=>'p'
                            ],
                        ],
                    ]
                );?>
                <ul>
                    <?=$form->field($model,'name')->textInput(['class'=>'txt']);?>
                    <li><label for="">所在地区</label>
                    <?=$form->field($model,'province',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择省=']);?>
                    <?=$form->field($model,'city',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择市=']);?>
                    <?=$form->field($model,'county',['template'=>"{input}",'options'=>['tag'=>false]])->dropDownList([''=>'=选择县=']);?>
                    </li>
                    <?=$form->field($model,'detail')->textInput(['class'=>'txt'])?>
                    <?=$form->field($model,'tel')->textInput(['class'=>'txt'])?>
                    <?=$form->field($model,'is_default')->checkbox();?>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存">
                    </li>
                </ul>
                <?php \yii\widgets\ActiveForm::end();?>
			</div>
		</div>
		<!-- 右侧内容区域 end -->
    <?php
    $this->registerJsFile('@web/js/address.js');
    $this->registerJs(new \yii\web\JsExpression(
       <<<JS
    //填充省的数据
    $(address).each(function(){
        var option='<option value="'+this.name+'">'+this.name+'</option>';
        $('#address-province').append(option);
        //切换（选中）省，读取该省对应的市，更新到市下拉框
        $("#address-province").change(function(){
            var province=$(this).val();//获取当前选中的省
            //获取当前省对应的市区数据
            $(address).each(function(){
                if(this.name==province){
                    var option='<option value="">=请选择市=</option>'
                    $(this.city).each(function(){
                       option+='<option value="'+this.name+'">'+this.name+'</option>';
                    });
                    $('#address-city').html(option);
                }
            });
            //将县的下拉框数据清空
            $('#address-county').html('<option value="">=请选择县=</option>');
        });
        //切换（选中的）市，读取该市对应的县，更新到县下拉框
        $('#address-city').change(function(){
            var city=$(this).val();//当前选中的城市
            $(address).each(function(){
                if(this.name==$('#address-province').val()){
                    $(this.city).each(function(){
                        if(this.name==city){
                            //遍历到当前选中的城市
                            var option='<option value="">=请选择县=</option>';
                             $(this.area).each(function(i,v){
                                 option+='<option value="'+v+'">'+v+'</option>';
                             });
                             $("#address-county").html(option);
                        }
                    });
                }
            });
        });
    });
JS
    ));
    $js='';
    if($model->province){
        $js.='$("#address-province").val("'.$model->province.'"");';
    }
    if($model->city){
       $js.='$("#address-province").change();$("#address-city").val("'.$model->city.'");';
    }
    if($model->county){
        $js.='$("#address-county").change();$("#address-county").val("'.$model->county.'"")';
    }
    $this->registerJs($js);
    ?>