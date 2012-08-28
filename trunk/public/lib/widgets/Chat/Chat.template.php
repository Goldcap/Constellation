<div id="history"></div>
<div id="inbox"></div>
<div id="input">
  <form action="/services/chat/post" method="post" id="messageform">
    <table>
      <tr>
        <td><input name="body" id="message" style="width:500px"/></td>
        <td style="padding-left:5px">
          <input type="submit" value="POST"/>
        </td>
      </tr>
    </table>
  </form>
</div>

<div id="room" style="visibility:hidden"><?php echo $room;?></div>
