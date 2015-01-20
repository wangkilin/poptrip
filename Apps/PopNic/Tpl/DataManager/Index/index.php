<layout name="../Layout/Layout" />

<div id="content">
	<div class="selectors">
		<div class="mainTitle">
			<span>Planning des Classes virtuelles</span><span
				style="border: none">March 3 - 09, 2014</span>
		</div>
		<div class="timezoneSelection">
			<span>Fuseau horaire : </span><span><form name="chose_town_form"
					method="POST">
					<select style="width: 500px"
						name="town"><option value="Etc/GMT+12">(UTC-12:00) Ligne de date
							internationale (Ouest)</option>

					</select>
				</form>
			</span>
		</div>
		<div class="levelSelection">
			<span>Niveau des Classes virtuelles : </span><span><form action=""
					name="chose_level_form" method="POST">
					<select
						onchange="_gaq.push(['_trackEvent','Navigation','changeLevel']);document.chose_level_form.submit();"
						name="level"><option selected="selected" value="1">Beginner</option>
						<option value="4">Intermediate</option>
						<option value="6">Advanced</option>
					</select>
				</form> </span>
		</div>
	</div>
	<div class="Classes">
		<div class="list">
		    <table width="100%" cellspacing="0" cellpadding="2" border="0">
                <tr><td><a href="?pageAction=classroom&amp;action=add" class="topButtonOnMain">Add schedule</a></td></tr>
            </table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0"
				class="table">
				<tbody>
					<tr class="header">
						<td class="left">&nbsp;</td>
						<td><span>03</span>Lundi</td>
						<td><span>04</span>Mardi</td>
						<td><span>05</span>Mercredi</td>
						<td><span>06</span>Jeudi</td>
						<td><span>07</span>Vendredi</td>
						<td><span>08</span>Samedi</td>
						<td><span>09</span>Dimanche</td>
					</tr>
					<tr>
						<td class="left">07:00</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><div id="classroomSchedule248" class="classroomSchedule">
								<p class="blurLevel">Describing Clothes</p>
							</div></td>
						<td><div id="classroomSchedule247" class="classroomSchedule">
								<div class="classroomInfos">
									<p>
										<a
											onclick="_gaq.push(['_trackEvent','Navigation','getClassroomInfo'])"
											href="overview.php?classroomId=247">Comparative And
											Superlative In Action (beginner) </a>
									</p>
								</div>
								<div class="classroomActions">
									<span
										onclick="$(location).attr('href','./webapp/common/createICS.php?classroomId=247').attr('target','_blank');"
										title="Ajouter à mon calendrier" id="appointementSave247"
										style="visibility: hidden" class="appointementSave">&nbsp;</span>
								</div>
								<script>$('#classroomSchedule247').mouseover(function(){$('#appointementSave247').css('visibility','visible');});$('#classroomSchedule247').mouseout(function(){$('#appointementSave247').css('visibility','hidden');});</script>
							</div></td>
						<td></td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
			<div class="bottomArea">
				<div class="pager" style="float: right">
					<div class="paginator">
						<div class="pager"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
