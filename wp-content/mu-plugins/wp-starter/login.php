<?php

namespace WDE\WPStarter;

function login_headerurl()
{
  return \get_bloginfo('url');
}

function login_headertitle()
{
  return \get_option('blogname');
}

add_filter( 'login_headerurl', __NAMESPACE__ . '\login_headerurl' );

add_filter( 'login_headertitle', __NAMESPACE__ . '\login_headertitle' );
