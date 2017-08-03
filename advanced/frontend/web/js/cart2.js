/*
@功能：购物车页面js
@作者：diamondwang
@时间：2013年11月14日
*/

$(function(){
    //小计
    $('#orderInfo tr').each(function(){
        var subtotal = parseFloat($(this).find(".col3 span").text()) * parseInt($(this).find(".amount").text());
        $(this).find(".col5 span").text(subtotal.toFixed(2));
    });
    //总计金额
	var total=0;
    $(".col5 span").each(function(){
        total += parseInt($(this).text());
    });
    $("#total").text(total.toFixed(2));
	var delivery_fee=0;
    $('#delivery').text(delivery_fee);
    var rs=total-delivery_fee;
    if(rs>0){
        $('#totalPrice').text(rs);
    }
	//选择运费，给下方的更新下方价格和总价
	$('#delivery_way tr').click(function(){
        delivery_fee=$(this).find('span.delivery_price').text();
        $('#delivery').text(delivery_fee);
        rs=total-delivery_fee;
        if(rs>0){
            $('#totalPrice').text(rs.toFixed(2));
        }
	});
	//收货人修改
	$("#address_modify").click(function(){
		$(this).hide();
		$(".address_info").hide();
		$(".address_select").show();
	});

	$(".new_address").click(function(){
		$("form[name=address_form]").show();
		$(this).parent().addClass("cur").siblings().removeClass("cur");

	}).parent().siblings().find("input").click(function(){
		$("form[name=address_form]").hide();
		$(this).parent().addClass("cur").siblings().removeClass("cur");
	});

	//送货方式修改
	$("#delivery_modify").click(function(){
		$(this).hide();
		$(".delivery_info").hide();
		$(".delivery_select").show();
	})

	$("input[name=delivery]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//支付方式修改
	$("#pay_modify").click(function(){
		$(this).hide();
		$(".pay_info").hide();
		$(".pay_select").show();
	})

	$("input[name=pay]").click(function(){
		$(this).parent().parent().addClass("cur").siblings().removeClass("cur");
	});

	//发票信息修改
	$("#receipt_modify").click(function(){
		$(this).hide();
		$(".receipt_info").hide();
		$(".receipt_select").show();
	})

	$(".company").click(function(){
		$(".company_input").removeAttr("disabled");
	});

	$(".personal").click(function(){
		$(".company_input").attr("disabled","disabled");
	});

});