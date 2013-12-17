{include "be_header.tpl"}
{if $info}<div class="bform_info{if $is_error} bform_error{/if}"><span class="icon icon-info">&nbsp;</span>{$info}</div><br /><br />{/if}
{if $form}{$form}{/if}
{if $content}{$content}{/if}
{include "be_footer.tpl"}