{extends file="layout.tpl"}

{block name="main"}
  <main role="main">
    <div class="py-5">
      <div class="container">
        {if $error}
          <div class="alert alert-danger" role="alert">{$error}</div>
        {/if}

        <form action="{if $task.id}/edit?task={$task.id}{else}/add{/if}" method="post">
          <div class="form-group">
            <label for="addTaskDescription">Description</label>
            <input type="text" class="form-control" id="addTaskDescription" name="description" value="{$task.description}">
          </div>
          <button type="submit" class="btn btn-primary">{if $task.id}Save{else}Create{/if}</button>
        </form>
      </div>
    </div>
  </main>
{/block}