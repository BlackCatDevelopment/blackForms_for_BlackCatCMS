{if $entries}
<form action="{$url}" method="post">
    <input type="hidden" name="do" value="exports" />
    <table>
        <thead>
            <tr>
                <th class="gradient1">
                    <input type="checkbox" name="toggle_export" id="toggle_export" />
                </th>
                <th class="gradient1">{translate('Filename')}</th>
                <th class="gradient1">{translate('Date')}</th>
                <th class="gradient1">{translate('Size')}</th>
                <th class="gradient1">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
{foreach $entries entry}
            <tr>
                <td>
                    <input type="checkbox" class="delete" name="delete[]" id="export_{$entry.filename}" value="{$entry.filename}" />
                </td>
                <td>
                    <a href="{$CAT_URL}/modules/blackForms/export/{$entry.filename}">{$entry.filename}</a>
                </td>
                <td>{format_date($entry.date)}</td>
                <td>{$entry.size}</td>
                <td><a href="javascript:confirm_link(cattranslate('Are you sure?','','','blackForms'),'{$url}&amp;do=exports&amp;del={$entry.filename}');" class="icon icon-remove">&nbsp;</a></td>
            </tr>
{/foreach}
            <tr>
                <td colspan="7">
                    <input type="submit" value="{translate('Delete selected')}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
{else}
<div class="bform_info align_right">
<span class="icon icon-info">&nbsp;</span>
{translate('No exports found')}
</div>
{/if}

<script charset=windows-1250 type="text/javascript">
    jQuery(':checkbox[name=toggle_export]').click(function() {
        jQuery(':checkbox[class=delete]').prop('checked', this.checked);
    });
</script>