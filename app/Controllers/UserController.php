<?php

namespace Controllers;


use App\Account;
use Sys\Data;

class UserController
{
  public function viewSignIn($error = false)
  {
    return view('sign_in')
      ->setTitle('Sign In')
      ->assign('error', $error);
  }

  public function signIn(Account $acc)
  {
    $login = Data::post('login', 's');
    if (empty($login))
      return redirect('/sign_in?error=Check your login and password');

    $password = Data::post('password', 's');
    if (empty($password))
      return redirect('/sign_in?error=Check your login and password');

    if (!$acc->signIn($login, $password))
      return redirect('/sign_in?error=Check your login and password');

    return redirect('/');
  }

  public function signOut(Account $acc)
  {
    $acc->signOut();
    return redirect('/');
  }
}