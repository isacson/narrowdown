<?php require('includes/config.php'); 
	
echo <<<_END
	
	<div style="width:60%; margin:auto;">

	<h2 id="tutorial">Tutorial</h2>

	<p>This is an unattractive but simple website that does one thing: pinpoint members of Congress who meet chosen criteria. It&rsquo;s probably most useful for advocates looking for people on Capitol Hill who may care most about their issues. Here&rsquo;s how to use it.</p>

	<h3 id="log_in">Log in</h3>

	<p>First, create a unique identity. There are two reasons why $sitename makes you log in with your own username:</p>

	<ul>
	<li>As I write this, pre-launch, you can choose from a menu of 47 groups of legislators. If this site gets a few active users, there may be hundreds. With your own identity, you only have to see the groups you&rsquo;ve chosen or created, rather than having to pick through hundreds each time. You can also create private groups that only you can see.</li>
	<li>You may find yourself frequently making the same searches involving a few groups (say, &ldquo;women members of the Appropriations Committees elected since 2014.&rdquo;) With your own identity, you can keep these saved searches.</li>
	</ul>

	<p>The site doesn&rsquo;t ask much of you&mdash;not even your real name. Just enter a username and password. All information is encrypted and sent over SSL. I can&rsquo;t see your password&mdash;it&rsquo;s encrypted. Remember to make a note of it, as for now there&rsquo;s no &ldquo;forgot password&rdquo; feature.</p>

	<p align="center"><img src="/images/signup.jpg" style="width:80%; border:0;"></p>

	<p>After submitting your username and password, log in with your new credentials, and you&rsquo;re looking at the main page. In fact, this site has only two pages (not counting this tutorial): the main page and the &ldquo;add a group&rdquo; page. Let&rsquo;s start with the main page.</p>

	<h3 id="groups">Groups</h3>

	<p>The main page has 3 columns: saved searches on the left, groups in the middle, and members of Congress on the right. (While this site works fine on smartphones, you&rsquo;ll probably find this 3-column layout to be tiresome on a tiny screen.) </p>

	<p>As the page advises, start with the groups in the middle.</p>

	<p align="center"><img src="/images/mainpage.jpg" style="width:80%; border:0;"></p>

	<p>&ldquo;Groups&rdquo; are the core of this site. A group is any set of members of Congress that makes sense to you. It can be a committee or caucus, sponsors of a bill, people who signed a letter or gave floor speeches on a topic. It can be a private listing of members of Congress you have your eye on for whatever reason. It can be &ldquo;all members of Congress whose names end with &lsquo;a.&rsquo;&rdquo; Whatever you want.</p>

	<p>When you log in with a new account, you see three sample groups in the main box in the middle. You can delete these later if you don&rsquo;t want them.</p>

	<p>(One detail: see the asterisk between parentheses (*) after some group names? That means there&rsquo;s a link to a page on the internet that can tell you more about the group. Clicking on the asterisk after &ldquo;Sample Group: House Armed Services Committee&rdquo; opens a new browser tab with the Committee&rsquo;s membership page.) </p>

	<p><strong>Search for a group</strong></p>

	<p>See the box that reads &ldquo;search by name?&rdquo; It, and the &ldquo;Sort by&rdquo; buttons below it, will be more useful when you have large numbers of groups. But for now, try typing the word &ldquo;house&rdquo; there. </p>

	<p>Now you just see one of the sample groups: the House Armed Services Committee. If you click on it, the checkbox fills in, and the &ldquo;Matched Legislators&rdquo; box on the right side of the page changes to show you all members of the House Armed Services Committee. The names of the Committee&rsquo;s chairman and ranking minority-party member appear in boldfaced text. If that bothers you, click the &ldquo;Boldface group leaders&rdquo; button.</p>

	<p><strong>Find matching legislators</strong></p>

	<p>Delete the word &ldquo;house&rdquo; from the search box. All three of the sample groups come back into view. Now, check all three. The &ldquo;Matched Legislators&rdquo; box on the right fills up with members of all three groups.</p>

	<p>But something cool happens: the legislators who are members of all three groups rise to the top and are highlighted in yellow. The legislators who match two out of three show up just under them, lightly outlined in yellow. As I write this in February 2017, there are two new members of Congress from California who sit on the House Armed Services Committee.</p>

	<p align="center"><img src="/images/matches.jpg" style="width:80%; border:0;"></p>

	<p>Click once on any legislator, and a box pops up showing district and contact information. Click somewhere else and it goes away. </p>

	<p>The checkboxes above the Matched Legislators should be self-explanatory. Clicking &#8220;House&#8221; and &#8220;D&#8221; shows only House Democrats.</p>

	<p>Right below them is the sentence &#8220;Show members with [box] or more matches.&#8221; Type &#8220;3&#8221;, then either &#8220;enter&#8221; or just click anywhere else. All legislators who match less than 3 times disappear, leaving you with a nice clean, short list. If you&#8217;ve been following this tutorial exactly and it&#8217;s early 2017, you&#8217;re now looking at two new Democratic House Armed Services Committee members from California, and nothing else.</p>

	<p><strong>Grab a list of legislators</strong></p>

	<p>If you want to take this list out of $sitename and use it somewhere else, you have two options:</p>

	<ul>
	<li>For just a list of names, click the &#8220;Select all&#8221; button. All visible legislators are selected, allowing you to copy and paste them. (This doesn&#8217;t seem to work on mobile devices, where you have to select them manually.)</li>
	<li>To get all contact information, including phone numbers and office locations, click the &#8220;Export as CSV&#8221; button. (This doesn&#8217;t seem to work on mobile web browsers.) Unless your pop-up blocker prevents it, a new window opens up with these legislators&#8217; full contact data in CSV (comma-separated values) format. Select all, then copy and paste this whole page somewhere that lets you save in plain text format (like Microsoft Word or a text editor). Save that file with a name like &ldquo;example.csv.&rdquo; Open that file in a spreadsheet like Microsoft Excel, and you have an instant contact list. (I don&#8217;t include offices&#8217; general-delivery email addresses because they are not a useful way to contact members or staff.)</li>
	</ul>

	<h3 id="saved_searches">Saved Searches</h3>

	<p>One last feature is the &#8220;Saved Searches&#8221; column on the left side of the main page. If you find yourself frequently checking off the same few groups, save a step by making a &ldquo;saved search,&rdquo; which will check them off for you. </p>

	<p>Making a saved search is easy. Check any group&rsquo;s box, and a blank appears in the Saved Searches box, with the caption &ldquo;Add this as a new saved search.&rdquo; Type a name in the blank that makes sense to you. Press &ldquo;enter&rdquo; or click the mouse somewhere else. The search you&rsquo;ve just created is now listed in the Saved Searches box. </p>

	<p>Click its radio button, and the groups you chose are automatically checked. If you choose another group, the Saved Searches box will quietly ask you if you want to update the currently selected saved search. If you don&rsquo;t want to do that, just ignore the message.</p>

	<h3 id="adding_creating_or_editing_groups">Adding, Creating, or Editing Groups</h3>

	<p>You&rsquo;re not stuck with just these sample groups, obviously. You can add your own. There are two ways to add groups.</p>

	<p><strong>Copy groups that others have made and shared</strong></p>

	<p>This site has a &ldquo;crowdsourcing&rdquo; capability. As you&rsquo;ll see in the next section, when making a group you can choose to make it &ldquo;public,&rdquo; or shareable with other people. As a member of the public, you can also add groups that others have created.</p>

	<p>Doing so is as simple as clicking the button in the top middle that reads &ldquo;Add a group created by the community.&rdquo; This brings up a searchable, sortable list of all the groups created by others. Click the checkboxes next to the ones you want to add, then click the &ldquo;Add this/these&rdquo; button that appears above them. And that&rsquo;s it.</p>

	<p align="center"><img src="/images/community.jpg" style="width:80%; border:0;"></p>

	<p><em>This part is a little confusing, but makes sense when you think about it:</em> A group created by someone else may change on its own, whenever the group&rsquo;s creator alters the list of legislators. That can be good: if the House Armed Services Committee&rsquo;s membership changes, and the creator updates the list of members, then s/he has done you a service. It can be bad if the original creator makes an error. The original creator might even delete it someday, overriding a warning that others are sharing his/her group. This is always a risk.</p>

	<p>If you&rsquo;d like to make your own copy of the group, which will never change unless you change it, click the &ldquo;Edit&rdquo; button below the group, then submit it without making any changes. (I&rsquo;ll cover how to do that below.) You will now have two groups with the same name in your group list: the one created by someone else, and the copy you just made. You can tell the difference by the date they were last updated. Feel free to delete the original one, if you don&rsquo;t want to benefit from that other user&rsquo;s updating.</p>

	<p>If you&rsquo;re just starting out, you may want to add a large number of others&#8217; lists right now. Go ahead and do that: if the groups list gets too crowded, you can delete some later.</p>

	<p><strong>Make a brand-new group</strong></p>

	<p>Often it makes more sense to create a new group from scratch. Click the &ldquo;Make a new group&rdquo; button, which takes you to the &ldquo;add a group&rdquo; page, the only other page on this site (except for the tutorial you&rsquo;re reading now).</p>

	<p>Creating a new group is a painless four-step process.</p>

	<ol>
	<li>Give this group a name: Type a name in the box. You&#8217;ll probably want a name that will be easy to find later, with words that will turn up in a search.</li>
	<li>Optionally add a URL or web address pointing to a page that explains more about this group. If there&rsquo;s something in this blank, the group&rsquo;s name will be listed with an asterisk in parentheses (*). Clicking that asterisk will open this URL in a new window. You are not required to fill this blank.</li>
	<li>Click the corresponding radio button to decide whether you want to share this group with other people, or just keep it to yourself. If it&rsquo;s information that&rsquo;s publicly available anyway, like members of a committee or sponsors of a bill, do consider sharing it. You&rsquo;ll add value for everyone.</li>
	<li>Add members of Congress to the group. There are two ways to do this: in a batch, or one by one.</li>
	</ol>

	<p><em>Batch</em>: The big box on the left side of the screen, under 4a, will try very hard to guess which members of Congress you&rsquo;ve typed or pasted into it. It&rsquo;s not perfect, and you&rsquo;ll probably need to check your work. But this is still usually far faster than typing in dozens of names individually.</p>

	<p align="center"><img src="/images/addgroups.jpg" style="width:80%; border:0;"></p>

	<p>Try copy-and-pasting a list of legislators&rsquo; names from a website, perhaps from a House or Senate committee membership page, into the box. Make sure that each legislator&rsquo;s name is on a separate line. Extra words like &ldquo;Rep.&rdquo; or &ldquo;(R-Arkansas)&rdquo; shouldn&rsquo;t interfere with detection, but you never know. A list of last names works, too, but if there is more than one member of Congress with that surname, the box will have to guess which &#8220;Smith&#8221; you meant.</p>

	<p>Click the &ldquo;Parse this list&rdquo; button. If all goes well, a list of legislators appears in the box to the right: one blank for each legislator. Go over this list carefully to make sure it guessed the right members of Congress, and correct any wrong ones.</p>

	<p>If it couldn&rsquo;t find a match at all, that member of Congress won&rsquo;t appear on the right, and you&rsquo;ll have to type his or her name in manually. You do that by scrolling down to the bottom and clicking the &ldquo;Add another&rdquo; button.</p>

	<p><em>One by one</em>: Feel free to skip the big box on the left and type members in one by one. Start typing a legislator&rsquo;s name, and suggestions will appear below the blank you're typing in. Choose the name you&rsquo;re looking for. To add another member, click the &ldquo;Add another&rdquo; button, and a new blank will appear. Repeat.</p>

	<p><em>Unrecognized legislators</em>: Blank members will be ignored. So will unrecognized names. If $sitename can&rsquo;t recognize the name of the legislator you typed, it will warn you. If you think I&rsquo;ve made an error&mdash;perhaps a new member of Congress arrived after a special election, and I haven&rsquo;t updated the master Congress list yet&mdash;click the &ldquo;Go&rdquo; button next to &ldquo;click here to notify an administrator,&rdquo; and I&rsquo;ll get an alert showing that name. If you found a missing name, I&rsquo;ll add it to the master list as soon as I&rsquo;m able.</p>

	<p><em>Leadership</em>: If a legislator is a leader of this group&mdash;a chairman or ranking member of a committee, the organizer of a letter, the original sponsor of a bill&mdash;check the box under the legislator&rsquo;s name labeled &ldquo;leadership position.&rdquo; Doing so will make the legislator appear in boldface type when the main page lists the group&rsquo;s membership.</p>

	<p><em>Submit the new group</em>: If everything looks good, click the green &ldquo;Submit this group&rdquo; button. Doing that adds the group immediately, but doesn&rsquo;t take you away from the &ldquo;add a group&rdquo; page. At this point, you can:</p>

	<ul>
	<li>Click the green button again to return to the main page.</li>
	<li>Make edits to the group you just created, then click the green button again.</li>
	<li>Click the &ldquo;Clear everything or make a new group&rdquo; button. You&rsquo;ll get a blank form and can create another group.</li>
	<li>Click &ldquo;Back to main page,&rdquo; to exit without making any changes.</li>
	</ul>

	<p>At any point, if you want to quit adding a group and go back to the main page without submitting it, just click the &ldquo;Back to main page&rdquo; button, and all is forgotten.</p>

	<p><strong>Edit an existing group</strong></p>

	<p>Changing an existing group&rsquo;s name or membership is also simple. You can do it in one of two ways:</p>

	<ul>
	<li>On the main page, click the &ldquo;Edit&rdquo; button next to any group.</li>
	<li>On the &ldquo;add a group&rdquo; page, type the name of an already-existing group into the &ldquo;Give this group a name&rdquo; blank, then hit enter or leave the blank.</li>
	</ul>

	<p>Both methods will fill the rest of the form with the existing group&rsquo;s members, URL, and public/private status. You can change any of it. Be aware that if you add members from the big box on the left, you will delete and replace all of the legislators on the right.</p>

	<p>Once you&rsquo;ve finished all of your edits, click the &ldquo;Submit this group&rdquo; button, just like when adding a new group. If you want to escape from editing without making changes, click the &ldquo;Back to main page&rdquo; button.</p>

	<h3 id="deleting_groups_and_saved_searches">Deleting Groups and Saved Searches</h3>

	<p>Saved searches and groups both have &ldquo;delete&rdquo; buttons next to them. There is nothing unusual about how these work. </p>

	<p>There is just one wrinkle: someday you may decide to delete a group that you created, but that other people are sharing. When you click &ldquo;delete,&rdquo; you will be asked whether you want the group deleted just from your list, or for everybody. As the group&rsquo;s creator, you can also be its destroyer. But if others are using it, think first about whether you really want to do that.</p>

	<h3 id="about_me">About me</h3>

	<p>And that&rsquo;s it! I hope that you find this as useful as I am so far. It was a pleasure to make, as a nights-and-weekends side project.</p>

	<p>I made this because it helps my work as an advocate at the Washington Office on Latin America (WOLA), a human rights group in Washington. (That&rsquo;s why so many of the groups created by &ldquo;admin&rdquo;&mdash;that&rsquo;s me&mdash;are about Latin America or human rights. <a href="https://www.wola.org/people/adam-isacson/" target="_blank">Here&rsquo;s my bio</a>.) For us, work with Congress has become essential to oversee an administration whose initial policy proposals threaten to harm human rights. In order to engage better with Capitol Hill, I&rsquo;ve used this site to identify a lot of legislative offices with which we should be working, but haven&rsquo;t been working enough.</p>

	<p>If you&rsquo;re finding $sitename to be useful and want to give back, don&rsquo;t send money to me. <a href="https://www.wola.org/donate/" target="_blank">Please consider making a donation to WOLA</a>, whose work is really important right now. Good luck.</p>
	
	<p>Cheers, Adam Isacson</p>

	</div>
	
_END;
	
?>