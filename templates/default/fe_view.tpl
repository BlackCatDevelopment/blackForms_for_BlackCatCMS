{include "fe_header.tpl"}
{if $info}<div class="bform_info{if $is_error} bform_error{/if}">{$info}</div><br /><br />{/if}
{if $form}{$form}{/if}
{if $content}<div class="bform_content">{$content}</div>{/if}
{include "fe_footer.tpl"}