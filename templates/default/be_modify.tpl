{include "be_header.tpl"}
{if $info}<div class="bform_info align_right{if $is_error} bform_error{/if}"><span class="icon icon-info">&nbsp;</span>{$info}</div><br /><br />{/if}
{if $form}{$form}{/if}
{if $content}{$content}{/if}
{if $add_form}<div id="dialog1" style="display:none;" title="{translate('Add field')}">{$add_form}</div>{/if}
{if $edit_form}<div id="dialog2" style="display:none;" title="{translate('Edit field')}">{$edit_form}</div>{/if}
{include "be_footer.tpl"}