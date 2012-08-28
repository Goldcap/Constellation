<?php

function find_sf_file($file)
{
  $module = sfContext::getInstance()->getModuleName();
  $paths = array();
  $paths[] = sfConfig::get('sf_app_dir') . '/';
  $paths[] = sfConfig::get('sf_app_module_dir') . '/' . $module . '/';
  $paths[] = dirname(__FILE__) . '/';
  
  foreach($paths as $path)
  {
    if(file_exists($path . $file))
    {
      return $path . $file;
    }
  }

  return null;
}

function get_sf_file_contents($file)
{
  $path = find_sf_file($file);
  if(!$path)
  {
    throw new Exception("Can't find file: $file");
  }
  
  return file_get_contents($path);
}

function include_sf_file($file)
{
  $path = find_sf_file($file);
  if(!$path)
  {
    throw new Exception("Can't find file: $file");
  }
  
  return include($path);
}