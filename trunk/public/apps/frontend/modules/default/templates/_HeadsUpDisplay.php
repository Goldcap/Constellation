<div class="pops pops_wide" id="hud-popup" style="display:none">
	<span class="hud_close" style="float: right">X</span>
	<form id="hud_settings">
	<table width="100%">
		<tr>
			<td colspan="3">
			<h2>HOST Cam Settings</h2>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Bandwidth Limit
			</td>
			<td width="33%">
			<input type="text" name="bandwidthLimit" id="bandwidthLimit" value="16384" />
			</td>
			<td width="33%" align="right">
			<button name="bandwidthLimitSubmit" id="bandwidthLimitSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Quality Level (<span id="qualityLevel_display"></span>)
			</td>
			<td width="33%">
			<div id="qualityLevel_slider"></div>
			<input type="hidden" name="qualityLevel" id="qualityLevel" value="75" />
			</td>
			<td width="33%" align="right">
			<button name="qualityLevelSubmit" id="qualityLevelSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			KeyFrame Interval (<span id="keyFrameInterval_display"></span>)
			</td>
			<td width="33%">
			<div id="keyFrameInterval_slider"></div>
			<input type="hidden" name="keyFrameInterval" id="keyFrameInterval" value="30" />
			</td>
			<td width="33%" align="right">
			<button name="keyFrameIntervalSubmit" id="keyFrameIntervalSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Capture FPS (<span id="captureFps_display"></span>)
			</td>
			<td width="33%">
			<div id="captureFps_slider"></div>
			<input type="hidden" name="captureFps" id="captureFps" value="20" />
			</td>
			<td width="33%" align="right">
			<button name="captureFpsSubmit" id="captureFpsSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Buffer Min (<span id="bufferMin_display"></span>)
			</td>
			<td width="33%">
			<div id="bufferMin_slider"></div>
			<input type="hidden" name="bufferMin" id="bufferMin" value="2" />
			</td>
			<td width="33%" align="right">
			<button name="bufferMinSubmit" id="bufferMinSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Buffer Max (<span id="bufferMax_display"></span>)
			</td>
			<td width="33%">
			<div id="bufferMax_slider"></div>
			<input type="hidden" name="bufferMax" id="bufferMax" value="15" />
			</td>
			<td width="33%" align="right">
			<button name="bufferMaxSubmit" id="bufferMaxSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Mic Rate (<span id="micRate_display"></span>)
			</td>
			<td width="33%">
			<div id="micRate_slider"></div>
			<input type="hidden" name="micRate" id="micRate" value="22" />
			</td>
			<td width="33%" align="right">
			<button name="micRateSubmit" id="micRateSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Mic Gain (<span id="micGain_display"></span>)
			</td>
			<td width="33%">
			<div id="micGain_slider"></div>
			<input type="hidden" name="micGain" id="micGain" value="75" />
			</td>
			<td width="33%" align="right">
			<button name="micGainSubmit" id="micGainSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Motion Timeout (<span id="motionTimeout_display"></span>)
			</td>
			<td width="33%">
			<div id="motionTimeout_slider"></div>
			<input type="hidden" name="motionTimeout" id="motionTimeout" value="10000" />
			</td>
			<td width="33%" align="right">
			<button name="motionTimeoutSubmit" id="motionTimeoutSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Echo Suppression
			</td>
			<td width="33%">
			Off <input type="radio" name="echoSuppression" id="echoSuppression_off" value="0" />
			On <input type="radio" name="echoSuppression" id="echoSuppression_on" value="1" checked="checked" />
			</td>
			<td width="33%" align="right">
			<button name="echoSuppressionSubmit" id="echoSuppressionSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<!--<tr>
			<td width="33%">
			Enhanced Microphone
			</td>
			<td width="33%">
			Off <input type="radio" name="enhancedMicrophone" id="enhancedMicrophone_off" value="0" />
			On <input type="radio" name="enhancedMicrophone" id="enhancedMicrophone_on" value="1" checked="checked" />
			</td>
			<td width="33%" align="right">
			<button name="enhancedMicrophoneSubmit" id="enhancedMicrophoneSubmit" class="applyButton">Apply</button>
			</td>
		</tr>-->
		<tr>
			<td width="33%">
			Silence Level (<span id="silenceLevel_display"></span>)
			</td>
			<td width="33%">
			<div id="silenceLevel_slider"></div>
			<input type="hidden" name="silenceLevel" id="silenceLevel" value="5" />
			</td>
			<td width="33%" align="right">
			<button name="silenceLevelSubmit" id="silenceLevelSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Mic Silence Timeout (<span id="micSilenceTimeout_display"></span>)
			</td>
			<td width="33%">
			<div id="micSilenceTimeout_slider"></div>
			<input type="hidden" name="micSilenceTimeout" id="micSilenceTimeout" value="10000" />
			</td>
			<td width="33%" align="right">
			<button name="micSilenceTimeoutSubmit" id="micSilenceTimeoutSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td width="33%">
			Enable VAD
			</td>
			<td width="33%">
			False <input type="radio" name="enableVAD" id="enableVAD_off" value="false" />
			True <input type="radio" name="enableVAD" id="enableVAD_on" value="true" checked="checked" />
			</td>
			<td width="33%" align="right">
			<button name="enableVADSubmit" id="enableVADSubmit" class="applyButton">Apply</button>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right">
			<input type="submit" name="submit" value="reload" id="hud_reload" />
			<input type="submit" name="submit" value="save" id="hud_save" />
			</td>
		</tr>
		<tr>
			<td colspan="3" style="color: orange">
			<span id="hud_message"></span>
			</td>
		</tr>
	</table>
	</form>
</div>
