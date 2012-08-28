<?php

function resetLink($value) {
  return '<span id="audience_'.$value.'"><a href="#" onclick="doReset('.$value.')">Reset</a></span>';
}

function setStatus($value) {
  if ($value == 1) {
    return '<span class="status" style="color:blue">Used</span>';
  } else {
    return '<span class="status" style="color:green">Open</span>';
  }
}

function getFilmName($value) {
  $film = FilmPeer::retrieveByPk($value);
  if ($film) {
    return $film -> getFilmName();
  } else {
    return "None";
  }
}
