/** Valida el que la cadena solo sea de 10 digitos y que pueda tener parentesis o guiones **/
function phoneCheck(str) {
  return /^(1\s|1|)?((\(\d{3}\))|\d{3})(\-|\s)?(\d{3})(\-|\s)?(\d{4})$/.test(str);
}
/** Valida el que la cadena solo tenga letras**/
function nameCheck(str){
	return /^[a-zA-Z]+$/.test(str);
}
