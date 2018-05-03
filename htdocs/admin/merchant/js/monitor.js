	//=====================================================================================
	//=====================================================================================
	//=====================================================================================
	function startAjaxMonitor(resto_id)
	{
		//varFareTimeOut = setTimeout(ajaxMonitoring, 2000);
		alert('Panggil ajaxMonitorOrder = ' + resto_id);
		setInterval(ajaxMonitorOrder(resto_id), 5000);
		//alert('Panggil ajaxMonitorWaitress = ' + resto_id);
		//setInterval(ajaxMonitorWaitress(resto_id), 5000);
		//alert('Panggil ajaxMonitorBayar = ' + resto_id);
		//setInterval(ajaxMonitorBayar(resto_id), 5000);
		alert('Selesai');
	}

	function ajaxMonitorOrder(parameters)
	{
		try {
		  $.ajax({
		    type: "POST",
		    data: {
		      "param01": 'user@chatbot.com',
		      "param02": parameters
		    },

		    url: "ajaxMonitorOrder.php",
		    dataType: "json",
		    success: function(JSONObject) {

		    //Clear first old data
		    $('#tblOrders tbody').html('');
			var table = document.getElementById("tblOrders").getElementsByTagName('tbody')[0];

		      // Loop through Object and 
		      for (var key in JSONObject) {
		        if (JSONObject.hasOwnProperty(key)) {
				    /*
				        ORDER STATUS
				        0 = Open
				        1 = Progress
				        2 = Close
				        3 = Urgent
				        4 = Invalid
				    */
				    var status = '';
				    if (JSONObject[key]["status"]=='0') {
				    	status = '<span class="label label-primary">OPEN</span>';
				    }else if (JSONObject[key]["status"]=='1') {
				    	status = '<span class="label label-info">PROGRESS</span>';
				    }else if (JSONObject[key]["status"]=='2') {
				    	status = '<span class="label label-success">CLOSE</span>';
				    }else if (JSONObject[key]["status"]=='3') {
				    	status = '<span class="label label-warning">URGENT</span>';
				    }else if (JSONObject[key]["status"]=='4') {
				    	status = '<span class="label label-danger">INVALID</span>';
				    }

				    var row = table.insertRow(table.rows.length);
				    var cell1 = row.insertCell(0);
				    var cell2 = row.insertCell(1);
				    var cell3 = row.insertCell(2);
				    var cell4 = row.insertCell(3);
				    var cell5 = row.insertCell(4);
				    var cell6 = row.insertCell(5);

					var newText1 = document.createTextNode("#" + JSONObject[key]["id"]);
					cell1.appendChild(newText1);

					var newText2 = document.createTextNode(status);
					cell2.appendChild(newText2);
					//cell2.innerHTML = '<span class="label label-danger">OPEN</span>';
					cell2.innerHTML = status;

					var newText3 = document.createTextNode(JSONObject[key]["table_id"]);
					cell3.appendChild(newText3);

					var newText4 = document.createTextNode(JSONObject[key]["user_name"]);
					cell4.appendChild(newText4);

					var newText5 = document.createTextNode(JSONObject[key]["details"]);
					//var newText5 = document.createTextNode(res);
					cell5.className = "newline";
					cell5.appendChild(newText5);

					var newText6 = document.createTextNode("5");
					cell6.appendChild(newText6);
					var strParse = JSONObject[key]["id"] + '&'+ JSONObject[key]["table_id"] + '&'+JSONObject[key]["user_name"];
					var strHTML = '<button type="button" class="open-ModalEdit btn btn-primary btn-lg" data-toggle="modal" data-id="' + strParse + '">Edit</button>';
					cell6.innerHTML = strHTML01 + strHTML02;

				    //Create notification
				    if (JSONObject[key]["notif"]>0) {
						document.getElementById("spnOrder").textContent=JSONObject[key]["notif"];
			            ion.sound.play("bell_ring");
				    }else {
				    	document.getElementById("spnOrder").textContent="";
				    }
		        }
		      }

		    }
		  });
		} catch(err) {
			  txt="There was an error on this page.\n\n";
			  txt+="Error description: " + err.message + "\n\n";
			  txt+="Click OK to continue.\n\n";
			  alert(txt);			
		}
	}
	
	function ajaxMonitorWaitress(parameters)
	{

		try {

		  $.ajax({
		    type: "POST",
		    data: {
		      "param01": 'user@chatbot.com',
		      "param02": parameters
		    },
		    url: "ajaxMonitorWaitress.php",
		    dataType: "json",
		    success: function(JSONObject) {

		      //Clear first old data
		      $('#tblWaitress tbody').html('');

			  var table = document.getElementById("tblWaitress").getElementsByTagName('tbody')[0];

		      // Loop through Object and 
		      for (var key in JSONObject) {
		        if (JSONObject.hasOwnProperty(key)) {
				    /*
				        ORDER STATUS
				        0 = Open
				        1 = Progress
				        2 = Close
				        3 = Urgent
				        4 = Invalid
				    */
				    var status = '';
				    if (JSONObject[key]["status"]=='0') {
				    	status = '<span class="label label-primary">OPEN</span>';
				    }else if (JSONObject[key]["status"]=='1') {
				    	status = '<span class="label label-info">PROGRESS</span>';
				    }else if (JSONObject[key]["status"]=='2') {
				    	status = '<span class="label label-success">CLOSE</span>';
				    }else if (JSONObject[key]["status"]=='3') {
				    	status = '<span class="label label-warning">URGENT</span>';
				    }else if (JSONObject[key]["status"]=='4') {
				    	status = '<span class="label label-danger">INVALID</span>';
				    }
					var row = table.insertRow(table.rows.length);
				    var cell1 = row.insertCell(0);
				    var cell2 = row.insertCell(1);
				    var cell3 = row.insertCell(2);
				    var cell4 = row.insertCell(3);
				    var cell5 = row.insertCell(4);
				    var cell6 = row.insertCell(5);

					var newText1 = document.createTextNode("#" + JSONObject[key]["id"]);
					cell1.appendChild(newText1);

					var newText2 = document.createTextNode(status);
					cell2.appendChild(newText2);
					//cell2.innerHTML = '<span class="label label-danger">OPEN</span>';
					cell2.innerHTML = status;

					var newText3 = document.createTextNode(JSONObject[key]["table_id"]);
					cell3.appendChild(newText3);

					var newText4 = document.createTextNode(JSONObject[key]["user_name"]);
					cell4.appendChild(newText4);

					var newText5 = document.createTextNode(JSONObject[key]["description"]);
					//var newText5 = document.createTextNode(res);
					cell5.className = "newline";
					cell5.appendChild(newText5);

					var newText6 = document.createTextNode("5");
					cell6.appendChild(newText6);
					cell6.innerHTML = '<align="center"><a rel="facebox" class="btn btn-primary" href="editkategori.php?id=' + JSONObject[key]["id"] + '"> <i class="fa fa-pencil"></i> </a>  <a href="#" id="'  + JSONObject[key]["id"] + '" class="btn btn-danger delbutton" title="Click To Delete"><i class = "fa fa-trash"></i></a>';

				    //Create notification
				    if (JSONObject[key]["notif"]>0) {
						document.getElementById("spnWaitress").textContent=JSONObject[key]["notif"];
			            ion.sound.play("bell_ring"); 
				    }else {
				    	document.getElementById("spnWaitress").textContent="";
				    }

		        }
		      }

		    }
		  });
		} catch(err) {
			  txt="There was an error on this page.\n\n";
			  txt+="Error description: " + err.message + "\n\n";
			  txt+="Click OK to continue.\n\n";
			  alert(txt);			
		}
	}

	
	function ajaxMonitorBayar(parameters)
	{

		try {

		  $.ajax({
		    type: "POST",
		    data: {
		      "param01": 'user@chatbot.com',
		      "param02": parameters
		    },
		    url: "ajaxMonitorBayar.php",
		    dataType: "json",
		    success: function(JSONObject) {
		      //Clear first old data
		      //$('#<%=tblOrders.ClientID%> tr').not(function(){ return !!$(this).has('th').length; }).remove();
		      //$("#<%=tblOrders.ClientID%> > tbody > tr").remove();
		      $('#tblBayar tbody').html('');

			  var table = document.getElementById("tblBayar").getElementsByTagName('tbody')[0];
		      // Loop through Object and 
		      for (var key in JSONObject) {
		        if (JSONObject.hasOwnProperty(key)) {
				    /*
				        ORDER STATUS
				        0 = Open
				        1 = Progress
				        2 = Close
				        3 = Urgent
				        4 = Invalid
				    */
				    var status = '';
				    if (JSONObject[key]["status"]=='0') {
				    	status = '<span class="label label-primary">OPEN</span>';
				    }else if (JSONObject[key]["status"]=='1') {
				    	status = '<span class="label label-info">PROGRESS</span>';
				    }else if (JSONObject[key]["status"]=='2') {
				    	status = '<span class="label label-success">CLOSE</span>';
				    }else if (JSONObject[key]["status"]=='3') {
				    	status = '<span class="label label-warning">URGENT</span>';
				    }else if (JSONObject[key]["status"]=='4') {
				    	status = '<span class="label label-danger">INVALID</span>';
				    }
					var row = table.insertRow(table.rows.length);
				    var cell1 = row.insertCell(0);
				    var cell2 = row.insertCell(1);
				    var cell3 = row.insertCell(2);
				    var cell4 = row.insertCell(3);
				    var cell5 = row.insertCell(4);
				    var cell6 = row.insertCell(5);

					var newText1 = document.createTextNode("#" + JSONObject[key]["id"]);
					cell1.appendChild(newText1);

					var newText2 = document.createTextNode(status);
					cell2.appendChild(newText2);
					//cell2.innerHTML = '<span class="label label-danger">OPEN</span>';
					cell2.innerHTML = status;

					var newText3 = document.createTextNode(JSONObject[key]["table_id"]);
					cell3.appendChild(newText3);

					var newText4 = document.createTextNode(JSONObject[key]["user_name"]);
					cell4.appendChild(newText4);

					var newText5 = document.createTextNode(JSONObject[key]["description"]);
					//var newText5 = document.createTextNode(res);
					cell5.className = "newline";
					cell5.appendChild(newText5);

					var newText6 = document.createTextNode("5");
					cell6.appendChild(newText6);
					cell6.innerHTML = '<align="center"><a rel="facebox" class="btn btn-primary" href="editkategori.php?id=' + JSONObject[key]["id"] + '"> <i class="fa fa-pencil"></i> </a>  <a href="#" id="'  + JSONObject[key]["id"] + '" class="btn btn-danger delbutton" title="Click To Delete"><i class = "fa fa-trash"></i></a>';

				    //Create notification
				    if (JSONObject[key]["notif"]>0) {
						document.getElementById("spnTagihan").textContent=JSONObject[key]["notif"];
			            ion.sound.play("bell_ring"); 
				    }else {
				    	document.getElementById("spnTagihan").textContent="";
				    }
		        }
		      }

		    }
		  });
		} catch(err) {
			  txt="There was an error on this page.\n\n";
			  txt+="Error description: " + err.message + "\n\n";
			  txt+="Click OK to continue.\n\n";
			  alert(txt);			
		}
	}	
