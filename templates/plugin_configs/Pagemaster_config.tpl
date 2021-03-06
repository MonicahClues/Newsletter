{assign var='pubtypes' value=$plugin_parameters.Pagemaster.param.pmPubTypes}
{assign var='pageargs' value=$plugin_parameters.Pagemaster.param.pmArgs}

{assign var='j' value=1}
{foreach from=$pubtypes key='tid' item='pubtype'}
<hr />
<div class="z-formrow">
    <label for="plugin_{$i}_enable_{$j}">{$pubtype.title|safehtml}</label>
    <input id="plugin_{$i}_enable_{$j}" type="checkbox" name="Pagemastertids[{$tid}]" value="1" {if $pubtype.nwactive}checked="checked"{/if} />
</div>
<div id="plugin_{$i}_suboption_{$j}">
    <div class="z-formrow">
        <label for="PagemasterArgs_{$tid}_itemsperpage">{gt text='Number of publications'}</label>
        <input type="text" value="{$pageargs.$tid.itemsperpage|default:''}" name="PagemasterArgs[{$tid}][itemsperpage]" id="PagemasterArgs_{$tid}_itemsperpage" maxlength="5" size="5">
    </div>
    <div class="z-formrow">
        <label for="PagemasterArgs_{$tid}_template">{gt text='Template'}</label>
        <input type="text" value="{$pageargs.$tid.template|default:''}" name="PagemasterArgs[{$tid}][template]" id="PagemasterArgs_{$tid}_template" maxlength="100" size="30">
    </div>
    <div class="z-formrow">
        <label for="PagemasterArgs_{$tid}_orderby">{gt text='Order by'}</label>
        <input type="text" value="{$pageargs.$tid.orderby|default:''}" name="PagemasterArgs[{$tid}][orderby]" id="PagemasterArgs_{$tid}_orderby" maxlength="255" size="30">
    </div>
    <div class="z-formrow">
        <label for="PagemasterArgs_{$tid}_filter">{gt text='Filter string'}</label>
        <input type="text" value="{$pageargs.$tid.filter|default:''}" name="PagemasterArgs[{$tid}][filter]" id="PagemasterArgs_{$tid}_filter" maxlength="512" size="30">
    </div>
</div>
{assign var='j' value=$j+1}
{foreachelse}
    <div class="z-warningmsg">{gt text='No publication types found.'}</div>
{/foreach}
