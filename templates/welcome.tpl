{extends file="layout.tpl"}

{block name='main'}
  <main role="main">
    <div class="py-5">
      <div class="container">
        {if count($tasks)}
        <ul class="list-group">
          {foreach $tasks as $task}
            <li class="list-group-item">
              <p class="float-left m-0 mr-2">
                <input type="checkbox" {if $role.guest}disabled="true"{else}onchange="location.href='/toggle?task={$task.id}'"{/if} {if $task.completed}checked="true"{/if}/>
              </p>
              <p class="float-left m-0">
                {if $task.completed}<s>{/if}{$task.description}{if $task.completed}</s>{/if}
              </p>
              <p class="float-right m-0">
                {if $role.admin}
                <a href="/edit?task={$task.id}">Edit</a>
                <a href="/delete?task={$task.id}">Delete</a>
                {/if}
              </p>
            </li>
          {/foreach}
        </ul>
        {else}
          <div class="jumbotron jumbotron-fluid rounded m-0 p-5">
            <h1 class="display-4">Task list is empty</h1>
          </div>
        {/if}
      </div>
    </div>
  </main>
{/block}