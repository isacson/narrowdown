thisPath = "";

$(function() {

	// initialize the member search object
	searchOn = {
		chamber: 0,
		entity: [],
		matches: 1,
		party: 0,
		showlead: 1
	};

	$("#registry_form").hide();

	entityLoad();

	//populate the members
	memberSelect(searchOn);

	//make the "matches" input update upon hitting "return"
	$( "#matches" ).bind('blur keyup',function(e) {

		if ($( this ).val() != "") {
			$( this ).css("background-color", "palegoldenrod");
		}
		else {
			$( this ).css("background-color", "initial");
		}
		if (e.type == 'blur' || e.keyCode == '13')  {

			searchOn.matches = $( "#matches" ).val();
			memberSelect(searchOn);
		}
	});
	
});

//what happens when you click on House or Senate checkbox
function chamberSelect() {

	//the number to be added to the searchOn object to indicate which chambers have been chosen
	val = 0;

	$('[id^="chamber"]').each(function() {
		if ($( this ).prop("checked") === true) {
			a = Number($( this ).prop("value"));
			// sum the values to get the combiation of chambers
			val += a;
		}
	});

	searchOn.chamber = val;

	memberSelect(searchOn);
}

//what happens when you click on the party checkbox
function partySelect() {

	//the number to be added to the searchOn object to indicate which parties have been chosen
	val = 0;

	$('[id^="party"]').each(function() {
		if ($( this ).prop("checked") === true) {
			a = Number($( this ).prop("value"));
			// sum the values to get the combination of parties
			val += a;
		}
	});

	searchOn.party = val;

	memberSelect(searchOn);
}

$( "#boldlead" ).click(function() {

	if (searchOn.showlead == 1) {
		searchOn.showlead = 0;
		$( "#boldlead" ).css("font-weight", "normal");
		memberSelect(searchOn);
	}
	else {
		searchOn.showlead = 1;
		$( "#boldlead" ).css("font-weight", "bold");
		memberSelect(searchOn);
	}
});

// allows you to hit "enter" to update the little blank to set a minimum number of matches
$( "#matches" ).blur(function() {

	searchOn.matches = $( "#matches" ).val();
	memberSelect(searchOn);
});

$( "#exportMem" ).click(function() {
	$.ajax({
		url: thisPath + "member_csv.php",
		type: 'POST',
		dataType: 'text',
		data: {
			searchOn: searchOn
		},
		success: function(data) {
			var myWindow = window.open("", "members.csv", "");
			myWindow.document.write(data);
		}
	});
});

function selectMem() {
	var doc = document
        , text = document.getElementById( "members" )
        , range, selection
    ;    
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

// populates the members box, returning a json object based on what was in the searchOn object
function memberSelect(searchOn) {
	$.ajax({
		url: thisPath + "member_select.php",
		type: 'POST',
		dataType: 'json',
		data: {
			searchOn: searchOn
		},
		success: function(data) {

			$( "#members" ).empty();

			if (data[0].senrep == undefined) {
				$("<div/>", {
					html: "Sorry, no results",
					style: "text-align: center;"
				})
				.appendTo('#members');				
			}
			else {

				var counts = new Array;
				for (var i = 0, len = data.length; i < len; i++) {
					counts.push(data[i].count);
				}
				maxcount = Math.max.apply(null, counts);

				for (var i = 0, len = data.length; i < len; i++) {

					a = "";

					if (data[i].leader == 1 && searchOn.showlead == 1) {
						a += "<strong>";
					}

					a += data[i].senrep + " ";

					a += data[i].firstname + " ";

					if (data[i].nickname != "") {
						a += "\"" + data[i].nickname + "\" ";
					}

					a += " " + data[i].lastname + " (" + data[i].party + "-" + data[i].state +")";

					if (data[i].leader == 1 && searchOn.showlead == 1) {
						a += "</strong>";
					}

					b = "";

					if (data[i].count > 1) {
						a += ": " + data[i].count + " matches";

						b = "padding: 1px; border-width: 0 1px 1px 1px; border-style: solid; border-color: palegoldenrod; margin:-1.5px;";

						if (data[i].count == maxcount) {
							b = "padding: 1px; background-color: lightGoldenrodYellow;";
						}
					}

					$("<div/>", { 
						id: "member" + data[i].member_key,
						html: a,
						class: "tooltip_guest",
						style: b
					})
					.appendTo( "#members" );

					a = data[i].firstname + " ";

					if (data[i].nickname != "") {
						a += "\"" + data[i].nickname + "\" ";
					}

					a += data[i].middlename + " " + data[i].lastname

					if (data[i].name_suffix != "")
					{
						a += " " + data[i].name_suffix;
					}
					a += "<br>District: " + data[i].district +
					"<br>Office: " + data[i].congress_office + 
					"<br>Phone: " + data[i].phone;

					$("<span/>", {
						id: "tooltipspan_guest" + data[i].member_key,
						class: "tooltiptext_guest",
						html: a
					})
					.appendTo( "#member" + data[i].member_key );
				}
			}
		}
	})
}

function entityLoad() {

	$.ajax({
		url: thisPath + "entity_load_guest.php",
		type: 'POST',
		dataType: 'json',
		success: function(data) {

			$( "#guest_entities" ).empty();

			if (data == "" || data[0].entity == undefined) {
				$("<div/>", {
					id: "sorrynogroups",
					html: "Sorry, there are no matching groups",
					style: "text-align: center;"
				})
				.appendTo('#guest_entities');				
			}
			else {

				for (var i = 0, len = data.length; i < len; i++) {

					$("<div/>", {
						id: "divFor" + data[i].entity_key
					})
					.appendTo( "#guest_entities" );

					$("<input/>", {
						type: "checkbox",
						id: "entity" + data[i].entity_key
					})
					.appendTo('#divFor' + data[i].entity_key)
					.click(function() {
						entityCheck();
					});

					$("<label/>", {
						for: "entity" + data[i].entity_key,
						id: "entity" + data[i].entity_key + "label",
						html: "&nbsp;" + data[i].entity + "&nbsp;",
						class: "entitylabel"
					})
					.appendTo('#divFor' + data[i].entity_key);

					if (data[i].url != "") {
						$("<span/>", {
							id: "entityurl" + data[i].entity_key,
							html: "(<a target='_blank' href='" + data[i].url + "'>*</a>)"
						})
						.appendTo('#divFor' + data[i].entity_key);
					}

					$("<span/>", {
						id: "entuserdatespan" + data[i].entity_key + "nowrap",
						style: "white-space: nowrap; font-size: 0.7em;",
						html: "<br>Updated on " + data[i].ts + "&nbsp;"
					})
					.appendTo('#divFor' + data[i].entity_key);
				}
			}
		}
	});
}

function entityCheck() {

	// clear the entity array
	searchOn.entity = [];

	//iterate over the checkboxes to populate the entity array
	$('[id^="entity"]').each(function() {
		if ($( this ).prop("checked")) {
			a = this.id;
			//get the entity_key by taking the digits after the word "entity" in the id
			a = a.substring(6);
			// put the checked entity in the array
			searchOn.entity.push(a);
		}
	});
	// populate the members list
	memberSelect(searchOn);	
}