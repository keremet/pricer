function proverka_dat(input) {
	input.value = input.value.replace(/[^\d]/g, '');
};	

function form_dat(datestr,arr_err){
	dt = new Date();
	switch(datestr.length){
	case 0:	
		arr_err.push('Дата не может быть пустой');
		return false;
	case 1:	
		datestr='0'+datestr;	
	case 2:
		var m = dt.getMonth()+1;
		if(m<10)
			datestr+='0';
		datestr+=m;
	case 4:	
		datestr+=dt.getFullYear();
		break;
	case 3:
		datestr=datestr.substring(0,2)+'0'+datestr.substring(2)+dt.getFullYear();
		break;
	case 5:
		arr_err.push('Дата не может состоять из 5 цифр'); 
		return false;
	case 6:
		datestr=datestr.substring(0,4)+'20'+datestr.substring(4);
		break;	
	}
	return datestr;	
}


function check_dat(datestr,arr_err)
{
	var month = parseInt(datestr.substring((datestr.charAt(2)=='0')?3:2,4));
	if((month<1)||(month>12)){
		arr_err.push('Месяц должен быть от 1 до 12'); 
		return false;
	}
	var day = parseInt(datestr.substring((datestr.charAt(0)=='0')?1:0, 2));
	var year = parseInt(datestr.substring(4));
	switch(month){
		case 1:
		case 3:
		case 5:
		case 7:
		case 8:
		case 10:
		case 12:
			if((day>=1)&&(day<=31))
					break;
			arr_err.push(day+' '+datestr+'" День должен быть от 1 до 31'); 
			return false;
		case 4:
		case 6:
		case 9:
		case 11:
			if((day>=1)&&(day<=30))
					break;
			arr_err.push('День должен быть от 1 до 30'); 
			return false;
		case 2:
			var days2 = (year%4==0)?29:28;
			if((day>=1)&&(day<=days2))
					break;
			arr_err.push('День должен быть от 1 до '+days2); 
			return false;		
	}
	return datestr;
}

function form_and_check_dat(datestr,arr_err)
{
	datestr = form_dat(datestr,arr_err);
	if(arr_err.length>0)
		return false;
	return check_dat(datestr,arr_err);
}
