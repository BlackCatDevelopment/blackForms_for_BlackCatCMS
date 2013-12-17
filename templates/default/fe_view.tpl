{include "fe_header.tpl"}
{if $info}<div class="bform_info{if $is_error} bform_error{/if}">{$info}</div>{/if}
{if $form}{$form}{/if}
{if $content}{$content}{/if}
{include "fe_footer.tpl"}