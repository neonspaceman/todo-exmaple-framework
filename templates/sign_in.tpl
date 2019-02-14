{extends file="layout.tpl"}

{block name="main"}
  <main role="main">
    <div class="py-5">
      <div class="container">
        {if $error}
          <div class="alert alert-danger" role="alert">{$error}</div>
        {/if}

        <form action="/sign_in" method="post">
          <div class="form-group">
            <label for="signInLogin">Login</label>
            <input type="text" class="form-control" id="signInLogin" name="login">
          </div>
          <div class="form-group">
            <label for="singInPassword">Password</label>
            <input type="password" class="form-control" id="singInPassword" name="password">
          </div>
          <button type="submit" class="btn btn-primary">Sign In</button>
        </form>
      </div>
    </div>
  </main>
{/block}