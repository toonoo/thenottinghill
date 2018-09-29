// V 2.0 Support Fire Fox
// EDITED on 20070308 by CMUMAD 
	var format=
	{	"NOTENTER"				:	/^[^\r]+$/,
		"EMAIL"							:	/^[\w-\.]+\@[\w\.-]+\.[a-zA-Z]{2,4}$/,
		"ENGTHAIALPHA"		:	/^[\s\r¡-ù0-9a-zA-Z]+$/,
		"ENGTHAINAME"			:	/^[\s\r\.\'¡-ëa-zA-Z]+$/,
		"NUMBER"					:	/^[0-9]+$/,
		"NOTNUMBER"			:	/^[^0-9)]+$/,					//cannot press enter from in this type. Must remove '^' by Invert CharSet
		"ENGALPHA"				:	/^[\ra-zA-Z]+$/,
		"ENGALPHALOWER"	:	/^[\ra-z]+$/,
		"ENGALPHAUPPER"	:	/^[\rA-Z]+$/,
		"ZIPCODE"					:	/^[\-\s()0-9]+$/,
		"PHONE"						:	/^[\-\s()0-9]+$/
			
	};
	function validation (inputform,customfunction)
	{	for (i=0;i<inputform.length;i++)
		{	if((inputform[i].type=='text'||inputform[i].type=='password')||inputform[i].type=='textarea'||inputform[i].type=='select-one'&&inputform[i].style.display!='none')
			{	if(inputform[i].id!=''&&inputform[i].value=='')
				{	alert(inputform[i].id);
					inputform[i].focus();
					return false;
				}
				if(inputform[i].alt!=''&&inputform[i].alt!=undefined&&inputform[i].value!='')
				{	if(!format[getType(inputform[i].alt)].test(inputform[i].value))
						return errorAction(inputform[i]);
				}
			}
		}
		if(customfunction)
		{	if (typeof(register_form) != 'undefined')
			{	for (i=0;i<inputform.length;i++ )
				{	if(inputform[i].type=='text'||inputform[i].type=='textarea'||inputform[i].type=='password')
					{	if(inputform[i].value.indexOf(">")>= 0||inputform[i].value.indexOf("<")>= 0)
						{	alert('<> character not allowed');
							inputform[i].select();
							return false;
						}else if(inputform[i].value.indexOf("http")>= 0)
						{	alert('http not allowed');
							inputform[i].select();
							return false;
						}else if(inputform[i].value.indexOf("www")>= 0)
						{	alert('www not allowed');
							inputform[i].select();
							return false;
						}
					}
				}
				if(typeof(register_form.mem_password) != 'undefined')
				{	if(register_form.mem_password.value!=register_form.mem_password_confirm.value)
					{	alert('Password not match');
						register_form.mem_password.select();
						return false;
					}
				}
			}
		}
	}
	function getType(property)
	{	return property.substr(0,property.indexOf("="));
	}
	function getMess(property)
	{	return property.substr(property.indexOf("=")+1,property.length);
	}
	function errorAction(element)
	{	alert(getMess(element.alt));
		element.select();
		return false;
	}
	function keyFilter(element,event)
	{	var event = event || window.event;
		var keyCode = event.keyCode || event.which;
		if(element.alt!=''&&element.alt!=undefined)
		{	if(getType(element.alt)=="EMAIL")
			{	if(!String.fromCharCode(keyCode).match(/[\r\w-\.\@]/))
					return false;
			}else if(getType(element.alt)=="CUSTOM")
			{	if(!String.fromCharCode(keyCode).match(/test@hotmail.com/))
					return false;
			}else
			{	if(!String.fromCharCode(keyCode).match(format[getType(element.alt)]))
					return false;
			}
		}
	}
	function validationFile (element, image, tr, hidden_delete, width)
	{	for(key in pattern = element.id.split(","))
			if(pattern[key].toLowerCase()==element.value.split(".")[element.value.split(".").length-1].toLowerCase())
			{	key = false;
				break;
			}
		if(key)
		{	element.outerHTML = element.outerHTML.replace(/value=*/,'');
			tr.style.display = 'none';
			return errorAction(element);
		}
		else
		{	document.getElementById(hidden_delete).disabled = true;
			image.src=element.value;
			image.width = width;
			tr.style.display = '';
		}
	}
	function imageCancel (element, tr, hidden_delete)
	{	document.getElementById(element).outerHTML = (document.getElementById(element).outerHTML+'').replace(/value=*/,'');
		document.getElementById(tr).style.display = 'none';
		if (document.all || document.getElementByid)
			document.hidden_delete.disabled = false;
	}