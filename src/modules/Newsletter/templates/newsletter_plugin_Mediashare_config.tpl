{assign var='mediashareAlbumsOnly' value=$plugin_parameters.Mediashare.param.mediashareAlbumsOnly}
{assign var='mediasharePath' value=$plugin_parameters.Mediashare.param.mediasharePath}

<div class="z-formrow">
    <label for="nw-ms-albums">{gt text='Albums only'}</label>
    <div>
        <input id="nw-ms-albums" name="mediashareAlbumsOnly" type="checkbox" value="1" {if $mediashareAlbumsOnly eq 1}checked="checked"{/if} />
        <input name="mediasharePath" type="text" value="{$mediasharePath}" size="30" maxlength="128" />
        <a href="#" title="{gt text='Help'}" onclick="Effect.toggle('hint-ms-1', 'BLIND'); return false;">(?)</a>
        <div id="hint-ms-1" class="z-informationmsg" style="display:none;">
            {gt text='Define the path of your Mediashare images path e.g. <em>mediashare/</em>, <em>pnTemp/mediashare/</em>, etc.'}
        </div>
    </div>
</div>
        