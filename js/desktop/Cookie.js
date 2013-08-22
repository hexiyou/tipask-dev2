
/**
 * cookie对象
 * @class cookie对象
 * @param 
 * @type object
 */
Cookie={};

/**
 * cookie对象的set方法
 * @requires cookie
 * @param indexName,value,savedays
 * @type void
 */
Cookie.del=function(name)//删除cookie
{
var exp = new Date();
exp.setTime(exp.getTime() - 1);
var cval=Cookie.get(name);
if(cval) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
};

Cookie.set=function(name, value,savedays){
try
{
if(!value)
{
	return Cookie.del(name);
}
if(value.toString().length>1000)
	{
	return '';
	}
if(!savedays)
{
savedays=365;
}
var exp   = new Date();
var ct=exp.getTime();
var pt=new Date(exp.getYear(),exp.getMonth(),exp.getDate()).getTime();
exp.setTime(exp.getTime() + savedays*24*60*60*1000-(ct-pt));
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
catch(e)
{
}

};
/**
 * cookie对象的get方法
 * @requires cookie
 * @param indexName
 * @type void
 */
Cookie.get=function(name){
try
{
var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr) return unescape(arr[2]); return '';
}
catch(e)
{
}
};