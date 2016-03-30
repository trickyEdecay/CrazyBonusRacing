<?php
//用于验证字符串的


//验证是否为姓名
function is_name($str){
    if(preg_match("/^[\x80-\xff]*$/",$str)){
        return true;//匹配成功
    }else{
        return false;//匹配失败
    }
}
//验证是否为性别
function is_sex($str){
    if($str=="男" or $str=="女"){
        return true;//匹配成功
    }else{
        return false;//匹配失败
    }
}
//验证是否为长号
function is_longtel($str){
    if(preg_match("/^1[0-9]{10}$/",$str)){
        return true;//匹配成功
    }else{
        return false;//匹配失败
    }
}

//验证是否为短号
function is_shorttel($str){
    if(preg_match("/^6[0-9]{5}$/",$str)){
        return true;//匹配成功
    }else{
        return false;//匹配失败
    }
}

//验证是否为学校住址
function is_address($str){
    $checkArray = array("东","中","南","東","east","middle","south","E","M","S","区");
    $checkArrayTwo = array("0","1","2","3","4","5","6","7","8","9","一","二","三","四","五","六","七","八","九","十","零");
    for($i=0;$i<count($checkArray);$i++){
        if(stripos($str,$checkArray[$i])>=0){
            for($j=0;$j<count($checkArrayTwo);$j++){
                if(stripos($str,$checkArrayTwo[$j])>=0){
                    return true;
                }
            }
        }else{
            continue;   
        }
    }
    return false;
}

//验证数字
function is_number($str){
    if(preg_match("/^[0-9]*$/",$str)){
        return true;//匹配成功
    }else{
        return false;//匹配失败
    }
}


//验证是否为学院班级
function is_college($str){
    $checkArray = array("数","物","光","电","化","环","生","命","科","地","理","旅","游","文","客","政","法","外","国","信",
                        "计","算","机","经","管","土","美","术","体","育","音","乐","教","育","师","医");
    $checkArrayTwo = array("0","1","2","3","4","5","6","7","8","9","一","二","三","四","五","六","七","八","九","十","零");
    for($i=0;$i<count($checkArray);$i++){
        if(stripos($str,$checkArray[$i])>=0){
            for($j=0;$j<count($checkArrayTwo);$j++){
                if(stripos($str,$checkArrayTwo[$j])>=0){
                    return true;
                }
            }
        }else{
            continue;   
        }
    }
    return false;
}


?>