<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" name="back" onclick="window.location='{$url}&amp;do=entries';return true;">
 &laquo; {translate('Back')}
</button>

{if $info}
<div class="fc_info">
{$info}
</div><br />
{/if}
<table>
    <thead>
        <tr><th colspan="2" class="gradient1">{translate('Submission details')}</th></tr>
    </thead>
    <tbody>
        <tr>
            <td style="border-top:1px solid #ccc;">{translate('Submission ID')}:</td>
            <td style="border-top:1px solid #ccc;">{$entry.submission_id}</td>
        </tr>
        <tr>
            <td>{translate('Submission Date')}:</td>
            <td>{format_date($entry.submitted_when,1)}</td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #ccc;">{translate('Submitted by')}:</td>
            <td style="border-bottom:1px solid #ccc;">{$entry.submitted_by}</td>
        </tr>
{foreach $data key value}
        <tr>
            <td>{$key}</td>
            <td>{$value}</td>
        </tr>
{/foreach}
{if $replies}
        <tr><th colspan="2" class="gradient1">{translate('Reply')}</th></tr>
{foreach $replies reply}
{foreach $reply key value}
        <tr>
            <td>{$key}</td>
            <td>{$value}</td>
        </tr>
{/foreach}
{/foreach}
{/if}
    </tbody>
</table>
{if !$hide_buttons}
<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" name="back" onclick="window.location='{$url}&amp;do=entries';return true;">
 &laquo; {translate('Back')}
</button>
{if $allow_reply}
<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" name="reply" onclick="window.location='{$url}&amp;do=entries&amp;reply={$entry.submission_id}';return true;">
 {translate('Send reply')}
</button>
{/if}
{else}
<br /><br />
{/if}