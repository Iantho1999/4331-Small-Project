var urlBase = 'http://cop4331group13.xyz/LAMPAPI';
var extension = 'php';

var userId = 0;
var firstName = "";
var lastName = "";

var a = document.getElementById ("registerButton");
if(a){
	a.addEventListener("click", doRegister, false);
}

// var b = document.getElementById ("loginButton");
// if(b){
// 	b.addEventListener("click", doRegister, false);
// }

//document.getElementById ("loginButton").addEventListener ("click", doLogin, false);

function doLogin()
{
    userId = 0;
	firstName = "";
	lastName = "";
	
	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;
	var hash = md5( password );
	
	document.getElementById("loginResult").innerHTML = "";

	var jsonPayload = '{"login" : "' + login + '", "password" : "' + hash + '"}';
//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Login.' + extension;

	console.log(jsonPayload);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);
		
		var jsonObject = JSON.parse(xhr.responseText);
		
		userId = jsonObject.id;
		
		if( userId < 1 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}
		
		firstName = jsonObject.firstName;
		lastName = jsonObject.lastName;

		saveCookie();
	
		window.location.href = "chart.html";
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}

function doRegister()
{
    var login = document.getElementById("registerUser").value;
	var password = document.getElementById("registerPass").value;
	var first = document.getElementById("registerFirst").value;
	var last = document.getElementById("registerLast").value;
	var hash = md5( password );
	
	//document.getElementById("loginResult").innerHTML = "";

	var jsonPayload = '{"firstName" : "' + first + '", "lastName" : "' + last + '", "login" : "' + login + '", "password" : "' + hash + '"}';
//	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Register.' + extension;

	console.log(jsonPayload);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.href = "index.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("registerResult").innerHTML = err.message;
	}

}

function saveCookie()
{
	var minutes = 20;
	var date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));	
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}
	
	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
		document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
	}
}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function doSearch()
{
 	var search = document.getElementById("search-bar").value;
 	//readCookie();
 	var cookieId = 0;
	var data = document.cookie;
	var splits = data.split(",");
	for(var i = 0; i < splits.length; i++) 
	{
		var thisOne = splits[i].trim();
		var tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			cookieId = parseInt( tokens[1].trim() );
		}
	}

 	document.getElementById("search-bar").innerHTML = "";

 	var jsonPayload = '{"search" : "' + search + '", "userId" : "' + cookieId + '"}';
	var url = urlBase + '/SearchContacts.' + extension;

	console.log(jsonPayload);
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	try
	{
		xhr.send(jsonPayload);
		var jsonObject = JSON.parse(xhr.responseText);
		//document.getElementById("searchResult").innerHTML = JSON.stringify(jsonObject);
	}
	catch(err)
	{
		document.getElementById("Results").innerHTML = err.message;
    return;
	}
   
   if ((jsonObject.results).length == 0)
   {
     document.getElementById("Results").innerHTML = "No Records Found";
     document.getElementById("contactTable").innerHTML = "";
     return;
   }
   else
     document.getElementById("Results").innerHTML = "";
  
  var table = document.getElementById("contactTable");
  table.innerHTML = "";
  
  for (var i = 0; i < (jsonObject.results).length; i++)
  {
    var row = `<tr>
                  <td>${(jsonObject.results)[i].firstName}</td>
                  <td>${(jsonObject.results)[i].lastName}</td>
                  <td>${(jsonObject.results)[i].phoneNumber}</td>
                  <td>${(jsonObject.results)[i].email}</td>
                  <td><div class="dropdown">
  <button onclick="myFunction()" class="dropbtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>
  </button>
  <div id="myDropdown" class="dropdown-content">
    <a href="#home">Edit</a>
    <a href="#about">Delete</a>
  </div>
</div> </td>
              </tr>`;
  
    table.innerHTML += row;
  }
}

function genTable(data)
{
  var table = document.getElementById("contactTable");
  
  for (var i = 0; i < data.length; i++)
  {
    var row = `<tr>
                  <td>${data[i].firstName}</td>
                  <td>${data[i].lastName}</td>
                  <td>${data[i].phoneNumber}</td>
                  <td>${data[i].email}</td>
              </tr>`;
  
    table.innerHTML += row;
  }
}

function addContact()
{
  var first = document.getElementById("user").value;
  var last = document.getElementById("pass").value;
  var phone = document.getElementById("phone").value;
  var email = document.getElementById("mail").value;

  var jsonPayload = '{"firstName" : "' + first + '", "lastName" : "' + last + '", "phoneNumber" : "' + phone + '", "email" : "' + mail + '", "userId" : "' + userId + '"}';
  var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

  try
  {
    xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				window.location.href = "chart.html";
			}
		};
    xhr.send(jsonPayload); 
  }
  catch(err)
  {
    document.getElementById("registerResult").innerHTML = err.message;
  }
}


function myFunction() 
{
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event)
{
  if (!event.target.matches('.dropbtn'))
  {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++)
    {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show'))
      {
        openDropdown.classList.remove('show');
      }
    }
  }
}