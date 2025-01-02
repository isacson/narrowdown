thisPath = "";

$(function() {

	// initialize the member search object
	searchOn = {
		chamber: 0,
		entity: [],
		matches: 1,
		party: 0,
		showlead: 1,
		searchMatch: ""
	};

	user_key = $( "#user_key" ).html();

	//populate the saved searches
	searchSelect(user_key);
	entsortsearchLoad();
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

	$('html').click(function(e) {
		$('[id^="tooltipspan"]').remove();
		$('[id^="divFor"]').css("background-color", "initial");	

		searchOn.entity = Array.from(new Set(searchOn.entity));
		for (var i=0; i < searchOn.entity.length; i++) {
			b = "padding: 1px; border-width: 1px 2px 1px 2px; border-style: solid; border-color: palegoldenrod; margin:-1.5px;";
			$( "#divFor" + searchOn.entity[i]).prependTo( "#entities" ).prop("style", b);
		}
		if (event.target.nodeName != "BUTTON" ) {
			$( "#entities" ).scrollTop(0);
		}

		if (event.target.nodeName != "SPAN" ) {
			$('[id^="memberspan"]').css("background-color", "initial");
		}

		if (event.target.className == "tooltip") {
			return;
		}

		$("#entities [id^='divFor']").sort(asc_sort).appendTo('#entities');
		$($("[id^='entity']").get().reverse()).each(function() {

			if ($( this ).prop("checked") === true) {
				j=this.id.substring(6);
				$( "#divFor" + j).prependTo("#entities");
			}
		})
	});
	
});

$( "#clearAll" )
.click( function() {
	// if we don't want to see a saved search, we dont need any of the helper bits
	$('[id^="askOverwrite"]').remove();
	$('[id^="youSureSaved"]').remove();
	// this also means no entities chosen, thus all members visible
	$( "input:checkbox" ).prop("checked", false);
	$( "#savedSearchInputNo" ).prop("checked", true);
	$( "#matches").val("").css("background-color", "initial");
	// also the entities should be alphabetized
	searchOn = {
		chamber: 0,
		entity: [],
		matches: 1,
		party: 0,
		showlead: 1,
		searchMatch: ""
	};
	entityLoad(user_key);
	entityCheck();
});

$( "#matchtext" ).keyup(function() {
	searchOn.searchMatch = $( this ).val();	
	if (searchOn.searchMatch != "") {
		$( this ).css("background-color", "lightGoldenrodYellow");
	}
	else {
		$( this ).css("background-color", "initial");
	}
	memberSelect(searchOn);
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

// populate the list of saved searches
function entityCheck() {

	// clear the entity array
	if ($("entsearchtext").val() == "") {
		searchOn.entity = [];
	}

	//iterate over the checkboxes to populate the entity array
	$('[id^="entity"]').each(function() {
		a = this.id;
		//get the entity_key by taking the digits after the word "entity" in the id
		a = a.substring(6);

		if ($( this ).prop("checked")) {
			// put the checked entity in the array
			searchOn.entity.push(a);
		}
		else {
			// take the unchecked entity out of the array
			b = searchOn.entity.indexOf(a);
			if (b != -1) {
				searchOn.entity.splice(b);

				$("#entities [id^='divFor']").sort(asc_sort).appendTo('#entities').prop("style", "");				

				entityCheck();
			}
		}
	});

	// populate the members list
	memberSelect(searchOn);

	if (searchOn.entity[0] != undefined) {
		// the suggestion to add a search lingers unless you clear it out
		$("#addSavedSearch").remove();
		//if an entity was checked while no saved searches were selected, ask to save the selection as a search
		if ( $("#savedSearchInputNo" ).prop("checked") === true) {
			$("<div/>", {
				id: "addSavedSearch",
				html: "Add this as a new saved search<br>",
				class: "bg-success"
			})
			.insertAfter('#noSavedSearch');

			$("<input/>", {
				id: "addSavedSearchInput",
				type: "text",
				style: "width: 98%;",
				placeholder: "Give these choices a name"
			})
			.appendTo('#addSavedSearch')
			.change(function() {
				// when entering a new search name, make a variable out of it
				ssi = $( this ).val();
				// first, look to see if this name exists. No permission to overwrite
				$.ajax({
					url: thisPath + "add_saved_search.php",
					type: 'POST',
					dataType: 'html',
					data: {
						searchOn: searchOn,
						user_key: user_key,
						saved_search: ssi,
						overwrite: "no"
					},
					success: function(data) {

						//if this is a never-used name, add it to the list of saved searches
						if (data != "exists") {
							// get rid of the search blank
							$("#addSavedSearch").remove();
							// if it doesn't exist, the data is the search_key
							j = data;

							$("<div/>", { 
								id: "savedSearch" + j,
							})
							.appendTo( "#saved_searches" );
							// add it as a new, checked saved search
							$("<input/>", {
								type: "radio",
								id: "savedSearchInput" + j,
								value: j,
								name: "savedsearch"
							})
							.appendTo( "#savedSearch" + j)
							.click(function() {
								$('[id^="youSureSaved"]').remove();
								// I still don't understand why it never updates "j," so here's "k"
								k = this.id.substring(16);
								groupSelect(k);
							});

							$("<label/>", {
								for: "savedSearchInput" + j,
								id: "savedSearchLabel" + j,
								html: "&nbsp;" + ssi + "&nbsp;"
							})
							.appendTo( "#savedSearch" + j)
							.click(function() {
								$('[id^="youSureSaved"]').remove();
								// I still don't understand why it never updates "j," so here's "k"
								k = this.id.substring(16);
								groupSelect(k);
							});

							// delete option
							$("<button/>", {
								id: "savedSearchDelete" + j,
								class: "savedSearchDelete",
								html: "Delete"
							})
							.appendTo( "#savedSearch" + j)
							//what happens when you delete
							.click(function() {
								// make sure this is working with the search's id and not the last chosen search
								k = this.id.substring(17);
								$( this ).prevAll("input").prop("checked", "checked");
								// keep new "are you sure"s from appearing every time you hit delete
								$('[id^="youSureSaved"]').remove();

								// this div asks "are you sure?"
								$("<div/>", {
									id: "youSureSaved" + k,
									html: "Are you sure?&nbsp;"
								})
								.insertAfter( "#savedSearch" + k);

								$("<button/>", {
									html: "Yes"
								}).appendTo( "#youSureSaved" + k )
								.click(function() {
									$.ajax({
										url: thisPath + "search_delete.php",
										type: 'POST',
										dataType: "html",
										data: {
											user_key: user_key,
											saved_search_key: k
										},
										success: function(data) {
											// the delete php sends back "success"
											if (data == "success") {
												// clear out the whole saved search list
												$('[id^="savedSearch"]').remove();
												// clear out the "are you sure" div
												$('[id^="youSureSaved"]').remove();
												// upon deleting, go back to "no search chosen"
												$( "#savedSearchInputNo" ).prop("checked", "checked");
												// repopulate the searches list
												searchSelect(user_key);
												// uncheck the entities
												$('[id^="entity"]').prop("checked", false);
												// re-alphabetize the entities
												entityLoad(user_key);
												entityCheck();
											}
										}
									})						
								})
								// make a space between "yes" and "no" buttons
								$("<span/>", {
									html: "&nbsp;"
								})
								.appendTo( "#youSureSaved" + k);

								// no button just makes the "yes or no" div go away
								$("<button/>", {
									html: "No"
								}).appendTo( "#youSureSaved" + k )
								.click(function() {
									$( this ).parent("div").remove();					
								})
							});
						}
						else {
							// what to do if there is a search by that name already
							$("<div/>", { 
								id: "newSearchExists",
								html: "You already have a search named \"" + ssi + ".\" Replace it?&nbsp;"
							}).insertAfter('#addSavedSearch');

							// yes, I want to rename it
							$("<button/>", {
								html: "Yes"
							}).appendTo( "#newSearchExists" )
							// use the add_saved_search php with overwriting enabled
							.click(function() {
								$.ajax({
									url: thisPath + "add_saved_search.php",
									type: 'POST',
									dataType: 'html',
									data: {
										searchOn: searchOn,
										user_key: user_key,
										saved_search: ssi,
										overwrite: "yes"
									},
									success: function(data) {
										$("#saved_searches").empty();
										searchSelect(user_key);
									}
								});
							});

							$("<span/>", {
								html: "&nbsp;"
							})
							.appendTo( "#newSearchExists" );

							// no I don't want to rename it -- make this disappear and put the cursor back into the blank
							$("<button/>", {
								html: "No"
							})
							.appendTo( "#newSearchExists" )
							.click(function() {
								$( this ).parent("div").prevAll("div").children(":text").val("").focus();
								$( this ).parent("div").remove();
							})

						}
					}
				})
			});
		}
		else {
			// what if a user clicks on an entity, but there's a saved search already selected?

			// first, find out which saved search is selected (number and name)
			
			saved_search_text = $(":radio:checked").next("label").html();
			saved_search_text = saved_search_text.replace(/\&nbsp;/g,"");
			saved_search_key = $(":radio:checked").prop("id");
			saved_search_key = saved_search_key.replace("savedSearchInput", "");

			// ask if they want to overwrite the saved search with current choices
			// first, remove everything that starts with askoverwrite to prevent propagation
			$('[id^="askOverwrite"]').remove();

			// let's use ajax to find out whether the saved search has been changed or not
			$.ajax({
				url: thisPath + "saved_search_check.php",
				type: "POST",
				dataType: "html",
				data: {
					saved_search_key: saved_search_key,
					searchOn: searchOn
				},
				success: function(data) {
					if (data == "same") {
						// do nothing; make askOverWrite disappear if it's there for some reason
						$('[id^="askOverwrite"]').remove();
					}
					if (data == "different") {
						$('[id^="askOverwrite"]').remove();
						// entities were changed. bring on the askoverwrite div
						$("<div/>", {
							id: "askOverwrite" + saved_search_key,
							html: "Update \"" + saved_search_text + "\" with these selections:&nbsp;",
							class: "bg-success"
						})
						.insertAfter('#savedSearch' + saved_search_key);
						$("<button/>", {
							html: "Do It"
						})
						.appendTo("#askOverwrite" + saved_search_key)
						.click(function() {
						// ajax to update the entity_saved_search table			
							$.ajax({
								url: thisPath + "edit_saved_search.php",
								type: "POST",
								dataType: "html",
								data: {
									saved_search_key: saved_search_key,
									searchOn: searchOn
								},
								success: function(data) {
									if (data = "success") {
										// make askOverWrite disappear
										$('[id^="askOverwrite"]').remove();
									}
								}
							})
						});
						// maybe a second button that says "buzz off" and makes askOverwrite disappear
						$("<button/>", {
							html: "No"
						})
						.appendTo("#askOverwrite" + saved_search_key)
						.click(function() {
							$('[id^="askOverwrite"]').remove();
						});
					}
				}
			})
		}
	}
	else {
		// if the change to the entities list was "uncheck everything," then we shouldn't be seeing an offer to save "everything unchecked" as a new search
		$("#addSavedSearch").remove();
	}
	searchOn.entity = Array.from(new Set(searchOn.entity));
	for (var i=0; i < searchOn.entity.length; i++) {
		b = "padding: 1px; border-width: 1px 2px 1px 2px; border-style: solid; border-color: palegoldenrod; margin:-1.5px;";
		$( "#divFor" + searchOn.entity[i]).prependTo( "#entities" ).prop("style", b);
	}
	$( "#entities" ).scrollTop(0);
}

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
			searchOn: searchOn,
			user_key: user_key
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
		
		console.log(data);

			$( "#members" ).empty();

			if (data[0].senrep == undefined) {
				$("<div/>", {
					html: "Sorry, no results",
					style: "text-align: center;"
				})
				.appendTo('#members');

				$( "#selectMem" ).html("No members to select");		
			}
			else {

				switch(data.length) {
					case 1:
						if (data[0].senrep == undefined) {
							$( "#selectMem" ).html("No members to select");
						}
						else {
							$( "#selectMem" ).html("Select this member");
						}
						break;
					case 2:
						$( "#selectMem" ).html("Select both members");
						break;
					default:
						$( "#selectMem" ).html("Select all " + data.length + " members");
				}

				var counts = new Array;
				for (var i = 0, len = data.length; i < len; i++) {
					counts.push(data[i].count);
				}
				maxcount = Math.max.apply(null, counts);

				for (var i = 0, len = data.length; i < len; i++) {

					a = "<span id='memberspan" + i + "_" + data[i].member_key + "'>";

					if (data[i].leads >= 1 && searchOn.showlead == 1) {
						a += "<span style='font-weight:bold;'>";
					}

					a += data[i].senrep + " ";

					a += data[i].firstname + " ";

					if (data[i].nickname != "") {
						a += "\"" + data[i].nickname + "\" ";
					}

					a += " " + data[i].lastname + " (" + data[i].party + "-" + data[i].state +")";

					if (data[i].leads >= 1 && searchOn.showlead == 1) {
						a += "</span>";
					}

					b = "";

					if (data[i].count > 1) {
						a += ": " + data[i].count + " matches</span>";
	
						b = "padding: 1px; border-width: 0 1px 1px 1px; border-style: solid; border-color: palegoldenrod; margin:-1.5px;";

						if (data[i].count == maxcount) {
							b = "padding: 1px; background-color: lightGoldenrodYellow;";
						}
					}

					$("<div/>", { 
						id: "member" + i + "_" + data[i].member_key,
						html: a,
						class: "tooltip",
						style: b
					})
					.appendTo( "#members" )
					.click(function(event) {
						tooltip(this.id, data);
					});
				}
				$("<div/>", {
					id: "spacer_for_tooltips",
					html: "&nbsp;",
					style: "min-height: 15em;"
				})
				.appendTo('#members');	
			}
		}
	})
}

function tooltip(theId, data) {
	$( "#entities" ).scrollTop(0);

	$('[id^="divFor"]').css("background-color", "initial");
	$('[id^="memberspan"]').css("background-color", "initial");

	$("#entities [id^='divFor']").sort(asc_sort).appendTo('#entities');

	buh = theId.match("member(.+)_");
	i = buh[1];
	buh = theId.match("_(.+)$");
	m = buh[1];

	$( "#memberspan" + i + "_" + m).css("background-color", "#EEEEEE");

	c = "<strong>" + data[i].firstname + " ";

	if (data[i].nickname != "") {
		c += "\"" + data[i].nickname + "\" ";
	}

	c += data[i].middlename + " " + data[i].lastname

	if (data[i].name_suffix != "")
	{
		c += " " + data[i].name_suffix;
	}
	c += "</strong><br>District: " + data[i].district;

	if (data[i].town != "") {
		c += " (" + data[i].town + ")";
	}

	if (data[i].pvi != "") {
		c += "; partisan index " + data[i].pvi;
	}

	c += "<br>Office: " + data[i].congress_office + 
	"<br>Phone: " + data[i].phone;

	if (data[i].website != "") {
		c += "<br><a href='" + data[i].website + "' target='_blank'>" + data[i].website + "</a>";
	}

	$.ajax({
		url: thisPath + "staffer_select.php",
		type: 'POST',
		dataType: 'html',
		data: {
			user_key: user_key,
			member_key: m
		},
		success: function(text) {
			c += text;
			$("<span/>", {
				id: "tooltipspan" + m,
				class: "tooltiptext",
				html: c
			})
			.appendTo( "#member" + i + "_" + m );
		}
	});

	$.ajax({
		url: thisPath + "member_entity_select.php",
		type: 'POST',
		dataType: 'json',
		data: {
			user_key: user_key,
			member_key: m
		},
		success: function(text2) {
			for (var k = 0; k < text2.length; k++) {

				preps = [];

				//walk through the list of entities, checking boxes and adding to prepend array
				for (var k = 0, len = text2.length; k < len; k++) {

					$( "#divFor" + text2[k].entity_key ).css("background-color", "#EEEEEE");
					preps.push(text2[k].entity_key);
				}

				//populate the entities box
//									entityCheck();

				// put the chosen checkboxes on top by prepending them
				for (var k = 0, len = preps.length; k < len; k++) {

					$( "#divFor" + preps[k] ).prependTo( "#entities" );
				}
			}
		}
	});
}

//populates the saved searches list
function searchSelect(user_key, sort) {

	$.ajax({
		url: thisPath + "search_select.php",
		type: 'POST',
		dataType: 'json',
		data: {
			user_key: user_key,
			sort: sort
		},
		success: function(data) {

			$( "#saved_searches" ).empty();

			// this is the option for "no saved search chosen"
			$("<div />", {
				id: "noSavedSearch"
			})
			.appendTo( "#saved_searches" );

			$("<input />", {
				type: "radio",
				id: "savedSearchInputNo",
				name: "savedsearch"
			})
			.appendTo( "#noSavedSearch" )
			.click( function() {
				// if we don't want to see a saved search, we dont need any of the helper bits
				$('[id^="askOverwrite"]').remove();
				$('[id^="youSureSaved"]').remove();
				// this also means no entities chosen, thus all members visible
				$('[id^="entity"]').prop("checked", false);
				// also the entities should be alphabetized
				entityLoad(user_key);
				entityCheck();
			});

			$("<label />", {
				for: "savedSearchInputNo",
				html: "&nbsp;Don&rsquo;t use a saved search"
			})
			.appendTo( "#noSavedSearch" );

			// just in case nothing is checked, check "no saved searches"
			if ($('[id^="savedSearch"]').prop("checked") !== true) {

				$( "#savedSearchInputNo" ).prop("checked", "checked");
			}

			if (data[0].saved_search_key != undefined) {
				// if the ajax found some saved searches for this user, list each one
				for (var i = 0, len = data.length; i < len; i++) {

					j = data[i].saved_search_key;

					$("<div/>", { 
						id: "savedSearch" + j,
					})
					.appendTo( "#saved_searches" )

					$("<input/>", {
						type: "radio",
						id: "savedSearchInput" + j,
						value: j,
						name: "savedsearch"
					})
					.appendTo( "#savedSearch" + j)
					.click(function() {
						$('[id^="youSureSaved"]').remove();
						// I still don't understand why it never updates "j," so here's "k"
						k = this.id.substring(16);
						groupSelect(k);
					});

					$("<label/>", {
						for: "savedSearchInput" + j,
						id: "savedSearchLabel" + j,
						html: "&nbsp;" + data[i].saved_search + "&nbsp;"
					})
					.appendTo( "#savedSearch" + j)
					.click(function() {
						$('[id^="youSureSaved"]').remove();
						// I still don't understand why it never updates "j," so here's "k"
						k = this.id.substring(16);
						groupSelect(k);
					});

					$("<button/>", {
						id: "savedSearchDelete" + j,
						class: "savedSearchDelete",
						html: "Delete"
					})
					.appendTo( "#savedSearch" + j)
					.click(function() {
						k = this.id.substring(17);
						$( this ).prevAll("input").prop("checked", "checked");
						$('[id^="youSureSaved"]').remove();

						$("<div/>", {
							id: "youSureSaved" + k,
							html: "Are you sure?&nbsp;",
							class: "bg-danger"
						})
						.insertAfter( "#savedSearch" + k);

						$("<button/>", {
							html: "Yes"
						}).appendTo( "#youSureSaved" + k )
						.click(function() {
							$.ajax({
								url: thisPath + "search_delete.php",
								type: 'POST',
								dataType: "html",
								data: {
									user_key: user_key,
									saved_search_key: k
								},
								success: function(data) {
									if (data == "success") {
										$( "#savedSearch" + k).remove();
										$( "#youSureSaved" + k).remove();
										$( "#savedSearchInputNo" ).prop("checked", "checked");
										searchSelect(user_key);
										$('[id^="entity"]').prop("checked", false);
										$('[id^="divFor"]').each(function() {
											if ( $( this ).html() < $( this ).prev().html() ) {
												$( this ).prependTo("#entities");
											}
										});
										entityCheck();
									}
								}
							})						
						})

						$("<span/>", {
							html: "&nbsp;"
						})
						.appendTo( "#youSureSaved" + k);

						$("<button/>", {
							html: "No"
						}).appendTo( "#youSureSaved" +k )
						.click(function() {
							$( this ).parent("div").remove();					
						})
					});
				}
			}
		}
	})
}

//checks the entities based on the saved search
function groupSelect(saved_search_key) {

	// first, un-check everything
	$('[id^="entity"]').each(function() {
		$( this ).prop("checked", false)
	});
	// check the entities
	$.ajax({
		url: thisPath + "entity_select.php",
		type: 'POST',
		dataType: 'json',
		data: {
			saved_search_key: saved_search_key
		},
		success: function(data) {
			// the ones chosen should be prepended, here's an array of them
			preps = [];

			if (data[0].entity_key != undefined) {

				//walk through the list of entities, checking boxes and adding to prepend array
				for (var i = 0, len = data.length; i < len; i++) {

					$( "#entity" + data[i].entity_key ).prop("checked", "checked");
					preps.push(data[i].entity_key);
				}
			}

			//populate the entities box
			entityCheck();

			// put the chosen checkboxes on top by prepending them
			for (var i = 0, len = preps.length; i < len; i++) {

				$( "#divFor" + preps[i] ).prependTo( "#entities" );				
			}
		}
	})
}

function entsortsearchLoad() {
	$( "#entsortsearch" ).empty();

	sorter = "date";
	search = "";

	$("<div/>", {
		id: "entsearch"
	})
	.appendTo("#entsortsearch");

	$("<input/>", {
		id: "entsearchtext",
		type: "text",
		maxlength: 10,
		placeholder: "Search by name"
	})
	.appendTo("#entsearch")
	.keyup(function() {
		search = $( this ).val();
		$("#sorrynogroups").remove();
		entityLoad(user_key, sorter, search);
	});

	$("<div/>", {
		id: "entsorters"
	})
	.appendTo("#entsortsearch");

	$("<span/>", {
		html: "Sort by"
	})
	.appendTo('#entsorters');

	$("<span/>", {
		html: "&nbsp;"
	})
	.appendTo('#entsorters');

	$("<input/>", {
		type: "radio",
		id: "entsortbyname",
		name: "entsort"
	})
	.appendTo("#entsorters")
	.click(function() {
		sorter = "name";
		entityLoad(user_key, sorter, search);
	});

	$("<label/>", {
		for: "entsortbyname",
		html: "&nbsp;Name"
	})
	.appendTo("#entsorters");

	$("<span/>", {
		html: "&nbsp;"
	})
	.appendTo('#entsorters');

	$("<input/>", {
		type: "radio",
		id: "entsortbydate",
		name: "entsort",
		checked: "checked"
	})
	.appendTo("#entsorters")
	.click(function() {
		sorter = "date";
		entityLoad(user_key, sorter, search);
	});

	$("<label/>", {
		for: "entsortbydate",
		html: "&nbsp;Date Added"
	})
	.appendTo("#entsorters");

	entityLoad(user_key, sorter, search);
}

function entityLoad(user_key, sorter, search) {

	$.ajax({
		url: thisPath + "entity_load.php",
		type: 'POST',
		dataType: 'json',
		data: {
			user_key: user_key,
			sorter: sorter,
			search: search

		},
		success: function(data) {

			$( "#entities" ).empty();

			if (data == "" || data[0].entity == undefined) {
				$("<div/>", {
					id: "sorrynogroups",
					html: "Sorry, there are no matching groups",
					style: "text-align: center;"
				})
				.appendTo('#entities');				
			}
			else {

				if (data.length < 4 && $("#entsearchtext").val() == "") {
					$("#newhere").html("Looks like you might be new here. &#8593;&#8593;Click the button above&#8593;&#8593; to add groups that other people have shared.");
				}
				else {
					$("#newhere").empty();
				}

				for (var i = 0, len = data.length; i < len; i++) {

					$("<div/>", {
						id: "divFor" + data[i].entity_key
					})
					.appendTo( "#entities" );

					$("<input/>", {
						type: "checkbox",
						id: "entity" + data[i].entity_key
					})
					.appendTo('#divFor' + data[i].entity_key)
					.click(function() {
						j = this.id.substring(6);
						b = "padding: 1px; border-width: 1px 2px 1px 2px; border-style: solid; border-color: palegoldenrod; margin:-1.5px;";
						$('#divFor' + j).attr("style", b);
						$("#members").scrollTop(0);
						$("#entsortbyname").prop("checked", "checked");
						entityCheck();
					});

					if (searchOn.entity.indexOf(data[i].entity_key) != -1) {
						$("#entity" + data[i].entity_key).prop("checked", "checked");
					}

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

					// this span makes the buttons stay on the same line
					$("<span/>", {
						id: "entity" + data[i].entity_key + "nowrap",
						style: "white-space: nowrap;"
					})
					.appendTo('#divFor' + data[i].entity_key);	

					$("<button />", {
						id: "entityedit" + data[i].entity_key,
						html: "Edit"
					})
					.appendTo("#entity" + data[i].entity_key + "nowrap")
					.click(function() {
						k = this.id.substring(10);
						addGroup(user_key, k);
					});

					$("<span/>", {
						html: "&nbsp;"
					})
					.appendTo("#entity" + data[i].entity_key + "nowrap");

					$("<button />", {
						id: "entitydel" + data[i].entity_key,
						html: "Delete"
					})
					.appendTo("#entity" + data[i].entity_key + "nowrap")
					.click(function() {
						// do an ajax call to find out whether other users are on this user_entity, and if so whether this user has the oldest timestamp for that user_entity
						$('[id^="youcreated"], [id^="yousurenf"]').remove();
						j = this.id.substring(9);
						ename = $( this ).parent().siblings("label").html();
						ename = ename.replace(/\&nbsp\;/g, "");
						$.ajax({
							url: thisPath + "entity_pre-del.php",
							type: 'POST',
							dataType: 'html',
							data: {
								user_key: user_key,
								entity_key: j,
								otherUsers: "unknown"
							},
							success: function(data) {
								// if there are other users on this, but the user is the creator, say "you created this group, but others are using it. Delete it for everyone or just for you?"
								if (data == "first") {
									$("<span/>", {
										id: "youcreated" + j,
										html: "<br><span class='bg-danger'>You created &ldquo;" + ename + ",&rdquo; but others are using it. Delete it for everyone or just for you?</span>&nbsp;"
									})
									.appendTo('#divFor' + j);

									$("<span/>", {
										id: "youcreated" + j + "nowrap",
										style: "white-space: nowrap;"
									})
									.appendTo("#youcreated" + j);

									$("<button/>", {
										id: "youcreatedevbut" + j,
										html: "Everyone"
									})
									.appendTo('#youcreated' + j + "nowrap")
									.click(function() {
										$.ajax({
											url: "entity_pre-del.php",
											type: "POST",
											dataType: "html",
											data: {
												entity_key: j,
												otherUsers: "everyone"
											},
											// if everyone: delete it from entity
											success: function(data) {
												if (data == "deleted all") {
													$("#divFor" + j).remove();
													searchOn.entity = Array.from(new Set(searchOn.entity));
													var index = searchOn.entity.indexOf(j);
													searchOn.entity.splice(index, 1);
													searchSelect(user_key);
													memberSelect(searchOn);
												}
											}
										})
									});

									$("<span/>", {
										html: "&nbsp;"
									})
									.appendTo('#youcreated' + j + "nowrap")

									$("<button/>", {
										id: "youcreatedmebut" + j,
										html: "Just Me"
									})
									.appendTo('#youcreated' + j + "nowrap")
									.click(function() {
										// if just for me: delete it from user_entity
										$.ajax({
											url: "entity_pre-del.php",
											type: "POST",
											dataType: "html",
											data: {
												entity_key: j,
												user_key: user_key,
												otherUsers: "just me"
											},
											// if everyone: delete it from entity
											success: function(data) {
												if (data == "deleted one") {
													$("#divFor" + j).remove();
													searchOn.entity = Array.from(new Set(searchOn.entity));
													var index = searchOn.entity.indexOf(j);
													searchOn.entity.splice(index, 1);
													searchSelect(user_key);
													memberSelect(searchOn);
												}
											}
										})
									});

									$("<span/>", {
										html: "&nbsp;"
									})
									.appendTo('#youcreated' + j + "nowrap")

									// offer the user a "never mind" button. if this is clicked, or if any other input gets the focus, remove this "delete it" div
									$("<button/>", {
										id: "youcreatednmbut" + j,
										html: "Never Mind"
									})
									.appendTo('#youcreated' + j + "nowrap")
									.click(function() {
										$( this ).parent("span").parent("span").remove();
									});
								}
								if (data == "not first" || data == "only") {
									// if this user is not the creator, or is the only one on this user_entity, ask "are you sure y/n"

									if (data == "not first") {
										otheru = "just me";
									}
									if (data == "only") {
										otheru = "everyone";
									}

									$("<span/>", {
										id: "yousurenf" + j,
										html: "<br><span class='bg-danger'>This will delete &ldquo;" + ename + ".&rdquo; Are you sure?</span>&nbsp;"
									})
									.appendTo('#divFor' + j);

									$("<span/>", {
										id: "yousurenf" + j + "nowrap",
										style: "white-space: nowrap;"
									})
									.appendTo( "#yousurenf" + j );

									$("<button/>", {
										id: "yousuredelenty" + j,
										html: "Yes"
									})
									.appendTo('#yousurenf' + j  + "nowrap")
									.click(function() {
										$.ajax({
											url: "entity_pre-del.php",
											type: "POST",
											dataType: "html",
											data: {
												user_key: user_key,
												entity_key: j,
												otherUsers: otheru
											},
											// if everyone: delete it from entity
											success: function(data) {
												if (data == "deleted one" || data == "deleted all") {
													$("#divFor" + j).remove();
													searchOn.entity = Array.from(new Set(searchOn.entity));
													var index = searchOn.entity.indexOf(j);
													searchOn.entity.splice(index, 1);
													searchSelect(user_key);
													memberSelect(searchOn);
												}
											}
										})
									});

									$("<span/>", {
										html: "&nbsp;"
									})
									.appendTo('#yousurenf' + j + "nowrap")

									$("<button/>", {
										id: "yousuredelentn" + j,
										html: "No"
									})
									.appendTo('#yousurenf' + j + "nowrap")
									.click(function() {
										$( this ).parent("span").parent("span").remove();
									});
								}
							}
						});
					})
				}
			}
		}
	});
}

function addPubEnt() {
	$( "#entsortsearch" ).empty();
	$( "#addPubEntBut" )
	.html("Go back to my groups")
	.click(function() {
		$( "#entities" ).css("background-color", "initial");
		$( "#addPubEntBut" ).remove();

		$("<button />", {
			id: "addPubEntBut",
			html: "Add a group created by the community"
		})
		.appendTo('#addgroupp')
		.click(function() {
			addPubEnt();
		})
		entsortsearchLoad();
	});

	$( "#entities" ).empty().css("background-color", "lightGoldenrodYellow");

	sorter = "date";
	search = "";

	$("<div/>", {
		id: "pubentsearch"
	})
	.appendTo("#entities");

	$("<input/>", {
		id: "pubentsearchtext",
		type: "text",
		maxlength: 10,
		placeholder: "Search by name"
	})
	.appendTo("#entities")
	.keyup(function() {
		search = $( this ).val();
		$("#sorrynopublic").remove();
		makePubEnts(user_key, sorter, search);
	});

	$("<div/>", {
		id: "pubentsorters"
	})
	.appendTo("#entities");

	$("<span/>", {
		html: "Sort by"
	})
	.appendTo('#pubentsorters');

	$("<span/>", {
		html: "&nbsp;"
	})
	.appendTo('#pubentsorters');

	$("<input/>", {
		type: "radio",
		id: "pubentsortbyname",
		name: "pubentsort"
	})
	.appendTo("#pubentsorters")
	.click(function() {
		$("#sorrynopublic").remove();
		sorter = "name";
		makePubEnts(user_key, sorter, search);
	});

	$("<label/>", {
		for: "pubentsortbyname",
		html: "&nbsp;Name"
	})
	.appendTo("#pubentsorters");

	$("<span/>", {
		html: "&nbsp;"
	})
	.appendTo('#pubentsorters');

	$("<input/>", {
		type: "radio",
		id: "pubentsortbydate",
		name: "pubentsort",
		checked: "checked"
	})
	.appendTo("#pubentsorters")
	.click(function() {
		$("#sorrynopublic").remove();
		sorter = "date";
		makePubEnts(user_key, sorter, search);
	});

	$("<label/>", {
		for: "pubentsortbydate",
		html: "&nbsp;Date Added&nbsp;"
	})
	.appendTo("#pubentsorters");

	$("<button/>", {
		id: "pubChecker",
		html: "Check all"
	})
	.appendTo("#pubentsorters")
	.click(function() {
		pubCheck();
	})

	makePubEnts(user_key, sorter, search);
}

function pubCheck() {
	$('[id^="pubentity"]').prop("checked", true);
	$('[id^="pubentity"]').each(function() {
		pubClick();
	})
	$( "#pubChecker" )
	.html("Un-check all")
	.click(function() {
		pubUnCheck();
	})
}

function pubUnCheck() {
	$('[id^="pubentity"]').prop("checked", false);
	pubClick();
	$( "#pubChecker" )
	.html("Check all")
	.click(function() {
		pubCheck();
	})
}

function pubClick() {
/*	if ($( this ).prop("checked") === true) {
		$("#pubentity" + j).css("border", "dotted 1px lightgrey");
	}
	else {
		$("#pubentity" + j).css("border", "0");
	}
*/
	// The 2 buttons are "add this/these" and "never mind"

	$( "#addPubEntBut, #addPubEntConf, #addPubEntSpan, #addPubEntNev" ).remove();

	if ($( "#entities div:not('#pubentsorters')" ).children("input:checked").length > 1) {
	thise = "Add these";
	}
	if ($( "#entities div:not('#pubentsorters')" ).children("input:checked").length == 1) {
	thise = "Add this";
	}
	if ($( "#entities div:not('#pubentsorters')" ).children("input:checked").length == 0) {
		$("<button />", {
			id: "addPubEntBut",
			html: "Go back to my groups"
		})
		.appendTo('#addgroupp')
		.click(function() {
			$( "#entities" ).css("background-color", "initial");
			$( "#addPubEntBut" ).remove();

			$("<button />", {
				id: "addPubEntBut",
				html: "Add a group created by the community"
			})
			.appendTo('#addgroupp')
			.click(function() {
				addPubEnt();
			})
			entsortsearchLoad();
		})
	return;
	}

	//add this/these will add all checked groups to user_entity
	$("<button/>", {
	id: "addPubEntConf",
	class: "bg-success",
	html: thise
	})
	.appendTo("#addgroupp")
	.click(function() {
		// get the entity_keys of all checked groups, put them in an array
		checked_ents = new Array();
		$( "#entities div:not('#pubentsorters')" ).children("input:checked").each(function() {
			chk = this.id.substring(12);
			checked_ents.push(chk);
		});
		// ajax that array to a page that will add them to user_entity
		$.ajax({
			url: "add_user_entity.php",
			type: "POST",
			dataType: "html",
			data: {
				checked_ents: checked_ents,
				user_key: user_key
			},
			// if everyone: delete it from entity
			success: function(data) {
				if(data == "success") {
					$( "#addPubEntBut, #addPubEntConf, #addPubEntSpan, #addPubEntNev" ).remove();
					$( "#entities" ).css("background-color", "initial");

					$("<button />", {
						id: "addPubEntBut",
						html: "Add a group created by the community"
					})
					.appendTo('#addgroupp')
					.click(function() {
						addPubEnt();
					});
					entsortsearchLoad();
					entityLoad(user_key);
				}
			}
		});
	});
	$("<span/>", {
		id: "addPubEntSpan",
		html: "&nbsp;"
	})
	.appendTo("#addgroupp");

	$("<button/>", {
		id: "addPubEntNev",
		html: "Never mind"
	})
	.appendTo("#addgroupp")
	.click(function() {
		$( "#addPubEntBut, #addPubEntConf, #addPubEntSpan, #addPubEntNev" ).remove();
		$( "#entities" ).css("background-color", "initial");

		$("<button />", {
			id: "addPubEntBut",
			html: "Add a group created by the community"
		})
		.appendTo('#addgroupp')
		.click(function() {
			addPubEnt();
		});
		entsortsearchLoad();
		entityLoad(user_key);
	})
}

function makePubEnts(user_key, sorter, search) {
	$('[id^="pubentity"]').remove();
	$.ajax({
		url: thisPath + "add_pub_entity.php",
		type: 'POST',
		dataType: 'json',
		data: {
			user_key: user_key,
			sorter: sorter,
			search: search
		},
		success: function(data) {
			if (data == "" || data[0].entity == undefined) {
				$("<div/>", {
					id: "sorrynopublic",
					html: "Sorry, there are no more public groups",
					style: "text-align: center;"
				})
				.appendTo('#entities');				
			}
			else {
				for(var i = 0, len = data.length; i < len; i++) {
					$("<div/>", {
						id: "pubentity" + data[i].entity_key
					})
					.appendTo('#entities');

					$("<input/>", {
						type: "checkbox",
						id: "pubentitychk" + data[i].entity_key,
						tabindex: i
					})
					.appendTo( "#pubentity" + data[i].entity_key )
					// clicking one or more of these checkboxes will make the checkbox's div stand out. It will delete any instance of the 2 buttons. It will make the  2 buttons appear.
					.click(function() {

						pubClick();
					})

					$("<label/>", {
						for: "pubentitychk" + data[i].entity_key,
						html: "&nbsp;" + data[i].entity,
						class: "entitylabel"
					})
					.appendTo( "#pubentity" + data[i].entity_key );

					if (data[i].url != "") {
						$("<span/>", {
							id: "pubentityurl" + data[i].entity_key,
							html: " (<a target='_blank' href='" + data[i].url + "'>*</a>)"
						})
						.appendTo('#pubentity' + data[i].entity_key);
					}

					$("<span/>", {
						id: "userdatespan" + data[i].entity_key + "nowrap",
						style: "white-space: nowrap; font-size: 0.7em;",
						html: "<br>Created by " + data[i].user + " updated " + data[i].ts
					})
					.appendTo('#pubentity' + data[i].entity_key);
				}
			}
		}
	})
}

function asc_sort(a, b){
    return ($(b).text()) < ($(a).text()) ? 1 : -1;    
}	
