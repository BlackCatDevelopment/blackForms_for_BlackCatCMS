<form action="{$url}" method="post">
    <input type="hidden" name="do" value="entries" />
    <table>
        <thead>
            <tr>
                <th class="gradient1">
                    <input type="checkbox" name="toggle_boxes" id="toggle_boxes" />
                </th>
                <th class="gradient1">{translate('Details')}</th>
                <th class="gradient1">{translate('Responded')}</th>
                <th class="gradient1">{translate('Submission ID')}</th>
                <th class="gradient1">{translate('Submission Date')}</th>
                <th class="gradient1">{translate('Submitted by')}</th>
                <th class="gradient1">{translate('Submission size')}</th>
                <th class="gradient1">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
{foreach $entries entry}
            <tr>
                <td>
                    <input type="checkbox" class="bfcbox" name="items[]" id="item_{$entry.submission_id}" value="{$entry.submission_id}" />
                </td>
                <td>
                    <a href="{$url}&amp;do=entries&amp;view={$entry.submission_id}" class="icon icon-eye" title="{translate('View entry details')}">&nbsp;</a>
                </td>
                <td>{if $entry.replies}<span class="icon icon-checkmark" style="color:green"></span>{/if}</td>
                <td>{$entry.submission_id}</td>
                <td>{format_date($entry.submitted_when)}</td>
                <td>{$entry.submitted_by}</td>
                <td>{$entry.size} Bytes</td>
                <td><a href="javascript:confirm_link('{translate('Are you sure?')}','{$url}&amp;do=entries&amp;action=delete&amp;items[]={$entry.submission_id}');" class="icon icon-remove">&nbsp;</a></td>
            </tr>
{/foreach}
            <tr>
                <td colspan="7">
                    <select name="action" id="action">
                        <option>{translate('Selected...')}</option>
                        <option value="export">{translate('Export')}</option>
                        <option value="delete">{translate('Delete')}</option>
                    </select>
                    <span style="display:none" id="span_export_filename">
                        {translate('to file (name)')}: <input type="text" name="export_filename" />
                    </span>
                    <input type="submit" name="submit_entries" id="submit_entries" value="{translate('Submit')}" />
                </td>
            </tr>
        </tbody>
    </table>
</form>

<script charset=windows-1250 type="text/javascript">
    jQuery(':checkbox[name=toggle_boxes]').click(function() {
        jQuery(':checkbox[class=bfcbox]').prop('checked', this.checked);
    });
    jQuery('#submit_entries').click(function(e) {
        if(jQuery(this).val() == 'delete') {
            e.stopPropagation();
            e.preventDefault();
            confirm_link("{translate('Are you sure?')}",'#');
        }
    });
    jQuery('select#action').change(function(e) {
        if(jQuery(this).val() == 'export') {
            jQuery('#span_export_filename').show('slow');
        }
        else {
            jQuery('#span_export_filename').hide('slow');
        }
    });
</script>