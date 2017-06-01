// JavaScript Document

var elEditor;
var _navegador = navigator.userAgent;
var ie = /msi/i.test(_navegador);
var op = /opera/i.test(_navegador);
var mo = /gecko/i.test(_navegador);
var otro = !(ie || mo);
var _insertor, _insertar, _formulario, _texto, _lector = "";

function edita(que){
if(que=='rojo')	_insertar(elEditor, '<font color="red">'+_lector()+'</font>');
if(que=='salto')	_insertar(elEditor,'<br />')
if(que=='negrita')	_insertar(elEditor, '<strong>'+_lector()+'</strong>');
if(que=='subrayado')	_insertar(elEditor, '<u>'+_lector()+'</u>');
if(que=='cursiva')	_insertar(elEditor, '<i>'+_lector()+'</i>');
if(que=='centrado')	_insertar(elEditor, '<center>'+_lector()+'</center>');
if(que=='comillas')	_insertar(elEditor, '"'+_lector()+'"');
if(que=='bola')	_insertar(elEditor,'&bull;&nbsp;');
$('vista').innerHTML=elEditor.value.replace(/\n/gi, '<br>');
}

function datos_ie() {
	_texto = document.selection.createRange().text;
	if (_formulario.createTextRange)
		_formulario.posi = document.selection.createRange().duplicate();
	return true;
}

function captura_ie()	{
	return _texto;
}

function captura_mo() {
	with (_formulario) return value.substring(selectionStart, selectionEnd);
}

function captura_otro()	{
	return "";
}

function poner_mo(f, x)	{//alert(x);
	var _ini = f.selectionStart;
	var _fin = f.selectionEnd;
	var inicio = f.value.substr(0, _ini);
	var fin = f.value.substr(_fin, f.value.length);

	f.value = inicio + x + fin;
	if (_ini == _fin)	{
		f.selectionStart = inicio.length + x.length;
		f.selectionEnd = f.selectionStart;
	}
	else	{
		f.selectionStart = inicio.length;
		f.selectionEnd = inicio.length + x.length;
	}
	f.focus();
}

function poner_otro(f, x)	{// opera u otros navegadores desconocidos
	f.value += x;//alert(x);
	f.focus();
}

function poner_ie(f, x)	{
	f.focus();
	if (f.createTextRange)	{// && f.posi)	{
		if (!f.posi)	datos_ie();
		with(f)	{
			var actuar = (posi.text == "");
			posi.text = x;
			if (!actuar)
				posi.moveStart("character", -x.length);
			posi.select();
		}
	}
}

function ini_editor(formu)	{
	_formulario = formu;
	
	if (op || mo)	{//alert("mozilla u opera");
		_insertar = function(f, x) {poner_mo(f, x);};
		_lector = captura_mo;
	}

else	if (otro)	{//alert("otro");
		_insertar = function(f, x) {poner_otro(f, x);};
		_lector = captura_otro;
	}

else	if (ie)	{
		_formulario.onchange = datos_ie;
		_formulario.onclick  = datos_ie;
		_insertar = function(f, x) {poner_ie(f, x);};
		_lector = captura_ie;
	}
	return formu;
}