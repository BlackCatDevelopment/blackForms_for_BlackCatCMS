<div id="mod_blackforms">
    <div id="tabs">
        <nav class="tabs gradient1">
            <a href="{$url}&amp;do=form" {if $current_tab=='form'}class="current"{/if} id="tab_form">{translate("Form")}</a>
            <a href="{$url}&amp;do=entries" {if $current_tab=='entries'}class="current"{/if} id="tab_entries">{translate("Entries")} ({$item_count})</a>
            <a href="{$url}&amp;do=exports" {if $current_tab=='exports'}class="current"{/if} id="tab_exports">{translate("Exports")} ({$exp_count})</a>
    		<a href="{$url}&amp;do=options" {if $current_tab=='options'}class="current"{/if} id="tab_options">{translate("Options")}</a>
            <a href="{$url}&amp;do=help" {if $current_tab=='help'}class="current"{/if} id="tab_help">{translate("Help")}</a>
    	</nav>