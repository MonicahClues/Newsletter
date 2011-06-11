{newsletter_selector_frequency assign="frequencies"}
{newsletter_selector_type assign="types"}
{modgetvar assign="limit_type" module="Newsletter" name="limit_type"}

{if (!$limit_type)}
  <tr>
    <td>{gt text="Default Newsletter Type"}</td>
    <td>{html_options name="nl_type" options=$types selected=$nl_type_sel}</td>
  </tr>
{else}
  <input type="hidden" name="nl_type" value="{$limit_type}" />
{/if}
<tr>
  <td>{gt text="Default Frequency"}</td>
  <td>{html_options name="nl_frequency" options=$frequencies selected=$nl_frequency_sel}</td>
</tr>
