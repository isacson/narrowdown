$(function() {

	user_key = $( "#user_key" ).html();
	entity_key = $( "#entity_key" ).html();
	copycat = "";

	groupMem(entity_key);

	$('[id^="groupmemtxt"]')
	.bind('blur keyup',function(e) {  
		if (e.type == 'blur' || e.keyCode == '13')  {
			$( this ).val(function(i,v) {
				c = this.id.substring(11);
				e = v.match(/\d{1,5}$/g);
				if (e != undefined) {
					d = e[0];
					$("#groupmemkey" + c).val(d);
				}
				f = v.replace(/\s+id\#\d{1,5}$/, "");
				recognizeName(f, c);
				return f;
			})
		}
	});

	function aTagsTwo() {
		var a = $.ajax({
			type: 'GET',
			url: "jsonmaker.php",
			dataType: "json",
			success: function(json) {
				return json;
			}
		});
		return a;
	}

	var availableTags;

	$('[id^="groupmemtxt"]').focus(function() {
		availableTags = aTagsTwo();
	});

	function split( val ) {
	  return val.split( /,\s*/ );
	}

	function extractLast( term ) {
	  return split( term ).pop();
	}

	$('[id^="groupmemtxt"]')
	  // don't navigate away from the field on tab when selecting an item
	.bind( "keydown", function( event ) {
	    if ( event.keyCode === $.ui.keyCode.TAB &&
	        $( this ).autocomplete( "instance" ).menu.active ) {
	      event.preventDefault();
	    }
	})
	.on("blur", function() {
		$( this ).val(function(i,v) {
			c = this.id.substring(11);
			e = v.match(/\d{1,5}$/g);
			if (e != undefined) {
				d = e[0];
				$("#groupmemkey" + c).val(d);
			}
			f = v.replace(/\s+id\#\d{1,5}$/, "");
			return f;
		})
	})
	.autocomplete({
	    minLength: 1,
	    source: function( request, response ) {
	    // delegate back to autocomplete, but extract the last term
		    response( $.ui.autocomplete.filter(availableTags.responseJSON, extractLast(request.term)) );
	    },
	    focus: function() {
	      // prevent value inserted on focus
	      return false;
	    }
	});

	$( "#entity" ).focus();
});

$( "#entity" ).change(function() {

	p = $( this ).val();
	// warn if there's no value
	if (p == "") {
		$( "#entity_msg").html("You won&rsquo;t be allowed to create a group without a name.").css("color", "red");
		$( "#entity" ).css("border-color", "red");
	}
	else {
		$( "#entity_msg").html("").css("color", "initial");
		$( "#entity" ).css("border-color", "initial");
		// let's see if this one exists, and whether this user created it
		$.ajax({
			url: thisPath + "entity_name.php",
			type: 'POST',
			dataType: "json",
			data: {
				user_key: user_key,
				entity: p
			},
			success: function(data) {
				if (data[0] !== undefined) {
					console.log(data);
					entity_key = data[0].entity_key;
					copycat = data[0].creator;
					$("#url").val(data[0].url);
					groupMem(entity_key);

					switch (data[0].creator) {
						case "creator":
							$( "#entity_msg").html("You have already created a group called \"" + p + ".\" Its contents are below, if you wish to edit them. Otherwise, change the name to something unused.").css("color", "goldenrod");
							$( "#entity" ).css("border-color", "goldenrod");
							break;
						case "copycat":
							$( "#entity_msg").html("Someone else has already created a group called \"" + p + ".\" Its contents are below, if you wish to make and edit your own copy.").css("color", "goldenrod");
							$( "#entity" ).css("border-color", "goldenrod");
							break;
					}
				}
				else {
					entity_key = "";
					copycat = "";
//					$( "#groupmembers" ).empty();
//					groupMem();
					$( "#entity_msg").html("").css("color", "initial");
					$( "#entity" ).css("border-color", "initial");
				}
			}
		})
	}

});

$( "#url" ).blur(function() {
	if (this.value != "") {
		$( "#url_msg" ).html("");
		$( "#url" ).css("border-color","initial");
		var rl = this.value;
		var d = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
		var r = new RegExp(d);
		if(!r.test(rl)) {
			$( "#url_msg" ).html("Just a warning: this doesn&rsquo;t look like a URL.").css("color", "goldenrod");
			$( "#url" ).css("border-color","goldenrod");
			if (rl.indexOf("http://") != 0 || rl.indexOf("https://") == 0) {
				$( "#url_msg" ).append(" Try starting with &ldquo;http://&rdquo;");	
			}
		}
	}
});

function newGroupMem(p) {

	$('[id^="groupmemadd"]').remove();
	var ti = Number(100+p);

	$("<div/>", {
		id: "groupmemdiv" + p,
		class: "groupmember"
	})
	.appendTo("#groupmembers");

	$("<input/>", {
		id: "groupmemtxt" + p,
		type: "text",
		style: "width: 98%;",
		name: "groupmemtxt" + p,
		tabindex: ti,
		placeholder: "Firstname Lastname"
	})
	.appendTo('#groupmemdiv' + p)
	.focus(function() {
		availableTags = aTagsTwo();
	})
	.bind( "keydown", function( event ) {
	    if ( event.keyCode === $.ui.keyCode.TAB &&
	        $( this ).autocomplete( "instance" ).menu.active ) {
	      event.preventDefault();
	    }
	})
	.on("blur change", function() {
		$( this ).val(function(i,v) {
			c = this.id.substring(11);
			e = v.match(/\d{1,5}$/g);
			if (e != undefined) {
				d = e[0];
				$("#groupmemkey" + c).val(d);
			}
			f = v.replace(/\s+id#\d{1,5}$/, "");
			recognizeName(f, c);
			return f;
		})
	})
	.autocomplete({
	    minLength: 1,
	    source: function( request, response ) {
	    // delegate back to autocomplete, but extract the last term
		    response( $.ui.autocomplete.filter(availableTags.responseJSON, extractLast(request.term)) );
	    },
	    focus: function() {
	      // prevent value inserted on focus
	      return false;
	    }
	});

	$("<span/>", {
		html: "<br>"
	})
	.appendTo('#groupmemdiv' + p);

	$("<input/>", {
		type: "checkbox",
		id: "groupmemlead" + p
	})
	.appendTo('#groupmemdiv' + p);

	$("<label/>", {
		for: "groupmemlead" + p,
		html: "&nbsp;Leadership position&nbsp;"
	})
	.appendTo('#groupmemdiv' + p);

	if (p != 0) {
		$("<button/>", {
			id: "groupmemdel" + p,
			html: "Delete"
		})
		.appendTo('#groupmemdiv' + p)
		.click(function() {
			$( this ).parent("div").remove();

			q = p-1;

//			$("#groupmemadd" + q).remove();

			if ($("#groupmemdiv" + q).is(":last-child") || q == 0) {

				$("<button/>", {
					id: "groupmemadd" + q,
					html: "Add another"
				})
				.click(function() {
					newGroupMem(p);
				})
				.appendTo('#groupmemdiv' + q);
			}
		});
	}

	$("<button/>", {
		id: "groupmemadd" + p,
		html: "Add another"
	})
	.click(function() {
		newGroupMem(p+1);
	})
	.appendTo('#groupmemdiv' + p);

	$("<input/>", {
		id: "groupmemkey" + p,
		type: "hidden"
	})
	.appendTo('#groupmemdiv' + p);

	$(function() {
		var wtf    = $('#groupmembers_box');
		var height = wtf[0].scrollHeight;
		wtf.scrollTop(height);
	});

	$("#groupmemtxt" + p).focus();
}

function groupMem(entity_key) {
	if (entity_key != undefined) {
		getMembers(entity_key);
	}
	else {
		newGroupMem(0);
	}
}

function groupMemLoad(obj) {

	$( "#groupmembers" ).empty();

	var members = $.map(obj, function(el) { return el });

	if (members[0].public == 1) {
		$( "#makepublic" ).prop("checked", true);
		$( "#makeprivate" ).prop("checked", false);
	}
	if (members[0].public == 0) {
		$( "#makepublic" ).prop("checked", false);
		$( "#makeprivate" ).prop("checked", true);
	}

	for(var p = 0, len = $(members).length; p < len; p++) {

		$("<div/>", {
			id: "groupmemdiv" + p,
			class: "groupmember"
		})
		.appendTo("#groupmembers");

		var a = members[p].senrep + " ";

		a += members[p].firstname + " ";

		if (members[p].nickname != "") {
			a += "\"" + members[p].nickname + "\" ";
		}

		a += members[p].lastname + " (" + members[p].party + "-" + members[p].state + ")";

		$("<input/>", {
			id: "groupmemtxt" + p,
			type: "text",
			style: "width: 98%;",
			name: "groupmemtxt" + p,
			value: a
		})
		.appendTo('#groupmemdiv' + p)
		.focus(function() {
			availableTags = aTagsTwo();
		})
		.bind( "keydown", function( event ) {
		    if ( event.keyCode === $.ui.keyCode.TAB &&
		        $( this ).autocomplete( "instance" ).menu.active ) {
		      event.preventDefault();
		    }
		})
		.bind('blur keyup',function(e) {  
			if (e.type == 'blur' || e.keyCode == '13')  {
				$( this ).val(function(i,v) {
					c = this.id.substring(11);
					e = v.match(/\d{1,5}$/g);
					if (e != undefined) {
						d = e[0];
						$("#groupmemkey" + c).val(d);
					}
					f = v.replace(/\s+id\#\d{1,5}$/, "");
					recognizeName(f, c);
					return f;
				})
			}
		})
		.on("blur change", function() {
			$( this ).val(function(i,v) {
				c = this.id.substring(11);
				e = v.match(/\d{1,5}$/g);
				if (e != undefined) {
					d = e[0];
					$("#groupmemkey" + c).val(d);
				}
				f = v.replace(/\s+id\#\d{1,5}$/, "");
				recognizeName(f, c);
				return f;
			})
		})
		.autocomplete({
		    minLength: 1,
		    source: function( request, response ) {
		    // delegate back to autocomplete, but extract the last term
			    response( $.ui.autocomplete.filter(availableTags.responseJSON, extractLast(request.term)) );
		    },
		    focus: function() {
		      // prevent value inserted on focus
		      return false;
		    }
		});

		$("<span/>", {
			html: "<br>"
		})
		.appendTo('#groupmemdiv' + p);

		$("<input/>", {
			type: "checkbox",
			id: "groupmemlead" + p
		})
		.appendTo('#groupmemdiv' + p);

		if (members[p].leader == 1) {
			$( "#groupmemlead" + p).prop("checked", "checked");
		}

		$("<label/>", {
			for: "groupmemlead" + p,
			html: "&nbsp;Leadership position&nbsp;"
		})
		.appendTo('#groupmemdiv' + p);

		if (p != 0) {
			$("<button/>", {
				id: "groupmemdel" + p,
				html: "Delete"
			})
			.appendTo('#groupmemdiv' + p)
			.click(function() {

				q = this.id.substring(11);
				q = q-1;

				$( this ).parent("div").remove();

//				$("#groupmemadd" + q).remove();

				if ($("#groupmemdiv" + q).is(":last-child") || q == 0) {

					$("<button/>", {
						id: "groupmemadd" + q,
						html: "Add another"
					})
					.click(function() {
						newGroupMem(p);
					})
					.appendTo('#groupmemdiv' + q);
				}
			});
		}

		if (p == len-1) {

			$("<button/>", {
				id: "groupmemadd" + p,
				html: "Add another"
			})
			.appendTo('#groupmemdiv' + p)
			.click(function() {
				newGroupMem(p+1);
			});
		}

		$("<input/>", {
			id: "groupmemkey" + p,
			type: "hidden",
			value: members[p].member_key
		})
		.appendTo('#groupmemdiv' + p);
	}
}

function getMembers(entity_key) {

	$.ajax({
		url: thisPath + "get_members_from_entity.php",
		type: 'POST',
		dataType: "json",
		data: {
			entity_key: entity_key
		},
		success: function(data) {
			groupMemLoad(data);
		}
	});
}

function aTagsTwo() {
	var a = $.ajax({
		type: 'GET',
		url: "jsonmaker.php",
		dataType: "json",
		success: function(json) {
			return json;
		}
	});
	return a;
}

function split( val ) {
  return val.split( /,\s*/ );
}

function extractLast( term ) {
  return split( term ).pop();
}

$( "#submit_pastebox" ).click(function() {
	aar = $( "#pasted_members" ).val();
	aar = aar.split(/\n/);
	blobs = new Array;
	for(var i = 0, len = aar.length; i < len; i++) {
		if (/[a-zA-Z]/.test(aar[i])) {
			a = aar[i].replace(/\s/g,"  ");
			a = a.replace(/((?:\s|^)[a-zA-Z\'\.\-][a-zA-Z\'\.\-]+\,)(\s*[a-zA-Z\'\.\-][a-zA-Z\'\.\-]+(?:\s|$))/g, "$2  $1");
			a = accents(a);
			a = a.replace(",", " ");
			a = a.replace(/jr\./i, "");
			a = a.replace(/III/, "");
			a = a.replace(/(\s|^)(Al|Mo|Ed|Ro|A\.)(\s|$)/, " $2-short ");
			blobs.push(a.match(/(\s|^)[a-zA-Z\'\.\-][a-zA-Z\'\.\-]{2,}(\s|$)/g));
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		if (blobs[i] === null) {
			blobs.splice(i, 1);
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			b = blobs[i][j].trim();
			blobs[i][j] = b;
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			if (/^(sen\.?|rep\.?|del\.|com\.)$/i.test(blobs[i][j])) {
				blobs[i].splice(j, 1);
			}
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			blobs[i][j].replace("-short","");
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		if (blobs[i].length == 3) {
			blobs[i].splice(1,1);
		}
	}
	blobs = blobs.slice(0,500);
	$.ajax({
		url: thisPath + "get_members_from_pastebox.php",
		type: 'POST',
		dataType: "json",
		data: {
			blobs: blobs
		},
		success: function(data) {

			$( "#or" ).empty();

			if (data.length == 0) {
				$( "#edit_or_add" ).html("<br>No names were recognized. Try again?").css("color", "red");	
				return;			
			}

//			console.log(data.length);
			$( "#edit_or_add" ).html("<br>These names appeared to match, but make sure.").css("color", "initial");
			groupMemLoad(data);
		}
	});
});

function recognizeName(text, p) {
	blobs = new Array;
	if (/[a-zA-Z]/.test(text)) {
		a = text.replace(/\s/g,"  ");
		a = a.replace(/((?:\s|^)[a-zA-Z\'\.\-][a-zA-Z\'\.\-]+\,)(\s*[a-zA-Z\'\.\-][a-zA-Z\'\.\-]+(?:\s|$))/g, "$2  $1");
		a = accents(a);
		a = a.replace(",", " ");
		a = a.replace(/jr\./i, "");
		a = a.replace(/III/, "");
		a = a.replace(/(\s|^)(Al|Mo|Ed|Ro|A\.)(\s|$)/, " $2-short ");
		blobs.push(a.match(/(\s|^)[a-zA-Z\'\.\-][a-zA-Z\'\.\-]{2,}(\s|$)/g));
	}
	else {
		$("#groupmemkey" + p).val("");
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		if (blobs[i] === null) {
			blobs.splice(i, 1);
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			b = blobs[i][j].trim();
			blobs[i][j] = b;
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			if (/^(sen\.?|rep\.?|del\.|com\.)$/i.test(blobs[i][j])) {
				blobs[i].splice(j, 1);
			}
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		for(var j = 0, le = blobs[i].length; j < le; j++) {
			blobs[i][j].replace("-short","");
		}
	}
	for(var i = 0, len = blobs.length; i < len; i++) {
		if (blobs[i].length == 3) {
			blobs[i].splice(1,1);
		}
	}
	blobs = blobs.slice(0,10);
	if (blobs[0] != "" && blobs[0] !== undefined) {
		$.ajax({
			url: thisPath + "get_members_from_pastebox.php",
			type: 'POST',
			dataType: "json",
			data: {
				blobs: blobs
			},
			success: function(data) {
				if (!data[0]) {

					$('[id^="unknownMember"]').remove();
					$("#groupmemkey" + p).val("");
					$("<div/>", {
						id: "unknownMember" + p,
						html: "There is no member of Congress named &ldquo;" + text + "&rdquo; in our records. &ldquo;" + text + "&rdquo; won&rdquo;t be added to this group. If you think we missed somebody, click here to notify an administrator:"
					})
					.appendTo('#groupmemdiv' + p);

					$("<button/>", {
						id: "send_error",
						html: "Go"
					})
					.appendTo("#unknownMember" + p)
					.click(function() {
						$.ajax({
							url: thisPath + "add_unrecognized.php",
							type: 'POST',
							dataType: "text",
							data: {
								admin_error: text,
								user_key: user_key
							},
							success: function(data) {
		
								$("#unknownMember" + p).html("&ldquo;" + text + "&rdquo; has been sent. Thank you.");
							}
						})
					})
				}
			}
		});
	}
}

$( "#deleteinputs" ).click(function() {
	$( "#groupmembers" ).empty();
	$( "#or" ).html("<br>or");
	$( "#edit_or_add" ).html("<br>Add members one by one.")
	groupMem();
});

$( "#clear_all" ).click(function() {
	addGroup(user_key);
});

$( "#submit_group" ).click(function() {
	submitGroup();
});

function submitGroup() {
	if ($( "#entity" ).val() == "") {
		$( "#submit_group" ).html("Please give this group a name and try again.");
		$( "#submit_group" ).css("color", "red");
		$( "#entity" ).focus();
		return;
	}
	memkeys = new Object;
	checkBlanks = new Array;

	$('[id^="groupmemkey"]').each(function() {
		p = this.id.substring(11);
		if ($( "#groupmemlead" + p).prop("checked") == true) {
			memkeys[p] = {"member": $( this ).val(), "leader": 1};
		}
		else {
			memkeys[p] = {"member": $( this ).val(), "leader": 0};
		}
		checkBlanks.push($( this ).val());
	})

	public = 1;

	if ($( "#makeprivate").prop("checked") == true) {
		public = 0;
	}

	blanksSum = checkBlanks.reduce((a, b) => a + b, 0);
	if (blanksSum == 0) {
		$( "#submit_group" ).html("You didn&rsquo;t add any members of Congress to the group. Try again.");
		$( this ).css("color", "red");
		return;
	}

	$.ajax({
		url: thisPath + "submit_group.php",
		type: 'POST',
		dataType: "html",
		data: {
			user_key: user_key,
			entity_key: entity_key,
			memkeys: memkeys,
			entity: $( "#entity" ).val(),
			url: $( "#url" ).val(),
			public: public
		},
		success: function(data) {

			$( "#submit_msg" ).html(data);

			if (data.match(/You have/)) {

				$("#submit_msg").css("color", "green");

				$( "#submit_group" )
				.html("Go back to " + sitename)
				.css("color", "green")
				.unbind("click")
				.click(function() {
					memberPage();
				});

				$( "input, button" ).on("click focus", function() {

					$( "#submit_group" )
					.html("Submit changes")
					.css("color", "initial")
					.unbind("click")
					.click(function() {
						submitGroup();
					})
				});
			}
			if (data.match(/An error/)) {
				$( "#clear_all" ).html("Make a new group");
				$( "#submit_msg" ).css("color", "red");
			}
		}
	});
}

function accents(title){
	title = title.replace(/[\xE0\xE1\xE2\xE3\xE4]/g, "a");
	title = title.replace(/\xE7/g, "c");
	title = title.replace(/\u2019/g, "'");
	title = title.replace(/[\xE8\xE9\xEA\xEB]/g, "e");
	title = title.replace(/[\xEC\xED\xEE\xEF]/g, "i");
	title = title.replace(/\xF1/g, "n");
	title = title.replace(/[\xF2\xF3\xF4\xF5\xF6]/g, "o");
	title = title.replace(/[\xF9\xFA\xFB\xFC]/g, "u");
	title = title.replace(/[\xC0\xC1\xC2\xC3\xC4]/g, "A");
	title = title.replace(/\xC7/g, "C");
	title = title.replace(/[\xC8\xC9\xCA\xCB]/g, "E");
	title = title.replace(/[\xCC\xCD\xCE\xCF]/g, "I");
	title = title.replace(/\xD1/g, "Ñ");
	title = title.replace(/[\xD2\xD3\xD4\xD5\xD6]/g, "O");
	title = title.replace(/[\xD9\xDA\xDB\xDC]/g, "U");
	return title;
}