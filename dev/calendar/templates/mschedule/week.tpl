	<table border="0" width="623" cellspacing="0" cellpadding="0">
		<tr>
			<td width="610" valign="top">
				<table width="610" border="0" cellspacing="0" cellpadding="0" class="calborder">
					<tr>
						<td align="center" valign="middle">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr valign="top">
								<td valign="top" align="right" width="120" class="navback">	
									<div style="padding-top: 3px;">
									</div>
								</td>
							</tr>     			
						</table>
						</td>
					</tr>
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="G10B">
								<tr>
									<td align="center" valign="top">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td></td><td></td><td></td><td></td>
												<td width="1"></td>
												<!-- loop daysofweek on -->
												<td width="80" {COLSPAN} align="center" class="{ROW1}" onmouseover="this.className='{ROW2}'" onmouseout="this.className='{ROW3}'">
												<span class="V9BOLD">{DAY}</span> 
												</td>
												<!-- loop daysofweek off -->
											</tr>
											<tr valign="top" id="allday">
												<td width="60" class="rowOff2" colspan="4"><img src="images/spacer.gif" width="60" height="1" alt=" " /></td>
												<td width="1"></td>
												<!-- loop alldaysofweek on -->
												<td width="80" {COLSPAN} class="rowOff">
													<!-- loop allday on -->
													<div class="alldaybg_{CALNO}">
														{ALLDAY}
														<img src="images/spacer.gif" width="80" height="1" alt=" " />
													</div>
													<!-- loop allday off -->
												</td>
												<!-- loop alldaysofweek off -->
											</tr>
											<!-- loop row on -->
											<tr>
												<td rowspan="4" align="center" valign="top" width="60" class="timeborder">9:00 AM</td>
												<td width="1" height="15"></td>
												<td class="dayborder">&nbsp;</td>
											</tr>
											<tr>
												<td width="1" height="15"></td>
												<td class="dayborder2">&nbsp;</td>
											</tr>
											<tr>
												<td width="1" height="15"></td>
												<td class="dayborder">&nbsp;</td>
											</tr>
											<tr>
												<td width="1" height="15"></td>
												<td class="dayborder2">&nbsp;</td>
											</tr>
											<!-- loop row off -->
											<!-- loop event on -->
											<div class="eventfont">
												<div class="eventbg_{EVENT_CALNO}">{CONFIRMED}<b>{EVENT_START}</b></div>
												<div class="padd">{EVENT}</div>
											</div>
											<!-- loop event off -->
										</table>	
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tbll"><img src="images/spacer.gif" alt="" width="8" height="4" /></td>
						<td class="tblbot"><img src="images/spacer.gif" alt="" width="8" height="4" /></td>
						<td class="tblr"><img src="images/spacer.gif" alt="" width="8" height="4" /></td>
					</tr>
				</table>
			</td>
			<td width="10">
				<img src="images/spacer.gif" width="10" height="1" alt=" " />
			</td>
<td></td>
		</tr>
	</table>
