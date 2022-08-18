function getDateNumericString(date1)
{
   return date1.getFullYear().toString() + (((date1.getMonth()+1).toString()).length == 1 ? "0" + ((date1.getMonth()+1).toString()) : ((date1.getMonth()+1).toString()))
   + (((date1.getDate().toString())).length == 1 ? "0" + ((date1.getDate().toString())) : ((date1.getDate().toString())))
   + (((date1.getHours().toString())).length == 1 ? "0" + ((date1.getHours().toString())) : ((date1.getHours().toString())))
   + (((date1.getMinutes().toString())).length == 1 ? "0" + ((date1.getMinutes().toString())) : ((date1.getMinutes().toString())))
   + (((date1.getSeconds().toString())).length == 1 ? "0" + ((date1.getSeconds().toString())) : ((date1.getSeconds().toString())))
   + (((date1.getMilliseconds().toString())).length == 1 ? "00" + ((date1.getSeconds().toString())) : (((date1.getMilliseconds().toString())).length == 2 ? "0" + ((date1.getSeconds().toString())) : ((date1.getSeconds().toString()))));
}
