<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="{$page.description}">
  <meta name="author" content="Dmitry Mitskus">
  <link rel="icon" href="{source path=$page.icon}">
  <title>{$page.title}</title>
  <link href="{source path='/public/css/bootstrap.min.css'}" rel="stylesheet">
</head>

<body>

{block name='header'}
  <header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container d-flex justify-content-between">
        <a href="/" class="navbar-brand d-flex align-items-center">
          <strong>Task list</strong>
        </a>
        <div class="left-menu">
          {if $role.admin}
          <a href="/add" class="text-white mr-3">New task</a>
          {/if}
          {if $role.user}
            <a href="/sign_out" class="btn btn-primary"><b>{$account.login}</b>,&nbsp;Sign Out</a>
          {else}
            <a href="/sign_in" class="btn btn-primary">Sign In</a>
          {/if}
        </div>
      </div>
    </div>
  </header>
{/block}

{block name='main'}
  <main role="main">
    <div class="py-5">
      <div class="container">
        <div class="jumbotron jumbotron-fluid rounded m-0 p-5">
          <h1 class="display-4">404</h1>
          <p class="lead">Page does not exist</p>
        </div>
      </div>
    </div>
  </main>
{/block}

{block name='footer'}
  <footer class="text-muted">
    <div class="container">
      <a href="#">Back to top</a>
    </div>
  </footer>
{/block}

</body>
</html>