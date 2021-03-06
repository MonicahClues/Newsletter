{lang assign="currLang"}
{formutil_getpassedvalue assign="nllang" name="language" default=$currLang}

{$objectArray.title}
===========================
{if (isset($user_name) && $user_name)}
{gt text="Hello"} {$user_name}
{/if}

{if (isset($objectArray.NewsletterMessage) && $objectArray.NewsletterMessage)}
{gt text="Message from the Site Administrators"}
===========================
{$objectArray.NewsletterMessage|html_entity_decode}
{/if}
 
{if (isset($objectArray.AdminMessages) && $objectArray.AdminMessages)}
{gt text="Special Announcements"}
===========================
{foreach from=$objectArray.AdminMessages item="item"}
{$item.title|html_entity_decode}
{$item.content|html_entity_decode}
{/foreach}
{/if} 

{if (isset($objectArray.News) && $objectArray.News)}
{gt text="News"}
===========================
{foreach from=$objectArray.News item="item"}
{$item.title|html_entity_decode}
{$item.hometext|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.NewMembers) && $objectArray.NewMembers)}
{gt text="Welcome New Members"}
===========================
{foreach from=$objectArray.NewMembers item="item"}
{$item.user_name|html_entity_decode}: {$item.register_date}
{/foreach}
{/if}

{if (isset($objectArray.Content) && $objectArray.Content)}
{gt text="New Content Items"}
===========================
{foreach from=$objectArray.Content item="item"}
{$item.title|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Pages) && $objectArray.Pages)}
{gt text="Recently Added Documents"}
===========================
{foreach from=$objectArray.Pages item="item"}
{$item.title|html_entity_decode}: {$item.content|html_entity_decode}
{/foreach}
{/if}

{if (isset($objectArray.Dizkus) && $objectArray.Dizkus)}
{gt text="Latest Forum Posts"}
===========================
{foreach from=$objectArray.Dizkus item="item"}
{$item.topic_title}
{/foreach}
{/if}

{if (isset($objectArray.Clip) && $objectArray.Clip)}
{gt text="Recently Added Publications"}
===========================
{foreach from=$objectArray.Clip.txt key='tid' item='publist'}
{foreach from=$publist item="item"}
{$item.core_title}
{/foreach}
{/foreach}
{/if}

{gt text="You are receiving this newsletter since you subscribed to it on our site. Should you no longer wish to receive it, you can unsubscribe here!"}: {modurl modname="Newsletter" type="user" func="main" ot="unsubscribe" newlang=$nllang fqurl=true}

{gt text="Link to the Newsletter Archive"}: {modurl modname="Newsletter" type="user" func="main" ot="archive" newlang=$nllang fqurl=true}

